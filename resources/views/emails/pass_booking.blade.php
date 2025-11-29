{{-- <x-mail::message>
# Introduction

The body of your message.

<x-mail::button :url="''">
Button Text
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message> --}}




@php
    $gold = '#ffcc00';
    $orange = '#ff8800';
    $dark = '#0a0a0a';
@endphp

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Pass Booking Confirmed</title>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: {{ $dark }};
            margin: 0;
            padding: 0;
            color: #fff;
        }

        .container {
            max-width: 650px;
            margin: 25px auto;
            background: #564e4e;
            border-radius: 14px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .header {
            text-align: center;
            padding: 25px;
            background: linear-gradient(135deg, {{ $gold }}, {{ $orange }});
            color: white;
            font-size: 26px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .content {
            padding: 30px !important;
            font-size: 16px;
            line-height: 1.7;
            text-align: left;
            color: white;
        }

        .highlight-box {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 18px;
            margin: 20px 0;
            color: white
        }

        .highlight-box span {
            color: {{ $gold }};
            font-weight: 600;
        }

        .button {
            display: inline-block;
            margin-top: 25px;
            padding: 14px 30px;
            background: linear-gradient(135deg, {{ $gold }}, {{ $orange }});
            color: #000 !important;
            font-weight: bold;
            text-decoration: none;
            border-radius: 50px;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #777;
            padding: 20px;
        }
    </style>
</head>

<body>
    <div class="container">

        <div class="header">
            Pass Booking Confirmed 🎟️
        </div>
        <hr>
        <div class="content">

            <p>Hello <strong>{{ $order->user_name }}</strong>,</p>

            <p>Thank you for booking! Your entry will confirm after the payment 🎉</p>

            <div class="highlight-box">
                <p><span>Pass Name:</span> {{ $order->pass_name }}</p>
                <p><span>Quantity:</span> {{ $order->qty }}</p>
                <p><span>Amount:</span> ₹{{ $order->amount }}</p>
            </div>

            <p>We look forward to seeing you at <strong>NAVLAY 1.0</strong> 🤩</p>
            <p>For any query you can contact at: 9993776088</p>


        </div>

        <div class="footer">
            <p>MAAN - Madhya Pradesh Alumni Association of Navodaya</p>
            © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </div>

    </div>
</body>

</html>
