<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
use App\Jobs\FBNotificationJob;
use App\Jobs\SendEventResultsMailJob;
use App\Jobs\SendWinnerNotificationJob;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\RaffleWinner;
use App\Models\Ticket;
use App\Models\TicketPackage;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserDevice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    // public function index()
    // {
    //     $user = auth()->user();

    //     $query = Event::with('category')->orderBy('created_at', 'desc');
    //     if (Gate::denies('super-admin')) {
    //         $query->where('created_by', $user->id);
    //     }
    //     $events = $query->get();
    //     return view('event.index', compact('events'));
    // }



    // public function index(Request $request)
    // {
    //     $user = auth()->user();

    //     if ($request->ajax()) {
    //         $query = Event::with('category')->orderBy('created_at', 'desc');

    //         if (Gate::denies('super-admin')) {
    //             $query->where('created_by', $user->id);
    //         }

    //         return DataTables::of($query)
    //             ->addIndexColumn()
    //             ->editColumn('category', fn($event) => $event->category?->name ?? 'No Category')
    //             ->addColumn('status', function ($event) {
    //                 return view('event.partials.status-dropdown', compact('event'))->render();
    //             })
    //             ->addColumn('action', function ($event) {
    //                 return view('event.partials.actions', compact('event'))->render();
    //             })
    //             ->rawColumns(['status', 'action'])
    //             ->make(true);
    //     }

    //     return view('event.index');
    // }


    public function index()
    {
        $user = auth()->user();

        $query = EventCategory::query();
        $categories = $query->orderBy('name')->get();

        return view('event.index', compact('categories'));
    }


    public function getData(Request $request)
    {
        $user = auth()->user();
        $query = Event::select(
            'events.*',
            'event_categories.name as category_name',
            'users.name as created_by_name',
            DB::raw("COALESCE((SELECT COUNT(t.id) FROM tickets t WHERE t.event_id = events.id AND t.status = 'paid'), 0) as event_sold_tickets"),

            // Sum only PAID ticket revenue
            DB::raw("COALESCE((SELECT SUM(t.price) FROM tickets t WHERE t.event_id = events.id AND t.status = 'paid'), 0) as event_total_revenue")
        )
            ->leftJoin('event_categories', 'events.category_id', '=', 'event_categories.id')
            ->leftJoin('users', 'events.created_by', '=', 'users.id');

        // Restrict events for organizers
        if ($user->hasRole('event-organizer')) {
            $query->where('events.created_by', $user->id);
        }



        // ✅ Filters
        if ($request->category_id) {
            $query->where('events.category_id', $request->category_id);
        }

        if ($request->type) {
            $today = now()->toDateString();

            if ($request->type === 'active') {
                $query->where('events.is_finalized', false);
            } elseif ($request->type === 'past') {
                $query->where('events.is_finalized', true);
            }
        }

        if ($request->start_date) {
            $query->whereDate('events.start_date', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $query->whereDate('events.end_date', '<=', $request->end_date);
        }

        if (!isset($request->order) || empty($request->order)) {
            $query->orderByDesc('events.id');
        }


        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('event_sold_tickets', fn($event) => $event->event_sold_tickets)
            ->editColumn('event_total_revenue', fn($event) => format_price(floor($event->event_total_revenue)))
            ->editColumn('start_date', fn($event) => format_datetime($event->start_date))
            ->editColumn('end_date', fn($event) => format_datetime($event->end_date))
            ->editColumn('draw_time', fn($event) => format_datetime($event->draw_time))
            ->editColumn('created_by', fn($event) => $event->created_by_name ?? 'Unknown')
            ->editColumn('category_name', fn($event) => $event->category_name ?? 'No Category')
            ->addColumn('status', fn($event) => view('event.partials.status-dropdown', compact('event'))->render())
            ->addColumn('action', fn($event) => view('event.partials.actions', compact('event'))->render())
            ->rawColumns(['status', 'action'])
            ->make(true);
    }



    public function create()
    {
        $categories = EventCategory::all();
        return view('event.create', compact('categories'));
    }


    public function store(EventRequest $request)
    {

        $data = [
            'title' => $request->title,
            'category_id' => $request->category_id,
            'location' => $request->location,
            'visiblity' => $request->visiblity,
            // 'ticket_quantity' => $request->ticket_quantity,
            'winner_type' => $request->winner_type,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'draw_time' => $request->draw_time,
            'cause' => $request->cause,
            'created_by' => auth()->id(),
            'multiple_price' => true,
        ];

        $firstRow = collect($request->ticket_prices)
            ->firstWhere('quantity', 1);

        $data['ticket_price'] = $firstRow['price'] ?? 0;


        if ($request->hasFile('rules')) {
            $rulesPath = $request->file('rules')->store('event_rules', 'public');
            $data['rules'] = $rulesPath;
        }

        if ($request->action === 'publish') {
            $data['is_publish'] = true;
        } else {
            $data['is_publish'] = false;
        }

        $event = Event::create($data);

        foreach ($request->ticket_prices as $ticket) {
            if (!empty($ticket['price']) && !empty($ticket['quantity'])) {
                $event->multiplePrices()->create([
                    'price' => $ticket['price'],
                    'quantity' => $ticket['quantity'],
                ]);
            }
        }

        // Save multiple banners in event_banners table
        if ($request->hasFile('banners')) {
            foreach ($request->file('banners') as $banner) {
                $bannerPath = $banner->store('event_banners', 'public');

                $event->banners()->create([
                    'banner' => $bannerPath,
                ]);
            }
        }

        if ($request->hasFile('eventscreen')) {
            $eventscreen = $request->file('eventscreen');
            $path = $eventscreen->store('event-screen', 'public');

            $event->event_screen = $path;
            $event->save();
        }


        if ($request->action === 'publish') {
            $this->sendNotification($event);
        }
        return redirect()->route(routePrefix() . 'event.index')->withSuccess(
            $request->action === 'publish' ? "Event Published." : "Event Saved as Draft."
        );
    }

    private function sendNotification($event)
    {
        $title = "🔥 New Raffle Event: {$event->title} is Live";
        $body = "The new raffle {$event->title} is open for bookings. Enter now and stand a chance to win big";

        $userIds = User::where('user_type', BUYER)->pluck('id')->toArray();

        FBNotificationJob::dispatch($title, $body, $userIds);
    }

    public function changeStatus(Request $request)
    {
        $event = Event::findOrFail($request->id);


        if ($request->is_publish === "true") {
            $missing = [];

            // Basic required fields
            if (empty($event->category_id)) $missing[] = 'Category';
            if (empty($event->location)) $missing[] = 'Location';
            if (empty($event->winner_type)) $missing[] = 'Winner Type';
            if (empty($event->start_date)) $missing[] = 'Start Date';
            if (empty($event->event_screen)) $missing[] = 'Event Screen Image';
            // if (empty($event->description)) $missing[] = 'Description';

            // ✅ Only check these if winner_type = 'automatic'
            if ($event->winner_type === 'automatic') {
                if (empty($event->end_date)) $missing[] = 'End Date';
                if (empty($event->draw_time)) $missing[] = 'Draw Time';
            }

            if (count($missing) > 0) {
                return response()->json([
                    'success' => false,
                    'popup' => true,
                    'message' => 'Please fill the following details before publishing: ' . implode(', ', $missing),
                ]);
            }

            $event->is_publish = true;
            $event->save();

            if ($request->is_publish === "true") {
                $this->sendNotification($event);
            }
            return response()->json([
                'success' => true,
                'message' => $event->is_publish ? 'Status updated successfully.' : 'Event set to draft.',
            ]);
        }

        // If user sets event to draft
        $event->is_publish = false;
        $event->save();

        return response()->json([
            'success' => true,
            'message' => 'Event set to draft.',
        ]);
        // $event->is_publish = $request->is_publish;
        // $event->save();


    }


    public function edit($id)
    {
        $event = Event::with(['banners', 'multiplePrices', 'tickets'])->findOrFail($id);
        $categories = EventCategory::all();

        return view('event.edit', compact('event', 'categories'));
    }

    public function update(EventRequest $request, $id)
    {
        // dd($request->location);
        $event = Event::findOrFail($id);

        // Handle keeping media
        $keepMedia = $request->input('keep_media', []); // IDs to keep

        // Delete banners not in keep_media[]
        $event->banners()
            ->whereNotIn('id', $keepMedia)
            ->get()
            ->each(function ($banner) {
                if (Storage::disk('public')->exists($banner->banner)) {
                    Storage::disk('public')->delete($banner->banner);
                }
                $banner->delete();
            });

        // If new banners uploaded
        if ($request->hasFile('banners')) {
            foreach ($request->file('banners') as $bannerFile) {
                $bannerPath = $bannerFile->store('event_banners', 'public');
                $event->banners()->create([
                    'banner' => $bannerPath,
                ]);
            }
        }

        if ($request->hasFile('eventscreen')) {
            // Delete old image if it exists
            if ($event->event_screen && Storage::disk('public')->exists($event->event_screen)) {
                Storage::disk('public')->delete($event->event_screen);
            }

            // Store new image
            $eventscreen = $request->file('eventscreen');
            $path = $eventscreen->store('event-screen', 'public');

            // Save new path in DB
            $event->event_screen = $path;
        }

        // Update main event fields
        $data = $request->except(['banners', 'keep_media', 'ticket_prices', 'multiple_price']);


        $firstRow = collect($request->ticket_prices)
            ->firstWhere('quantity', 1);

        $data['ticket_price'] = $firstRow['price'] ?? 0;


        $event->update($data);


        /**
         * Handle Multiple Prices
         */
        // Always clear old prices first
        // $event->multiplePrices()->delete();


        // foreach ($request->ticket_prices as $ticket) {
        //     if (!empty($ticket['price']) && !empty($ticket['quantity'])) {
        //         $event->multiplePrices()->create([
        //             'price'    => $ticket['price'],
        //             'quantity' => $ticket['quantity'],
        //         ]);
        //     }
        // }

        $bookingStarted = Ticket::where('event_id', $id)->count();

        // dd($bookingStarted);

        $existingIds = $event->multiplePrices->pluck('id')->toArray();
        $incomingIds = collect($request->ticket_prices)->pluck('id')->filter()->toArray();

        // 1️⃣ Delete prices that were removed in the form
        if ($bookingStarted === 0) {
            $toDelete = array_diff($existingIds, $incomingIds);
            if (!empty($toDelete)) {
                $event->multiplePrices()->whereIn('id', $toDelete)->delete();
            }
        }

        // 2️⃣ Update existing rows and add new ones
        foreach ($request->ticket_prices as $ticket) {
            if (!empty($ticket['price']) && !empty($ticket['quantity'])) {
                if (!empty($ticket['id'])) {
                    // Update existing price
                    $event->multiplePrices()
                        ->where('id', $ticket['id'])
                        ->update([
                            'price'    => $ticket['price'],
                            'quantity' => $ticket['quantity'],
                        ]);
                } else {
                    // Create new price
                    $event->multiplePrices()->create([
                        'price'    => $ticket['price'],
                        'quantity' => $ticket['quantity'],
                    ]);
                }
            }
        }


        //return redirect()->back()->with('success', 'Event updated successfully!');
        return redirect()->route(routePrefix() . 'event.index')->with('success', 'Event updated successfully!');
    }

    public function show($id)
    {
        $event = Event::with(['category', 'banners', 'multiplePrices'])->findOrFail($id);

        $packageRevenue = Ticket::join('multiple_prices as price_packages', 'tickets.package_id', '=', 'price_packages.id')
            ->select(
                'tickets.package_id',
                'price_packages.price as package_price',
                DB::raw('SUM(tickets.price) as package_revenue'),
                DB::raw('COUNT(tickets.id) as sold_tickets'),
                DB::raw('price_packages.quantity as package_quantity'),
                DB::raw('COUNT(tickets.id) / price_packages.quantity as sold_packages')
            )
            ->where('tickets.event_id', $id)
            ->where('tickets.status', 'paid')
            ->groupBy('tickets.package_id', 'price_packages.quantity', 'price_packages.price')
            ->get();

        $totalParticipats = collect($packageRevenue)->sum('sold_packages');

        $revenues = Transaction::selectRaw("
        SUM(CASE WHEN payment_method = 'cash' THEN amount ELSE 0 END) AS cash_revenue,
        SUM(CASE WHEN payment_method != 'cash' THEN amount ELSE 0 END) AS online_revenue
    ")
            ->where('event_id', $id)
            ->where('status', 'completed')
            ->first();

        $cashRevenue   = $revenues->cash_revenue;
        $onlineRevenue = $revenues->online_revenue;

        $claimRow = DB::table('claim_requests')->where('event_id', $id)->first();


        return view('event.show-new', compact('event', 'packageRevenue', 'totalParticipats', 'claimRow', 'cashRevenue', 'onlineRevenue'));
    }


    public function ticketsData($id)
    {
        $tickets = Ticket::select(
            'tickets.id',
            'tickets.price',
            'tickets.status',
            'tickets.ticket_number',
            'tickets.created_at',
            'buyer.name as buyer_name',
            'tickets.seller_id as seller_id',
            'tickets.event_id'
        )

            ->leftJoin('users as buyer', 'tickets.user_id', '=', 'buyer.id')
            ->where('tickets.status', 'paid')
            ->where('tickets.event_id', $id);



        return DataTables::of($tickets)
            ->addIndexColumn()
            // expose alias columns (these are sortable because they exist in the select)
            ->editColumn('event_title', function ($ticket) {
                return $ticket->event_title ?? 'N/A';
            })
            ->editColumn('buyer_name', function ($ticket) {
                return $ticket->buyer_name ?? 'N/A';
            })
            // price is numeric column; render but allow sorting on tickets.price
            ->editColumn('price', function ($ticket) {
                return format_price($ticket->price);
            })
            // status rendered as HTML but order should use tickets.status
            ->editColumn('status', function ($ticket) {
                $status = ucfirst($ticket->status);
                if ($ticket->status === 'paid') {
                    return '<span class="badge bg-success text-white">' . $status . '</span>';
                } elseif ($ticket->status === 'cancel') {
                    return '<span class="badge bg-warning text-white">' . $status . '</span>';
                } elseif ($ticket->status === 'expired') {
                    return '<span class="badge bg-danger text-white">' . $status . '</span>';
                }
                return '<span class="badge bg-secondary">' . $status . '</span>';
            })
            ->editColumn('created_at', function ($ticket) {
                return format_datetime($ticket->created_at);
            })

            ->addColumn('book_from', function ($ticket) {
                return !empty($ticket->seller_id) ? "POS" : "Online";
            })
            // When a column is rendered differently, tell DataTables which DB column to use for ordering.
            ->orderColumn('event_title', 'events.title $1')
            ->orderColumn('buyer_name', 'buyer.name $1')
            ->orderColumn('price', 'tickets.price $1')
            ->orderColumn('status', 'tickets.status $1')
            ->orderColumn('created_at', 'tickets.created_at $1')
            ->rawColumns(['status', 'price'])
            ->make(true);
    }

    public function destroy($id)
    {
        $event = Event::findOrFail($id);

        // Delete rules file if exists
        if ($event->rules && Storage::disk('public')->exists($event->rules)) {
            Storage::disk('public')->delete($event->rules);
        }

        // Delete banner files if exist
        if ($event->banners && $event->banners->count() > 0) {
            foreach ($event->banners as $banner) {
                if ($banner->banner && Storage::disk('public')->exists($banner->banner)) {
                    Storage::disk('public')->delete($banner->banner);
                }
                $banner->delete(); // remove banner record from DB
            }
        }

        // Finally delete the event
        $event->delete();

        return response()->json([
            'success' => true,
            'message' => 'Event deleted successfully!'
        ]);
    }

    public function finalizeEvent(Request $request)
    {

        $event = Event::find($request->event_id);
        $drawTime = $event->draw_time;

        if ($event->is_finalized) {
            return response()->json([
                "status" => false,
                "message" => "Event is already finalized!"
            ], 401);
        }

        // if ($drawTime > Carbon::now()) {
        //     return response()->json([
        //         "status" => false,
        //         "message" => "Event drawn time is not completed now"
        //     ], 422);
        // }

        $winnerTicket = Ticket::where('event_id', $event->id)
            ->where('status', 'paid')
            ->inRandomOrder()
            ->first();

        if (!$winnerTicket) {
            return response()->json(["status" => false, 'message' => 'No eligible tickets found'], 422);
        }

        $collectedAmt = Ticket::where('event_id', $event->id)
            ->where('status', 'paid')
            ->sum('price');

        $winningPrice = $collectedAmt / 2;


        $winner = RaffleWinner::create([
            'event_id' => $event->id,
            'ticket_id' => $winnerTicket->id,
            'ticket_number' => $winnerTicket->ticket_number,
            'winning_price' => $winningPrice,
            'user_id' => $winnerTicket->user_id,
        ]);

        $participants = Ticket::where('event_id', $event->id)->whereNotNull('user_id')->pluck('user_id')->toArray();

        $title = "🌟 Grand Winner of {$event->title} Announced";
        $body  = "Check now to see if you're the lucky winner!";
        $data  = ['event_id' => $event->id, 'winner_id' => $winner->user_id];

        // Dispatch to queue
        SendWinnerNotificationJob::dispatch($title, $body, $participants, $data);

        $users = User::whereIn('id', $participants)->get();

        foreach ($users as $user) {
            if ($user->id === $winner->user_id) {
                SendEventResultsMailJob::dispatch($user, $event, $winner->ticket_number, true);
            } else {
                SendEventResultsMailJob::dispatch($user, $event, $winner->ticket_number, false);
            }
        }

        $event->is_finalized = true;
        $event->save();

        return response()->json([
            'status' => true,
            'message' => "Winner ({$winnerTicket->ticket_number}) announced successfully",
            'winner' => $winnerTicket,
        ]);
    }

    public function updateEventScreen(Request $request)
    {
        try {

            $request->validate([
                'screen' => 'required|image|mimes:jpg,png,jpeg,webp|dimensions:min_width=875,min_height=800',
                'event_id' => 'required|exists:events,id'
            ], [
                'screen.required'   => 'Please upload a screen/banner image.',
                'screen.image'      => 'The uploaded file must be an image.',
                'screen.mimes'      => 'Only JPG, PNG, JPEG, and WEBP formats are allowed.',
                'screen.dimensions' => 'The screen image must be at least 875px width and 800px height.'
            ]);

            $event = Event::find($request->event_id);
            $path = $request->file('screen')->store('event-screen', 'public');

            $event->event_screen = $path;
            $event->save();

            return back()->with('success', 'Screen updated successfully!');
        } catch (\Exception $ex) {
            return back()->with('error', $ex->getMessage());
        }
    }
}
