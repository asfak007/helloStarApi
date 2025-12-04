<?php
namespace App\Helpers;

use App\Models\Otp;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Exception;

class OtpHelper
{
    /**
     * Generate and send OTP
     *
     * @param string $phone
     * @param int $length
     * @param int $expiryMinutes
     * @return Otp
     * @throws Exception
     */
    public static function generateOtp(string $phone, int $length = 4, int $expiryMinutes = 5): Otp
    {
        // Generate numeric OTP
        $otpCode = '';
        for ($i = 0; $i < $length; $i++) {
            $otpCode .= random_int(0, 9);
        }

        // Send SMS
        $smsResponse = SmsHelper::send($phone, "Your OTP is: {$otpCode}");

        if (!$smsResponse['success']) {
            Log::error("OTP sending failed for {$phone}", ['error' => $smsResponse['error'] ?? null]);
            throw new Exception($smsResponse['error'] ?? 'Failed to send OTP');
        }

        // Create OTP record
        $otp = Otp::create([
            'phone' => $phone,
            'otp_code' => $otpCode,
            'expires_at' => Carbon::now()->addMinutes($expiryMinutes),
        ]);

        return $otp;
    }

    /**
     * Verify OTP
     */
    public static function verifyOtp(string $phone, string $otpCode): bool
    {
        $otp = Otp::where('phone', $phone)
            ->where('otp_code', $otpCode)
            ->where('is_used', false)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$otp) {
            return false;
        }

        $otp->is_used = true;
        $otp->save();

        return true;
    }
}
