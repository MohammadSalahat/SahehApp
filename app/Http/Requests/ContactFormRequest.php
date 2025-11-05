<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name' => 'required|min:2|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|min:10',
        ];
    }

    public function messages(): array
    {
        return [
            'full_name.required' => __('validation.custom.full_name.required'),
            'full_name.min' => __('validation.custom.full_name.min'),
            'email.required' => __('validation.custom.email.required'),
            'email.email' => __('validation.custom.email.email'),
            'message.required' => __('validation.custom.message.required'),
            'message.min' => __('validation.custom.message.min'),
        ];
    }
}
