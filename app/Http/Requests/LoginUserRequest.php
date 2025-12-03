<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */

    public function authorize(): bool
    {
        return true; // allow access
    }

    public function rules(): array
    {
        return [
            // login_field will be email OR phone
            'login_field' => 'required|string',

            // password check
            'password' => 'required|string|min:6',
        ];
    }

    public function messages(): array
    {
        return [
            'login_field.required' => 'Email or phone is required.',
            'password.required' => 'Password is required.',
        ];
    }
}
