<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Api\LandingController;

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

/*
|---------------------------------------------------------------------------
| Landing Page API Routes
|---------------------------------------------------------------------------
| Mengelola seluruh konten landing page:
| - Carousel (gambar + teks)
| - Leader (founder & kepala yayasan)
| - Galeri foto
| - Footer (informasi kontak & sosial media)
|
| Seluruh endpoint digunakan oleh admin untuk update konten landing page.
*/
// ================= LANDING PAGE =================
Route::prefix('landing')->group(function () {

    // Carousel
    Route::get('/carousel', [LandingController::class, 'getCarousel']);
    Route::post('/carousel', [LandingController::class, 'storeCarousel']);

    // About
    Route::get('/about', [LandingController::class, 'getAbout']);
    Route::post('/about/update', [LandingController::class, 'updateAbout']);

    // Gallery
    Route::get('/gallery', [LandingController::class, 'getGallery']);
    Route::post('/gallery', [LandingController::class, 'storeGallery']);

    // Footer
    Route::post('/footer', [LandingController::class, 'storeFooter']);
});

