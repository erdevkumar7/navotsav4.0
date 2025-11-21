<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
use App\Http\Resources\EventResource;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\FavouriteEvent;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            "visiblity" => "nullable|in:online,offline"
        ]);
        $visiblity = $request->visiblity;
        $query = Event::where(['is_publish' => true, 'is_finalized' => false])->latest();
        $auth = auth('sanctum')->id();
        // Filter by status
        // if ($auth->check() && $auth->user()->user_type === EVENT_ORGANIZER) {
        //     $query->where('created_by', $auth->id());
        // }

        if ($visiblity == 'offline') {
            $query->whereIn('visiblity', ['offline', 'both']);
        }

        $status = $request->get('status', 'active');
        $today = Carbon::now();


        if ($status === 'active') {
           // $query->where('start_date', '>=', $today);
        }

        // Filter by sport
        if ($request->has('category') && !empty($request->category)) {
            $query->where('category_id', $request->category);
        }

        // Filter by location
        if ($request->has('location')  && !empty($request->location)) {
            $query->where('location', 'ILIKE', '%' . $request->location . '%');
        }

        // Filter by date range
        if ($request->has('date') && !empty($request->date)) {
            $query->where('start_date', '>=', Carbon::parse($request->date)->format('Y-m-d'));
        }

        // Search by title/description
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                    ->orWhere('description', 'like', "%$search%");
            });
        }

        //



        if ($request->has('paginate')) {
            $events = $query->with('banners')->paginate(10);
        } else if ($request->has('limit')) {
            $limit =  (int) $request->limit;
            $events = $query->with('banners')->limit($limit)->get();
        } else {
            $events = $query->with('banners')->paginate(9);
        }

        return EventResource::collection($events)->additional(["status" => true]);
    }

    public function pastEvents(Request $request)
    {

        $visiblity = $request->visiblity;
        $query = Event::where(['is_publish' => true, 'is_finalized' => true])->latest();
        $auth = auth('sanctum')->id();
        // Filter by status
        // if ($auth->check() && $auth->user()->user_type === EVENT_ORGANIZER) {
        //     $query->where('created_by', $auth->id());
        // }

        // if ($visiblity == 'online') {
        //     $query->whereIn('visiblity', ['online', 'both']);
        // } else if ($visiblity == 'offline') {
        //     $query->whereIn('visiblity', ['offline', 'both']);
        // }


        // Filter by sport
        if ($request->has('category') && !empty($request->category)) {
            $query->where('category_id', $request->category);
        }

        // Filter by location
        if ($request->has('location')  && !empty($request->location)) {
            $query->where('location', 'ILIKE', '%' . $request->location . '%');
        }

        // Filter by date range
        if ($request->has('date') && !empty($request->date)) {
            $query->where('start_date', '>=', Carbon::parse($request->date)->format('Y-m-d'));
        }

        // Search by title/description
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                    ->orWhere('description', 'like', "%$search%");
            });
        }


        if ($request->has('paginate')) {
            $events = $query->with('banners')->paginate(10);
        } else {
            $events = $query->with('banners')->paginate(9);
        }

        return EventResource::collection($events)->additional(["status" => true]);
    }



    public function search(Request $request)
    {
        $search = $request->get('location');

        $events = Event::select('location')->where('location', 'ILIKE', "%{$search}%")
            ->orderByRaw("similarity(location, ?) DESC", [$search])
            ->limit(50)
            ->pluck('location');

        return response()->json([
            'status' => true,
            'data'   => $events,
        ]);
    }
    public function eventCategories(Request $request)
    {
        $categories = EventCategory::orderBy('name')->get();
        return response()->json([
            'status' => true,
            'categories' => $categories->map(function ($cat) {
                return [
                    'id' => $cat->id,
                    'name' => $cat->name
                ];
            })
        ]);
    }

    public function eventDetail(Event $event)
    {
        $event->load('multiplePrices');
        return response()->json([
            'status' => true,
            'data' => new EventResource($event)
        ]);
    }

    public function collectedAmount($eventId)
    {
        $total = Ticket::where('status', 'paid')
            ->where('event_id', $eventId)
            ->sum('price');

        $soldTickets = Ticket::where('status', 'paid')
            ->where('event_id', $eventId)
            ->count();

        $event = Event::find($eventId);

        return response()->json([
            'status' => true,
            'amount' => floor($total),
            'is_finalized' => $event->is_finalized,
            'sold_tickets' => $soldTickets
        ]);
    }

    public function favourite($eventId)
    {
        $authId = Auth::id();

        $favourite = FavouriteEvent::where([
            'event_id' => $eventId,
            'user_id'  => $authId,
        ])->first();

        if ($favourite) {
            $favourite->delete();
            return response()->json([
                'status'  => true,
                'message' => "Event removed from favourites",
            ]);
        }

        FavouriteEvent::create([
            'event_id' => $eventId,
            'user_id'  => $authId,
        ]);

        return response()->json([
            'status'  => true,
            'message' => "Event added to favourites",
        ]);
    }

    public function favouriteEvent(Request $request)
    {
        $authId = Auth::id();


        $favourites = FavouriteEvent::with(['event.banners', 'event.multiplePrices', 'event.creator'])
            ->where('user_id', $authId)
            ->paginate(9);

        return EventResource::collection(
            $favourites->getCollection()->map(fn($fav) => $fav->event)
        )->additional([
            'status' => true,
            'meta' => [
                'current_page' => $favourites->currentPage(),
                'last_page' => $favourites->lastPage(),
                'per_page' => $favourites->perPage(),
                'total' => $favourites->total(),
            ],
            'links' => [
                'first' => $favourites->url(1),
                'last'  => $favourites->url($favourites->lastPage()),
                'prev'  => $favourites->previousPageUrl(),
                'next'  => $favourites->nextPageUrl(),
            ],
        ]);
    }


    public function pause(Event $event)
    {
        $event->update(['status' => 'paused']);
        return response()->json(['status' => true, 'message' => 'Event paused']);
    }

    public function resume(Event $event)
    {
        $event->update(['status' => 'active']);
        return response()->json(['status' => true, 'message' => 'Event resumed']);
    }
}
