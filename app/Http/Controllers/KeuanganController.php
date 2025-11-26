<?php

namespace App\Http\Controllers;

use App\Models\Keuangan;
use App\Models\Kategori;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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

            // Query untuk CHART
            $chartQuery = Keuangan::with(['kategori', 'user'])
                ->where('ponpes_id', $userPonpesId);
                
            $chartQuery = $this->applyDateFilter($chartQuery, $filter);
            $data = $chartQuery->orderBy('tanggal', 'asc')->get();

            // Query untuk TABEL
            $tableQuery = Keuangan::with(['kategori', 'user'])
                ->where('ponpes_id', $userPonpesId);

            $tableData = $tableQuery->orderBy('tanggal', 'desc')->paginate(10);

            $columns = ['User', 'Jumlah', 'Kategori', 'Sumber Dana', 'Tanggal', 'Status'];
            $rows = $tableData->map(function ($item) {
                return [
                    'id' => $item->id_keuangan, // Pastikan ini id_keuangan
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

            // Data untuk chart kategori (PIE CHART)
            $grouped = $data->groupBy(function ($item) {
                return $item->kategori->nama_kategori ?? 'Tidak ada kategori';
            })->map(function ($items) {
                return [
                    'total' => $items->sum('jumlah'),
                    'sumber_dana' => $items->pluck('sumber_dana')->unique()->join(', ')
                ];
            });

            $labels = $grouped->keys()->toArray();
            $values = $grouped->pluck('total')->toArray();
            $sumber_dana = $grouped->pluck('sumber_dana')->toArray();

            // Data untuk CASH FLOW CHART (LINE CHART)
            $dates = $data->pluck('tanggal')
                ->filter()
                ->map(fn($t) => Carbon::parse($t)->format('Y-m-d'))
                ->unique()
                ->sort()
                ->values()
                ->toArray();

            $dailyFlow = [];
            $total = 0;

            foreach ($dates as $tanggal) {
                $transactions = $data->filter(
                    fn($i) =>
                    $i->tanggal && Carbon::parse($i->tanggal)->format('Y-m-d') === $tanggal
                );

                $masuk = $transactions->where('status', 'Masuk')->sum('jumlah');
                $keluar = $transactions->where('status', 'Keluar')->sum('jumlah');

                $total += ($masuk - $keluar);
                $dailyFlow[] = $total;
            }

            $saldo_terakhir = !empty($dailyFlow) ? end($dailyFlow) : 0;

            // Hitung total untuk cards
            $totalPemasukan = $data->where('status', 'Masuk')->sum('jumlah');
            $totalPengeluaran = $data->where('status', 'Keluar')->sum('jumlah');
            $saldo = $totalPemasukan - $totalPengeluaran;

            return view('pages.keuangan', compact(
                'data',
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
                'tableData'
            ));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat data keuangan.');
        }
    }

    public function create()
    {
        try {
            $userPonpesId = $this->getUserPonpesId();
            
            $kategories = Kategori::where('ponpes_id', $userPonpesId)->get();
            
            return view('pages.action.create_keuangan', compact('kategories'));

        } catch (\Exception $e) {
            return redirect()->route('keuangan.index')->with('error', 'Terjadi kesalahan saat memuat form tambah data.');
        }
    }

    public function store(Request $request)
    {
        try {
            $user = Auth::user();
            $userPonpesId = $this->getUserPonpesId();

            // Validasi kategori
            $validKategori = Kategori::where('id_kategori', $request->id_kategori)
                ->where('ponpes_id', $userPonpesId)
                ->exists();

            if (!$validKategori) {
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

            Keuangan::create([
                'user_id' => (int) $user->id_user,
                'ponpes_id' => $userPonpesId,
                'jumlah' => $request->jumlah,
                'id_kategori' => $request->id_kategori,
                'sumber_dana' => $request->sumber_dana,
                'status' => $request->status,
                'tanggal' => $request->tanggal,
                'keterangan' => $request->keterangan
            ]);

            return redirect()->route('keuangan.index')->with('success', 'Data keuangan berhasil ditambahkan!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menambah data keuangan.')->withInput();
        }
    }

    public function edit($id)
    {
        try {
            \Log::info('Edit method called with ID: ' . $id); // Debug log
            
            $userPonpesId = $this->getUserPonpesId();
            
            // Cek kepemilikan data keuangan - PERBAIKAN DI SINI
            $keuangan = Keuangan::where('id_keuangan', $id)
                ->where('ponpes_id', $userPonpesId)
                ->firstOrFail();
            
            $keuangan->load('kategori');
            
            // Hanya ambil kategori yang milik ponpes user
            $kategories = Kategori::where('ponpes_id', $userPonpesId)->get();
            
            \Log::info('Keuangan found: ' . $keuangan->id_keuangan); // Debug log
            
            return view('pages.action.edit_keuangan', compact('keuangan', 'kategories'));

        } catch (ModelNotFoundException $e) {
            \Log::error('Keuangan not found: ' . $id);
            return redirect()->route('keuangan.index')->with('error', 'Data keuangan tidak ditemukan.');
        } catch (\Exception $e) {
            \Log::error('Error in edit method: ' . $e->getMessage());
            return redirect()->route('keuangan.index')->with('error', 'Terjadi kesalahan saat memuat form edit.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            \Log::info('Update method called with ID: ' . $id); // Debug log
            
            $userPonpesId = $this->getUserPonpesId();

            // Cek kepemilikan data keuangan - PERBAIKAN DI SINI
            $keuangan = Keuangan::where('id_keuangan', $id)
                ->where('ponpes_id', $userPonpesId)
                ->firstOrFail();

            // Validasi bahwa kategori yang dipilih milik ponpes user
            $validKategori = Kategori::where('id_kategori', $request->id_kategori)
                ->where('ponpes_id', $userPonpesId)
                ->exists();

            if (!$validKategori) {
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

            return redirect()->route('keuangan.index')->with('success', 'Data keuangan berhasil diperbarui!');

        } catch (ModelNotFoundException $e) {
            \Log::error('Keuangan not found for update: ' . $id);
            return redirect()->route('keuangan.index')->with('error', 'Data keuangan tidak ditemukan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Error in update method: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui data keuangan.')->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            \Log::info('Delete method called with ID: ' . $id); // Debug log
            
            // Cek kepemilikan data keuangan - PERBAIKAN DI SINI
            $keuangan = Keuangan::where('id_keuangan', $id)
                ->where('ponpes_id', Auth::user()->ponpes_id)
                ->firstOrFail();
            
            $keuangan->delete();

            return redirect()->route('keuangan.index')->with('success', 'Data keuangan berhasil dihapus!');

        } catch (ModelNotFoundException $e) {
            \Log::error('Keuangan not found for delete: ' . $id);
            return redirect()->route('keuangan.index')->with('error', 'Data keuangan tidak ditemukan.');
        } catch (\Exception $e) {
            \Log::error('Error in delete method: ' . $e->getMessage());
            return redirect()->route('keuangan.index')->with('error', 'Terjadi kesalahan saat menghapus data keuangan.');
        }
    }

    /**
     * Get keuangan data for API (optional)
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

            return response()->json([
                'success' => true,
                'data' => $keuangan
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data keuangan'
            ], 500);
        }
    }
}