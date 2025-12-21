<?php

namespace App\Http\Controllers;

use App\Models\Keuangan;
use App\Models\Kategori;
use App\Imports\KeuanganImport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Maatwebsite\Excel\Facades\Excel;

class KeuanganController extends Controller
{
    /**
     * Get user's ponpes_id
     */
    private function getUserPonpesId()
    {
        return Auth::user()->ponpes_id;
    }

    /**
     * Check if keuangan belongs to user's ponpes
     */
    private function checkKeuanganOwnership($keuanganId)
    {
        $userPonpesId = $this->getUserPonpesId();

        $keuangan = Keuangan::where('id_keuangan', $keuanganId)
            ->where('ponpes_id', $userPonpesId)
            ->first();

        if (!$keuangan) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mengakses data ini.');
        }

        return $keuangan;
    }

    /**
     * Apply date filter to query
     */
    private function applyDateFilter($query, $filter)
    {
        $now = Carbon::now();

        switch ($filter) {
            case 'hari-ini':
                $startDate = $now->copy()->startOfDay();
                break;
            case 'minggu-ini':
                $startDate = $now->copy()->startOfWeek();
                break;
            case '1-bulan':
                $startDate = $now->copy()->subMonth();
                break;
            case '3-bulan':
                $startDate = $now->copy()->subMonths(3);
                break;
            case '6-bulan':
                $startDate = $now->copy()->subMonths(6);
                break;
            case '1-tahun':
                $startDate = $now->copy()->subYear();
                break;
            case '5-tahun':
                $startDate = $now->copy()->subYears(5);
                break;
            default:
                $startDate = $now->copy()->subYear();
        }

        return $query->where('tanggal', '>=', $startDate->format('Y-m-d'))
            ->where('tanggal', '<=', $now->format('Y-m-d'));
    }

public function index(Request $request)
{
    try {
        $userPonpesId = $this->getUserPonpesId();
        $filter = $request->get('filter', '1-tahun');
        
        // ==================== LAZY LOADING IMPLEMENTATION ====================
        
        // 1. DATA UNTUK TABEL (PAGINATION - Tetap efficient)
        $tableQuery = Keuangan::with(['kategori:id_kategori,nama_kategori', 'user:id_user,username'])
            ->where('ponpes_id', $userPonpesId)
            ->select('id_keuangan', 'jumlah', 'sumber_dana', 'status', 
                     'tanggal', 'keterangan', 'id_kategori', 'user_id', 'ponpes_id');

        $tableData = $tableQuery->orderBy('tanggal', 'desc')->paginate(10);
        
        $columns = ['User', 'Jumlah', 'Kategori', 'Sumber Dana', 'Keterangan', 'Tanggal', 'Status'];
        $rows = $tableData->map(function ($item) {
            return [
                'id' => $item->id_keuangan,
                'user' => $item->user->username ?? 'Tidak ada user',
                'jumlah' => 'Rp ' . number_format($item->jumlah, 0, ',', '.') . ',00',
                'jumlah_raw' => $item->jumlah,
                'keterangan_raw' => $item->keterangan,
                'kategori' => $item->kategori->nama_kategori ?? 'Tidak ada kategori',
                'id_kategori' => $item->id_kategori,
                'sumber_dana' => $item->sumber_dana ?? '-',
                'tanggal' => $item->tanggal ? Carbon::parse($item->tanggal)->format('d M Y') : '-',
                'tanggal_raw' => $item->tanggal,
                'status' => $item->status ?? '-',
                'keterangan' => $item->keterangan ?? '',
            ];
        })->toArray();
        
        // ==================== LAZY LOADING UNTUK DATA STATISTIK ====================
        
        // 2. DATA UNTUK CARDS (LAZY LOADING DENGAN CHUNKING)
        $totalPemasukan = 0;
        $totalPengeluaran = 0;
        $totalTransaksi = 0;
        
        // Gunakan chunk untuk menghitung statistik tanpa load semua data ke memory
        $statsQuery = Keuangan::where('ponpes_id', $userPonpesId)
            ->select('jumlah', 'status');
            
        // Apply filter untuk statistik cards jika diperlukan
        $statsQuery = $this->applyDateFilter($statsQuery, $filter);
        
        // Chunking untuk data besar (> 1000 records)
        $statsQuery->chunk(1000, function ($chunk) use (&$totalPemasukan, &$totalPengeluaran, &$totalTransaksi) {
            foreach ($chunk as $item) {
                $totalTransaksi++;
                if ($item->status === 'Masuk') {
                    $totalPemasukan += $item->jumlah;
                } else {
                    $totalPengeluaran += $item->jumlah;
                }
            }
        });
        
        $saldo = $totalPemasukan - $totalPengeluaran;
        
        // ==================== LAZY LOADING UNTUK PIE CHART ====================
        
        // 3. DATA UNTUK PIE CHART (LAZY LOADING DENGAN QUERY AGREGASI)
        $pieData = [];
        $labels = [];
        $values = [];
        $sumber_dana = [];
        
        // Jika data tidak terlalu besar, gunakan query aggregasi langsung
        if ($totalTransaksi < 50000) { // Threshold 50k transaksi
            $pieQuery = Keuangan::join('kategori', 'keuangan.id_kategori', '=', 'kategori.id_kategori')
                ->where('keuangan.ponpes_id', $userPonpesId)
                ->where('kategori.ponpes_id', $userPonpesId)
                ->when($filter !== 'all-time', function($q) use ($filter) {
                    return $this->applyDateFilter($q, $filter, 'keuangan.');
                })
                ->selectRaw('
                    kategori.nama_kategori as label,
                    SUM(keuangan.jumlah) as value,
                    GROUP_CONCAT(DISTINCT keuangan.sumber_dana SEPARATOR ", ") as sumber_dana
                ')
                ->groupBy('kategori.id_kategori', 'kategori.nama_kategori')
                ->orderByDesc('value')
                ->limit(8);
            
            // Gunakan chunk untuk data pie chart yang besar
            $pieQuery->chunk(100, function ($chunk) use (&$pieData) {
                foreach ($chunk as $row) {
                    $pieData[] = [
                        'label' => $row->label,
                        'value' => $row->value,
                        'sumber_dana' => $row->sumber_dana
                    ];
                }
            });
            
            $labels = array_column($pieData, 'label');
            $values = array_column($pieData, 'value');
            $sumber_dana = array_column($pieData, 'sumber_dana');
        } else {
            // Untuk data sangat besar (> 50k), gunakan sampling atau cache
            $labels = ['Data Sangat Besar', 'Sampling Aktif'];
            $values = [$totalPemasukan, $totalPengeluaran];
            $sumber_dana = ['Total Pemasukan', 'Total Pengeluaran'];
        }
        
        // ==================== LAZY LOADING UNTUK LINE CHART ====================
        
        // 4. DATA UNTUK LINE CHART (CASH FLOW) - OPTIMIZED
        $dates = [];
        $dailyFlow = [];
        
        // Untuk data besar, batasi range atau gunakan sampling
        $maxDataPoints = 50; // Maksimal 50 titik data untuk chart
        
        if ($totalTransaksi < 10000) {
            // Query aggregasi per hari untuk data sedang
            $cashFlowQuery = Keuangan::where('ponpes_id', $userPonpesId)
                ->when($filter !== 'all-time', function($q) use ($filter) {
                    return $this->applyDateFilter($q, $filter);
                })
                ->selectRaw('
                    DATE(tanggal) as date,
                    SUM(CASE WHEN status = "Masuk" THEN jumlah ELSE 0 END) as pemasukan,
                    SUM(CASE WHEN status = "Keluar" THEN jumlah ELSE 0 END) as pengeluaran
                ')
                ->groupBy('date')
                ->orderBy('date');
            
            $cashFlowData = [];
            $cashFlowQuery->chunk(500, function ($chunk) use (&$cashFlowData) {
                foreach ($chunk as $row) {
                    $cashFlowData[] = $row;
                }
            });
            
            // Jika data terlalu banyak, lakukan sampling
            if (count($cashFlowData) > $maxDataPoints) {
                $step = ceil(count($cashFlowData) / $maxDataPoints);
                $cashFlowData = array_filter($cashFlowData, function($key) use ($step) {
                    return $key % $step === 0;
                }, ARRAY_FILTER_USE_KEY);
                $cashFlowData = array_values($cashFlowData);
            }
            
            // Hitung cumulative flow
            $total = 0;
            foreach ($cashFlowData as $row) {
                $dates[] = Carbon::parse($row->date)->format('Y-m-d');
                $total += ($row->pemasukan - $row->pengeluaran);
                $dailyFlow[] = $total;
            }
            
        } else {
            // Untuk data sangat besar, gunakan data per bulan
            $monthlyFlowQuery = Keuangan::where('ponpes_id', $userPonpesId)
                ->when($filter !== 'all-time', function($q) use ($filter) {
                    return $this->applyDateFilter($q, $filter);
                })
                ->selectRaw('
                    DATE_FORMAT(tanggal, "%Y-%m") as month,
                    SUM(CASE WHEN status = "Masuk" THEN jumlah ELSE 0 END) as pemasukan,
                    SUM(CASE WHEN status = "Keluar" THEN jumlah ELSE 0 END) as pengeluaran
                ')
                ->groupBy('month')
                ->orderBy('month')
                ->limit($maxDataPoints);
            
            $monthlyData = [];
            $monthlyFlowQuery->chunk(100, function ($chunk) use (&$monthlyData) {
                foreach ($chunk as $row) {
                    $monthlyData[] = $row;
                }
            });
            
            $total = 0;
            foreach ($monthlyData as $row) {
                $dates[] = Carbon::createFromFormat('Y-m', $row->month)->format('M Y');
                $total += ($row->pemasukan - $row->pengeluaran);
                $dailyFlow[] = $total;
            }
        }
        
        $saldo_terakhir = !empty($dailyFlow) ? end($dailyFlow) : $saldo;
        
        // ==================== LAZY LOADING UNTUK DATA DETAIL ====================
        
        // 5. DATA UNTUK CHART DETAIL (Hanya jika diperlukan dan data kecil)
        $chartData = null;
        if ($request->has('show_chart_data') && $totalTransaksi < 1000) {
            $chartQuery = Keuangan::with(['kategori:id_kategori,nama_kategori'])
                ->where('ponpes_id', $userPonpesId)
                ->when($filter !== 'all-time', function($q) use ($filter) {
                    return $this->applyDateFilter($q, $filter);
                })
                ->select('jumlah', 'status', 'tanggal', 'id_kategori', 'sumber_dana')
                ->orderBy('tanggal', 'asc')
                ->limit(1000); // Batasi data chart detail
                
            $chartData = $chartQuery->get();
        }
        
        // ==================== LOG MEMORY USAGE ====================
        $memoryUsage = round(memory_get_usage() / 1024 / 1024, 2);
        $memoryPeak = round(memory_get_peak_usage() / 1024 / 1024, 2);
        
        Log::info('Keuangan Index - Memory Usage', [
            'user_id' => Auth::id(),
            'ponpes_id' => $userPonpesId,
            'total_transactions' => $totalTransaksi,
            'memory_usage_mb' => $memoryUsage,
            'memory_peak_mb' => $memoryPeak,
            'filter' => $filter
        ]);
        
        // ==================== RETURN DATA ====================
        return view('pages.keuangan', compact(
            'columns',
            'rows',
            'labels',
            'values',
            'sumber_dana',
            'dates',
            'dailyFlow',
            'saldo_terakhir',
            'filter',
            'totalPemasukan',
            'totalPengeluaran',
            'saldo',
            'tableData',
            'chartData',
            'totalTransaksi'
        ));
        
    } catch (\Exception $e) {
        Log::error('Error loading keuangan index', [
            'user_id' => Auth::id(),
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat data keuangan.');
    }
}

    public function create()
    {
        try {
            Log::info('Mengakses form create keuangan', [
                'user_id' => Auth::id(),
                'ponpes_id' => $this->getUserPonpesId()
            ]);

            $userPonpesId = $this->getUserPonpesId();

            $kategories = Kategori::where('ponpes_id', $userPonpesId)->get();

            return view('pages.action.create_keuangan', compact('kategories'));
        } catch (\Exception $e) {
            Log::error('Error pada keuangan create form', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);
            return redirect()->route('keuangan.index')->with('error', 'Terjadi kesalahan saat memuat form tambah data.');
        }
    }

    public function store(Request $request)
    {
        try {
            Log::info('Memproses store keuangan', [
                'user_id' => Auth::id(),
                'ponpes_id' => $this->getUserPonpesId(),
                'input_data' => $request->except(['_token'])
            ]);

            $user = Auth::user();
            $userPonpesId = $this->getUserPonpesId();

            // Validasi kategori
            $validKategori = Kategori::where('id_kategori', $request->id_kategori)
                ->where('ponpes_id', $userPonpesId)
                ->exists();

            if (!$validKategori) {
                Log::warning('Kategori tidak valid', [
                    'id_kategori' => $request->id_kategori,
                    'ponpes_id' => $userPonpesId
                ]);
                return redirect()->back()->with('error', 'Kategori tidak valid atau tidak ditemukan.')->withInput();
            }

            $request->validate([
                'jumlah' => 'required|numeric|min:0',
                'id_kategori' => 'required|exists:kategori,id_kategori',
                'sumber_dana' => 'required|string|max:100',
                'status' => 'required|in:Masuk,Keluar',
                'tanggal' => 'required|date',
                'keterangan' => 'nullable|string|max:500'
            ], [
                'jumlah.min' => 'Jumlah tidak boleh negatif',
                'id_kategori.exists' => 'Kategori tidak valid'
            ]);

            $keuangan = Keuangan::create([
                'user_id' => (int) $user->id_user,
                'ponpes_id' => $userPonpesId,
                'jumlah' => $request->jumlah,
                'id_kategori' => $request->id_kategori,
                'sumber_dana' => $request->sumber_dana,
                'status' => $request->status,
                'tanggal' => $request->tanggal,
                'keterangan' => $request->keterangan
            ]);

            Log::info('Berhasil membuat keuangan baru', [
                'user_id' => Auth::id(),
                'keuangan_id' => $keuangan->id_keuangan,
                'jumlah' => $keuangan->jumlah,
                'status' => $keuangan->status
            ]);

            return redirect()->route('keuangan.index')->with('success', 'Data keuangan berhasil ditambahkan!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Validasi gagal pada store keuangan', [
                'errors' => $e->errors(),
                'input' => $request->all()
            ]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error pada keuangan store', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menambah data keuangan.')->withInput();
        }
    }

    public function edit($id)
    {
        try {
            Log::info('Mengakses form edit keuangan', [
                'user_id' => Auth::id(),
                'keuangan_id' => $id
            ]);

            $userPonpesId = $this->getUserPonpesId();

            // Cek kepemilikan data keuangan
            $keuangan = Keuangan::where('id_keuangan', $id)
                ->where('ponpes_id', $userPonpesId)
                ->firstOrFail();

            $keuangan->load('kategori');

            // Hanya ambil kategori yang milik ponpes user
            $kategories = Kategori::where('ponpes_id', $userPonpesId)->get();

            Log::debug('Data keuangan ditemukan', [
                'keuangan_id' => $keuangan->id_keuangan,
                'kategori' => $keuangan->kategori->nama_kategori ?? 'Tidak ada'
            ]);

            return view('pages.action.edit_keuangan', compact('keuangan', 'kategories'));
        } catch (ModelNotFoundException $e) {
            Log::warning('Keuangan tidak ditemukan untuk edit', [
                'user_id' => Auth::id(),
                'keuangan_id' => $id
            ]);
            return redirect()->route('keuangan.index')->with('error', 'Data keuangan tidak ditemukan.');
        } catch (\Exception $e) {
            Log::error('Error pada keuangan edit form', [
                'user_id' => Auth::id(),
                'keuangan_id' => $id,
                'error' => $e->getMessage()
            ]);
            return redirect()->route('keuangan.index')->with('error', 'Terjadi kesalahan saat memuat form edit.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            Log::info('Memproses update keuangan', [
                'user_id' => Auth::id(),
                'keuangan_id' => $id,
                'input_data' => $request->except(['_token', '_method'])
            ]);

            $userPonpesId = $this->getUserPonpesId();

            // Cek kepemilikan data keuangan
            $keuangan = Keuangan::where('id_keuangan', $id)
                ->where('ponpes_id', $userPonpesId)
                ->firstOrFail();

            // Validasi bahwa kategori yang dipilih milik ponpes user
            $validKategori = Kategori::where('id_kategori', $request->id_kategori)
                ->where('ponpes_id', $userPonpesId)
                ->exists();

            if (!$validKategori) {
                Log::warning('Kategori tidak valid untuk update', [
                    'id_kategori' => $request->id_kategori,
                    'ponpes_id' => $userPonpesId
                ]);
                return redirect()->back()->with('error', 'Kategori tidak valid atau tidak ditemukan.')->withInput();
            }

            $request->validate([
                'jumlah' => 'required|numeric|min:0',
                'id_kategori' => 'required|exists:kategori,id_kategori',
                'sumber_dana' => 'required|string|max:100',
                'status' => 'required|in:Masuk,Keluar',
                'tanggal' => 'required|date',
                'keterangan' => 'nullable|string|max:500'
            ], [
                'jumlah.min' => 'Jumlah tidak boleh negatif',
                'id_kategori.exists' => 'Kategori tidak valid'
            ]);

            $keuangan->update([
                'jumlah' => $request->jumlah,
                'id_kategori' => $request->id_kategori,
                'sumber_dana' => $request->sumber_dana,
                'status' => $request->status,
                'tanggal' => $request->tanggal,
                'keterangan' => $request->keterangan
            ]);

            Log::info('Berhasil update keuangan', [
                'user_id' => Auth::id(),
                'keuangan_id' => $keuangan->id_keuangan,
                'jumlah' => $keuangan->jumlah,
                'status' => $keuangan->status
            ]);

            return redirect()->route('keuangan.index')->with('success', 'Data keuangan berhasil diperbarui!');
        } catch (ModelNotFoundException $e) {
            Log::warning('Keuangan tidak ditemukan untuk update', [
                'user_id' => Auth::id(),
                'keuangan_id' => $id
            ]);
            return redirect()->route('keuangan.index')->with('error', 'Data keuangan tidak ditemukan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Validasi gagal pada update keuangan', [
                'errors' => $e->errors(),
                'input' => $request->all()
            ]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error pada keuangan update', [
                'user_id' => Auth::id(),
                'keuangan_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui data keuangan.')->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            Log::info('Memproses delete keuangan', [
                'user_id' => Auth::id(),
                'keuangan_id' => $id
            ]);

            // Cek kepemilikan data keuangan
            $keuangan = Keuangan::where('id_keuangan', $id)
                ->where('ponpes_id', Auth::user()->ponpes_id)
                ->firstOrFail();

            $keuanganData = [
                'id_keuangan' => $keuangan->id_keuangan,
                'jumlah' => $keuangan->jumlah,
                'status' => $keuangan->status
            ];

            $keuangan->delete();

            Log::info('Berhasil delete keuangan', [
                'user_id' => Auth::id(),
                'deleted_keuangan' => $keuanganData
            ]);

            return redirect()->route('keuangan.index')->with('success', 'Data keuangan berhasil dihapus!');
        } catch (ModelNotFoundException $e) {
            Log::warning('Keuangan tidak ditemukan untuk delete', [
                'user_id' => Auth::id(),
                'keuangan_id' => $id
            ]);
            return redirect()->route('keuangan.index')->with('error', 'Data keuangan tidak ditemukan.');
        } catch (\Exception $e) {
            Log::error('Error pada keuangan delete', [
                'user_id' => Auth::id(),
                'keuangan_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('keuangan.index')->with('error', 'Terjadi kesalahan saat menghapus data keuangan.');
        }
    }

    /**
     * Get keuangan data for API
     */
    public function getKeuanganByPonpes()
    {
        try {
            $userPonpesId = $this->getUserPonpesId();

            $keuangan = Keuangan::with(['kategori', 'user'])
                ->where('ponpes_id', $userPonpesId)
                ->select('id_keuangan', 'jumlah', 'sumber_dana', 'status', 'tanggal', 'keterangan', 'id_kategori', 'user_id')
                ->orderBy('tanggal', 'desc')
                ->paginate(10);

            Log::info('API - Get keuangan data', [
                'user_id' => Auth::id(),
                'total_data' => $keuangan->total()
            ]);

            return response()->json([
                'success' => true,
                'data' => $keuangan
            ]);
        } catch (\Exception $e) {
            Log::error('API Error - Get keuangan data', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data keuangan'
            ], 500);
        }
    }

    /**
     * Display import form
     */
    public function importForm()
    {
        try {
            Log::info('Mengakses form import keuangan', [
                'user_id' => Auth::id(),
                'ponpes_id' => $this->getUserPonpesId()
            ]);

            $userPonpesId = $this->getUserPonpesId();

            // Ambil kategori untuk validasi
            $kategories = Kategori::where('ponpes_id', $userPonpesId)->get();

            return view('pages.action.import_keuangan', compact('kategories'));
        } catch (\Exception $e) {
            Log::error('Error pada import form keuangan', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('keuangan.index')
                ->with('error', 'Terjadi kesalahan saat memuat form import.');
        }
    }

    /**
     * Process import using Laravel Excel
     */
    public function import(Request $request)
    {
        $startTime = microtime(true);
        $user = Auth::user();

        try {
            Log::info('=== START IMPORT PROCESS ===', [
                'user_id' => $user->id_user ?? $user->id,
                'email' => $user->email,
                'ponpes_id' => $user->ponpes_id,
                'request_data' => $request->except('csv_file')
            ]);

            // Validasi file
            $validator = Validator::make($request->all(), [
                'csv_file' => [
                    'required',
                    'file',
                    'max:5120', // 5MB
                    function ($attribute, $value, $fail) {
                        $extension = strtolower($value->getClientOriginalExtension());
                        $mimeType = $value->getMimeType();

                        Log::debug('File validation check', [
                            'filename' => $value->getClientOriginalName(),
                            'extension' => $extension,
                            'mime_type' => $mimeType,
                            'size' => $value->getSize()
                        ]);

                        // Accept various CSV formats
                        $allowedExtensions = ['csv', 'txt', 'xlsx', 'xls'];
                        $allowedMimes = [
                            'text/csv',
                            'text/plain',
                            'text/x-csv',
                            'application/csv',
                            'application/x-csv',
                            'text/comma-separated-values',
                            'text/x-comma-separated-values',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/vnd.ms-excel'
                        ];

                        if (
                            !in_array($extension, $allowedExtensions) &&
                            !in_array($mimeType, $allowedMimes)
                        ) {
                            $fail('File harus berformat CSV atau Excel (.csv, .xlsx, .xls)');
                        }
                    }
                ],
                'skip_header' => 'boolean'
            ], [
                'csv_file.required' => 'Silakan pilih file',
                'csv_file.file' => 'File tidak valid',
                'csv_file.max' => 'File maksimal 5MB'
            ]);

            if ($validator->fails()) {
                Log::warning('Import validation failed', [
                    'errors' => $validator->errors()->toArray()
                ]);

                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // Proses file
            $file = $request->file('csv_file');
            $skipHeader = $request->boolean('skip_header', true);

            Log::info('File validated successfully', [
                'original_name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'extension' => $file->getClientOriginalExtension()
            ]);

            // Gunakan KeuanganImport class
            $import = new KeuanganImport($user->ponpes_id, $user->id_user ?? $user->id);

            // Import dengan progress
            Excel::import($import, $file);

            $results = $import->getResults();

            $executionTime = round(microtime(true) - $startTime, 2);

            Log::info('=== IMPORT PROCESS COMPLETED ===', [
                'execution_time' => $executionTime . 's',
                'success_count' => $results['success_count'],
                'error_count' => $results['error_count'],
                'total_rows' => $results['success_count'] + $results['error_count']
            ]);

            // Response
            if ($results['success_count'] > 0) {
                $message = "✅ Berhasil mengimport {$results['success_count']} data keuangan.";

                if ($results['error_count'] > 0) {
                    $message .= " {$results['error_count']} data gagal diimport.";

                    // Save error details to session
                    session()->flash('import_errors', $results['error_messages']);
                }

                return redirect()->route('keuangan.index')
                    ->with('success', $message)
                    ->with('import_stats', [
                        'success' => $results['success_count'],
                        'errors' => $results['error_count']
                    ]);
            } else {
                return redirect()->back()
                    ->with('error', '❌ Tidak ada data yang berhasil diimport. Periksa format file.')
                    ->with('import_errors', $results['error_messages'])
                    ->withInput();
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Import validation exception', ['errors' => $e->errors()]);
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            Log::error('=== IMPORT PROCESS FAILED ===', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'execution_time' => round(microtime(true) - $startTime, 2) . 's'
            ]);

            return redirect()->back()
                ->with('error', '🚨 Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Download CSV template
     */
    public function downloadTemplate()
    {
        try {
            $user = Auth::user();
            Log::info('Downloading CSV template', [
                'user_id' => $user->id_user ?? $user->id,
                'email' => $user->email
            ]);

            // CSV content dengan header yang benar
            $csvContent = "jumlah,status,tanggal,kategori,sumber_dana,keterangan\n" .
                "1000000,Masuk,2024-01-15,Donatur,Donatur Tetap,Sumbangan bulanan dari Bpk. Ahmad\n" .
                "500000,Keluar,2024-01-16,Operasional,Kas Pesantren,Pembelian beras dan bahan makanan\n" .
                "750000,Masuk,2024-01-17,Iuran,Santri,Iuran bulan Januari 2024\n" .
                "300000,Keluar,2024-01-18,Gaji,Guru,Gaji ustadz bulan Januari\n" .
                "1500000,Masuk,2024-01-19,Investasi,Investor,Investasi pembangunan asrama\n" .
                "200000,Keluar,2024-01-20,Listrik,PLN,Pembayaran listrik bulan Januari\n" .
                "450000,Masuk,2024-01-21,Sumbangan,Yayasan,Sumbangan untuk kegiatan pesantren\n" .
                "180000,Keluar,2024-01-22,Akademik,Peralatan,Beli buku dan alat tulis\n" .
                "1200000,Masuk,2024-01-23,SPP,Orang Tua,SPP santri bulan Januari\n" .
                "250000,Keluar,2024-01-24,Kesehatan,UKS,Obat-obatan dan perawatan";

            // Tambahkan BOM untuk UTF-8
            $bom = chr(0xEF) . chr(0xBB) . chr(0xBF);

            return response($bom . $csvContent)
                ->header('Content-Type', 'text/csv; charset=utf-8')
                ->header('Content-Disposition', 'attachment; filename="template_import_keuangan_pesantren.csv"')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');
        } catch (\Exception $e) {
            Log::error('Error downloading template', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);
            return redirect()->back()
                ->with('error', 'Gagal mendownload template: ' . $e->getMessage());
        }
    }

    /**
     * Get statistics for dashboard
     */
    public function getStats()
    {
        try {
            $userPonpesId = $this->getUserPonpesId();

            $totalKeuangan = Keuangan::where('ponpes_id', $userPonpesId)->count();
            $keuanganBulanIni = Keuangan::where('ponpes_id', $userPonpesId)
                ->whereMonth('tanggal', now()->month)
                ->count();
            $keuanganMingguIni = Keuangan::where('ponpes_id', $userPonpesId)
                ->whereBetween('tanggal', [now()->startOfWeek(), now()->endOfWeek()])
                ->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'total_keuangan' => $totalKeuangan,
                    'keuangan_bulan_ini' => $keuanganBulanIni,
                    'keuangan_minggu_ini' => $keuanganMingguIni
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error pada getStats keuangan', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil statistik.'
            ], 500);
        }
    }

    /**
     * Export keuangan data to Excel
     */
    public function export(Request $request)
    {
        try {
            $userPonpesId = $this->getUserPonpesId();
            $filter = $request->get('filter', '1-tahun');

            // Query dengan filter
            $query = Keuangan::with(['kategori', 'user'])
                ->where('ponpes_id', $userPonpesId);

            $query = $this->applyDateFilter($query, $filter);
            $data = $query->orderBy('tanggal', 'asc')->get();

            // Format data untuk export
            $exportData = $data->map(function ($item) {
                return [
                    'Tanggal' => $item->tanggal ? Carbon::parse($item->tanggal)->format('d M Y') : '-',
                    'Jumlah' => 'Rp ' . number_format($item->jumlah, 0, ',', '.'),
                    'Status' => $item->status,
                    'Kategori' => $item->kategori->nama_kategori ?? '-',
                    'Sumber Dana' => $item->sumber_dana ?? '-',
                    'Keterangan' => $item->keterangan ?? '-',
                    'User' => $item->user->username ?? '-'
                ];
            });

            Log::info('Export keuangan data', [
                'user_id' => Auth::id(),
                'total_data' => $exportData->count(),
                'filter' => $filter
            ]);

            // Return JSON for now (bisa di-extend untuk Excel export)
            return response()->json([
                'success' => true,
                'data' => $exportData,
                'total' => $exportData->count()
            ]);
        } catch (\Exception $e) {
            Log::error('Error exporting keuangan data', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat export data.'
            ], 500);
        }
    }
}
