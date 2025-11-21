<?php

namespace App\Http\Controllers;

use App\Mail\BookingConfirm;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret = config('services.stripe.webhook_secret');

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $secret);
        } catch (\Exception $e) {
            return response('Invalid signature', 400);
        }

        if (in_array($event->type, ['checkout.session.completed', 'payment_link.completed'])) {
            $session = $event->data->object;

            $metadata = $session->metadata ?? [];
            $userId = $metadata['user_id'] ?? null;
            $eventId = $metadata['event_id'] ?? null;

            $transactionId = $metadata['transaction_id'] ?? null;

            $ticketNumber = $metadata['ticket_number'] ?? null;

            if (!$userId || !$eventId || !$transactionId || !$ticketNumber) {
                return response('Missing metadata', 400);
            }

            DB::transaction(function () use ($userId, $eventId, $transactionId, $ticketNumber) {
                $authUser = User::find($userId);
                $event = Event::find($eventId);

                $tickets = Ticket::where('ticket_number', $ticketNumber)->get();

                $ticketQty = $tickets->count();
                foreach ($tickets as $ticket) {
                    $qrData = json_encode([
                        'ticket_number' => $ticket->ticket_number,
                        'event_id' => $ticket->event_id,
                        'ticket_qty'    => $ticketQty,
                        'hash' => sha1($ticket->ticket_number . $ticket->event_id),
                    ]);

                    $ticket->update([
                        'status' => 'paid',
                        'user_id' => $authUser->id,
                        'qr_code_data' => $qrData,
                    ]);
                }

                // Send confirmation email
                Mail::to($authUser->email)->queue(new BookingConfirm(
                    $authUser,
                    $event->title,
                    $ticketNumber,
                    $ticketQty,
                    $event->draw_time,
                ));

                $event->increment('sold_tickets', $ticketQty);

                Transaction::where('id', $transactionId)
                    ->update(['status' => 'completed']);
            });
        }

        return response('Webhook processed', 200);
    }
}
