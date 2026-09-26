<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function showForm()
    {
        return view('frontend.forgot_password');
    }

    public function sendResetLink(ForgotPasswordRequest $request)
    {
        Password::sendResetLink($request->only('email'));

        // Uniform response whether the account exists, the link was sent,
        // or the request was throttled — prevents email enumeration (H1).
        return redirect()->route('login', ['reset_pass' => 'yes']);
    }
}
