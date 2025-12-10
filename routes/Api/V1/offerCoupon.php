<?php

use App\Http\Controllers\Api\V1\CouponController;
use App\Http\Controllers\Api\V1\OfferController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/offer')->group(function () {
    Route::get('/', [OfferController::class, 'index']);
});

Route::prefix('v1/coupon')->group(function () {
    Route::get('/', [CouponController::class, 'index']);
});
