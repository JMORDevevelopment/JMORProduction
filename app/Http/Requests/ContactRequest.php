<?php

namespace App\Http\Requests;

use Closure;
use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        // Trim all fields
        $this->merge(array_map('trim', $this->all()));
    }

    public function rules(): array
    {
        return [
            'name'    => ['required', 'string', 'max:255'],
            'email'   => ['required', 'email', 'max:255'],
            'phone'   => ['required', 'string', 'regex:/^[0-9\s\-\+\(\)]+$/', 'max:20'],
            'reason'  => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
            'protection_question' => [
                'required',
                'integer',
                function (string $attribute, mixed $value, Closure $fail) {
                    $expected = (int) $this->input('firstNumber') + (int) $this->input('secondNumber');
                    if ((int) $value !== $expected) {
                        $fail('Your answer is wrong!');
                    }
                },
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'       => 'Please enter your full name.',
            'name.max'            => 'Name cannot exceed 255 characters.',

            'email.required'      => 'Email address is required.',
            'email.email'         => 'Please enter a valid email address.',
            'email.max'           => 'Email cannot exceed 255 characters.',

            'phone.required'      => 'Phone number is required.',
            'phone.regex'         => 'Please enter a valid phone number (digits, spaces, +, -, parentheses).',
            'phone.max'           => 'Phone number cannot exceed 20 characters.',

            'reason.required'     => 'Please select a reason for contacting us.',
            'reason.max'          => 'Reason cannot exceed 255 characters.',

            'message.required'    => 'Please enter your message.',
            'message.max'         => 'Message cannot exceed 5000 characters.',

            'protection_question.required' => 'Please answer the protection question.',
            'protection_question.integer'   => 'Your answer must be a number.',
        ];
    }
}