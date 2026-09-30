<?php

namespace App\Http\Requests;

use Closure;
use Illuminate\Foundation\Http\FormRequest;

class TalkShowInterviewRequest extends FormRequest
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
            'last_name' => ['required', 'string', 'max:80'],
            'company_name' => ['nullable', 'string', 'max:100'],
            'phone' => ['required', 'string', 'regex:/^[0-9\s\-\+\(\)]+$/', 'max:20'],
            'email' => ['required', 'email', 'max:50'],
            'bio' => ['required', 'string', 'max:999'],
            'work_name' => ['required', 'array', 'min:1'],
            'work_name.*' => ['string', 'max:500'],
            'work_detail' => ['required', 'array', 'min:1'],
            'work_detail.*' => ['string', 'max:5000'],
            'weblink' => ['required', 'array', 'min:1'],
            'weblink.*' => ['string', 'max:500'],
            'interview' => ['required', 'string', 'max:10000'],
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
            'name.required' => 'Please enter your first name.',
            'name.max' => 'First name cannot exceed 80 characters.',
            'last_name.required' => 'Please enter your last name.',
            'last_name.max' => 'Last name cannot exceed 80 characters.',
            'company_name.max' => 'Company name cannot exceed 100 characters.',
            'phone.required' => 'Phone number is required.',
            'phone.regex' => 'Please enter a valid phone number (digits, spaces, +, -, parentheses).',
            'phone.max' => 'Phone number cannot exceed 20 characters.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.max' => 'Email cannot exceed 50 characters.',
            'bio.required' => 'Please enter your bio.',
            'bio.max' => 'Bio cannot exceed 999 characters.',
            'work_name.required' => 'Please add at least one show or experience.',
            'work_name.min' => 'Please add at least one show or experience.',
            'work_name.*.max' => 'Show or experience name cannot exceed 500 characters.',
            'work_detail.required' => 'Please add at least one show or experience.',
            'work_detail.min' => 'Please add at least one show or experience.',
            'work_detail.*.max' => 'Description cannot exceed 5000 characters.',
            'weblink.required' => 'Please add at least one show or experience.',
            'weblink.min' => 'Please add at least one show or experience.',
            'weblink.*.max' => 'Web link cannot exceed 500 characters.',
            'interview.required' => 'Please enter your interview pitch concept.',
            'interview.max' => 'Interview pitch cannot exceed 10000 characters.',
            'protection_question.required' => 'Please answer the protection question.',
            'protection_question.integer' => 'Your answer must be a number.',
        ];
    }
}
