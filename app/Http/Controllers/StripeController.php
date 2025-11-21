<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;

class StripeController extends Controller
{
    public function paymentSuccess(Request $request)
    {
        \Stripe\Stripe::setApiKey(get_admin_setting('STRIPE_SECRET'));

        $session = Session::retrieve($request->session_id, [
            'expand' => ['payment_intent', 'line_items'],
        ]);

        $metadata = $session->metadata;

        $transactionId = $metadata->transaction_id;

        $event = Event::findOrFail($metadata->event_id);

        $transaction = Transaction::find($transactionId);

        $meta = json_decode($transaction->meta, true);

        $ticketNumber = $meta['ticket_number'];

        return view('payment.success', [
            'ticketNumber' => $ticketNumber,
            'event' => $event,
            'userId' => $metadata->user_id,
        ]);
    }
}
