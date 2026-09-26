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
        $this->merge($this->trimRecursive($this->all()));
    }

    private function trimRecursive($value)
    {
        if (is_array($value)) {
            return array_map([$this, 'trimRecursive'], $value);
        }

        return is_string($value) ? trim($value) : $value;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:80'],
            'email' => ['required', 'email', 'max:50'],
            'phone' => ['required', 'string', 'regex:/^[0-9\s\-\+\(\)]+$/', 'max:20'],
            'reason' => ['required', 'string', 'max:100'],
            'message' => ['required', 'string', 'max:5000'],
            'protection_question' => [
                'required',
                'integer',
                function (string $attribute, mixed $value, Closure $fail) {
                    // Validate against the numbers seeded in session when the
                    // form was rendered; posted firstNumber/secondNumber are
                    // ignored so the challenge cannot be solved client-side (M12).
                    $numbers = session('captcha_numbers');
                    if (! is_array($numbers) || count($numbers) !== 2) {
                        $fail('Your answer is wrong!');

                        return;
                    }
                    $expected = (int) $numbers[0] + (int) $numbers[1];
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
            'name.required' => 'Please enter your full name.',
            'name.max' => 'Name cannot exceed 80 characters.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.max' => 'Email cannot exceed 50 characters.',
            'phone.required' => 'Phone number is required.',
            'phone.regex' => 'Please enter a valid phone number (digits, spaces, +, -, parentheses).',
            'phone.max' => 'Phone number cannot exceed 20 characters.',
            'reason.required' => 'Please select a reason for contacting us.',
            'reason.max' => 'Reason cannot exceed 100 characters.',
            'message.required' => 'Please enter your message.',
            'message.max' => 'Message cannot exceed 5000 characters.',
            'protection_question.required' => 'Please answer the protection question.',
            'protection_question.integer' => 'Your answer must be a number.',
        ];
    }
}
