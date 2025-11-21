<?php

namespace App\Console\Commands;

use App\Jobs\SendWinnerNotificationJob;
use App\Models\Event;
use App\Models\RaffleWinner;
use App\Models\Ticket;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class FinalizeRaffleEvent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'raffle:finalize-events';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Finalize events two Hour PM & announce winners';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $events = Event::where('is_finalized', false)
            ->where('winner_type', 'automatic')
            ->whereDate('draw_time', '<=', Carbon::now())
            ->get();

        foreach ($events as $event) {

            $winnerTicket = Ticket::where('event_id', $event->id)
                ->where('status', 'paid')
                ->inRandomOrder()
                ->first();

            if (!$winnerTicket) {
                $this->error("No eligible tickets found for Event ID: {$event->id}");
                continue;
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

            $participants = Ticket::where('event_id', $event->id)
                ->whereNotNull('user_id')
                ->pluck('user_id')
                ->toArray();

            // Notification details
            $title = "🌟 Grand Winner of {$event->title} Announced";
            $body  = "Check now to see if you're the lucky winner!";
            $data  = ['event_id' => $event->id, 'winner_id' => $winner->user_id];

            SendWinnerNotificationJob::dispatch($title, $body, $participants, $data);

            $event->is_finalized = true;
            $event->save();

            $this->info("Winner ({$winnerTicket->ticket_number}) finalized for Event: {$event->id}");
        }

        return Command::SUCCESS;
    }
}
