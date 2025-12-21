<?php

use App\Http\Controllers\Api\V1\Nrb\NrbProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api.auth', 'role:nrb'])
    ->prefix('v1/nrb')
    ->group(function () {


        Route::prefix('profile')->group(function () {
            Route::get('/', [NrbProfileController::class, 'getProfile']);
            Route::post('/update', [NrbProfileController::class, 'updateProfile']);
        });

    });
