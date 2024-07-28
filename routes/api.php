<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;

Route::group(
    [
        // 'middleware' => 'api',
        'prefix' => 'auth'
    ],
    function ($router) {
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
        Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api');
        Route::get('/user-profile', [AuthController::class, 'userProfile'])->middleware('auth:api');
    }
);

Route::controller(CategoryController::class)->group(function () {
    Route::get('/categories', 'index');
    Route::post('/categories', 'store');
    Route::get('/categories/{category:slug}', 'show');
    Route::put('/categories/{category:slug}', 'update');
    Route::delete('/categories/{category:slug}', 'destroy');
});
