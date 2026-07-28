<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    public function showForm()
    {
        return view('frontend.forgot_password');
    }

    public function sendResetLink(ForgotPasswordRequest $request)
    {
        $email = $request->validated('email');
        $user = User::where('email', $email)->first();

        if (! $user) {
            return redirect()->route('forgot-password', ['error_email' => 'nomatch']);
        }

        // Generate new password (same as CI)
        $newPassword = substr(md5(mt_rand()), 0, 8);
        $newHash = md5($newPassword);

        User::where('email', $email)->update(['password' => $newHash]);

        try {
            Mail::send('mails.forgot-password', [
                'firstname' => $user->firstname,
                'email' => $email,
                'newPassword' => $newPassword,
            ], function ($mail) use ($email) {
                $mail->to($email)
                    ->subject('Reset Password')
                    ->from('Info@jmor.com', 'Info@jmor.com');
            });
        } catch (\Exception $e) {

        }

        return redirect()->route('login', ['reset_pass' => 'yes']);
    }
}
