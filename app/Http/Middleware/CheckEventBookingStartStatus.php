<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Event;

class CheckEventBookingStartStatus
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {

        // ✅ Allow Super Admins to always access
        $user = auth()->user();
        if ($user && $user->user_type == 1) {
            return $next($request);
        }

        $eventId = $request->route('id'); // event ID from route parameter
        $event = Event::find($eventId);

        if (!$event) {
            abort(404, 'Event not found.');
        }

        // ✅ Check if any tickets exist for this event
        $hasTickets = DB::table('tickets')->where('event_id', $event->id)->exists();

        if ($hasTickets) {
            // Redirect back with an error message
            return redirect()->back()->with('error', "Event booking started — you can't edit this event.");
        }

        return $next($request);
    }
}
