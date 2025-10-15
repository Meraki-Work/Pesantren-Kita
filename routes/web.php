<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Models\Ponpes;
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


Route::get('/login', function () {return view('auth.login');})->name('login');

// Halaman form registrasi
Route::get('/registrasi', function () {
    $ponpes = Ponpes::select('id_ponpes', 'nama')->get();
    return view('auth.registrasi', compact('ponpes'));
})->name('registrasi.index');

// Proses simpan data + kirim OTP
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

// Form verifikasi OTP
Route::get('/verify', [RegisterController::class, 'verifyForm'])->name('verify.form');

// Proses verifikasi OTP
Route::post('/verify', [RegisterController::class, 'verifyOtp'])->name('verify.otp');


Route::get('/ubahkatasandi', function () {return view('auth.ubahkatasandi');})->name('ubahkatasandi');
Route::get('/landing_utama', function () {return view('landing_utama');})->name('landing_utama');
Route::get('/landing_about', function () {return view('landing_about');})->name('landing_about');
Route::get('/landing_al-amal', function () {return view('landing_al-amal');})->name('landing_al-amal');





