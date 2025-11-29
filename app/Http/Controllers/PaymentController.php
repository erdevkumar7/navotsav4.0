<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function createOrder(Request $request)
    {
        try {
            $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

            $order = $api->order->create([
                'receipt'  => 'PASS_' . time(),
                'amount'   => $request->amount * 100,
                'currency' => 'INR'
            ]);

            return response()->json([
                'success' => true,
                'order_id' => $order['id'],
                'key' => env('RAZORPAY_KEY'),
            ]);
        } catch (\Exception $e) {
            Log::error("Razorpay Order Error: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Order creation failed']);
        }
    }

    public function verifyPayment(Request $req)
    {
        try {
            $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

            $attributes = [
                'razorpay_order_id' => $req->razorpay_order_id,
                'razorpay_payment_id' => $req->razorpay_payment_id,
                'razorpay_signature' => $req->razorpay_signature
            ];

            $api->utility->verifyPaymentSignature($attributes);


            $orderId = \DB::table('event_orders')->insertGetId([
                "user_name" => $req->name,
                "email" => $req->email,
                "mobile" => $req->mobile,
                "pass_name" => $req->pass_name,
                "jnv" => $req->jnv,
                "year" => $req->year,
                "event_id" => $req->event_id,
                "pass_id" => $req->pass_id,
                "qty" => $req->qty,
                "amount" => $req->amount,
                "jnv_state" => $req->jnv_state,
                "merchant_transaction_id" => $req->razorpay_payment_id,
                'payment_status'=> 'success'
            ]);

            $order = DB::table('event_orders')->where('id', $orderId)->first();

            // Save booking, email ticket, etc.
            return response()->json([
                'success' => true,
                'message' => 'Payment verified',
                'orderId' => $orderId
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Signature verification failed'
            ]);
        }
    }
}
