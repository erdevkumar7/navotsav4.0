<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingConfirm extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $eventName;
    public $ticketNumber;
    public $ticketQty;
    public $drawDate;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $eventName, $ticketNumber, $ticketQty, $drawDate)
    {
        $this->user = $user;
        $this->eventName = $eventName;
        $this->ticketNumber = $ticketNumber;
        $this->ticketQty = $ticketQty;
        $this->drawDate = $drawDate;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Raffle Donate Ticket Booked'
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.booking_confirmation',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
