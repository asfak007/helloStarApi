<?php

namespace App\Http\Controllers\Api\V1\Opt;

use App\Helpers\ApiResponseHelper;
use App\Helpers\OtpHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\OtpSendRequest;
use Exception;

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
}
