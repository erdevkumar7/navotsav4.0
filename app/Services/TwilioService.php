<?php

namespace App\Services;

use Twilio\Rest\Client;

class TwilioService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client(config('services.twilio.sid'), config('services.twilio.token'));
    }

    /**
     * @param string $phoneNumber - Customer phone like +919876543210
     * @param string $eventName
     * @param array $ticketNumbers
     * @param string $drawDate - Y-m-d H:i format
     */
    public function sendTicketBookedMessage($phoneNumber, $eventName, array $ticketNumbers, $ticketQty, $drawDate, $totalPrice)
    {
        $message = "Thank you for participating your Raffle donate tickets booked!\n";
        $message .= "Event: {$eventName}\n";
        $message .= "Ticket Qty: " . $ticketQty . "\n";
        $message .= "Ticket No(s): " . implode(', ', $ticketNumbers) . "\n";
        $message .=  "Total Price: " . format_price($totalPrice) . "\n";
        $message .= "Draw Time: {$drawDate}\n";
        $message .= "Good luck! 🍀";

        return $this->client->messages->create($phoneNumber, [
            'from' => config('services.twilio.from'),
            'body' => $message,
        ]);
    }
}
