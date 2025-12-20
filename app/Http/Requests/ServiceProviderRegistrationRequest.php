<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceProviderRegistrationRequest extends FormRequest
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

        // Required Basic Information
        'name'      => 'required|string|max:100',
        'mobile_number'    => 'required|string|max:20|unique:users,number',
        'password'  => 'required|min:6',


        // Optional fields
        'email'     => 'nullable|email|unique:users,email',
        'role_id'   => 'nullable|exists:roles,id',
        'division_id' => 'required|exists:divisions,id',
        'district_id' => 'required|exists:districts,id',
        'thana_id'    => 'required|exists:thanas,id',

        // Optional details
        'professional_category_id'      => 'nullable|array',
        'professional_category_id.*'    => 'exists:categories,id',

        'education_type' => 'nullable|in:ssc,hsc,diploma,bachelor,masters,other',



        // Optional Files
        'nid_front_side' => 'nullable|image|max:2048',
        'nid_back_side'  => 'nullable|image|max:2048',


        'certificates' => 'nullable|image|max:2048',

        // Optional payout
        'payout_type'   => 'nullable|in:mfs,bank',

        // MFS optional
        'mfs_provider'  => 'required_if:payout_type,mfs',
        'mfs_number'    => 'required_if:payout_type,mfs',

        // Bank optional
        'bank_name'     => 'required_if:payout_type,bank',
        'account_name'  => 'required_if:payout_type,bank',
        'account_number'=> 'required_if:payout_type,bank',
    ];
    }
}
