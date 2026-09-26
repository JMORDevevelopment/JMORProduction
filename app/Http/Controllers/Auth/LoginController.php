<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\LogHistory;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('frontend.login');
    }

    public function login(LoginRequest $request)
    {
        // Throttle failed attempts per email + IP (H2).
        $throttleKey = Str::transliterate(Str::lower($request->input('email')).'|'.$request->ip());

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            throw ValidationException::withMessages([
                'email' => 'Too many login attempts. Please try again in '.RateLimiter::availableIn($throttleKey).' seconds.',
            ]);
        }

        // Validates bcrypt hashes directly and transparently upgrades legacy
        // MD5 hashes (see MD5EloquentUserProvider::validateCredentials).
        if (! Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            RateLimiter::hit($throttleKey);

            throw ValidationException::withMessages([
                'email' => 'Wrong login details',
            ]);
        }

        RateLimiter::clear($throttleKey);

        $user = Auth::user();

        // Regenerate session
        $request->session()->regenerate();

        // Store user in custom session (matching CI's User library)
        session()->put('user_id', $user->user_id);
        session()->put('token', bin2hex(random_bytes(25))); // random_token(25)

        // Link guest order (if any)
        if ($order_id = session()->get('order_id')) {
            Order::where('id', $order_id)->update(['user_id' => $user->user_id]);
        }

        // Log IP history
        LogHistory::create([
            'user_id' => $user->user_id,
            'ip' => $request->ip(),
        ]);

        // Redirect based on gift_cards session
        if (session()->has('gift_cards')) {
            return redirect()->route('checkout.confirm');
        }

        return redirect()->route('checkout');
    }
}
