<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NRBRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'terms' => 'required|accepted',
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'This email address is already registered.',
            'terms.accepted' => 'You must accept the Terms of Use and Privacy Policy.',
        ];
    }
}
