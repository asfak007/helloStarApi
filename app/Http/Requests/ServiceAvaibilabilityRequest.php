<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceAvaibilabilityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // login_field will be email OR phone
            'service_id' => 'required',

            // password check
            'thana_id' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'service_id.required' => 'service_id is required.',
            'thana_id.required' => 'thana_id is required.',
        ];
    }
}
