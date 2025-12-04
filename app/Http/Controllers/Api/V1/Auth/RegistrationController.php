<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRegisterRequest;
use App\Http\Requests\NRBRegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegistrationController extends Controller
{
    public function registerCustomer(CustomerRegisterRequest $request)
    {
        try {
            // Find or create Customer role
            $customerRole = Role::where('name', 'customer')->first();

            if (!$customerRole) {
                $customerRole = Role::create([
                    'name' => 'customer',

                ]);
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

            $token = $user->createToken('customer-auth-token')->plainTextToken;



            return response()->json([
                'success' => true,
                'message' => 'Customer registration successful. Please verify your mobile number.',
                'user' => new UserResource($user),
                'token' => $token, // Optional: remove if not using immediate login
                'requires_verification' => true,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
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
                ]);
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



            return response()->json([
                'success' => true,
                'message' => 'NRB registration successful. Please verify your email address.',
                'user' => new UserResource($user),
                'token' => $token, // Optional: remove if not using immediate login
                'requires_verification' => true,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
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
