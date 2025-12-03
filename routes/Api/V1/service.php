<?php

use App\Http\Controllers\Api\V1\Service\ServiceController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/service')->group(function () {
    Route::get('/', [ServiceController::class, 'index']);

});