<?php

namespace App\Http\Requests\Checkout;

use Illuminate\Foundation\Http\FormRequest;

class ChargeCreditCardRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Clean and format card data before validation.
     */
    protected function prepareForValidation(): void
    {
        $data = $this->all();

        // Card number: remove all spaces
        if (isset($data['number']) && is_string($data['number'])) {
            $data['number'] = preg_replace('/\s+/', '', $data['number']);
        }

        // Expiry: remove spaces, convert to MM/YY if possible
        if (isset($data['expiry']) && is_string($data['expiry'])) {
            // Remove spaces and other non-digit/non-slash characters
            $cleaned = preg_replace('/[^0-9\/]/', '', $data['expiry']);
            // If it's 6 digits (MMYYYY), convert to MM/YY
            if (preg_match('/^\d{4}$/', $cleaned)) {
                // e.g., "1224" -> "12/24"
                $cleaned = substr($cleaned, 0, 2) . '/' . substr($cleaned, 2, 2);
            } elseif (preg_match('/^\d{6}$/', $cleaned)) {
                // e.g., "122024" -> "12/24" (strip century)
                $cleaned = substr($cleaned, 0, 2) . '/' . substr($cleaned, 2, 2);
            }
            $data['expiry'] = $cleaned;
        }

        // CVC: remove spaces
        if (isset($data['cvc']) && is_string($data['cvc'])) {
            $data['cvc'] = preg_replace('/\s+/', '', $data['cvc']);
        }

        $this->replace($data);
    }

    public function rules(): array
    {
        return [
            'number' => ['required', 'string', 'regex:/^[0-9]{13,19}$/'],
            'expiry' => ['required', 'string', 'regex:/^(0[1-9]|1[0-2])\/([0-9]{2})$/'],
            'cvc'    => ['required', 'string', 'regex:/^[0-9]{3,4}$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'number.required' => 'Credit card number is required.',
            'number.regex'    => 'Please enter a valid credit card number (13–19 digits).',
            'expiry.required' => 'Expiry date is required.',
            'expiry.regex'    => 'Please enter a valid expiry date in MM/YY format (e.g., 12/24).',
            'cvc.required'    => 'CVC code is required.',
            'cvc.regex'       => 'Please enter a valid CVC (3 or 4 digits).',
        ];
    }

}