<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Helpers\OtpHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRegisterRequest;
use App\Http\Requests\NRBRegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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

            if (User::where('email', $request->email)->exists()) {
                return response()->json([
                    'status' => 400,
                    'success' => false,
                    'message' => 'Email already registered',
                ], 400);
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

}
