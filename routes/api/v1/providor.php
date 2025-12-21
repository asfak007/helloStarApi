<?php

use App\Http\Controllers\Api\V1\Provider\ProviderProfileController;
use Illuminate\Support\Facades\Route;


Route::middleware(['api.auth', 'role:provider'])
    ->prefix('v1/provider')
    ->group(function () {


        Route::prefix('profile')->group(function () {
            Route::get('/', [ProviderProfileController::class, 'getProfile']);
            Route::post('/update', [ProviderProfileController::class,'updateProfile']);
        });

    });
