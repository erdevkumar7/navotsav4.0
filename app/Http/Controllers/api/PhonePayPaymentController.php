<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Mail\PassBookingMail;
use App\Models\EventOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PhonePayPaymentController extends Controller
{
    public function createOrder(Request $request)
    {
        // dd('rrrrrrr');
        $request->validate([
            'eventId' => 'required|integer',
            'passId' => 'required|integer',
            'quantity' => 'required|integer|min:1',
            'amount' => 'required|numeric|min:1',
        ]);


        $merchantId = env('PHONEPE_MERCHANT_ID');
        $saltKey = env('PHONEPE_SALT_KEY');
        $saltIndex = env('PHONEPE_SALT_INDEX', 1);

        $orderId = Str::uuid()->toString();
        $amount = $request->amount * 100; // Convert to paise       

        $data = [
            "merchantId" => $merchantId,
            "merchantTransactionId" => $orderId,
            "merchantUserId" => 'GUEST',
            "amount" => $amount,
            "redirectUrl" => url('/payment-callback'),
            "redirectMode" => "REDIRECT",
            "callbackUrl" => url('/api/payment-webhook'),
            "paymentInstrument" => [
                "type" => "PAY_PAGE"
            ],
        ];

        //   dd($data);

        return response()->json([
            'success' => true,
            'message' => 'Payment initiation failed',
            'data' => $data
        ], 200);

        $base64Payload = base64_encode(json_encode($data));

        // Generate checksum
        $string = $base64Payload . '/pg/v1/pay' . $saltKey;
        $sha256 = hash('sha256', $string);
        $checksum = $sha256 . '###' . $saltIndex;

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'X-VERIFY' => $checksum,
        ])->post('https://api.phonepe.com/apis/hermes/pg/v1/pay', [
            'request' => $base64Payload
        ]);

        $responseData = $response->json();

        if ($responseData['success'] && $responseData['code'] === 'PAYMENT_INITIATED') {
            // Save order to database
            $order = \App\Models\Order::create([
                'order_id' => $orderId,
                'user_id' => $request->user()->id ?? null,
                'event_id' => $request->eventId,
                'pass_id' => $request->passId,
                'quantity' => $request->quantity,
                'amount' => $request->amount,
                'status' => 'pending',
            ]);

            return response()->json([
                'success' => true,
                'paymentUrl' => $responseData['data']['instrumentResponse']['redirectInfo']['url']
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Payment initiation failed'
        ], 400);
    }

    public function processCardPayment(Request $request)
    {
        $request->validate([
            'eventId' => 'required|integer',
            'passId' => 'required|integer',
            'quantity' => 'required|integer|min:1',
            'amount' => 'required|numeric|min:1',
            'cardDetails' => 'required|array',
        ]);

        // Process card payment using your preferred payment gateway
        // This is a simplified example - implement proper PCI-compliant card processing

        try {
            $orderId = Str::uuid()->toString();

            // Save order
            $order = \App\Models\Order::create([
                'order_id' => $orderId,
                'user_id' => $request->user()->id ?? null,
                'event_id' => $request->eventId,
                'pass_id' => $request->passId,
                'quantity' => $request->quantity,
                'amount' => $request->amount,
                'status' => 'completed', // Mark as completed for demo
                'payment_method' => 'card',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment successful',
                'orderId' => $orderId
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Payment processing failed'
            ], 400);
        }
    }

    public function verifyPayment(Request $request)
    {
        $request->validate([
            'transactionId' => 'required|string'
        ]);

        // Verify payment status with PhonePe
        $merchantId = env('PHONEPE_MERCHANT_ID');
        $saltKey = env('PHONEPE_SALT_KEY');
        $saltIndex = env('PHONEPE_SALT_INDEX', 1);

        $string = '/pg/v1/status/' . $merchantId . '/' . $request->transactionId . $saltKey;
        $sha256 = hash('sha256', $string);
        $checksum = $sha256 . '###' . $saltIndex;

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'X-VERIFY' => $checksum,
            'X-MERCHANT-ID' => $merchantId,
        ])->get('https://api.phonepe.com/apis/hermes/pg/v1/status/' . $merchantId . '/' . $request->transactionId);

        return response()->json($response->json());
    }

    public function generateUpiQr(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'orderId' => 'required|string'
        ]);

        $amount = $request->amount;
        $orderId = $request->orderId;
        $merchantUpiId = env('MERCHANT_UPI_ID', 'barodiya.devendra@ybl');

        // Create UPI payment URL
        $upiUrl = "upi://pay?pa={$merchantUpiId}&pn=" . urlencode(env('APP_NAME', 'Event Booking')) .
            "&am={$amount}&tn=" . urlencode("Payment for Order: {$orderId}");

        // Generate QR code using Google Charts API
        $qrCodeUrl = "https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=" . urlencode($upiUrl);

        return response()->json([
            'success' => true,
            'upiUrl' => $upiUrl,
            'qrCodeUrl' => $qrCodeUrl,
            'merchantUpiId' => $merchantUpiId
        ]);
    }


    // public function offlineBooking(Request $req)
    // { 
    //      $transactionId = Str::uuid();

    //     // Save order in DB
    //     $orderId = \DB::table('event_orders')->insertGetId([
    //         "user_name" => $req->name,
    //         "email" => $req->email,
    //         "mobile" => $req->mobile,
    //         "pass_name" => $req->pass_name,
    //         "jnv" => $req->jnv,
    //         "year" => $req->year,
    //         "event_id" => $req->event_id,
    //         "pass_id" => $req->pass_id,
    //         "qty" => $req->qty,
    //         "amount" => $req->amount,
    //         "merchant_transaction_id" => $transactionId
    //     ]);

    //       return response()->json([
    //             'success' => true,
    //             'message' => 'Payment successful',
    //             'transactionId' => $transactionId,
    //             // 'orderId' => $orderId
    //         ]);

    // }

    public function offlineBooking(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'email' => 'required|email|unique:event_orders,email',
            'mobile' => 'required|digits_between:10,15|unique:event_orders,mobile',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Generate unique transaction ID
        $transactionId = Str::uuid();

        // Save order in DB
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
            "merchant_transaction_id" => $transactionId
        ]);

        // Fetch order data for email
        $order = DB::table('event_orders')->where('id', $orderId)->first();

        // Send Email
      //  Mail::to($order->email)->send(new PassBookingMail($order));


        return response()->json([
            'success' => true,
            'message' => 'Payment successful',
            'transactionId' => $transactionId,
            'orderId' => $orderId
        ]);
    }
}
