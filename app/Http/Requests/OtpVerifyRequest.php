<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OtpVerifyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
            'phone' => 'required|string|max:20',
            'otp_code' => 'required|string|max:4',
        ];
    }
    public function messages(): array{
        return [
            'phone.required' => 'Phone number is required.',
            'phone.string' => 'Phone number must be a string.',
            'phone.max' => 'Phone number must be 11 characters.',
            'otp_code.required' => 'OTP code is required.',
            'otp_code.string' => 'OTP code must be a string.',
            'otp_code.max' => 'OTP code must be 4 characters.',
        ];
    }
}
