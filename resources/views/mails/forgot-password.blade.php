<p style="color:black;"><strong>Hi,</strong> {{ $firstname }}</p>
<br>
<strong style="color:black;">Your email against:</strong> {{ $email }}
<br>
<strong style="color:black;">To choose a new password, open this link:</strong>
<br>
<a href="{{ $resetUrl }}">{{ $resetUrl }}</a>
<br><br>
This link will expire in 60 minutes. If you did not request a password reset, you can safely ignore this email.
