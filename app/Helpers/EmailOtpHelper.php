<?php

namespace App\Helpers;

use App\Models\Otp;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Exception;

class EmailOtpHelper
{
    public static function generateOtp(string $email, int $length = 4, int $expiryMinutes = 5): Otp
    {
        // Generate numeric OTP
        $otpCode = '';
        for ($i = 0; $i < $length; $i++) {
            $otpCode .= random_int(0, 9);
        }

        // Send Email OTP
        $response = OtpHelper::send($email, (int)$otpCode, null, $expiryMinutes);

        if (!$response['success']) {
            Log::error("OTP sending failed for {$email}", ['error' => $response['error'] ?? null]);
            throw new Exception($response['error'] ?? 'Failed to send OTP');
        }

        // Save OTP to database
        $otp = Otp::create([
            'email'      => $email,
            'otp_code'   => $otpCode,
            'expires_at' => Carbon::now()->addMinutes($expiryMinutes),
        ]);

        return $otp;
    }
}
