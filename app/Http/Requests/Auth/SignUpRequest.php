<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class SignUpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge(array_map('trim', $this->all()));
    }

    public function rules(): array
    {
        return [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname'  => ['required', 'string', 'max:255'],
            'email'     => ['required', 'email', 'max:255', 'unique:user,email'],
            'password'  => ['required', 'string', 'min:8', 'max:255'],
            'address'   => ['required', 'string', 'max:255'],
            'city'      => ['required', 'string', 'max:255'],
            'state'     => ['required', 'string', 'max:255'],
            'zip'       => ['required', 'string', 'regex:/^[a-zA-Z0-9\s\-]+$/', 'max:10'],
        ];
    }

    public function messages(): array
    {
        return [
            'firstname.required' => 'First name is required.',
            'firstname.max'      => 'First name cannot exceed 255 characters.',

            'lastname.required'  => 'Last name is required.',
            'lastname.max'       => 'Last name cannot exceed 255 characters.',

            'email.required'     => 'Email address is required.',
            'email.email'        => 'Please enter a valid email address.',
            'email.max'          => 'Email cannot exceed 255 characters.',
            'email.unique'       => 'This email is already registered.',

            'password.required'  => 'Password is required.',
            'password.min'       => 'Password must be at least 8 characters.',
            'password.max'       => 'Password cannot exceed 255 characters.',

            'address.required'   => 'Address is required.',
            'address.max'        => 'Address cannot exceed 255 characters.',

            'city.required'      => 'City is required.',
            'city.max'           => 'City cannot exceed 255 characters.',

            'state.required'     => 'State / Province is required.',
            'state.max'          => 'State cannot exceed 255 characters.',

            'zip.required'       => 'ZIP / Postal code is required.',
            'zip.regex'          => 'ZIP code may contain letters, numbers, spaces, or dashes.',
            'zip.max'            => 'ZIP code cannot exceed 10 characters.',
        ];
    }
}