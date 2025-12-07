<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRegisterRequest extends FormRequest
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
            'mobile_number' => 'required|string|max:20',
            'password' => 'required|string|min:8',
            'terms' => 'required|accepted',
        ];
    }

    public function messages(): array
    {
        return [
            'mobile_number.unique' => 'This mobile number is already registered.',
            'terms.accepted' => 'You must accept the Terms of Use and Privacy Policy.',
        ];
    }
}
