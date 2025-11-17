<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\SantriController;
use App\Http\Controllers\Api\KeuanganController;
use App\Http\Controllers\Api\KelasController; // Tambahkan ini

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::get('/keuangan', [KeuanganController::class, 'index']);
Route::get('/santri', [SantriController::class, 'index']);
Route::get('/santri/{id}', [SantriController::class, 'show']);

// 🔥 KELAS API ROUTES - Tambahkan ini
Route::get('/kelas', [KelasController::class, 'index']);
Route::get('/kelas/{id}', [KelasController::class, 'show']);
Route::post('/kelas', [KelasController::class, 'store']);
Route::put('/kelas/{id}', [KelasController::class, 'update']);
Route::delete('/kelas/{id}', [KelasController::class, 'destroy']);
Route::get('/kelas/{id}/santri', [KelasController::class, 'getSantri']);

// Protected routes dengan auth (jika perlu)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
