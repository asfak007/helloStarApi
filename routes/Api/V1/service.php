<?php

use App\Http\Controllers\Api\V1\Search\ServiceSearchController;
use App\Http\Controllers\Api\V1\Service\ServiceController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/service')->group(function () {
    Route::get('/', [ServiceController::class, 'index']);
    Route::get('/trending', [ServiceController::class, 'popularServices']);
    Route::get('/demanding', [ServiceController::class, 'demandingServices']);
    Route::post('/service-flow', [ServiceSearchController::class, 'serviceFlow']);


});
