<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class TestimonialRequest extends FormRequest
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
            'service_used' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ];
    }

    public function messages(): array
    {
        return [
            'service_used.required' => 'Service is required.',
            'service_used.string' => 'Service must be text.',
            'service_used.max' => 'Service cannot exceed 255 characters.',
            'message.required' => 'Message is required.',
            'message.string' => 'Message must be text.',
            'message.max' => 'Message cannot exceed 5000 characters.',
        ];
    }
}
