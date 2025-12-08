<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerProfileRequest extends FormRequest
{

    
    public function authorize(): bool
    {
        return true; // Allow all authenticated users
    }

    public function rules(): array
    {
        $userId = $this->user()->id;

        return [
            'name'  => 'nullable|string|max(255)',
            'phone' => 'nullable|string|max(20)|unique:users,phone,' . $userId,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'phone.unique' => 'This mobile number is already registered.',
            'image.image'  => 'The uploaded file must be an image.',
        ];
    }
}
