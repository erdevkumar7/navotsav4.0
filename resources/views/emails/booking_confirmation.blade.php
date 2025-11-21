<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Raffle Donate Ticket Confirmation</title>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@400;500;600;700&display=swap" rel="stylesheet">

</head>

<body
    style="margin:0; padding:0; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color:#f5f7fa; color:#333333;font-family: 'Jost', sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f5f7fa; padding:40px 0;">
        <tr>
            <td align="center">
                <table cellpadding="0" cellspacing="0" border="0" width="600"
                    style="background-color:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 2px 10px rgba(0,0,0,0.08);">

                    <!-- Header -->
                    <tr>
                        <td align="center" style="background: linear-gradient(69deg,#f9037a,#743289); padding:30px;">
                            <h1 style="margin:0; color:#ffffff; font-size:26px;text-align:center">🎟️ Raffle Donate
                                Ticket
                                Confirmed!</h1>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding: 30px 40px;text-align: center;">
                            <p style="font-size:16px; color:#333; margin:0 0 15px;text-align:center">Hi
                                <strong>{{ $user->name }}</strong>,
                            </p>

                            <p style="font-size:16px; color:#333; margin:0 0 20px;text-align:center">
                                Your raffle entry has been successfully confirmed! 🙌
                                You’re now officially in the running for exciting rewards.
                            </p>

                            <table width="100%" cellpadding="0" cellspacing="0"
                                style="margin:20px 0; border-collapse:collapse; font-family:Arial, sans-serif; border:1px solid #e0e0e0; border-radius:6px; overflow:hidden;">
                                <tr style="background-color:#f8f9fa;">
                                    <td colspan="2"
                                        style="padding:12px 15px; font-size:16px; font-weight:bold; color:#333; border-bottom:1px solid #e0e0e0;">
                                        🎉 Event Details
                                    </td>
                                </tr>

                                <tr>
                                    <td
                                        style="padding:10px 15px; font-size:15px; color:#333; width:40%; border-bottom:1px solid #eaeaea;text-align:left">
                                        <strong>Event:</strong>
                                    </td>
                                    <td
                                        style="padding:10px 15px; font-size:15px; color:#007bff; border-bottom:1px solid #eaeaea;text-align:right">
                                        {{ $eventName }}
                                    </td>
                                </tr>

                                <tr>
                                    <td
                                        style="padding:10px 15px; font-size:15px; color:#333; width:40%; border-bottom:1px solid #eaeaea;text-align:left">
                                        <strong>Ticket No:</strong>
                                    </td>
                                    <td
                                        style="padding:10px 15px; font-size:15px; color:#28a745; border-bottom:1px solid #eaeaea;line-height: 1.5;text-align:right">
                                        {{ $ticketNumber }}
                                    </td>
                                </tr>

                                <tr>
                                    <td
                                        style="padding:10px 15px; font-size:15px; color:#333; width:40%;text-align:left">
                                        <strong>Ticket Qty:</strong>
                                    </td>
                                    <td style="padding:10px 15px; font-size:15px; color:#dc3545;text-align:right">
                                        {{ $ticketQty }}
                                    </td>
                                </tr>

                                <tr>
                                    <td
                                        style="padding:10px 15px; font-size:15px; color:#333; width:40%;text-align:left">
                                        <strong>Draw Date:</strong>
                                    </td>
                                    <td style="padding:10px 15px; font-size:15px; color:#dc3545;text-align:right">
                                        {{ $drawDate }}
                                    </td>
                                </tr>
                            </table>

                            <p style="font-size:15px; line-height:1.6; color:#555;text-align:center">
                                Stay tuned — we’ll notify you as soon as the results are out.
                                Good luck! 🍀 Hope you’re the lucky winner.
                            </p>

                            <p style="margin-top:30px; font-size:15px; color:#333;text-align:center">
                                Thanks for participating,<br>
                                <strong>The {{ config('app.name') }} Team</strong>
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center"
                            style="    background: linear-gradient(69deg, #f9037a, #743289); padding:20px; font-size:13px; color:#fff;">
                            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
