<?php

namespace App\Http\Requests\Checkout;

use Illuminate\Foundation\Http\FormRequest;

class SaveCheckoutFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Trim all input fields to remove extra whitespace
        $this->merge(
            array_map('trim', $this->all())
        );
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name'      => 'required|string|max:255',
            'mobile'    => 'required|string|regex:/^[0-9\s\-\+\(\)]+$/|max:20',
            'company'   => 'nullable|string|max:255',
            'address'   => 'required|string|max:255',
            'address2'  => 'nullable|string|max:255',
            'city'      => 'required|string|max:255',
            'state'     => 'required|string|max:255',
            'zip'       => 'required|string|regex:/^[a-zA-Z0-9\s\-]+$/|max:10',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'name.required'       => 'Please enter your full name.',
            'name.max'            => 'Name cannot exceed 255 characters.',

            'mobile.required'     => 'Mobile number is required.',
            'mobile.regex'        => 'Please enter a valid mobile number (digits, spaces, +, -, parentheses allowed).',
            'mobile.max'          => 'Mobile number cannot exceed 20 characters.',

            'company.string'      => 'Company name must be text.',
            'company.max'         => 'Company name cannot exceed 255 characters.',

            'address.required'    => 'Street address is required.',
            'address.max'         => 'Address cannot exceed 255 characters.',

            'address2.string'     => 'Address 2 must be text.',
            'address2.max'        => 'Address 2 cannot exceed 255 characters.',

            'city.required'       => 'City is required.',
            'city.max'            => 'City cannot exceed 255 characters.',

            'state.required'      => 'State / Province is required.',
            'state.max'           => 'State cannot exceed 255 characters.',

            'zip.required'        => 'ZIP / Postal code is required.',
            'zip.regex'           => 'ZIP code may contain letters, numbers, spaces, or dashes.',
            'zip.max'             => 'ZIP code cannot exceed 10 characters.',
        ];
    }
}