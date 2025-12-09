<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventDonation;
use App\Models\EventOrder;
use App\Models\RaffleWinner;
use App\Models\Ticket;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TicketController extends Controller
{
    // MAAN FUnctions ---------------------------------------
    public function ticketListOffline()
    {
        $events = EventOrder::latest()->get();
        return view('ticket-package.ticket-list', compact('events'));
    }

    public function AdminEditTicket($id)
    {
        $event = EventOrder::findOrFail($id);
        return view('ticket-package.ticket-edit-maan', compact('event'));
    }

    public function AdminUpdateTicket(Request $request, $id)
    {
        $event = EventOrder::findOrFail($id);

        // Tracking which pass type user selected
        $passId = $event->pass_id;
        $qty    = $request->qty;

        // Validation rules based on pass_id
        $maxQty = match ($passId) {
            1 => 2,   // Student
            2 => 2,   // Adult
            3 => 4,   // Family
            4 => 2,   // Host
            default => 1
        };

        if ($qty > $maxQty) {
            return back()->with('error', "You cannot select more than $maxQty quantity for this pass.");
        }


        $event->user_name = $request->user_name;
        $event->email = $request->email;
        $event->mobile = $request->mobile;
        $event->jnv = $request->jnv;
        $event->year = $request->year;
        $event->qty = $request->qty;
        $event->amount = $request->amount;
        // $event->payment_status = $request->payment_status;
        // $event->merchant_transaction_id = $request->merchant_transaction_id;

        if ($request->hasFile('payment_image')) {
            $image      = $request->file('payment_image');
            $imageName  = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            // Move file to /public/payment_proofs
            $image->move(public_path('assets/payment_proofs'), $imageName);
            // Save path in DB (relative path)
            $event->payment_image = $imageName;
        }

        $event->save();

        return back()->with('success', 'Details updated successfully');
    }

    public function updateStatus(Request $request, $id)
    {
        $event = EventOrder::findOrFail($id);

        $event->online_transaction_id = $request->online_transaction_id;
        $event->payment_status = $request->payment_status;

        // if ($request->hasFile('payment_image')) {
        //     $file = $request->payment_image->store('payment_proofs', 'public');
        //     $event->payment_image = $file;
        // }

        if ($request->hasFile('payment_image')) {

            $image      = $request->file('payment_image');
            $imageName  = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            // Move file to /public/payment_proofs
            $image->move(public_path('assets/payment_proofs'), $imageName);

            // Save path in DB (relative path)
            $event->payment_image = $imageName;
        }


        $event->save();

        return back()->with('success',  'Payment updated successfully');
    }

    public function donationList()
    {
        $events = EventDonation::latest()->get();
        return view('ticket-package.donation-list', compact('events'));
    }

    // MAAN function  ------------------------------------------------------

    public function ticketList()
    {
        $events = Event::get();
        return view('ticket-package.ticket-list', compact('events'));
    }

    public function ticketData(Request $request)
    {

        $user = Auth::user();

        $grouped = Ticket::select(
            'ticket_number',
            'event_id',
            DB::raw('COUNT(*) AS sold_tickets'),
            DB::raw('SUM(price) AS total_price'),
            DB::raw('MAX(id) AS latest_ticket_id')
        )
            ->where('status', 'paid')
            ->groupBy('ticket_number', 'event_id');

        $tickets = Ticket::select(
            'tickets.id',
            'tickets.price',
            'tickets.status',
            'tickets.ticket_number',
            'tickets.created_at',
            'events.title as event_title',
            'buyer.name as buyer_name',
            'transactions.buyer_phone',
            'tickets.seller_id',
            'tg.sold_tickets',
            'tg.total_price'
        )
            ->joinSub($grouped, 'tg', function ($join) {
                $join->on('tickets.id', '=', 'tg.latest_ticket_id');
            })
            ->leftJoin('events', 'tickets.event_id', '=', 'events.id')
            ->leftJoin('users as buyer', 'tickets.user_id', '=', 'buyer.id')
            ->leftJoin('transactions', function ($join) {
                $join->on('tickets.ticket_number', '=', DB::raw("(transactions.meta->>'ticket_number')"));
            });

        if ($request->filled('event_id')) {
            $tickets->where('tickets.event_id', $request->event_id);
        }

        // if ($user->hasRole('event-organizer')) {
        //     $tickets->where('events.created_by', $user->id);
        // }

        return DataTables::of($tickets)
            ->addIndexColumn()

            ->editColumn('event_title', fn($t) => $t->event_title ?? 'N/A')
            ->editColumn('buyer_name', function ($t) {
                $name = $t->buyer_name ?? null;
                $phone = $t->buyer_phone ?? null;

                if (!$name && !$phone) {
                    return 'N/A';
                }

                $output = $name ?: '';
                if ($phone) {
                    $output .= $name ? " ({$phone})" : $phone;
                }

                return $output;
            })

            ->editColumn('price', fn($t) => format_price($t->price))
            ->editColumn('total_price', fn($t) => format_price($t->total_price))
            ->editColumn('sold_tickets', fn($t) => $t->sold_tickets)
            ->editColumn('created_at', fn($t) => format_datetime($t->created_at))

            ->addColumn('book_from', fn($t) => !empty($t->seller_id) ? "POS" : "Online")

            ->editColumn('status', function ($t) {
                $label = ucfirst($t->status);
                return match ($t->status) {
                    'paid'    => "<span class='badge bg-success text-white'>$label</span>",
                    'cancel'  => "<span class='badge bg-warning text-white'>$label</span>",
                    'expired' => "<span class='badge bg-danger text-white'>$label</span>",
                    default   => "<span class='badge bg-secondary'>$label</span>",
                };
            })

            ->orderColumn('event_title', 'events.title $1')
            ->orderColumn('buyer_name', 'buyer.name $1')
            ->orderColumn('price', 'tickets.price $1')
            ->orderColumn('status', 'tickets.status $1')
            ->orderColumn('created_at', 'tickets.created_at $1')

            ->rawColumns(['status'])
            ->make(true);
    }

    public function ticketQr($id)
    {
        $ticket = Ticket::findOrFail($id);

        if (!$ticket->qr_code_data) {
            abort(404, 'QR code not available');
        }
        return response(
            QrCode::format('svg')->size(300)->generate($ticket->qr_code_data)
        )->header('Content-Type', 'image/svg+xml');
    }

    public function winners()
    {
        return view('ticket-package.winner-list');
    }

    public function winnersData()
    {
        $user = Auth::user();
        // $user = auth()->user();

        // eager load relationships
        $winners = RaffleWinner::with(['event', 'user', 'ticket']);


        //  Event organizer should only see tickets for their own events
        // if ($user->hasRole('event-organizer')) {
        //     $tickets->whereHas('event', function ($query) use ($user) {
        //         $query->where('created_by', $user->id);
        //     });
        // }

        return DataTables::of($winners)
            ->addIndexColumn()
            ->addColumn('event', function ($winner) {
                return $winner->event->title ?? 'N/A';
            })
            // ->addColumn('seller', function ($ticket) {
            //     return $ticket->seller->name ?? 'Buyer';
            // })
            ->addColumn('user', function ($winner) {
                return $winner->user->name ?? 'N/A';
            })
            ->addColumn('phone', function ($winner) {
                return $winner->user->phone ?? 'N/A';
            })
            ->editColumn('price', function ($winner) {
                return format_price($winner?->winning_price ?? 00); // uses your helper
            })
            ->addColumn('ticket_number', function ($winner) {
                return $winner->ticket->ticket_number ?? 'N/A';
            })
            ->editColumn('created_at', function ($winner) {
                return format_datetime($winner->created_at);
            })
            ->make(true);
    }
}
