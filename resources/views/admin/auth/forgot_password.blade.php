<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forgot Password — JMOR Connection</title>
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}">
</head>
<body style="background:#f4f4f4;">
    <div style="max-width:420px; margin:80px auto;">
        <div style="background:#fff; border:1px solid #e5e5e5; padding:30px;">
            <h4 style="margin-top:0;">JMOR Connection</h4>
            <p class="text-muted">Reset your admin password</p>

            @if(session('status') === 'sent')
                <div class="alert alert-success">
                    If that email address exists, a password reset link has been sent.
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('admin.forgot-password.send') }}">
                @csrf
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control"
                           value="{{ old('email') }}" required autofocus>
                </div>
                <button type="submit" class="btn btn-primary btn-block" style="margin-top:15px;">
                    Send reset link
                </button>
            </form>

            <p style="margin-bottom:0; margin-top:15px; text-align:center;">
                <a href="{{ route('filament.admin.auth.login') }}">Back to sign in</a>
            </p>
        </div>
    </div>
</body>
</html>
