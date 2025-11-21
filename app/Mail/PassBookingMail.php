<?php

// namespace App\Mail;

// use Illuminate\Bus\Queueable;
// use Illuminate\Contracts\Queue\ShouldQueue;
// use Illuminate\Mail\Mailable;
// use Illuminate\Mail\Mailables\Content;
// use Illuminate\Mail\Mailables\Envelope;
// use Illuminate\Queue\SerializesModels;

// class PassBookingMail extends Mailable
// {
//     use Queueable, SerializesModels;

//     /**
//      * Create a new message instance.
//      */
//     public function __construct()
//     {
//         //
//     }

//     /**
//      * Get the message envelope.
//      */
//     public function envelope(): Envelope
//     {
//         return new Envelope(
//             subject: 'Pass Booking Mail',
//         );
//     }

//     /**
//      * Get the message content definition.
//      */
//     public function content(): Content
//     {
//         return new Content(
//             markdown: 'emails.pass_booking',
//         );
//     }

//     /**
//      * Get the attachments for the message.
//      *
//      * @return array<int, \Illuminate\Mail\Mailables\Attachment>
//      */
//     public function attachments(): array
//     {
//         return [];
//     }
// }



namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PassBookingMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order; // store order details

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function build()
    {
        return $this->subject('Your Pass Booking is Successful')
                    ->markdown('emails.pass_booking');
    }
}

