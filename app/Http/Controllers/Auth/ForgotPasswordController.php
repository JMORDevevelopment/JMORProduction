<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    public function showForm()
    {
        return view('frontend.forgot_password');
    }

    public function sendResetLink(Request $request)
    {
        $email = $request->input('email');
        $user = DB::table('user')->where('email', $email)->first();

        if (! $user) {
            return redirect('/forgot-password?error_email=nomatch');
        }

        // Generate new password (same as CI)
        $newPassword = substr(md5(mt_rand()), 0, 8);
        $newHash = md5($newPassword);

        DB::table('user')->where('email', $email)->update(['password' => $newHash]);

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
            // In CI, if email fails, it still redirects – so we do the same
            // Optionally log the error
        }

        return redirect('/login?reset_pass=yes');
    }
}