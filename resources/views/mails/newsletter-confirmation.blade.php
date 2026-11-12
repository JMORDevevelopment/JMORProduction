<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Confirm your JMOR newsletter subscription</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f6f9; font-family: Arial, Helvetica, sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f6f9; padding: 24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; max-width: 600px; width: 100%;">
                    <tr>
                        <td style="background-color: #0d6efd; padding: 24px 32px;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 22px;">JMOR</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 32px;">
                            <p style="margin: 0 0 16px; font-size: 16px; color: #333333;">
                                Hi {{ $firstName }},
                            </p>
                            <p style="margin: 0 0 16px; font-size: 16px; color: #333333;">
                                Thanks for joining the JMOR newsletter. Please confirm this email address to start receiving updates:
                            </p>
                            <p style="margin: 0 0 8px; font-size: 14px; color: #666666;">
                                Email: <strong>{{ $email }}</strong>
                            </p>
                            <p style="margin: 24px 0;">
                                <a href="{{ $confirmUrl }}" style="display: inline-block; background-color: #0d6efd; color: #ffffff; text-decoration: none; padding: 12px 24px; border-radius: 4px; font-weight: bold;">
                                    Confirm subscription
                                </a>
                            </p>
                            <p style="margin: 16px 0 0; font-size: 13px; color: #888888;">
                                If you did not request this, you can safely ignore this email.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color: #f8f9fa; padding: 16px 32px; font-size: 12px; color: #888888;">
                            © {{ date('Y') }} JMOR. All rights reserved.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
