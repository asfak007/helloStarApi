<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Helpers\ApiResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerProfileRequest;
use App\Http\Requests\CustomerProfileUpdateRequest;
use App\Http\Resources\CustomerProfileResponse;
use App\Http\Resources\CustomerUpdateProfileRequest;

class CustomerProfileController extends Controller
{
    //

    public function getProfile(CustomerProfileRequest $request)
    {
        $user = $request->user();

        return ApiResponseHelper::success(new CustomerProfileResponse($user),"Profile fetched successfully",200);


    }

    public function updateProfile(CustomerProfileUpdateRequest $request)
    {
        $user = $request->user();

        if ($request->all()==[]) {
            return ApiResponseHelper::error("No data provided for update",400);
        }



        // UPDATE NAME
        if ($request->filled('name')) {
            $user->name = $request->name;
        }

        // UPDATE PHONE
        if ($request->filled('phone')) {
            $user->number = $request->phone;
        }

        // IMAGE UPLOAD
        if ($request->hasFile('image')) {


            $image = $request->file('image');

            // Generate file name
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            // Ensure directory exists
            $uploadPath = public_path('assets/images/customers/');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            // Move file
            $image->move($uploadPath, $filename);

            // Save URL
            $user->profile_image = "assets/images/customers/" . $filename;
        }

        $user->save();



        return ApiResponseHelper::success(new CustomerProfileResponse($user),"Profile updated successfully",200);

    }
}
