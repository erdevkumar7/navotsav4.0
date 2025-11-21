<?php

namespace App\Jobs;

use App\Mail\EventResultsMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendEventResultsMailJob implements ShouldQueue
{
    use Queueable;

    protected $user;
    protected $event;
    protected $ticketNumber;
    protected $isWinner;

    /**
     * Create a new job instance.
     */
    public function __construct($user, $event, string $ticketNumber, bool $isWinner = false)
    {
        $this->user = $user;
        $this->event = $event;
        $this->ticketNumber = $ticketNumber;
        $this->isWinner = $isWinner;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Mail::to($this->user->email)
                ->send(new EventResultsMail(
                    $this->user,
                    $this->event->title,
                    $this->ticketNumber,
                    $this->isWinner,
                    env('WEB_URL') . '/past-events'
                ));
        } catch (\Throwable $e) {
            Log::error('Failed to send event result mail', [
                'user_id' => $this->user->id,
                'event_id' => $this->event->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
