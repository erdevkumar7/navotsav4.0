<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Your OTP Code</title>
  <link href="https://fonts.googleapis.com/css2?family=Jost:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body style="margin:0; padding:0; background-color:#f5f7fa; font-family:'Jost', sans-serif;">
  <table width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color:#f5f7fa; padding:40px 0;">
    <tr>
      <td align="center">
        <table width="480" border="0" cellspacing="0" cellpadding="0" style="background-color:#ffffff; border-radius:12px; box-shadow:0 4px 10px rgba(0,0,0,0.08); overflow:hidden;">
          <tr>

          </tr>
          <tr>
            <td align="center" style="padding:30px 25px; color:#333333;">
              <h2 style="margin:0; font-size:20px; font-weight:600;color: #000;">Hello {{ $name }}!</h2>
              <p style="font-size:15px; line-height:22px; margin:15px 0 0;color: #000;">Your One-Time Password (OTP) for {{ $purpose }} is:</p>
              <div style="display:inline-block; margin:25px 0; padding:15px 25px; background:#f1f5ff; border:2px dashed #743289; border-radius:8px; font-size:30px; font-weight:600; letter-spacing:8px; color:#f9037a;">
               {{ $otp }}
              </div>
              <p style="font-size:15px; line-height:22px; margin:0;width: 72%;color: #000;">This code will expire in <strong>10 minutes</strong>.Please do not share it with anyone.</p>
            </td>
          </tr>
          <tr>

          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
