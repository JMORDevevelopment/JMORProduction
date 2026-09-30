<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TalkShowGuestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge($this->trimRecursive($this->all()));

        if (is_string($this->input('number'))) {
            $this->merge(['number' => str_replace(' ', '', $this->input('number'))]);
        }

        if (is_string($this->input('expiry'))) {
            $this->merge(['expiry' => str_replace(' ', '', $this->input('expiry'))]);
        }
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:96'],
            'services' => ['sometimes', 'array'],
            'services.*' => ['string'],
            'ans' => ['sometimes', 'array'],
            'ans.*' => ['nullable', 'string'],
            'number' => ['required', 'string', 'regex:/^[0-9]{13,19}$/'],
            'expiry' => ['required', 'string', 'regex:/^(0[1-9]|1[0-2])\/([0-9]{2})$/'],
            'cvc' => ['required', 'string', 'regex:/^[0-9]{3,4}$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Enter name',
            'email.required' => 'Enter email',
            'email.email' => 'Enter email',
            'number.required' => 'Enter card number',
            'number.regex' => 'Enter a valid card number',
            'expiry.required' => 'Enter card expiry',
            'expiry.regex' => 'Enter expiry as MM / YY',
            'cvc.required' => 'Enter CCV',
            'cvc.regex' => 'Enter a valid CCV',
        ];
    }
}
