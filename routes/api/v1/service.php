<?php

use App\Http\Controllers\Api\V1\LocationController;
use App\Http\Controllers\Api\V1\Search\ServiceSearchController;
use App\Http\Controllers\Api\V1\Service\ServiceController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/service')->group(function () {
    Route::get('/', [ServiceController::class, 'index']);
    Route::get('/trending', [ServiceController::class, 'popularServices']);
    Route::get('/demanding', [ServiceController::class, 'demandingServices']);
    Route::post('/service-flow', [ServiceSearchController::class, 'serviceFlow']);


});

Route::prefix('/v1')->group(function () {
    Route::prefix('locations')->group(function () {
    Route::get('divisions', [LocationController::class, 'divisions']);
    Route::get('districts/{division_id}', [LocationController::class, 'districts']);
    Route::get('thanas/{district_id}', [LocationController::class, 'thanas']);
});
});


