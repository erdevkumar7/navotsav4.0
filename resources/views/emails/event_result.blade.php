<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $isWinner ? 'Winner Announcement' : 'Results Announced' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body style="margin:0; padding:0; font-family:'Jost', sans-serif; background-color:#f5f7fa; color:#333333;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f5f7fa; padding:40px 0;">
        <tr>
            <td align="center">
                <table cellpadding="0" cellspacing="0" border="0" width="600"
                    style="background-color:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 2px 10px rgba(0,0,0,0.08);">

                    <!-- Header -->
                    <tr>
                        <td align="center"
                            style="background: linear-gradient(69deg,#f9037a,#743289); padding:30px; color:#fff;">
                            @if ($isWinner)
                                <h1 style="margin:0; font-size:26px;">🏆 Congratulations Ticket #{{ $ticketNumber }}!
                                </h1>
                            @else
                                <h1 style="margin:0; font-size:26px;">💫 Results Announced for {{ $eventName }}</h1>
                            @endif
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding: 30px 40px;text-align: center;">
                            <p style="font-size:16px; margin:0 0 15px;">
                                Hi <strong>{{ $user->name }}</strong>,
                            </p>

                            @if ($isWinner)
                                <p style="font-size:16px; margin:0 0 20px;">
                                    We’re thrilled to announce that your ticket <strong>#{{ $ticketNumber }}</strong>
                                    is one of the <strong>winning entries</strong> for
                                    <strong>{{ $eventName }}</strong> 🎉
                                </p>

                                <table width="100%" cellpadding="0" cellspacing="0"
                                    style="margin:20px 0; border-collapse:collapse; border:1px solid #e0e0e0; border-radius:6px; overflow:hidden;">
                                    <tr style="background-color:#f8f9fa;">
                                        <td colspan="2"
                                            style="padding:12px 15px; font-size:16px; font-weight:bold; color:#333; border-bottom:1px solid #e0e0e0;">
                                            🎉 Winner Details
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding:10px 15px; text-align:left;"><strong>Event:</strong></td>
                                        <td style="padding:10px 15px; text-align:right; color:#007bff;">
                                            {{ $eventName }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding:10px 15px; text-align:left;"><strong>Status:</strong></td>
                                        <td style="padding:10px 15px; text-align:right; color:#28a745;">🥇 Winner</td>
                                    </tr>
                                    <tr>
                                        <td style="padding:10px 15px; text-align:left;"><strong>Ticket Number:</strong>
                                        </td>
                                        <td style="padding:10px 15px; text-align:right; color:#333;">
                                            #{{ $ticketNumber }}</td>
                                    </tr>

                                </table>

                                <a href="{{ $prizeUrl }}"
                                    style="display:inline-block; margin:20px auto 10px; background-color:#28a745; color:#fff; text-decoration:none; padding:12px 25px; border-radius:6px; font-weight:600;">
                                    🎁 Claim Your Prize
                                </a>

                                <p style="font-size:14px; color:#777; margin-top:15px;">
                                    💡 Please claim your prize before the deadline mentioned on the event page.
                                </p>
                            @else
                                <p style="font-size:16px; margin:0 0 20px;">
                                    Thank you for participating in <strong>{{ $eventName }}</strong> 🎟️
                                </p>

                                <p style="font-size:15px; color:#555; margin-bottom:20px;">
                                    Your ticket <strong>#{{ $ticketNumber }}</strong> wasn’t selected this time,
                                    but don’t lose hope — your next ticket might be the lucky one! 🍀
                                </p>

                                <table width="100%" cellpadding="0" cellspacing="0"
                                    style="margin:20px 0; border-collapse:collapse; border:1px solid #e0e0e0; border-radius:6px; overflow:hidden;">
                                    <tr style="background-color:#f8f9fa;">
                                        <td colspan="2"
                                            style="padding:12px 15px; font-size:16px; font-weight:bold; color:#333; border-bottom:1px solid #e0e0e0;">
                                            🎫 Event Details
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding:10px 15px; text-align:left;"><strong>Event:</strong></td>
                                        <td style="padding:10px 15px; text-align:right; color:#007bff;">
                                            {{ $eventName }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding:10px 15px; text-align:left;"><strong>Status:</strong></td>
                                        <td style="padding:10px 15px; text-align:right; color:#dc3545;">Not Selected
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding:10px 15px; text-align:left;"><strong>Ticket Number:</strong>
                                        </td>
                                        <td style="padding:10px 15px; text-align:right;">#{{ $ticketNumber }}</td>
                                    </tr>
                                </table>
                            @endif

                            <p style="margin-top:30px; font-size:15px; color:#333;">
                                Thanks for being part of <strong>{{ config('app.name') }}</strong>! 🎊<br>
                                <span style="font-size:14px; color:#555;">We appreciate your participation.</span>
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center"
                            style="background: linear-gradient(69deg, #f9037a, #743289); padding:20px; font-size:13px; color:#fff;">
                            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>

</html>
