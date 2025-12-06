<?php

namespace App\Http\Controllers\Api\V1\Opt;

use App\Helpers\ApiResponseHelper;
use App\Helpers\EmailOtpGenerator;
use App\Helpers\EmailOtpHelper;
use App\Helpers\OtpHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\OtpSendRequest;
use App\Models\Otp;
use Exception;
use Illuminate\Http\Request;

class OtpController extends Controller
{
    //
    public function sendOtp(OtpSendRequest $request)
    {
        $validated = $request->validated();
        $phone = $validated['phone'];

        try {
            $otp = OtpHelper::generateOtp($phone);

            // Only return the OTP code in development/testing
            return ApiResponseHelper::success([
                'otp' => $otp->otp_code
            ], 'OTP sent successfully');

        } catch (Exception $e) {
            return ApiResponseHelper::error($e->getMessage(), 500);
        }
    }

    public function verifyOtp(Request $request)
    {
        $validated = $request->validated();
        $phone = $validated['phone'];
        $otp_code = $validated['otp_code'];


        $isValid = OtpHelper::verifyOtp($phone, $otp_code);

        if ($isValid) {
            return response()->json([
                'status' => true,
                'message' => 'OTP verified successfully'
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Invalid or expired OTP'
        ], 400);
    }

    // Send OTP
    public function send(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        try {
            $otp = EmailOtpHelper::generateOtp($request->email);
            return response()->json([
                'success' => true,
                'message' => 'OTP sent successfully to your email.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // Verify OTP
    public function verify(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp'   => 'required|numeric',
        ]);

        $otpRecord = Otp::where('email', $request->email)
            ->where('otp_code', $request->otp)
            ->where('is_used', false)
            ->where('expires_at', '>=', Carbon::now())
            ->first();

        if (!$otpRecord) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired OTP.',
            ], 400);
        }

        $otpRecord->update(['is_used' => true]);

        return response()->json([
            'success' => true,
            'message' => 'OTP verified successfully.',
        ]);
    }

}
