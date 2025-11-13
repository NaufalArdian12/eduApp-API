<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\OAuthController;
use App\Http\Controllers\Api\V1\TokenController;
use App\Http\Controllers\Api\V1\AuthController;

// prefix versi (lebih rapi)
Route::prefix('v1')->group(function () {
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);

    Route::post('/auth/oauth/google/exchange', [OAuthController::class, 'exchange']);
    Route::post('/auth/oauth/google/link', [OAuthController::class, 'link'])->middleware('auth:sanctum');

    Route::post('/auth/refresh', [TokenController::class, 'refresh']);
    Route::post('/auth/logout', [TokenController::class, 'logout'])->middleware('auth:sanctum');

    Route::get('/me', fn() => request()->user())->middleware('auth:sanctum');
});
