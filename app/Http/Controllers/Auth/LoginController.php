<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('frontend.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Manual MD5 authentication (legacy)
        $user = DB::table('user')
            ->where('email', $request->email)
            ->where('password', md5($request->password))
            ->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => 'Wrong login details',
            ]);
        }

        // Log in manually
        Auth::loginUsingId($user->user_id, $request->has('remember'));

        // Regenerate session
        $request->session()->regenerate();

        // Store user in custom session (matching CI's User library)
        session()->put('user_id', $user->user_id);
        session()->put('token', bin2hex(random_bytes(25))); // random_token(25)

        // Link guest order (if any)
        if ($order_id = session()->get('order_id')) {
            DB::table('orders')->where('id', $order_id)->update(['user_id' => $user->user_id]);
        }

        // Log IP history
        DB::table('log_history')->insert([
            'user_id' => $user->user_id,
            'ip' => $request->ip(),
        ]);

        // Redirect based on gift_cards session
        if (session()->has('gift_cards')) {
            return redirect('/checkout-confirm');
        }

        return redirect('/checkout');
    }
}