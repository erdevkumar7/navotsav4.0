<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TicketResource;
use App\Jobs\FBNotificationJob;
use App\Mail\BookingConfirm;
use App\Mail\WelcomeMail;
use App\Models\CashPaymentHistory;
use App\Models\Event;
use App\Models\MultiplePrice;
use App\Models\Ticket;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Stripe\Stripe;
use Stripe\Price;
use Stripe\PaymentLink;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TicketPurchaseController extends Controller
{

    private function ticketNumber()
    {
        do {
            $ticketNumber = (string) random_int(1000000000000, 9999999999999);
        } while (\App\Models\Ticket::where('ticket_number', $ticketNumber)->exists());

        return $ticketNumber;
    }

    public function reserve(Request $request)
    {
        $rules = [
            'event_id' => 'required|exists:events,id',
            'multiple_price' => 'required|boolean',
            'package_id' => "required|integer|exists:multiple_prices,id",
        ];

        $request->validate($rules);

        $authUser = auth()->user();

        return DB::transaction(function () use ($request, $authUser) {
            $event = Event::where('id', $request->event_id)
                ->lockForUpdate()
                ->first();


            $package = MultiplePrice::find($request->package_id);
            $quantity = $package->quantity;
            $totalPrice = $package->price;

            // Base price (floor to 2 decimals)
            $perTicket = floor(($totalPrice / $quantity) * 100) / 100;

            // Fill array with base price
            $ticketPrices = array_fill(0, $quantity, $perTicket);

            // Adjust last ticket with rounding remainder
            $allocated = array_sum($ticketPrices);
            $ticketPrices[$quantity - 1] = round(
                $ticketPrices[$quantity - 1] + ($totalPrice - $allocated),
                2
            );
            $isPricePackage = true;


            if ($authUser->user_type == BUYER) {

                $oldTickets = Ticket::where('event_id', $event->id)
                    ->where('user_id', $authUser->id)
                    ->where('status', 'reserved')
                    ->get();

                $oldCount = $oldTickets->count();
                if ($oldCount > 0) {
                    foreach ($oldTickets as $ticket) {
                        $ticket->update([
                            'status' => 'cancel',
                            'reserved_until' => now(),
                        ]);
                    }
                    $event->decrement('sold_tickets', $oldCount);
                }
            }

            // Stock check
            $remaining = $event->ticket_quantity - $event->sold_tickets;
            if ($remaining < $quantity) {
                return response()->json(['error' => 'Not enough tickets available'], 422);
            }

            // Reserve
            $expiry = now()->addMinutes(10);
            $tickets = [];


            for ($i = 0; $i < $quantity; $i++) {
                $ticket = Ticket::create([
                    'ticket_number' => 'no-',
                    'event_id' => $event->id,
                    'user_id' => $authUser->user_type == BUYER ? $authUser->id : null,
                    'seller_id' => $authUser->user_type != BUYER ? $authUser->id : null,
                    'price' => $ticketPrices[$i],
                    'status' => 'reserved',
                    'reserved_until' => $expiry,
                    'package_id' => $request?->package_id
                ]);

                $ticket->ticket_number = $this->ticketNumber($ticket->id);

                $ticket->save();

                $tickets[] = $ticket;
            }

            $event->increment('sold_tickets', $quantity);

            return response()->json([
                'status' => true,
                'message' => 'Tickets reserved successfully',
                'expires_at' => $expiry,
                'tickets' => $tickets,
            ]);
        });
    }

    public function reserveTickets($eventId)
    {

        $authId = Auth::id();
        $tickets = Ticket::where('status', 'reserved')
            ->where('event_id', $eventId)
            ->where('user_id', $authId)->where("reserved_until", ">", now())
            ->get();

        if ($tickets->isEmpty()) {
            return response()->json(['status' => false, 'error' => 'Reservation expired or invalid']);
        }
        return response()->json([
            "status" => true,
            "reserve_tickets" => $tickets
        ]);
    }

    public function bookingFromPos(Request $request)
    {

        $request->validate([
            'event_id' => "required|integer|exists:events,id",
            'package_id' => "required|integer|exists:multiple_prices,id",
            'payment_method' => "required|string|in:cash",
            'notes'           => 'required_if:payment_method,cash|array',
            'notes.*.value'   => 'required_if:payment_method,cash|numeric',
            'notes.*.qty'     => 'required_if:payment_method,cash|integer|min:0',
            'buyer_phone' => 'nullable|digits:10',

        ]);

        $authUser = Auth::user();

        return DB::transaction(function () use ($request, $authUser) {

            // Lock event to avoid race conditions (prevent over-selling)
            $event = Event::lockForUpdate()->findOrFail($request->event_id);

            // Get only required fields
            $package = MultiplePrice::select('id', 'quantity', 'price')
                ->findOrFail($request->package_id);

            $quantity   = $package->quantity;
            $totalPrice = $package->price;

            // ----------- HANDLE CASH PAYMENT
            $amountGiven = $totalPrice;
            $changeDue   = 0;

            if ($request->payment_method === 'cash') {

                // Calculate amount from notes
                $amountGiven = collect($request->notes)->sum(function ($n) {
                    return $n['value'] * $n['qty'];
                });

                if ($amountGiven < $totalPrice) {
                    return response()->json([
                        'status'  => false,
                        'message' => 'Customer paid ' . format_price($amountGiven) . ', required ' . format_price($totalPrice)
                    ], 422);
                }

                $changeDue = $amountGiven - $totalPrice;
            }

            // Distribute price evenly
            $perTicket = floor(($totalPrice / $quantity) * 100) / 100;
            $remaining = round($totalPrice - ($perTicket * $quantity), 2);

            $ticketRows = [];
            $ticketIds = [];
            $now = now();

            $ticketNumber = $this->ticketNumber();

            for ($i = 0; $i < $quantity; $i++) {

                // Build QR data immediately (no update query later)
                $qrData = json_encode([
                    'ticket_number' => $ticketNumber,
                    'event_id'      => $event->id,
                    'ticket_qty'    => $quantity,
                    'hash'          => sha1($ticketNumber . $event->id),
                ]);

                $ticketRows[] = [
                    'ticket_number' => $ticketNumber,
                    'event_id'      => $event->id,
                    'user_id'       => null,
                    'seller_id'     => $authUser->id,
                    'price'         => ($i == $quantity - 1) ? ($perTicket + $remaining) : $perTicket,
                    'status'        => 'paid',
                    'payment_method' => $request->payment_method,
                    'package_id'    => $package->id,
                    'qr_code_data'  => $qrData,
                    'created_at'    => $now,
                    'updated_at'    => $now,
                ];
            }

            // Bulk insert — only 1 DB write instead of 2×$quantity
            DB::table('tickets')->insert($ticketRows, 'id');

            // Increment sold tickets once — not inside loop
            $event->increment('sold_tickets', $quantity);

            $transaction = Transaction::create([
                'user_id'        => $authUser->id,
                'event_id'       => $event->id,
                'amount'         => $totalPrice,
                'payment_method' => $request->payment_method,
                'status'         => 'completed',
                'meta'           => json_encode([
                    'ticket_number' => $ticketNumber,
                    'package_id'    => $request->package_id,
                    'amount_given'  => $amountGiven,
                    'change_due'    => $changeDue
                ]),
                'buyer_phone' =>$request->buyer_phone
            ]);

            // -------- Store Cash Notes (if cash)
            if ($request->payment_method === 'cash') {
                foreach ($request->notes as $note) {
                    CashPaymentHistory::create([
                        'transaction_id' => $transaction->id,
                        'denomination'   => $note['value'],
                        'qty'            => $note['qty'],
                        'total'          => $note['value'] * $note['qty'],
                    ]);
                }
            }

            return response()->json([
                'status'       => true,
                'message'      => 'Payment confirmed, tickets activated',
                'change_due'   => $changeDue,
                'amount_given' => $amountGiven,
                'tickets'      => [$this->ticketHistory($ticketNumber)]
            ]);
        });
    }


    public function booking(Request $request)
    {
        $request->validate([
            'event_id' => "required|integer|exists:events,id",
            'package_id' => "required|integer|exists:multiple_prices,id",
            'payment_method' => "required|string",
        ]);

        $authUser = Auth::user();

        // Delete previously reserved tickets for this event
        Ticket::where([
            ['status', 'reserved'],
            ['user_id', $authUser->id],
            ['event_id', $request->event_id],
        ])->delete();

        try {
            $result = DB::transaction(function () use ($request, $authUser) {

                $event = Event::lockForUpdate()->findOrFail($request->event_id);

                $package = MultiplePrice::select(['id', 'quantity', 'price'])
                    ->findOrFail($request->package_id);

                $quantity   = $package->quantity;
                $totalPrice = $package->price;

                // Distribute price evenly
                $perTicket = floor(($totalPrice / $quantity) * 100) / 100;
                $remainder = round($totalPrice - ($perTicket * $quantity), 2);

                $ticketData = [];
                $ticketNumbers = [];

                $ticketNumber = $this->ticketNumber();

                for ($i = 0; $i < $quantity; $i++) {

                    $ticketData[] = [
                        'ticket_number' => $ticketNumber,
                        'event_id'      => $event->id,
                        'user_id'       => $authUser->id,
                        'price'         => ($i == $quantity - 1) ? ($perTicket + $remainder) : $perTicket,
                        'status'        => 'reserved',
                        'payment_method' => $request->payment_method,
                        'package_id'    => $package->id,
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ];
                }

                // 3× faster than looping Ticket::create()
                Ticket::insert($ticketData);

                // Record pending transaction (no Stripe call inside DB transaction)
                $transaction = Transaction::create([
                    'user_id'        => $authUser->id,
                    'event_id'       => $event->id,
                    'amount'         => $totalPrice,
                    'payment_method' => $request->payment_method,
                    'status'         => 'pending',
                    'meta' => json_encode([
                        'ticket_number' => $ticketNumber,
                        'package_id'     => $package->id,
                    ]),
                ]);

                return [
                    'transaction' => $transaction,
                    'transaction_id' => $transaction->id,
                    'ticket_number' => $ticketNumber,
                    'totalPrice' => $totalPrice,
                    'event' => $event,
                    'package' => $package,
                ];
            });

            // Stripe outside transaction (Stripe is slow)
            Stripe::setApiKey(get_admin_setting('STRIPE_SECRET'));

            $price = Price::create([
                'unit_amount' => $result['totalPrice'] * 100,
                'currency' => 'usd',
                'product_data' => [
                    'name' => "Raffle Tickets for {$result['event']->title}",
                    'metadata' => [
                        'event_id' => $result['event']->id,
                    ],
                ],
            ]);

            $paymentLink = PaymentLink::create([
                'line_items' => [[
                    'price' => $price->id,
                    'quantity' => 1,
                ]],
                'payment_method_types' => ['card'],
                'metadata' => [
                    'user_id' => $authUser->id,
                    'event_id' => $result['event']->id,
                    'transaction_id' => $result['transaction_id'],
                    'ticket_number' => $result['ticket_number'],
                    'package_id' => $result['package']->id,
                ],
                'after_completion' => [
                    'type' => 'redirect',
                    'redirect' => [
                        'url' => url("payment-success?session_id={CHECKOUT_SESSION_ID}"),
                    ],
                ],
            ]);

            // Update transaction with payment link id
            $result['transaction']->update([
                'meta' => json_encode([
                    'payment_link_id' => $paymentLink->id,
                    'ticket_number' => $result['ticket_number'],
                ])
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Payment link generated successfully',
                'payment_url' => $paymentLink->url
            ]);
        } catch (\Throwable $ex) {
            Log::error("Stripe Purchase Failed", ['error' => $ex->getMessage()]);
            return response()->json(['status' => false, 'message' => $ex->getMessage()], 500);
        }
    }

    private function ticketHistory($ticketNumber)
    {

        $grouped = Ticket::select(
            'ticket_number',
            'event_id',
            DB::raw('COUNT(*) AS sold_tickets'),
            DB::raw('SUM(price) AS total_price'),
            DB::raw('MAX(id) AS latest_ticket_id')
        )
            ->where('status', 'paid')
            ->where('ticket_number', $ticketNumber)
            ->groupBy('ticket_number', 'event_id');

        $ticket = Ticket::with('event', 'event.banners')
            ->joinSub($grouped, 'tg', function ($join) {
                $join->on('tickets.id', '=', 'tg.latest_ticket_id');
            })
            ->select(
                'tickets.*',
                'tg.sold_tickets',
                'tg.total_price'
            )
            ->first();

        return new TicketResource($ticket);
    }

    /**
     * Show user's tickets
     */
    public function myTickets()
    {
        $authUser = Auth::user();

        $query = Ticket::query()
            ->where('status', 'paid');

        // Buyer (purchased tickets)
        if ($authUser->user_type == BUYER) {
            $query->where('user_id', $authUser->id);
        }
        // Seller (sold tickets)
        else {
            $query->where('seller_id', $authUser->id);
        }

        // ✅ derive grouped table (grouped by ticket_number + event_id)
        $grouped = $query->clone()
            ->select(
                'ticket_number',
                'event_id',
                DB::raw('COUNT(*) AS sold_tickets'),
                DB::raw('SUM(price) AS total_price'),
                DB::raw('MAX(id) AS latest_ticket_id')
            )
            ->groupBy('ticket_number', 'event_id');

        $tickets = Ticket::with('event', 'event.banners')
            ->joinSub($grouped, 'tg', function ($join) {
                $join->on('tickets.id', '=', 'tg.latest_ticket_id');
            })
            ->leftJoin('transactions', function ($join) {
                $join->on('tickets.ticket_number', '=', DB::raw("(transactions.meta->>'ticket_number')"));
            })
            ->select('tickets.*', 'tg.total_price', 'tg.sold_tickets','transactions.buyer_phone')
            ->orderByDesc('tickets.created_at')
            ->paginate(5);



        return TicketResource::collection($tickets)->additional(['status' => true]);
    }
}
