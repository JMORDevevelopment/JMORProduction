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


    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'email' => ['required', 'email'],
            'phone' => ['required', 'string'],
            'reason' => ['required', 'string'],
            'message' => ['required', 'string'],
            'protection_question' => [
                'required',
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
            'name.required' => 'Enter Name',
            'email.required' => 'Enter Email',
            'email.email' => 'Enter Email',
            'phone.required' => 'Enter Phone',
            'reason.required' => 'Enter Reason',
            'message.required' => 'Enter Message',
            'protection_question.required' => 'Your answer is wrong!',
        ];
    }
}
