<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        // Send email (you can use Laravel's Mail facade or your own logic)
        // For now we just redirect with success flag
        return redirect('/login?reset_pass=yes');
    }
}