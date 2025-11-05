<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ProfileController;

Route::prefix('v1')->group(function () {
    Route::middleware('throttle:auth')->group(function () {
        Route::post('/auth/login', [AuthController::class, 'login']);
        Route::post('/auth/forgot', [AuthController::class, 'forgotPassword']);
        Route::post('/auth/reset', [AuthController::class, 'resetPassword']);
    });

    Route::post('/auth/register', [AuthController::class, 'register'])->middleware('throttle:auth');

    Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
        Route::get('/auth/me', [AuthController::class, 'me']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);

        // contoh:
        // Route::apiResource('courses', CourseController::class)->middleware('throttle:api');
    });
});
