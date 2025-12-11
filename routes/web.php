<?php


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\DashboardController;
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
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\SanksiController;
use App\Http\Controllers\LaundryController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\KepegawaianController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Models\Ponpes;
use App\Models\Gambar;
use Illuminate\Support\Facades\Mail; // <- ini penting
use App\Mail\TestEmail;
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

// ==================== PUBLIC ROUTES ====================

use App\Services\BrevoApiService;

// Landing Page Public Routes
Route::get('/landing/{identifier}', [LandingPageController::class, 'showPublic'])->name('landing.public.show');
Route::get('/ponpes/{identifier}', [LandingPageController::class, 'showPublic'])->name('ponpes.public.show');
Route::get('/pesantren/{identifier}', [LandingPageController::class, 'showPublic'])->name('pesantren.public.show');

// List all ponpes for public
Route::get('/ponpes-list', [LandingPageController::class, 'listPublic'])->name('landing.public.list');
Route::get('/pesantren-list', [LandingPageController::class, 'listPublic'])->name('pesantren.public.list');

// Search ponpes
Route::get('/cari-pesantren', function () {
    $keyword = request('q');
    if ($keyword) {
        $ponpesList = \App\Models\Ponpes::where('status', 'Aktif')
            ->where(function ($query) use ($keyword) {
                $query->where('nama_ponpes', 'like', "%{$keyword}%")
                    ->orWhere('alamat', 'like', "%{$keyword}%")
                    ->orWhere('pimpinan', 'like', "%{$keyword}%");
            })
            ->orderBy('nama_ponpes')
            ->paginate(12);

        return view('landing.search', compact('ponpesList', 'keyword'));
    }

    return redirect()->route('landing.public.list');
})->name('landing.search');

Route::get('/test-email', function () {
    $brevo = new BrevoApiService();

    try {
        $res = $brevo->sendEmail(
            'sandybom21@gmail.com',
            'Tes Email dari Brevo API',
            '<h1>Berhasil!</h1><p>Email via API Brevo.</p>'
        );

        return response()->json([
            'status' => 'success',
            'data' => $res
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
});


// Landing Pages
Route::get('/', function () {
    return view('landing_utama');
})->name('landing_utama');

Route::get('/', [LandingPageController::class, 'utama'])->name('landing_utama');
Route::get('/landing_about', [LandingPageController::class, 'about'])->name('landing_about');
Route::get('/landing_al-amal', [LandingPageController::class, 'alAmal'])->name('landing_al-amal');


Route::get('/about', function () {
    return view('landing_about');
})->name('landing_about');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::get('/landing_al-amal', function () {
    return view('landing_al-amal');
})->name('landing_al-amal');

// Subscription Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/subscription/plans', [App\Http\Controllers\SubscriptionController::class, 'plans'])->name('subscription.plans');
    Route::get('/subscription/upgrade', [App\Http\Controllers\SubscriptionController::class, 'upgrade'])->name('subscription.upgrade');
    Route::post('/subscription/upgrade', [App\Http\Controllers\SubscriptionController::class, 'processUpgrade'])->name('subscription.process-upgrade');
    Route::get('/subscription/success', [App\Http\Controllers\SubscriptionController::class, 'success'])->name('subscription.success');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');

    // Registration
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('registrasi.index');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
    Route::get('/verify-otp', [RegisterController::class, 'verifyForm'])->name('verify.form');
    Route::post('/verify-otp', [RegisterController::class, 'verifyOtp'])->name('verify.otp');
    Route::post('/resend-otp', [RegisterController::class, 'resendOtp'])->name('resend.otp');

    // ✅ SIMPAN - Rute baru yang sudah diperbaiki
    Route::get('/lupa-katasandi', [ResetPasswordController::class, 'showForm'])->name('password.reset.form');
    Route::post('/kirim-otp', [ResetPasswordController::class, 'sendOtp'])->name('password.otp.send');
    Route::post('/verifikasi-otp', [ResetPasswordController::class, 'verifyOtp'])->name('password.otp.verify');
    Route::post('/reset-password', [ResetPasswordController::class, 'updatePassword'])->name('password.update');
    Route::post('/kirim-ulang-otp', [ResetPasswordController::class, 'resendOtp'])->name('password.otp.resend');
});
Route::post('/lupakatasandi/send-otp', [ResetPasswordController::class, 'sendOtp'])->name('password.sendOtp');

// Landing Page Routes (public)
Route::get('/landing/{ponpes}', [LandingPageController::class, 'show'])->name('landing.show');
Route::get('/landing', [LandingPageController::class, 'index'])->name('landing.index');
Route::get('/landing/{id}', [LandingPageController::class, 'show'])->name('landing.show');
Route::get('/landing/slug/{slug}', [LandingPageController::class, 'showBySlug'])->name('landing.show.slug');


// Logout (accessible by both guest and auth)
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::get('/landing', [LandingPageController::class, 'utama'])->name('landing.index');

    Route::resource('ponpes', PonpesController::class);

    // Logo update route harus ditempatkan SEBELUM resource
    Route::patch('/ponpes/{id}/logo', [PonpesController::class, 'updateLogo'])->name('ponpes.update-logo');

    Route::prefix('landing-content')->name('landing-content.')->group(function () {
        Route::get('/', [LandingContentController::class, 'index'])->name('index');
        Route::get('/card-view', [LandingContentController::class, 'indexCard'])->name('card');

        Route::get('/create', [LandingContentController::class, 'create'])->name('create');
        Route::get('/create/{type}', [LandingContentController::class, 'createByType'])->name('create-type');
        Route::post('/', [LandingContentController::class, 'store'])->name('store');

        // dynamic routes pindahkan ke bawah
        Route::get('/{landingContent}/edit', [LandingContentController::class, 'edit'])->name('edit');
        Route::put('/{landingContent}', [LandingContentController::class, 'update'])->name('update');
        Route::delete('/{landingContent}', [LandingContentController::class, 'destroy'])->name('destroy');
        Route::get('/{landingContent}', [LandingContentController::class, 'show'])->name('show');

        Route::get('/{id}/detail', [LandingContentController::class, 'getContentDetail'])->name('detail');
        Route::patch('/{id}/toggle-status', [LandingContentController::class, 'toggleStatus'])->name('toggle-status');
        Route::patch('/{id}/update-order', [LandingContentController::class, 'updateOrderSingle'])->name('update-order-single');
        Route::post('/update-order', [LandingContentController::class, 'updateOrder'])->name('update-order');
    });
});

// Debug Routes (hanya untuk development)
if (app()->environment('local')) {
    Route::get('/debug-gambar', function () {
        $gambar = Gambar::with('notulen')
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

            $url = asset('storage/' . $g->path_gambar);
            echo "<strong>URL:</strong> <a href='{$url}' target='_blank'>{$url}</a><br>";

            $fileExists = file_exists(public_path('storage/' . $g->path_gambar));
            echo "<strong>File Exists:</strong> " . ($fileExists ? '✅ YA' : '❌ TIDAK') . "<br>";
            echo "</div>";
        }

        return "Debug selesai - lihat output di atas";
    });

    Route::get('/debug-mail', function () {
        return config('mail.mailers.smtp.host');
    });

    Route::get('/env-check', function () {
        return [
            'APP_ENV' => config('app.env'),
            'MAIL_HOST' => config('mail.mailers.smtp.host'),
            'ENV_FILE_EXISTS' => file_exists(base_path('.env'))
        ];
    });
}

// ==================== PROTECTED ROUTES (MEMBUTUHKAN AUTHENTIKASI) ====================

Route::middleware(['auth'])->group(function () {

    // Dashboard Routes
    Route::prefix('dashboard')
        ->group(function () {
            Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
            Route::post('/absensi', [DashboardController::class, 'store'])->name('dashboard.absensi.store');
            Route::get('/absensi', [DashboardController::class, 'getAbsensi'])->name('dashboard.absensi');
            Route::get('/absensi/all', [DashboardController::class, 'getAllAbsensi'])->name('dashboard.absensi.all');
            Route::get('/absensi/check', [DashboardController::class, 'checkTodayAbsensi'])->name('dashboard.absensi.check');
            Route::get('/prestasi', [DashboardController::class, 'getGrafikPrestasi'])->name('dashboard.prestasi');
        });

    // Keuangan Routes
    Route::prefix('keuangan')
        ->middleware('feature:keuangan')
        ->group(function () {
            Route::get('/', [KeuanganController::class, 'index'])->name('keuangan.index');
            Route::get('/create', [KeuanganController::class, 'create'])->name('keuangan.create');
            Route::post('/', [KeuanganController::class, 'store'])->name('keuangan.store');
            Route::get('/{keuangan}/edit', [KeuanganController::class, 'edit'])->name('keuangan.edit');
            Route::put('/{keuangan}', [KeuanganController::class, 'update'])->name('keuangan.update');
            Route::delete('/{keuangan}', [KeuanganController::class, 'destroy'])->name('keuangan.destroy');
        });

    // Cash Routes
    Route::get('/cash', [CashController::class, 'index'])->name('cash');

    // Sanksi Routes
    Route::prefix('sanksi')
        ->middleware('feature:sanksi')
        ->group(function () {
            Route::get('/sanksi', [SanksiController::class, 'index'])->name('sanksi.index');
            Route::get('/sanksi/create', [SanksiController::class, 'create'])->name('sanksi.create');
            Route::post('/sanksi', [SanksiController::class, 'store'])->name('sanksi.store');
            Route::get('/sanksi/{sanksi}', [SanksiController::class, 'show'])->name('sanksi.show');
            Route::get('/sanksi/{sanksi}/edit', [SanksiController::class, 'edit'])->name('sanksi.edit');
            Route::put('/sanksi/{sanksi}', [SanksiController::class, 'update'])->name('sanksi.update');
            Route::delete('/sanksi/{sanksi}', [SanksiController::class, 'destroy'])->name('sanksi.destroy');
        });

    // Kelas Routes
    Route::prefix('kelas')
        ->group(function () {
            Route::get('/create', [KelasController::class, 'create'])->name('kelas.create');
            Route::post('/store', [KelasController::class, 'store'])->name('kelas.store');
            Route::get('/', [KelasController::class, 'index'])->name('kelas.index');
            Route::get('/{id}/edit', [KelasController::class, 'edit'])->name('kelas.edit');
            Route::put('/{id}', [KelasController::class, 'update'])->name('kelas.update');
            Route::delete('/{id}', [KelasController::class, 'destroy'])->name('kelas.destroy');
            Route::get('/api/get-kelas', [KelasController::class, 'getKelasByPonpes'])->name('kelas.api');
        });

    // Kompetensi Routes
    Route::prefix('kompetensi')
        ->group(function () {
            Route::get('/', [KompetensiController::class, 'index'])->name('kompetensi.index');
            Route::post('/store', [KompetensiController::class, 'store'])->name('kompetensi.store');
            Route::get('/{id}/edit', [KompetensiController::class, 'edit'])->name('kompetensi.edit');
            Route::put('/{id}', [KompetensiController::class, 'update'])->name('kompetensi.update');
            Route::delete('/{id}', [KompetensiController::class, 'destroy'])->name('kompetensi.destroy');
            Route::get('/santri/{santriId}', [KompetensiController::class, 'getBySantri'])->name('kompetensi.by-santri');
            Route::get('/kelas/{kelasId}', [KompetensiController::class, 'getByKelas'])->name('kompetensi.by-kelas');
        });

    // Pencapaian Routes
    Route::prefix('pencapaian')->group(function () {
        Route::get('/{id}/edit', [PencapaianController::class, 'edit'])->name('pencapaian.edit');
        Route::put('/{id}', [PencapaianController::class, 'update'])->name('pencapaian.update');
        Route::delete('/{id}', [PencapaianController::class, 'destroy'])->name('pencapaian.destroy');
        Route::get('/chart-data', [PencapaianController::class, 'getChartData'])->name('pencapaian.chart-data');
    });

    // Laundry Routes
    Route::prefix('laundry')
        ->middleware('feature:laundry')
        ->group(function () {
            Route::get('/', [LaundryController::class, 'index'])->name('laundry.index');
            Route::post('/', [LaundryController::class, 'store'])->name('laundry.store');
            Route::get('/{id}/edit', [LaundryController::class, 'edit'])->name('laundry.edit');
            Route::put('/{id}', [LaundryController::class, 'update'])->name('laundry.update');
            Route::delete('/{id}', [LaundryController::class, 'destroy'])->name('laundry.destroy');
            Route::get('/insert-example', [LaundryController::class, 'insertExampleData']);
            Route::get('/statistics', [LaundryController::class, 'getStatistics'])->name('laundry.statistics');
        });

    // Kategori Routes
    Route::prefix('kategori')->group(function () {
        Route::get('/', [KategoriController::class, 'index'])->name('kategori.index');
        Route::get('/create', [KategoriController::class, 'create'])->name('kategori.create');
        Route::post('/', [KategoriController::class, 'store'])->name('kategori.store');
        Route::get('/{id}/edit', [KategoriController::class, 'edit'])->name('kategori.edit');
        Route::put('/{id}', [KategoriController::class, 'update'])->name('kategori.update');
        Route::delete('/{id}', [KategoriController::class, 'destroy'])->name('kategori.destroy');
    });

    // Kategori Test Route
    Route::get('/kategori-test', function () {
        try {
            $userPonpesId = Auth::user()->ponpes_id;
            $data = \App\Models\Kategori::where('ponpes_id', $userPonpesId)
                ->select('id_kategori', 'nama_kategori')
                ->limit(5)
                ->get();

            return response()->json(['data' => $data, 'count' => count($data)]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    });

    // Modal Routes
    Route::get('/modal/{type}', function ($type) {
        $allowedModals = ['create_kelas', 'create_santri'];

        if (!in_array($type, $allowedModals)) {
            abort(404, 'Modal tidak ditemukan');
        }

        $viewPath = "pages.modal.{$type}";
        if (!view()->exists($viewPath)) {
            abort(404, "View {$viewPath} tidak ditemukan");
        }

        return view($viewPath);
    })->name('modal.load');

    // Santri Routes - SEMUA ROUTE DALAM SATU GROUP
    Route::prefix('santri')
        ->middleware('feature:santri')
        ->group(function () {
            Route::get('/', [SantriController::class, 'index'])->name('santri.index');
            Route::get('/create', [SantriController::class, 'create'])->name('santri.create');
            Route::post('/', [SantriController::class, 'store'])->name('santri.store');
            Route::get('/{id}/edit', [SantriController::class, 'edit'])->name('santri.edit');
            Route::put('/{id}', [SantriController::class, 'update'])->name('santri.update');
            Route::delete('/{id}', [SantriController::class, 'destroy'])->name('santri.destroy');

            // API Routes untuk Santri
            Route::post('/check-unique', [SantriController::class, 'checkUnique'])->name('santri.check-unique');
            Route::get('/api/santri-data', [SantriController::class, 'getSantriByPonpes'])->name('santri.api');
            Route::get('/{id}/kompetensi', function ($id) {
                $kompetensi = DB::table('pencapaian as p')
                    ->join('santri as s', 's.id_santri', '=', 'p.id_santri')
                    ->where('s.id_santri', $id)
                    ->select('p.judul', 'p.deskripsi', 'p.tipe', 'p.tanggal', 'p.skor')
                    ->orderBy('p.tanggal', 'desc')
                    ->get();

                return response()->json($kompetensi);
            })->name('santri.kompetensi');
        });

    // Inventaris Routes
    Route::prefix('inventaris')
        ->group(function () {
            Route::get('/', [InventarisController::class, 'index'])->name('inventaris.index');
            Route::get('/create', [InventarisController::class, 'create'])->name('inventaris.create');
            Route::post('/', [InventarisController::class, 'store'])->name('inventaris.store');
            Route::get('/{id}/edit', [InventarisController::class, 'edit'])->name('inventaris.edit');
            Route::put('/{id}', [InventarisController::class, 'update'])->name('inventaris.update');
            Route::delete('/{id}', [InventarisController::class, 'destroy'])->name('inventaris.destroy');
            Route::get('/{id}', [InventarisController::class, 'show'])->name('inventaris.show');
            Route::get('/export/export', [InventarisController::class, 'export'])->name('inventaris.export');
        });

    // Inventaris API Routes
    Route::prefix('api/inventaris')->group(function () {
        Route::get('/chart-data', [InventarisController::class, 'getChartData']);
        Route::get('/stats', [InventarisController::class, 'getStats']);
    });

    // Notulen Routes
    Route::prefix('notulen')
        ->middleware('feature:notulensi')
        ->group(function () {
            Route::get('/notulensi', [NotulenController::class, 'index'])->name('notulen.index');
            Route::get('/create', [NotulenController::class, 'create'])->name('notulen.create');
            Route::post('/', [NotulenController::class, 'store'])->name('notulen.store');
            Route::get('/{id}', [NotulenController::class, 'show'])->name('notulen.show');
            Route::get('/{id}/edit', [NotulenController::class, 'edit'])->name('notulen.edit');
            Route::put('/{id}', [NotulenController::class, 'update'])->name('notulen.update');
            Route::delete('/{id}', [NotulenController::class, 'destroy'])->name('notulen.destroy');
            Route::delete('/gambar/{id}', [NotulenController::class, 'hapusGambar'])->name('notulen.hapus-gambar');
            Route::post('/{id}/tambah-gambar', [NotulenController::class, 'tambahGambar'])->name('notulen.tambah-gambar');
            Route::get('/{id}/export', [NotulenController::class, 'export'])->name('notulen.export');
        });

    // Notulen API Routes
    Route::get('/api/notulen/stats', [NotulenController::class, 'getStats']);

    // Test Route untuk Debugging (opsional)
    Route::get('/test-check-unique', function () {
        try {
            $userPonpesId = Auth::user()->ponpes_id;
            $testNisn = '00172432149';

            $exists = \App\Models\Santri::where('nisn', $testNisn)
                ->where('ponpes_id', $userPonpesId)
                ->exists();

            return response()->json([
                'test_data' => [
                    'user_ponpes_id' => $userPonpesId,
                    'test_nisn' => $testNisn,
                    'exists' => $exists,
                    'total_santri' => \App\Models\Santri::where('ponpes_id', $userPonpesId)->count()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    });

    // Kepegawaian Routes
    Route::prefix('kepegawaian')->name('kepegawaian.')->group(function () {
        Route::get('/', [KepegawaianController::class, 'index'])->name('index');
        Route::post('/', [KepegawaianController::class, 'store'])->name('store');
        Route::put('/{id}', [KepegawaianController::class, 'update'])->name('update');
        Route::delete('/{id}', [KepegawaianController::class, 'destroy'])->name('destroy');
    });
});

// ==================== FALLBACK ROUTE ====================
Route::fallback(function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect('/');
});

// Dalam auth group, tambahkan:
Route::get('/test-check-unique', function () {
    try {
        $userPonpesId = Auth::user()->ponpes_id;
        $testNisn = '00172432149';

        $exists = \App\Models\Santri::where('nisn', $testNisn)
            ->where('ponpes_id', $userPonpesId)
            ->exists();

        return response()->json([
            'test_data' => [
                'user_ponpes_id' => $userPonpesId,
                'test_nisn' => $testNisn,
                'exists' => $exists,
                'total_santri' => \App\Models\Santri::where('ponpes_id', $userPonpesId)->count()
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()]);
    }
});
