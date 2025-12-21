<?php

use App\Http\Controllers\Api\V1\Customer\CustomerProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api.auth', 'role:customer'])
    ->prefix('v1/customer')
    ->group(function () {

        Route::get('/', fn() => response()->json(['message' => 'Login success']));

        Route::prefix('profile')->group(function () {
            Route::get('/', [CustomerProfileController::class, 'getProfile']);
            Route::post('/update', [CustomerProfileController::class, 'updateProfile']);
        });

    });
