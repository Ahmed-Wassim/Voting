<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\IdeaController;
use App\Http\Controllers\SpamController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\VoteController;


// Auth Routes
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

// Category Routes
Route::controller(CategoryController::class)->group(function () {
    Route::get('/categories', 'index');
    Route::post('/categories', 'store')->middleware('auth:api');
    Route::get('/categories/{category:slug}', 'show');
    Route::put('/categories/{category:slug}', 'update')->middleware('auth:api');
    Route::delete('/categories/{category:slug}', 'destroy')->middleware('auth:api');
});

// Idea Routes
Route::controller(IdeaController::class)->group(function () {
    Route::get('/ideas', 'index');
    Route::post('/ideas', 'store')->middleware('auth:api');
    Route::get('/ideas/{idea:slug}', 'show')->name('idea.show');
    Route::put('/ideas/{idea:slug}', 'update')->middleware('auth:api');
    Route::delete('/ideas/{idea:slug}', 'destroy')->middleware('auth:api');
    Route::patch('/idea/{idea:slug}/status', 'updateStatus')->middleware('auth:api');
});

// Status Routes
Route::get('/status', StatusController::class);

// Vote Routes
Route::post('/idea/{idea:slug}/vote', VoteController::class)->middleware('auth:api');

// Spam idea
Route::post('idea/{idea:slug}/spam', [SpamController::class, 'markAsSpam'])->middleware('auth:api');
Route::post('idea/{idea:slug}/notSpam', [SpamController::class, 'markAsNotSpam'])->middleware('auth:api');

// Comment Resource
Route::post('/idea/{idea:slug}/comment', [CommentController::class, 'store'])->middleware('auth:api');
Route::put('/idea/{idea:slug}/comment/{comment}/update', [CommentController::class, 'update'])->middleware('auth:api');
Route::delete('/idea/{idea:slug}/comment/{comment}/delete', [CommentController::class, 'destroy'])->middleware('auth:api');