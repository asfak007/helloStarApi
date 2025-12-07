<?php


use App\Http\Controllers\Api\V1\Opt\OtpController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/otp')->group(function () {
    Route::post('/send-otp', [OtpController::class, 'sendSmsOtp']);
    Route::post('/verify-otp', [OtpController::class, 'verifySmsOtp']);

    Route::post('email/send-otp', [OtpController::class, 'sendEmailOtp']);
    Route::post('email/verify-otp', [OtpController::class, 'verifyEmailOtp']);

});
