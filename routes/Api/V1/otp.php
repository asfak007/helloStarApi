<?php

use App\Helpers\SmsHelper;
use App\Http\Controllers\Api\V1\Opt\OtpController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/otp')->group(function () {
    Route::post('/send-otp', [OtpController::class, 'sendOtp']);
    Route::post('/verify-otp', [OtpController::class, 'verifyOtp']);

    Route::post('email/send-otp', [OtpController::class, 'send']);
    Route::post('email/verify-otp', [OtpController::class, 'verify']);

});