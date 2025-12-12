<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Helpers\ImageUploadHelper;
use App\Helpers\OtpHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRegisterRequest;
use App\Http\Requests\NRBRegisterRequest;
use App\Http\Requests\ServiceProviderRegistrationRequest;
use App\Http\Requests\StudentAndProfissonalRegistrationRequest;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\Service;
use App\Models\User;
use App\Models\UserDetail;
use App\Models\UserServiceCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Image;

class RegistrationController extends Controller
{

    // Check if user with given number or email is already registered
    public function checkUserRegistrationStatus($number = null, $email = null)
    {
        // Check email first
        if ($email !== null) {
            if (User::where('email', $email)->where('is_varified', true)->exists()) {
                return response()->json([
                    'status' => 409,
                    'success' => false,
                    'message' => 'Email already registered and verified. Please login.',
                ], 409);
            }

            if (User::where('email', $email)->where('is_varified', false)->exists()) {

                OtpHelper::generateEmailOtp($email);

                return response()->json([
                    'status' => 400,
                    'success' => false,
                    'message' => 'Email already registered but not verified. OTP sent successfully. Please verify your email.',
                ], 400);
            }
        }

        // Check mobile number
        if ($number !== null) {
            if (User::where('number', $number)->where('is_varified', true)->exists()) {
                return response()->json([
                    'status' => 409,
                    'success' => false,
                    'message' => 'Mobile number already registered and verified. Please login.',
                ], 409);
            }

            if (User::where('number', $number)->where('is_varified', false)->exists()) {
                OtpHelper::generateSmsOtp($number);
                return response()->json([
                    'status' => 400,
                    'success' => false,
                    'message' => 'Mobile number already registered but not verified. OTP sent successfully. Please verify your number.',
                ], 400);
            }
        }

        // No conflict found
        return null;
    }

    public function registerCustomer(CustomerRegisterRequest $request)
    {
        try {
            // Find or create Customer role
            $customerRole = Role::where('name', 'customer')->first();

            if (!$customerRole) {
                $customerRole = Role::create([
                    'name' => 'customer',
                    'guard_name' => 'customer'

                ]);
            }


            $response = $this->checkUserRegistrationStatus($request->mobile_number, null);
            if ($response) {
                return $response; // stops registration if conflict exists
            }



            // Generate referral token
            $refToken = $this->generateRefToken();

            $user = User::create([
                'name' => $request->name,
                'number' => $request->mobile_number,
                'email' => null, // Customer doesn't use email
                'role_id' => $customerRole->id,
                'password' => Hash::make($request->password),
                'ref_token' => $refToken,
                'point' => 0,
                'is_varified' => false, // Will be verified via OTP
            ]);

            $otp  = OtpHelper::generateSmsOtp($user->number);

            $token = $user->createToken('customer-auth-token')->plainTextToken;



            return response()->json([
                'success' => true,
                'message' => 'Customer registration successful. OTP sent successfully. Please verify your mobile number.',
                'user' => new UserResource($user),
                'otp' => $otp,
                // 'token' => $token, // Optional: remove if not using immediate login
                'requires_verification' => true,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'success' => false,
                'message' => 'Registration failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function registerNRB(NRBRegisterRequest $request)
    {
        try {
            // Find or create NRB role
            $nrbRole = Role::where('name', 'nrb')->first();

            if (!$nrbRole) {
                $nrbRole = Role::create([
                    'name' => 'nrb',
                    'guard_name' => 'nrb'
                ]);
            }

            $response = $this->checkUserRegistrationStatus($request->email, null);
            if ($response) {
                return $response; // stops registration if conflict exists
            }

            // Generate referral token
            $refToken = $this->generateRefToken();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'number' => null,
                'role_id' => $nrbRole->id,
                'password' => Hash::make($request->password),
                'ref_token' => $refToken,
                'point' => 0,
                'is_varified' => false, // Will be verified via email
            ]);

            $token = $user->createToken('nrb-auth-token')->plainTextToken;

            $otp = OtpHelper::generateEmailOtp($user->email);

            return response()->json([
                'status' => 201,
                'success' => true,
                'message' => 'NRB registration successful. OTP sent successfully. Please verify your email address.',
                'user' => new UserResource($user),
                'otp' => $otp,
                'requires_verification' => true,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'success' => false,
                'message' => 'Registration failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate unique referral token
     */
    private function generateRefToken(): string
    {
        do {
            $token = strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
        } while (User::where('ref_token', $token)->exists());

        return $token;
    }

    public function registerServiceProvider(ServiceProviderRegistrationRequest $request)
    {
        DB::beginTransaction();


        $providerRole = Role::where('name', 'provider')->first();

            if (!$providerRole) {
                $providerRole = Role::create([
                    'name' => 'provider',
                    'guard_name' => 'provider'
                ]);
            }


            $response = $this->checkUserRegistrationStatus($request->mobile_number, null);
            if ($response) {
                return $response; // stops registration if conflict exists
            }

        try {

            // Create User (only required fields)
            $user = User::create([
                'name'      => $request->name,
                'email'     => $request->email,
                'number'    => $request->mobile_number,
                'password'  => Hash::make($request->password),
                'role_id'   => $providerRole, // optional default role
            ]);


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

            // Create user_details only if ANY optional detail is provided
            if (
                $request->professional_category_id ||
                $request->education_type ||
                $request->division_id ||
                $request->district_id ||
                $request->thana_id ||
                $request->address ||
                $request->permanent_address ||
                $nidFront ||
                $nidBack ||
                $certPaths
            ) {
                UserDetail::create([
                    'user_id' => $user->id,
                    'professional_category_id' => $request->professional_category_id
                        ? json_encode($request->professional_category_id)
                        : null,
                    'education_type' => $request->education_type,
                    'division_id'  => $request->division_id,
                    'district_id'  => $request->district_id,
                    'thana_id'     => $request->thana_id,
                    'area'         => $request->address,
                    'permanent_address' => $request->permanent_address,
                    'nid_front_side' => $nidFront,
                    'nid_back_side'  => $nidBack,
                    'certificates'   => $certPaths,
                ]);
            }

            // Save categories in pivot table if provided
            if ($request->professional_category_id) {
                foreach ($request->professional_category_id as $cid) {
                    UserServiceCategory::create([
                        'user_id' => $user->id,
                        'category_id' => $cid
                    ]);
                }
            }

            // Save payout info if provided
            if ($request->payout_type) {

                $payoutData = [
                    'type' => $request->payout_type,
                    'mfs' => $request->payout_type === 'mfs'
                        ? [
                            'provider' => $request->mfs_provider,
                            'number'   => $request->mfs_number,
                        ]
                        : null,

                    'bank' => $request->payout_type === 'bank'
                        ? [
                            'bank_name'      => $request->bank_name,
                            'account_name'   => $request->account_name,
                            'account_number' => $request->account_number,
                        ]
                        : null,
                ];

                $user->payout = json_encode($payoutData);
                $user->save();
            }

            DB::commit();

            $otp = OtpHelper::generateSmsOtp($user->number);

            return response()->json([
                'status' => 201,
                'success' => true,
                'message' => 'Service Provider registration successful. OTP sent successfully. Please verify your mobile number.',
                'user' => new UserResource($user),
                'otp' => $otp,
                'requires_verification' => true,
            ], 201);

        } catch (\Exception $e) {

            DB::rollBack();
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function studentProfessionalsRegister(StudentAndProfissonalRegistrationRequest $request)
    {
        DB::beginTransaction();


        $providerRole = Role::where('name', 'provider')->first();

            if (!$providerRole) {
                $providerRole = Role::create([
                    'name' => 'provider',
                    'guard_name' => 'provider'
                ]);
            }


            $response = $this->checkUserRegistrationStatus($request->mobile_number, null);
            if ($response) {
                return $response; // stops registration if conflict exists
            }

        try {

            // Create User (only required fields)
            $user = User::create([
                'name'      => $request->name,
                'email'     => $request->email,
                'number'    => $request->mobile_number,
                'password'  => Hash::make($request->password),
                'role_id'   => $request->role_id ?? 3, // optional default role
            ]);


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

            // Create user_details only if ANY optional detail is provided
            if (
                $request->professional_category_id ||
                $request->education_type ||
                $request->division_id ||
                $request->district_id ||
                $request->thana_id ||
                $request->address ||
                $request->permanent_address ||
                $nidFront ||
                $nidBack ||
                $certPaths
            ) {
                UserDetail::create([
                    'user_id' => $user->id,
                    'professional_category_id' => $request->professional_category_id
                        ? json_encode($request->professional_category_id)
                        : null,
                    'education_type' => $request->education_type,
                    'division_id'  => $request->division_id,
                    'district_id'  => $request->district_id,
                    'thana_id'     => $request->thana_id,
                    'area'         => $request->address,
                    'permanent_address' => $request->permanent_address,
                    'nid_front_side' => $nidFront,
                    'nid_back_side'  => $nidBack,
                    'certificates'   => $certPaths,
                ]);
            }

            // Save categories in pivot table if provided
            if ($request->professional_category_id) {
                foreach ($request->professional_category_id as $cid) {
                    UserServiceCategory::create([
                        'user_id' => $user->id,
                        'category_id' => $cid
                    ]);
                }
            }

            // Save payout info if provided
            if ($request->payout_type) {

                $payoutData = [
                    'type' => $request->payout_type,
                    'mfs' => $request->payout_type === 'mfs'
                        ? [
                            'provider' => $request->mfs_provider,
                            'number'   => $request->mfs_number,
                        ]
                        : null,

                    'bank' => $request->payout_type === 'bank'
                        ? [
                            'bank_name'      => $request->bank_name,
                            'account_name'   => $request->account_name,
                            'account_number' => $request->account_number,
                        ]
                        : null,
                ];

                $user->payout = json_encode($payoutData);
                $user->save();
            }

            DB::commit();

            $otp = OtpHelper::generateSmsOtp($user->number);

            return response()->json([
                'status' => 201,
                'success' => true,
                'message' => ' registration successful. OTP sent successfully. Please verify your mobile number.',
                'user' => new UserResource($user),
                'otp' => $otp,
                'requires_verification' => true,
            ], 201);

        } catch (\Exception $e) {

            DB::rollBack();
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ], 500);
        }
    }

}
