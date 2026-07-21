<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            'regions'   => DB::table('region')->get(),
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

    public function validate(Request $request)
    {
        $post = $request->all();
        $error = [];

        // Validation rules (mirroring CI)
        if (empty($post['firstname'])) $error['error_firstname'] = 'First name required';
        if (empty($post['lastname']))  $error['error_lastname']  = 'Last name required';
        if (empty($post['email']))     $error['error_email']     = 'Email required';
        if (empty($post['password']))  $error['error_password']  = 'Password required';
        if (empty($post['city']))      $error['error_city']      = 'Enter city';
        if (empty($post['address']))   $error['error_address']   = 'Enter address';
        if (empty($post['state']))     $error['error_state']     = 'Enter state';
        if (empty($post['zip']))       $error['error_zip']       = 'Enter zip code';

        // Check existing email
        $user_exists = DB::table('user')->where('email', $post['email'])->first();
        if ($user_exists) {
            $error['error_exists'] = 'Email already exists';
        }

        if (!empty($error)) {
            return redirect()->back()->withInput()->withErrors($error);
        }

        // Insert user
        $insertData = [
            'firstname'    => htmlspecialchars($post['firstname']),
            'lastname'     => htmlspecialchars($post['lastname']),
            'email'        => htmlspecialchars($post['email']),
            'password'     => md5($post['password']),
            'address'      => $post['address'],
            'city'         => $post['city'],
            'state'        => $post['state'],
            'zip'          => $post['zip'],
            'date_added'   => date('Y-m-d'),
            'user_group_id'=> config('app.c_default_group', 1),
        ];
        $user_id = DB::table('user')->insertGetId($insertData);

        // Log the user in
        Auth::loginUsingId($user_id);
        session()->put('user_id', $user_id);

        // Link guest order if exists
        if ($order_id = session()->get('order_id')) {
            DB::table('orders')->where('id', $order_id)->update(['user_id' => $user_id]);
        }

        return redirect('/checkout');
    }
}