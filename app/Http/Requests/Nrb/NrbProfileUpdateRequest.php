<?php

namespace App\Http\Requests\Nrb;

use Illuminate\Foundation\Http\FormRequest;

class NrbProfileUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
   public function authorize(): bool
    {
        return true; // Allow all authenticated users
    }

    public function rules(): array
    {
        $userId = $this->user()->id;

        return [
            'name'  => 'nullable|string',
            'email' => 'nullable|string|unique:users,email,' . $userId,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'This email address is already registered.',
            'image.image'  => 'The uploaded file must be an image.',
        ];
    }
}

