<?php

namespace App\Http\Requests;

use Closure;
use Illuminate\Foundation\Http\FormRequest;

class MediaInquiryRequest extends FormRequest
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
            'media' => ['required', 'string', 'max:100'],
            'contact' => ['required', 'string', 'max:60'],
            'email' => ['required', 'email', 'max:50'],
            'phone' => ['required', 'string', 'max:60'],
            'story_concept' => ['required', 'string', 'max:100'],
            'press_deadline' => ['required', 'date'],
            'story_details' => ['required', 'string', 'max:5000'],
            'best_contact' => ['required', 'string', 'max:5000'],
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
            'media.required' => 'Please enter the media name.',
            'media.max' => 'Media cannot exceed 100 characters.',
            'contact.required' => 'Please enter a contact name.',
            'contact.max' => 'Contact cannot exceed 60 characters.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.max' => 'Email cannot exceed 50 characters.',
            'phone.required' => 'Phone number is required.',
            'phone.max' => 'Phone cannot exceed 60 characters.',
            'story_concept.required' => 'Please enter the story concept.',
            'story_concept.max' => 'Story concept cannot exceed 100 characters.',
            'press_deadline.required' => 'Please enter the press deadline.',
            'press_deadline.date' => 'Please enter a valid date.',
            'story_details.required' => 'Please enter the story details.',
            'story_details.max' => 'Story details cannot exceed 5000 characters.',
            'best_contact.required' => 'Please enter the best day and time to contact.',
            'best_contact.max' => 'Best contact cannot exceed 5000 characters.',
            'protection_question.required' => 'Please answer the protection question.',
            'protection_question.integer' => 'Your answer must be a number.',
        ];
    }
}
