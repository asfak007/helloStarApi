<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerProfileUpdateRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true; // Allow all authenticated users
    }

    public function rules(): array
    {
        $userId = $this->user()->id;

        return [
            'name'  => 'nullable|string',
            'phone' => 'nullable|string|unique:users,number,' . $userId,
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
