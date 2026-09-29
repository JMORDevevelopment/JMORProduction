<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password — JMOR Connection</title>
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}">
</head>
<body style="background:#f4f4f4;">
    <div style="max-width:420px; margin:80px auto;">
        <div style="background:#fff; border:1px solid #e5e5e5; padding:30px;">
            <h4 style="margin-top:0;">JMOR Connection</h4>
            <p class="text-muted">Choose a new admin password</p>

            @if($errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('admin.reset-password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <div class="form-group">
                    <label for="password">New password</label>
                    <input type="password" id="password" name="password" class="form-control"
                           minlength="8" required autofocus>
                </div>
                <div class="form-group" style="margin-top:15px;">
                    <label for="password_confirmation">Confirm password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                           class="form-control" minlength="8" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block" style="margin-top:15px;">
                    Reset password
                </button>
            </form>

            <p style="margin-bottom:0; margin-top:15px; text-align:center;">
                <a href="{{ route('filament.admin.auth.login') }}">Back to sign in</a>
            </p>
        </div>
    </div>
</body>
</html>
