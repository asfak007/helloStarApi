<?php

namespace App\Http\Controllers\Api\V1\Opt;

use App\Helpers\ApiResponseHelper;
use App\Helpers\OtpHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;

class OtpController extends Controller
{
   public function sendSmsOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
        ]);



        try {
            $data = OtpHelper::generateSmsOtp($request->phone);
            
            return ApiResponseHelper::success([], 'OTP sent successfully');
        } catch (Exception $e) {
            return ApiResponseHelper::error($e->getMessage(), 500);
        }
    }

    // Verify SMS OTP
    public function verifySmsOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'otp_code' => 'required|string',
        ]);

        $isValid = OtpHelper::verifySmsOtp($request->phone, $request->otp_code);

        if ($isValid) {
            return ApiResponseHelper::success([], 'OTP verified successfully');
        }

        return ApiResponseHelper::error('Invalid or expired OTP', 400);
    }

    // Send Email OTP
    public function sendEmailOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        try {
            OtpHelper::generateEmailOtp($request->email);
            return ApiResponseHelper::success([], 'OTP sent successfully to your email');
        } catch (Exception $e) {
            return ApiResponseHelper::error($e->getMessage(), 500);
        }
    }

    // Verify Email OTP
    public function verifyEmailOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp_code' => 'required|string',
        ]);

        $isValid = OtpHelper::verifyEmailOtp($request->otp_code, $request->email);

        if ($isValid) {
            return ApiResponseHelper::success([], 'OTP verified successfully');
        }

        return ApiResponseHelper::error('Invalid or expired OTP', 400);
    }

}
