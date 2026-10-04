<!doctype html>
<html lang="en">
<head><meta charset="utf-8"><title>Research portal verification</title></head>
<body style="margin:0;padding:30px 12px;background:#f1f5f9;font-family:Arial,Helvetica,sans-serif;color:#15243b">
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="max-width:600px;margin:auto;background:#fff;border:1px solid #e5ebf2;border-radius:12px">
    <tr><td style="padding:24px 28px;background:#0f1e32;color:#fff;border-radius:12px 12px 0 0"><strong style="font-size:18px">{{ config('app.name') }}</strong><div style="font-size:12px;color:#9bc3ca;margin-top:4px">Research portal</div></td></tr>
    <tr><td style="padding:30px 28px"><h1 style="font-size:23px;margin:0 0 18px">Verify your access</h1><p>Hello {{ $details['researcher_name'] }},</p><p style="line-height:1.6">Use this one-time code to access the research portal:</p><p style="display:inline-block;padding:15px 22px;background:#eaf7f5;border-radius:10px;color:#087e83;font-size:25px;font-weight:bold;letter-spacing:.12em">{{ $details['otp'] }}</p><p style="font-size:13px;color:#68778d;line-height:1.6">This code expires in 30 minutes. If you did not request it, ignore this email.</p></td></tr>
    <tr><td style="padding:17px 28px;background:#f8fafc;border-top:1px solid #e5ebf2;border-radius:0 0 12px 12px;font-size:12px;color:#718198">© {{ date('Y') }} {{ config('app.name') }}</td></tr>
</table>
</body>
</html>
