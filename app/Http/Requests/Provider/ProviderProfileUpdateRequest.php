<?php

namespace App\Http\Requests\Provider;

use Illuminate\Foundation\Http\FormRequest;

class ProviderProfileUpdateRequest extends FormRequest
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
        $userId = $this->user()->id;

        return [
            'name'  => 'nullable|string',
            'phone' => 'nullable|string|unique:users,number,' . $userId,
            'email' => 'nullable|string|unique:users,email,' . $userId,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'professional_category_id'      => 'nullable|array',
            'professional_category_id.*'    => 'exists:categories,id',
            'professional_name'  => 'nullable|string',


            // Optional Files
            'nid_front_side' => 'nullable|image|max:2048',
            'nid_back_side'  => 'nullable|image|max:2048',


            'certificates' => 'nullable|image|max:2048',

        ];
    }
    public function messages(): array
    {
        return [
            'phone.unique' => 'This mobile number is already registered.',
            'image.image'  => 'The uploaded file must be an image.',
            'email.unique' => 'This email is already registered.',
            'name.string' => 'Name must be a string.',
            'professional_name.string' => 'Professional name must be a string.',
            'nid_front_side.image' => 'The NID front side must be an image.',
            'nid_back_side.image'  => 'The NID back side must be an image.',
            'certificates.image'   => 'The certificates must be an image.',
        ];
    }
}
