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
            'lastname'  => old('lastname', ''),
            'email'     => old('email', ''),
            'password'  => '',
            'address'   => old('address', ''),
            'city'      => old('city', ''),
            'state'     => old('state', ''),
            'zip'       => old('zip', ''),
            'error'     => session('errors') ? session('errors')->getBag('default')->getMessages() : [],
            'regions'   => Region::get(),
            'text_sign_up' => 'Sign Up',
            'text_form' => 'You can create an account here.',
            'text_firstname' => 'First Name',
            'text_lastname' => 'Last Name',
            'text_email' => 'Email',
            'text_password' => 'Password',
            'text_address' => 'Address',
            'text_city' => 'City',
            'text_state' => 'State',
            'text_zip' => 'Zip Code',
            'text_region' => 'Region',
            'text_select_region' => 'Select region',
            'text_sign_in' => 'Already have an account?',
        ];

        return view('frontend.sign_up', $data);
    }

    public function validate(SignUpRequest $request)
    {
        $validated = $request->validated();

        // Insert user
        $insertData = [
            'firstname'    => htmlspecialchars($validated['firstname']),
            'lastname'     => htmlspecialchars($validated['lastname']),
            'email'        => htmlspecialchars($validated['email']),
            'password'     => md5($validated['password']),
            'address'      => $validated['address'],
            'city'         => $validated['city'],
            'state'        => $validated['state'],
            'zip'          => $validated['zip'],
            'date_added'   => date('Y-m-d'),
            'user_group_id'=> config('app.c_default_group', 1),
        ];
        $user = User::create($insertData);

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
