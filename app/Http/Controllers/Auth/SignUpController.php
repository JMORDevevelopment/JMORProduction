<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SignUpRequest;
use App\Models\Order;
use App\Models\Region;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SignUpController extends Controller
{
    public function showSignUpForm(Request $request)
    {
        $data = [
            'firstname' => old('firstname', ''),
            'lastname' => old('lastname', ''),
            'email' => old('email', ''),
            'password' => '',
            'address' => old('address', ''),
            'city' => old('city', ''),
            'state' => old('state', ''),
            'zip' => old('zip', ''),
            'error' => session('errors') ? session('errors')->getBag('default')->getMessages() : [],
            'regions' => Region::get(),
            'text_sign_up' => __('register.text_sign_up'),
            'text_form' => __('register.text_form'),
            'text_firstname' => __('register.text_firstname'),
            'text_lastname' => __('register.text_lastname'),
            'text_email' => __('register.text_email'),
            'text_password' => __('register.text_password'),
            'text_address' => __('register.text_address'),
            'text_city' => __('register.text_city'),
            'text_state' => __('register.text_state'),
            'text_zip' => __('register.text_zip'),
            'text_region' => __('register.text_region'),
            'text_select_region' => __('register.text_select_region'),
            'text_sign_in' => __('register.text_already_have_account'),
        ];

        return view('frontend.sign_up', $data);
    }

    public function validate(SignUpRequest $request)
    {
        $validated = $request->validated();

        // Insert user
        $insertData = [
            'firstname' => htmlspecialchars($validated['firstname']),
            'lastname' => htmlspecialchars($validated['lastname']),
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'address' => $validated['address'],
            'city' => $validated['city'],
            'state' => $validated['state'],
            'zip' => $validated['zip'],
            'date_added' => date('Y-m-d'),
            'user_group_id' => config('app.c_default_group', 1),
        ];
        $user = User::create($insertData);

        // Prevent session fixation across the privilege change (L7).
        $request->session()->regenerate();

        // Log the user in
        Auth::loginUsingId($user->user_id);
        session()->put('user_id', $user->user_id);

        // Link guest order if exists
        if ($order_id = session()->get('order_id')) {
            Order::where('id', $order_id)->update(['user_id' => $user->user_id]);
        }

        return redirect()->route('checkout');
    }
}
