<?php

namespace App\Http\Requests\Checkout;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ChargeCreditCardRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        // Trim and remove spaces from card number and expiry
        $this->merge([
            'number' => preg_replace('/\s+/', '', trim($this->input('number') ?? '')),
            'expiry' => preg_replace('/\s+/', '', trim($this->input('expiry') ?? '')),
            'cvc'    => trim($this->input('cvc') ?? ''),
        ]);
    }

    public function rules(): array
    {
        return [
            'number' => ['required', 'string', 'regex:/^[0-9]{13,19}$/'],
            'expiry' => ['required', 'string', 'regex:/^(0[1-9]|1[0-2])\/?([0-9]{2})$/'],
            'cvc'    => ['required', 'string', 'regex:/^[0-9]{3,4}$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'number.required' => 'Credit card number is required.',
            'number.regex'    => 'Please enter a valid credit card number (13–19 digits).',

            'expiry.required' => 'Expiry date is required.',
            'expiry.regex'    => 'Please enter a valid expiry date in MM/YY or MMYY format.',

            'cvc.required'    => 'CVC code is required.',
            'cvc.regex'       => 'Please enter a valid CVC (3 or 4 digits).',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            redirect()->route('checkout.confirm', ['failed' => 'true'])
        );
    }
}