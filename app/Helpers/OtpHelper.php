<?php
namespace App\Helpers;

use App\Models\Otp;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\Mail;

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


    public static function send(string $email, int $otp, string $subject = null, int $expiryMinutes = 5): array
    {
        $subject = $subject ?? "Your OTP Code";

        try {
            $messageBody = "Your OTP is: $otp\nThis code will expire in $expiryMinutes minutes.";

            Mail::raw($messageBody, function ($msg) use ($email, $subject) {
                $msg->to($email)->subject($subject);
            });

            Log::info('Email OTP Sent', [
                'email' => $email,
                'otp' => $otp,
                'subject' => $subject,
            ]);

            return ['success' => true, 'message' => "OTP sent successfully to {$email}"];

        } catch (\Throwable $e) {
            Log::error('Email OTP Sending Failed', [
                'email' => $email,
                'error' => $e->getMessage(),
            ]);

            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    //  OTP throttling (1 minute limit)
    public static function throttle(string $email): bool
    {
        return Otp::where('email', $email)
            ->where('created_at', '>=', now()->subMinute())
            ->exists();
    }

    //  Verify OTP
    public static function verifyEmailOtp(string $otp, string $email): bool
    {
        $record = Otp::where('email', $email)
            ->where('otp_code', $otp)
            ->where('is_used', false)
            ->where('expires_at', '>=', now())
            ->first();

        if (!$record) {
            return false;
        }

        $record->update(['is_used' => true]);
        return true;
    }
}
