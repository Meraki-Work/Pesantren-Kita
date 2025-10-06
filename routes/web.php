<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () { return view('pages.dashboard'); })->name('dashboard'); 
Route::get('/settings', function () { return view('settings'); })->name('settings'); 
Route::get('/notulensi', function () { return view('notulensi'); })->name('notulensi'); 
Route::get('/keuangan', function () { return view('pages.keuangan'); })->name('keuangan'); 
Route::get('/santri', function () { return view('pages.santri'); })->name('santri'); 
Route::get('/inventaris', function () { return view('inventaris'); })->name('inventaris'); 
Route::get('/kepegawaian', function () { return view('kepegawaian'); })->name('kepegawaian');



// Logout (dummy, biasanya pakai Laravel Breeze atau Fortify)
Route::post('/logout', function () {
    // sementara redirect ke home
    return redirect('/');
})->name('logout');
