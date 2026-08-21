<?php

namespace App\Http\Requests;

use Closure;
use Illuminate\Foundation\Http\FormRequest;

class RequestInformationRequest extends FormRequest
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
            'first_name' => ['required', 'string', 'max:80'],
            'last_name' => ['required', 'string', 'max:80'],
            'company' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:80'],
            'phone' => ['required', 'string', 'max:50'],
            'fax' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'suite' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:80'],
            'zip' => ['nullable', 'string', 'max:80'],
            'service_intersted' => ['required', 'string', 'max:100'],
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
            'first_name.required' => 'Enter first name is required.',
            'first_name.max' => 'First name cannot exceed 80 characters.',
            'last_name.required' => 'Enter last name is required.',
            'last_name.max' => 'Last name cannot exceed 80 characters.',
            'company.required' => 'Enter company is required.',
            'company.max' => 'Company cannot exceed 100 characters.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.max' => 'Email cannot exceed 80 characters.',
            'phone.required' => 'Enter Phone.',
            'phone.max' => 'Phone cannot exceed 50 characters.',
            'service_intersted.required' => 'Please select a service you are interested in.',
            'service_intersted.max' => 'Service cannot exceed 100 characters.',
            'message.required' => 'Enter message details.',
            'message.max' => 'Message cannot exceed 5000 characters.',
            'protection_question.required' => 'Please answer the protection question.',
            'protection_question.integer' => 'Your answer must be a number.',
        ];
    }
}
