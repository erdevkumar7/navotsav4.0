<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WebviewController extends Controller
{
    public function eventView(Event $event)
    {
        $event->load(['multiplePrices', 'banners']);
        return view('event-view', compact('event'));
    }


    public function winnerScreen(Event $event)
    {
        $winner = DB::table('raffle_winners')->where('event_id', $event->id)->first();
        $ticketNumber = Ticket::find($winner->ticket_id)->ticket_number;

        $collectAmt = Ticket::where('status', 'paid')
            ->where('event_id', $event->id)
            ->sum('price');


        $winningPrice = floor($collectAmt / 2);

        return view('winner-screen', compact('event', 'ticketNumber', 'winningPrice', 'ticketNumber', 'collectAmt'));
    }
}
