<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Models\Ponpes;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\Admin\LandingController;
use App\Http\Controllers\Admin\LandingContentController;
use App\Http\Controllers\Admin\PonpesController;



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
Route::post('/login', [LoginController::class, 'login'])->name('login');
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

    Route::get('/landing', [LandingContentController::class, 'index'])
        ->name('landing.index');

Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::get('/landing', [LandingPageController::class, 'utama'])->name('landing.index');

    Route::resource('ponpes', PonpesController::class);

    Route::prefix('landing-content')->name('landing-content.')->group(function () {

        Route::get('/', [LandingContentController::class, 'index'])->name('index');
        Route::get('/card-view', [LandingContentController::class, 'indexCard'])->name('card');

        Route::get('/create', [LandingContentController::class, 'create'])->name('create');
        Route::get('/create/{type}', [LandingContentController::class, 'createByType'])->name('create-type');
        Route::post('/', [LandingContentController::class, 'store'])->name('store');

        Route::get('/{landingContent}', [LandingContentController::class, 'show'])->name('show');
        Route::get('/{landingContent}/edit', [LandingContentController::class, 'edit'])->name('edit');
        Route::put('/{landingContent}', [LandingContentController::class, 'update'])->name('update');
        Route::delete('/{landingContent}', [LandingContentController::class, 'destroy'])->name('destroy');

        Route::get('/{id}/detail', [LandingContentController::class, 'getContentDetail'])->name('detail');
        Route::patch('/{id}/toggle-status', [LandingContentController::class, 'toggleStatus'])->name('toggle-status');
        Route::patch('/{id}/update-order', [LandingContentController::class, 'updateOrderSingle'])->name('update-order-single');
        Route::post('/update-order', [LandingContentController::class, 'updateOrder'])->name('update-order');
    });
});
// Kepegawaian Routes
use App\Http\Controllers\KepegawaianController;
use App\Http\Controllers\Auth\AuthController as WebAuthController;

Route::middleware(['auth'])->group(function () {

    Route::get('/kepegawaian', [KepegawaianController::class, 'index'])
        ->name('kepegawaian.index');

    Route::put('/kepegawaian/{id_user}', [KepegawaianController::class, 'update'])
        ->name('kepegawaian.update');

    Route::delete('/kepegawaian/{id_user}', [KepegawaianController::class, 'destroy'])
        ->name('kepegawaian.destroy');
});

// Route untuk membuat session dari API token (dipanggil oleh client setelah API-login)
Route::post('/session/create', [WebAuthController::class, 'createSession'])->name('session.create');

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');