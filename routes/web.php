<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\KeuanganController;
use App\Http\Controllers\DummyController;
use App\Http\Controllers\InventarisController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\NotulenController;
use App\Http\Controllers\CashController;
use App\Http\Controllers\SantriController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\KompetensiController;
use App\Http\Controllers\PencapaianController;
use App\Http\Controllers\LaundryController;
use Illuminate\Support\Facades\DB;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

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

Route::get('/keuangan', [KeuanganController::class, 'index'])->name('keuangan');
// Routes Keuangan
Route::get('/keuangan', [KeuanganController::class, 'index'])->name('keuangan.index');
Route::get('/keuangan/create', [KeuanganController::class, 'create'])->name('keuangan.create');
Route::post('/keuangan', [KeuanganController::class, 'store'])->name('keuangan.store');
Route::get('/keuangan/{id}/edit', [KeuanganController::class, 'edit'])->name('keuangan.edit');
Route::put('/keuangan/{id}', [KeuanganController::class, 'update'])->name('keuangan.update');
Route::delete('/keuangan/{id}', [KeuanganController::class, 'destroy'])->name('keuangan.destroy');


Route::post('/dashboard', [DashboardController::class, 'store'])->name('dashboard.store');
Route::get('/dashboard/absensi', [DashboardController::class, 'getAbsensi'])->name('dashboard.absensi');
Route::get('/dashboard/absensi/all', [DashboardController::class, 'getAllAbsensi'])->name('dashboard.absensi.all');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/grafik-prestasi', [DashboardController::class, 'getGrafikPrestasi'])->name('dashboard.prestasi');


Route::get('/cash', [CashController::class, 'index'])->name('cash');

Route::get('/', [DashboardController::class, 'index']);
Route::get('/settings', function () { return view('settings'); })->name('settings'); 


Route::get('/kelas/create', [KelasController::class, 'create'])->name('kelas.create');
Route::post('/kelas/store', [KelasController::class, 'store'])->name('kelas.store');

Route::get('/kompetensi', [KompetensiController::class, 'index'])->name('kompetensi.index');
// Routes untuk Laundry/Keuangan
Route::get('/laundry', [LaundryController::class, 'index'])->name('laundry.index');
Route::post('/laundry', [LaundryController::class, 'store'])->name('laundry.store');
Route::get('/laundry/{id}/edit', [LaundryController::class, 'edit'])->name('laundry.edit');
Route::put('/laundry/{id}', [LaundryController::class, 'update'])->name('laundry.update');
Route::delete('/laundry/{id}', [LaundryController::class, 'destroy'])->name('laundry.destroy');

// Route untuk contoh data (opsional)
Route::get('/laundry/insert-example', [LaundryController::class, 'insertExampleData']);
Route::post('/kompetensi/store', [KompetensiController::class, 'store'])->name('kompetensi.store');




// Rute untuk lazy loading modal
Route::get('/modal/{type}', function ($type) {
    $allowedModals = ['create_kelas', 'create_santri'];
    
    if (!in_array($type, $allowedModals)) {
        abort(404, 'Modal tidak ditemukan');
    }
    
    // Cek jika view exists
    $viewPath = "pages.modal.{$type}";
    if (!view()->exists($viewPath)) {
        abort(404, "View {$viewPath} tidak ditemukan");
    }
    
    return view($viewPath);
})->name('modal.load');


// Rute untuk pencapaian
Route::get('/pencapaian/{id}/edit', [App\Http\Controllers\PencapaianController::class, 'edit'])->name('pencapaian.edit');
Route::put('/pencapaian/{id}', [App\Http\Controllers\PencapaianController::class, 'update'])->name('pencapaian.update');
Route::delete('/pencapaian/{id}', [App\Http\Controllers\PencapaianController::class, 'destroy'])->name('pencapaian.destroy');

Route::get('/santri/{id}/kompetensi', function ($id) {
    $kompetensi = DB::table('pencapaian as p')
        ->join('santri as s', 's.id_santri', '=', 'p.id_santri')
        ->where('s.id_santri', $id)
        ->select('p.judul', 'p.deskripsi', 'p.tipe', 'p.tanggal', 'p.skor')
        ->orderBy('p.tanggal', 'desc')
        ->get();
    
    return response()->json($kompetensi);
})->name('santri.kompetensi');

Route::get('/inventaris', [InventarisController::class, 'index'])->name('inventaris');
Route::get('/notulensi', [NotulenController::class, 'index'])->name('notulensi');

Route::get('/santri', [SantriController::class, 'index'])->name('santri.index');
Route::get('/santri/create', [SantriController::class, 'create'])->name('santri.create');
Route::post('/santri', [SantriController::class, 'store'])->name('santri.store');

Route::get('/kepegawaian', function () { return view('kepegawaian'); })->name('kepegawaian');

// Logout (dummy, biasanya pakai Laravel Breeze atau Fortify)
Route::post('/logout', function () {
    // sementara redirect ke home
    return redirect('/');
})->name('logout');
