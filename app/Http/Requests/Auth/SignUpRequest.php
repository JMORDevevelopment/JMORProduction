<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class SignUpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'firstname' => ['required', 'string'],
            'lastname' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:user,email'],
            'password' => ['required', 'string'],
            'address' => ['required', 'string'],
            'city' => ['required', 'string'],
            'state' => ['required', 'string'],
            'zip' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'firstname.required' => 'First name required',
            'lastname.required' => 'Last name required',
            'email.required' => 'Email required',
            'email.email' => 'Email required',
            'email.unique' => 'Email already exists',
            'password.required' => 'Password required',
            'address.required' => 'Enter address',
            'city.required' => 'Enter city',
            'state.required' => 'Enter state',
            'zip.required' => 'Enter zip code',
        ];
    }
}
