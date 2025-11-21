<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PhonePeController extends Controller
{
    public function initiatePayment(Request $req)
    {
        $merchantId = env('PHONEPE_MERCHANT_ID');
        $saltKey    = env('PHONEPE_SALT_KEY');
        $saltIndex  = env('PHONEPE_SALT_INDEX');
        $baseUrl    = env('PHONEPE_BASE_URL');

        // dd($baseUrl);

        // Unique transaction
        $transactionId = Str::uuid();

        // Save order in DB
        $orderId = \DB::table('event_orders')->insertGetId([
            "event_id" => $req->event_id,
            "pass_id" => $req->pass_id,
            "pass_name" => $req->pass_name,
            "qty" => $req->qty,
            "amount" => $req->amount,
            "user_name" => $req->name,
            "email" => $req->email,
            "mobile" => $req->mobile,
            "merchant_transaction_id" => $transactionId
        ]);

        // PhonePe Payload
        $payload = [
            "merchantId" => $merchantId,
            "merchantTransactionId" => $transactionId,
            "merchantUserId" => "MAANUSER001",
            "amount" => $req->amount * 100,
            "callbackUrl" => url("/api/phonepe/status/" . $transactionId),

            "paymentInstrument" => [
                "type" => "PAY_PAGE"
            ]
        ];

        $payloadJson = json_encode($payload);
        $base64Payload = base64_encode($payloadJson);

        $checksum = hash("sha256", $base64Payload . "/pg/v1/pay" . $saltKey) . "###" . $saltIndex;

        $response = Http::withHeaders([
            "Content-Type" => "application/json",
            "X-VERIFY" => $checksum,
        ])->post($baseUrl . "/pg/v1/pay", [
            "request" => $base64Payload
        ]);

        $res = $response->json();

        return response()->json([
            "paymentUrl" => $res["data"]["instrumentResponse"]["redirectInfo"]["url"],
            "txnId" => $transactionId
        ]);
    }

    public function checkStatus($transactionId)
    {
        $merchantId = env('PHONEPE_MERCHANT_ID');
        $saltKey    = env('PHONEPE_SALT_KEY');
        $saltIndex  = env('PHONEPE_SALT_INDEX');
        $baseUrl    = env('PHONEPE_BASE_URL');

        $string = "/pg/v1/status/$merchantId/$transactionId" . $saltKey;
        $checksum = hash("sha256", $string) . "###" . $saltIndex;

        $response = Http::withHeaders([
            "Content-Type" => "application/json",
            "X-VERIFY" => $checksum,
            "X-MERCHANT-ID" => $merchantId
        ])->get($baseUrl . "/pg/v1/status/$merchantId/$transactionId");

        $res = $response->json();

        // Update DB
        \DB::table('event_orders')
            ->where("merchant_transaction_id", $transactionId)
            ->update([
                "payment_status" => $res["data"]["state"],
                "phonepe_transaction_id" => $res["data"]["transactionId"] ?? null
            ]);

        return response()->json($res);
    }
}
