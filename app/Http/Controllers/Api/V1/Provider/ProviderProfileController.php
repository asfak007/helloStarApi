<?php

namespace App\Http\Controllers\Api\V1\Provider;

use App\Helpers\ApiResponseHelper;
use App\Helpers\ImageUploadHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Provider\ProviderProfileUpdateRequest;
use App\Http\Resources\Nrb\NrbProfileResource;
use App\Http\Resources\Provider\providerProfileResource;
use App\Models\User;
use App\Models\UserServiceCategory;

class ProviderProfileController extends Controller
{

    public function getProfile()
    {
        $user = request()->user();

        return $user;

        // return ApiResponseHelper::success(new providerProfileResource($user),"Profile fetched successfully",200);

    }


    public function updateProfile(ProviderProfileUpdateRequest $request)
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

        if ($request->filled('professional_name')) {
            $user->professional_name = $request->professional_name;
        }

        if ($request->filled('email')) {
            $user->email = $request->email;
        }
        // UPDATE PROFESSIONAL CATEGORIES
        if ($request->professional_category_id) {
                foreach ($request->professional_category_id as $cid) {
                    UserServiceCategory::create([
                        'user_id' => $user->id,
                        'category_id' => $cid
                    ]);
                }
        }



        // IMAGE UPLOAD
        if ($request->hasFile('image')) {
            $user->image = ImageUploadHelper::upload(
                $request->file('image'),  // UploadedFile
                'assets/images/provider',        // Folder
                'provider_'.$user->id,     // Old image to delete
                75,                       // Quality
                500,                      // Max width
                500                       // Max height
            );
        }

        if ($request->hasFile('nid_front_side')) {
            $user->nid_front_side = ImageUploadHelper::upload(
                $request->file('nid_front_side'),
                'assets/images/provider/nid',
                'provider_nid_front_'.$user->id,
                75,
                500,
                500
            );
        }

        if ($request->hasFile('nid_back_side')) {
            $user->nid_back_side = ImageUploadHelper::upload(
                $request->file('nid_back_side'),
                'assets/images/provider/nid',
                'provider_nid_back_'.$user->id,
                75,
                500,
                500
            );
        }

        if ($request->hasFile('certificates')) {
            $user->certificates = ImageUploadHelper::upload(
                $request->file('certificates'),
                'assets/images/provider/certificates',
                'provider_certificates_'.$user->id,
                75,
                500,
                500
            );
        }

        $user->save();

        return ApiResponseHelper::success("Profile updated successfully",200);
    }


}
