<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Models\Ponpes;
use App\Http\Controllers\Auth\ResetPasswordController;

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
Route::get('/lupakatasandi', [ResetPasswordController::class, 'showForm'])->name('lupakatasandi');
Route::post('/lupakatasandi/send-otp', [ResetPasswordController::class, 'sendOtp'])->name('password.sendOtp');
Route::post('/lupakatasandi/verify-otp', [ResetPasswordController::class, 'verifyOtp'])->name('password.verifyOtp');
Route::post('/lupakatasandi/update', [ResetPasswordController::class, 'updatePassword'])->name('password.update');

// Landing pages
Route::get('/', function () {return view('landing_utama');})->name('landing_utama');
Route::get('/landing_about', function () {return view('landing_about');})->name('landing_about');
Route::get('/landing_al-amal', function () {return view('landing_al-amal');})->name('landing_al-amal');

// Admin
Route::get('/admin/kepegawaian', function () {return view('admin.kepegawaian');})->name('admin.kepegawaian');
