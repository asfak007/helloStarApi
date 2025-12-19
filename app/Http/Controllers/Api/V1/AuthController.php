<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    //Login Controller
    public function login(LoginUserRequest $request)
    {
        $validated = $request->validated();

        $loginField = $validated['login_field'];
        $password   = $validated['password'];

        // Check if login_field is email or phone
        $credentials = filter_var($loginField, FILTER_VALIDATE_EMAIL)
            ? ['email' => $loginField]
            : ['number' => $loginField];


        $user = User::where($credentials)->with('role')->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 401,
                'success' => false,
                'message' => 'Invalid credentials',
            ], 401);
        }

        if (!$user->is_varified) {
            return response()->json([
                'status' => 403,
                'success' =>false,
                'message' => 'Phone/email not verified',
            ], 403);
        }

        // Generate long Sanctum token
        $token = $user->createToken(Str::random(150))->plainTextToken;

        return response()->json([
            'status' => 200,
            'success' => true,
            'message' => 'Login successful',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user_role' => $user->role->name,
            'user' => new UserResource($user)
        ]);
    }

    public function changePassword(Request $request)
    {
        // Validate request
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:6',
        ]);

        $user = $request->user();

        // Check old password
        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json([
                'status' => 400,
                'success' => false,
                'message' => 'Old password is incorrect',
            ], 400);
        }

        // If old == new
        if ($request->old_password === $request->new_password) {
            return response()->json([
                'status' => 400,
                'success' => false,
                'message' => 'New password cannot be the same as old password',
            ], 400);
        }

        // Save the new password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'status' => 200,
            'success' => true,
            'message' => 'Password changed successfully',
        ], 200);
    }

    public function destroy(Request $request)
    {
        if($request->number){
            $user = User::with(['payoutAccounts','addresses','serviceCategories'])->where('number', $request->number)->first();
        }
        if($request->email){
            $user = User::with(['payoutAccounts','addresses','serviceCategories'])->where('email', $request->email)->first();
        }

        // Delete all tokens
        $user->tokens()->delete();

        // Delete user
        $user->delete();


        return response()->json([
            'status' => 200,
            'success' => true,
            'message' => 'User account deleted successfully',
        ], 200);
    }
}
