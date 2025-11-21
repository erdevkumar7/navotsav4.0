<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EventResultsMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $eventName;
    public $eventDate;
    public $ticketNumber;
    public $isWinner;
    public $prizeUrl;
    public $eventsUrl;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $eventName, $ticketNumber, $isWinner = false, $prizeUrl = null)
    {
        $this->user = $user;
        $this->eventName = $eventName;
        $this->ticketNumber = $ticketNumber;
        $this->isWinner = $isWinner;
        $this->prizeUrl = $prizeUrl ?? url('/prizes');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->isWinner
                ? "🎉 Ticket #{$this->ticketNumber} Won in {$this->eventName}!"
                : "💫 Results for {$this->eventName} – Ticket #{$this->ticketNumber}"
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.event_result',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
