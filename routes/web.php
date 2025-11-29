<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Models\Ponpes;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\Admin\LandingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Login
Route::get('/login', function () {
    return view('auth.login');
})->name('login');
// Route::post('/login', [LoginController::class, 'login'])->name('login');
// Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Registrasi
Route::get('/registrasi', function () {
    $ponpes = Ponpes::select('id_ponpes', 'nama_ponpes')->get();
    return view('auth.registrasi', compact('ponpes'));
})->name('registrasi.index');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
// Route::get('/verify', [RegisterController::class, 'verifyForm'])->name('verify.form');
Route::get('/verify-otp', function () {
    return view('auth.verify-otp');
})->name('verify.form');

// Lupa kata sandi
Route::get('/lupakatasandi', function () {
    return view('auth.lupakatasandi');
})->name('lupakatasandi');

// ==========================
// LANDING PAGE USER
// ==========================

Route::get('/', [LandingPageController::class, 'utama'])->name('landing_utama');
Route::get('/landing_about', [LandingPageController::class, 'about'])->name('landing_about');
Route::get('/landing_al-amal', [LandingPageController::class, 'alAmal'])->name('landing_al-amal');


// ==========================
// ADMIN PANEL
// ==========================

Route::prefix('admin')->group(function () {

    Route::get('/kepegawaian', function () {
        return view('admin.kepegawaian');
    })->name('admin.kepegawaian');

    Route::get('/landing', [LandingController::class, 'index'])
        ->name('admin.landing.index');

});