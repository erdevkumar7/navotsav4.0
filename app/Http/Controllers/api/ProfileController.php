<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeviceTokenRequest;
use App\Http\Requests\ProfileRequest;
use App\Http\Resources\NotificationResource;
use App\Models\ClaimRequest;
use App\Models\Event;
use App\Models\Notification;
use App\Models\RaffleWinner;
use App\Models\Ticket;
use App\Models\User;
use App\Models\UserDevice;
use App\Services\FirebaseNotificationService;
use App\Services\TwilioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use function Adminer\format_time;

class ProfileController extends Controller
{
    public function me(Request $request)
    {
        return response()->json(['status' => true, 'user' => $request->user()]);
    }

    public function registerDevice(DeviceTokenRequest $request)
    {

        auth()->user()->devices()->updateOrCreate(
            ['device_token' => $request->device_token],
            ['platform' => $request->platform]
        );

        return response()->json(['success' => true]);
    }

    public function update(ProfileRequest $request)
    {
        $user = $request->user();

        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->is_notify =  $request->boolean('is_notify');

        if ($request->has('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $user = $request->user();
            $user->avatar_url = $path;
        }

        // recalc age verification if dob updated
        //if ($user->dob) $user->is_age_verified = $user->dob->age >= 18;

        $user->save();
        return response()->json(['status' => true, 'message' => 'Profile updated', 'user' => $user]);
    }

    public function uploadAvatar(Request $request)
    {
        $request->validate(['avatar' => 'required|image|max:2048']);

        $user->save();

        return response()->json(['message' => 'Avatar uploaded', 'data' => ['avatar_url' => $user->avatar_url]]);
    }

    public function notifications(Request $request)
    {
        $authId = Auth::id();
        $query = Notification::where('user_id', $authId);
        if ($request->has('limit') && !empty($request->limit)) {
            $nofy = $query->limit(5)->orderBy('created_at', 'desc')->get();
            return response()->json([
                "status" => true,
                "data" => $nofy
            ]);
        }
        $notifications = $query->orderBy('created_at', 'desc')->paginate(10);

        return NotificationResource::collection($notifications)->additional(['status' => true]);
    }

    public function sendTickets(Request $request)
    {
        $request->validate([
            'phone_number' => "required|min:10",
            'event_id' => "required|integer|exists:events,id",
            'ticket_numbers' => "required|array|min:1",
            'ticket_numbers.*' => "required|exists:tickets,ticket_number"
        ]);

        $ticketNumbs = $request->ticket_numbers;

        $event = Event::find($request->event_id);

        $totalPrice = Ticket::whereIn('ticket_number', $ticketNumbs)->sum('price');

        $ticketQty = Ticket::whereIn('ticket_number', $ticketNumbs)->count();

        $twilio = new TwilioService();

        $drawTime = format_datetime($event->draw_time);

        return $twilio->sendTicketBookedMessage($request->phone_number, $event->title, $ticketNumbs, $ticketQty, $drawTime, $totalPrice);
    }

    public function claimRequest(Request $request)
    {
        $request->validate([
            "event_id" => "required|integer|exists:events,id",
            'name' => "required|string",
            'ticket_number' => "required|exists:tickets,ticket_number"
        ]);

        $ticket = $request->ticket_number;
        $eventId = $request->event_id;
        $user = Auth::user();


        $isWinner = RaffleWinner::where('event_id', $eventId)
            ->where('ticket_number', $ticket)
            ->when($user->user_type == BUYER, fn($q) => $q->where('user_id', $user->id))
            ->exists();

        if (!$isWinner) {
            return response()->json([
                'status' => false,
                'message' => "Sorry you did not win, this event!"
            ]);
        }

        $isClaimed = ClaimRequest::where('event_id', $eventId)->where('ticket_number', $ticket)->exists();
        if ($isClaimed) {
            return response()->json([
                'status' => false,
                'message' => "Already claimed"
            ]);
        }

        ClaimRequest::create([
            "name" => $request->name,
            "event_id" => $eventId,
            "ticket_number" => $ticket,
            "user_id" => $user->id,
            "claim_from" => $user->user_type == BUYER ? 'Customer App' : 'POS',
            "status" => 'pending'
        ]);

        return response()->json([
            'status' => true,
            'message' => "Claimed successfully!",
        ]);
    }
}
