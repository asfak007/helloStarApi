<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1/auth')->middleware('api.auth')->group(function () {
    Route::get('/', fn() => response()->json(['message' => 'Login success']));

});
