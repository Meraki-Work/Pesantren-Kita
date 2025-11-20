<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ResetPasswordController;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes (requires token)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

// Password reset routes
Route::post('/password/send-otp', [ResetPasswordController::class, 'sendOtp']);
Route::post('/password/verify-otp', [ResetPasswordController::class, 'verifyOtp']);
Route::post('/password/update', [ResetPasswordController::class, 'updatePassword']);
