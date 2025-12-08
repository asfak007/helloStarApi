<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
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

        $user = User::where($credentials)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([

                'success' => 401,
                'message' => 'Invalid credentials',
            ], 401);
        }

        if (!$user->is_varified) {
            return response()->json([
                'success' => 403,
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
            'user' => $user
        ]);
    }
}
