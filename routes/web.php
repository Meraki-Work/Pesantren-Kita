<?php

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

use App\Http\Controllers\SanksiController;

use App\Http\Controllers\LaundryController;
use App\Http\Controllers\Auth\LoginController;

use App\Http\Controllers\Auth\RegisterController;

use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Support\Facades\DB;


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

Route::get('/keuangan', [KeuanganController::class, 'index'])->name('keuangan');
// Routes Keuangan
Route::get('/keuangan', [KeuanganController::class, 'index'])->name('keuangan.index');
Route::get('/keuangan/create', [KeuanganController::class, 'create'])->name('keuangan.create');
Route::post('/keuangan', [KeuanganController::class, 'store'])->name('keuangan.store');
Route::get('/keuangan/{id}/edit', [KeuanganController::class, 'edit'])->name('keuangan.edit');
Route::put('/keuangan/{id}', [KeuanganController::class, 'update'])->name('keuangan.update');
Route::delete('/keuangan/{id}', [KeuanganController::class, 'destroy'])->name('keuangan.destroy');


Route::get('/cash', [CashController::class, 'index'])->name('cash');

Route::get('/dashboard', function () { return view('pages.dashboard'); })->name('dashboard'); 
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


// Inventaris Routes
Route::get('/inventaris', [InventarisController::class, 'index'])->name('inventaris.index');
Route::get('/inventaris/create', [InventarisController::class, 'create'])->name('inventaris.create');
Route::post('/inventaris', [InventarisController::class, 'store'])->name('inventaris.store');
Route::get('/inventaris/{id}', [InventarisController::class, 'show'])->name('inventaris.show');
Route::get('/inventaris/{id}/edit', [InventarisController::class, 'edit'])->name('inventaris.edit');
Route::put('/inventaris/{id}', [InventarisController::class, 'update'])->name('inventaris.update');
Route::delete('/inventaris/{id}', [InventarisController::class, 'destroy'])->name('inventaris.destroy');

// API Routes
Route::get('/api/inventaris/chart-data', [InventarisController::class, 'getChartData']);
Route::get('/api/inventaris/stats', [InventarisController::class, 'getStats']);
Route::get('/inventaris/export', [InventarisController::class, 'export'])->name('inventaris.export');

// Notulen Routes
Route::get('/notulensi', [NotulenController::class, 'index'])->name('notulen.index');
Route::get('/notulen/create', [NotulenController::class, 'create'])->name('notulen.create'); // ✅
Route::post('/notulen', [NotulenController::class, 'store'])->name('notulen.store');
Route::get('/notulen/{id}', [NotulenController::class, 'show'])->name('notulen.show');
Route::get('/notulen/{id}/edit', [NotulenController::class, 'edit'])->name('notulen.edit');
Route::put('/notulen/{id}', [NotulenController::class, 'update'])->name('notulen.update');
Route::delete('/notulen/{id}', [NotulenController::class, 'destroy'])->name('notulen.destroy');

Route::get('/sangksi', [SanksiController::class, 'index'])->name('sangksi');
Route::get('/sangksi/create', [SanksiController::class, 'create'])->name('sangksi.create');
Route::post('/sangksi', [SanksiController::class, 'store'])->name('sangksi.store');
Route::get('/sangksi/{sanksi}', [SanksiController::class, 'show'])->name('sangksi.show');
Route::get('/sangksi/{sanksi}/edit', [SanksiController::class, 'edit'])->name('sangksi.edit');
Route::put('/sangksi/{sanksi}', [SanksiController::class, 'update'])->name('sangksi.update');
Route::delete('/sangksi/{sanksi}', [SanksiController::class, 'destroy'])->name('sangksi.destroy');


// Route debugging - sementara saja
Route::get('/debug-gambar', function() {
    $gambar = \App\Models\Gambar::with('notulen')
        ->whereNotNull('id_notulen')
        ->whereNotNull('path_gambar')
        ->orderBy('created_at', 'desc')
        ->take(10)
        ->get();
    
    echo "<h1>Data Gambar dari Database:</h1>";
    foreach ($gambar as $g) {
        echo "<div style='border:1px solid #ccc; padding:10px; margin:10px;'>";
        echo "<strong>ID:</strong> " . $g->id_gambar . "<br>";
        echo "<strong>Path Gambar:</strong> " . $g->path_gambar . "<br>";
        echo "<strong>Notulen ID:</strong> " . $g->id_notulen . "<br>";
        echo "<strong>Agenda:</strong> " . ($g->notulen->agenda ?? 'Tidak ada agenda') . "<br>";
        echo "<strong>Created At:</strong> " . $g->created_at . "<br>";
        
        // Test URL
        $url = asset('storage/' . $g->path_gambar);
        echo "<strong>URL:</strong> <a href='{$url}' target='_blank'>{$url}</a><br>";
        
        // Test if file exists
        $fileExists = file_exists(public_path('storage/' . $g->path_gambar));
        echo "<strong>File Exists:</strong> " . ($fileExists ? '✅ YA' : '❌ TIDAK') . "<br>";
        
        echo "</div>";
    }
    
    return "Debug selesai - lihat output di atas";
});

// Route untuk hapus gambar individual
Route::delete('/notulen/gambar/{id}', [NotulenController::class, 'hapusGambar'])->name('notulen.hapus-gambar');

// Route untuk tambah gambar ke notulen yang sudah ada
Route::post('/notulen/{id}/tambah-gambar', [NotulenController::class, 'tambahGambar'])->name('notulen.tambah-gambar');

// API Routes
Route::get('/api/notulen/stats', [NotulenController::class, 'getStats']);
Route::get('/notulen/{id}/export', [NotulenController::class, 'export'])->name('notulen.export');

Route::get('/santri', [SantriController::class, 'index'])->name('santri.index');
Route::get('/santri/create', [SantriController::class, 'create'])->name('santri.create');
Route::post('/santri', [SantriController::class, 'store'])->name('santri.store');

Route::get('/kepegawaian', function () { return view('kepegawaian'); })->name('kepegawaian');

// Logout (dummy, biasanya pakai Laravel Breeze atau Fortify)
Route::post('/logout', function () {
    // sementara redirect ke home
    return redirect('/');
})->name('logout');
// Login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Registrasi
Route::get('/registrasi', function () {
    $ponpes = Ponpes::select('id_ponpes', 'nama_ponpes')->get();
    return view('auth.registrasi', compact('ponpes'));
})->name('registrasi.index');

Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
Route::get('/verify', [RegisterController::class, 'verifyForm'])->name('verify.form');
Route::post('/verify', [RegisterController::class, 'verifyOtp'])->name('verify.otp');

// Lupa kata sandi 
Route::get('/lupakatasandi', [ResetPasswordController::class, 'showForm'])->name('lupakatasandi');
Route::post('/lupakatasandi/send-otp', [ResetPasswordController::class, 'sendOtp'])->name('password.sendOtp');
Route::post('/lupakatasandi/verify-otp', [ResetPasswordController::class, 'verifyOtp'])->name('password.verifyOtp');
Route::post('/lupakatasandi/update', [ResetPasswordController::class, 'updatePassword'])->name('password.update');

// Landing pages
Route::get('/', function () {return view('landing_utama');})->name('landing_utama');
Route::get('/landing_about', function () {return view('landing_about');})->name('landing_about');
Route::get('/landing_al-amal', function () {return view('landing_al-amal');})->name('landing_al-amal');
