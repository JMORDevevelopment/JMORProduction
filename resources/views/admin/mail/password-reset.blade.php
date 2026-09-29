<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Reset your admin password</title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; background:#f4f4f4; padding:30px;">
    <div style="max-width:520px; margin:0 auto; background:#fff; padding:30px; border:1px solid #e5e5e5;">
        <h2 style="margin-top:0;">JMOR Connection</h2>
        <p>Hello {{ $adminName }},</p>
        <p>We received a request to reset your admin panel password.</p>
        <p>
            <a href="{{ $resetUrl }}"
               style="display:inline-block; padding:10px 18px; background:#2563eb; color:#fff; text-decoration:none; border-radius:4px;">
                Reset password
            </a>
        </p>
        <p style="color:#666;">This link expires in 60 minutes. If you did not request this, you can safely ignore this email.</p>
    </div>
</body>
</html>
