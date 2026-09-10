<?php

namespace App\Http\Requests\Checkout;

use Illuminate\Foundation\Http\FormRequest;

class SaveCheckoutFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Recursively trim all string values (preserve arrays)
     */
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
            'First_Name' => 'required|string|max:255',
            'Mobile' => 'required|string|regex:/^[0-9\s\-\+\(\)]+$/|max:20',
            'Company' => 'nullable|string|max:255',
            'Address' => 'required|string|max:255',
            'Address_2' => 'nullable|string|max:255',
            'City' => 'required|string|max:255',
            'State' => 'required|string|max:255',
            'Zip' => 'required|string|regex:/^[a-zA-Z0-9\s\-]+$/|max:10',
        ];
    }

    public function messages(): array
    {
        return [
            'First_Name.required' => 'Please enter your full name.',
            'First_Name.max' => 'Name cannot exceed 255 characters.',

            'Mobile.required' => 'Mobile number is required.',
            'Mobile.regex' => 'Please enter a valid mobile number (digits, spaces, +, -, parentheses allowed).',
            'Mobile.max' => 'Mobile number cannot exceed 20 characters.',

            'Company.string' => 'Company name must be text.',
            'Company.max' => 'Company name cannot exceed 255 characters.',

            'Address.required' => 'Street address is required.',
            'Address.max' => 'Address cannot exceed 255 characters.',

            'Address_2.string' => 'Address 2 must be text.',
            'Address_2.max' => 'Address 2 cannot exceed 255 characters.',

            'City.required' => 'City is required.',
            'City.max' => 'City cannot exceed 255 characters.',

            'State.required' => 'State / Province is required.',
            'State.max' => 'State cannot exceed 255 characters.',

            'Zip.required' => 'ZIP / Postal code is required.',
            'Zip.regex' => 'ZIP code may contain letters, numbers, spaces, or dashes.',
            'Zip.max' => 'ZIP code cannot exceed 10 characters.',
        ];
    }
}
