<?php

use App\Http\Controllers\Api\V1\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);
Route::prefix('v1/auth')->middleware('api.auth')->group(function () {
    Route::get('/', fn() => response()->json(['message' => 'Login success']));

});
