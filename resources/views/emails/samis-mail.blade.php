<!doctype html>
<html lang="en">
<head><meta charset="utf-8"><title>{{ $details['title'] ?? config('app.name') }}</title></head>
<body style="margin:0;padding:30px 12px;background:#f1f5f9;font-family:Arial,Helvetica,sans-serif;color:#15243b">
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="max-width:600px;margin:auto;background:#fff;border:1px solid #e5ebf2;border-radius:12px">
    <tr><td style="padding:24px 28px;background:#0f1e32;color:#fff;border-radius:12px 12px 0 0">
        <span style="display:inline-block;background:#30bbb1;color:#0f1e32;border-radius:8px;padding:6px 10px;margin-right:10px;font-size:18px">✈</span>
        <strong style="font-size:18px">{{ config('app.name') }}</strong>
    </td></tr>
    <tr><td style="padding:32px 28px">
        <h1 style="font-size:23px;line-height:1.3;margin:0 0 16px;color:#15243b">{{ $details['title'] ?? 'Account notice' }}</h1>
        <p style="font-size:15px;line-height:1.75;white-space:pre-line;margin:0 0 22px;color:#3f5469">{{ $details['body'] }}</p>
        <p style="font-size:13px;line-height:1.6;color:#68778d;margin:0">If you did not request this message, you can ignore it.</p>
    </td></tr>
    <tr><td style="padding:17px 28px;background:#f8fafc;border-top:1px solid #e5ebf2;border-radius:0 0 12px 12px;color:#718198;font-size:12px">© {{ date('Y') }} {{ config('app.name') }} · Operations control</td></tr>
</table>
</body>
</html>
