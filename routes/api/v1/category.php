<?php


use App\Http\Controllers\Api\V1\Category\CategoryConroller;
use App\Http\Controllers\Api\V1\Service\ServiceController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/category')->group(function () {
    Route::get('/', [CategoryConroller::class, 'index']);
    Route::get('/Service/{id}', [CategoryConroller::class, 'categoryService']);

});