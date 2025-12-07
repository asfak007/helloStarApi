<?php

namespace App\Helpers;

use App\Models\Otp;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Exception;

class OtpHelper
{
    // Generate SMS OTP
    public static function generateSmsOtp(string $phone, int $length = 4, int $expiryMinutes = 5)
    {
        if (self::throttleSms($phone)) {
            throw new Exception('Please wait 1 minute before requesting another OTP.');
        }

        $otpCode = '';
        for ($i = 0; $i < $length; $i++) {
            $otpCode .= random_int(0, 9);
        }

        $smsResponse = SmsHelper::send($phone, "Your OTP is: $otpCode");



        if (!$smsResponse['success']) {
            throw new Exception($smsResponse['error'] ?? 'Failed to send OTP');
        }

        $otpRecord = Otp::create([
            'phone' => $phone,
            'otp_code' => $otpCode,
            'expires_at' => now()->addMinutes($expiryMinutes),
            'is_used' => false,
        ]);

        Log::info('SMS OTP Sent', [
            'phone' => $phone,
            'otp_code' => $otpCode,
            'sms_response' => $smsResponse,
        ]);

        return $otpRecord;
    }

    // Verify SMS OTP
    public static function verifySmsOtp(string $phone, string $otpCode): bool
    {
        $user = User::where('number', $phone)->first();
        if($user) {
            $user->is_varified = true;
            $user->save();
        }
        $record = Otp::where('phone', $phone)
            ->where('otp_code', $otpCode)
            ->where('is_used', false)
            ->where('expires_at', '>=', now())
            ->first();

        if (!$record) return false;

        $record->update(['is_used' => true]);
        return true;
    }

    // Throttle  (1 per minute)
    public static function throttleSms(string $phone): bool
    {
        return Otp::where('phone', $phone)
            ->where('created_at', '>=', now()->subMinute())
            ->exists();
    }

    // Generate Email OTP
    public static function generateEmailOtp(string $email, int $length = 4, int $expiryMinutes = 5): Otp
    {
        if (self::throttleEmail($email)) {
            throw new Exception('Please wait 1 minute before requesting another OTP.');
        }

        $otpCode = '';
        for ($i = 0; $i < $length; $i++) {
            $otpCode .= random_int(0, 9);
        }

        $messageBody = "Your OTP is: $otpCode\nThis code will expire in $expiryMinutes minutes.";

        Mail::raw($messageBody, function ($msg) use ($email) {
            $msg->to($email)->subject('Your OTP Code');
        });

        $otpRecord = Otp::create([
            'email' => $email,
            'otp_code' => $otpCode,
            'expires_at' => now()->addMinutes($expiryMinutes),
            'is_used' => false,
        ]);

        Log::info('Email OTP Sent', [
            'email' => $email,
            'otp_code' => $otpCode,
        ]);

        return $otpRecord;
    }

    // Verify Email OTP
    public static function verifyEmailOtp(string $otpCode, string $email): bool
    {

        $user = User::where('email', $email)->first();
        if($user) {
            $user->is_varified = true;
            $user->save();
        }

        $record = Otp::where('email', $email)
            ->where('otp_code', $otpCode)
            ->where('is_used', false)
            ->where('expires_at', '>=', now())
            ->first();

        if (!$record) return false;

        $record->update(['is_used' => true]);
        return true;
    }

    // Throttle Email OTP (1 per minute)
    public static function throttleEmail(string $email): bool
    {
        return Otp::where('email', $email)
            ->where('created_at', '>=', now()->subMinute())
            ->exists();
    }
}
