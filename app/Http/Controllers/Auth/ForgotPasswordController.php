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

        if (!$user) {
            return redirect('/forgot-password?error_email=nomatch');
        }

        // Generate new password (same as CI)
        $random = substr(md5(mt_rand()), 0, 8);
        $new_hash = md5($random);

        DB::table('user')->where('email', $email)->update(['password' => $new_hash]);

        // Build the exact same email message as CI
        $message = "<p style='color:black;'><strong>Hi,</strong>" . $user->firstname . "</p>";
        $message .= "<br>";
        $message .= "<strong style='color:black;'>Your email against:</strong>" . $email;
        $message .= "<br>";
        $message .= "<strong style='color:black;'>Your New Password is:</strong>" . $random;

        // Send the email using Laravel's Mail – exact replication of CI's email_queue
        try {
            Mail::html($message, function ($mail) use ($email) {
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