<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1/customer')->group(function () {
    Route::get('/', fn() => response()->json(['message' => 'Login success']));

});
