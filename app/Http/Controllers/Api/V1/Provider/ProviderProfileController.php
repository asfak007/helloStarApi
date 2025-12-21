<?php

namespace App\Http\Controllers\Api\V1\Provider;

use App\Helpers\ApiResponseHelper;
use App\Helpers\ImageUploadHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Provider\ProviderProfileUpdateRequest;

use App\Http\Resources\Provider\providerProfileResource;
use App\Models\UserDetail;
use App\Models\UserServiceCategory;

class ProviderProfileController extends Controller
{

    public function getProfile()
    {
        $user = request()->user();


        return ApiResponseHelper::success(new providerProfileResource($user),"Profile fetched successfully",200);

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

        if ($request->filled('email')) {
            $user->email = $request->email;
        }
        $user->save();
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

            // Upload optional files
            $nidFront = $request->hasFile('nid_front_side')
                ? ImageUploadHelper::upload(
                    $request->file('nid_front_side'),  // UploadedFile
                    'assets/images/nid',        // Folder
                    'nid_front_'.$user->id,     // Old image to delete
                    75,                       // Quality
                    800,                      // Max width
                    800                       // Max height
                )
                : null;

            $nidBack = $request->hasFile('nid_back_side')
                ? ImageUploadHelper::upload(
                    $request->file('nid_back_side'),  // UploadedFile
                    'assets/images/nid',        // Folder
                    'nid_back_'.$user->id,     // Old image to delete
                    75,                       // Quality
                    800,                      // Max width
                    800                       // Max height
                )
                : null;

            $certPaths = $request->hasFile('certificates')
            ? ImageUploadHelper::upload(
                $request->file('certificates'),  // UploadedFile array
                'assets/images/certificates',    // Folder
                'cert_'.$user->id,               // Old image prefix to delete
                75,                               // Quality
                800,                              // Max width
                800                               // Max height
            ):
            null
            ;

                    if (
                $request->professional_category_id ||
                $request->education_type ||
                $nidFront ||
                $nidBack ||
                $certPaths
            ) {
                UserDetail::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'user_id' => $user->id,
                        'professional_category_id' => $request->professional_category_id
                            ? json_encode($request->professional_category_id)
                            : null,
                        // 'education_type' => $request->education_type,
                    'professional_name' => $request->professional_name,
                    'permanent_address' => $request->permanent_address,
                    'nid_front_side' => $nidFront,
                    'nid_back_side'  => $nidBack,
                    'certificates'   => $certPaths,
                ]);
            }



        return ApiResponseHelper::success("Profile updated successfully",200);
    }


}
