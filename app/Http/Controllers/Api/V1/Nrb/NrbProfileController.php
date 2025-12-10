<?php

namespace App\Http\Controllers\Api\V1\Nrb;

use App\Helpers\ApiResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Nrb\NrbProfileUpdateRequest;
use App\Http\Resources\Nrb\NrbProfileResource;
use App\Helpers\ImageUploadHelper;

class NrbProfileController extends Controller
{
    //

    public function getProfile()
    {
        $user = request()->user();

        return ApiResponseHelper::success(new NrbProfileResource($user),"Profile fetched successfully",200);

    }


    public function updateProfile(NrbProfileUpdateRequest $request)
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
            $user->image = ImageUploadHelper::upload(
                $request->file('image'),  // UploadedFile
                'assets/images/nrb',        // Folder
                'nrb_'.$user->id,     // Old image to delete
                75,                       // Quality
                500,                      // Max width
                500                       // Max height
            );
        }

        $user->save();

        return ApiResponseHelper::success(new NrbProfileResource($user),"Profile updated successfully",200);



    }
}
