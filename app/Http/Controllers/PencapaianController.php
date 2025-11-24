<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class PencapaianController extends Controller
{
    /**
     * Mendapatkan ponpes_id user yang login
     */
    private function getUserPonpesId(): string
    {
        return Auth::user()->ponpes_id;
    }

    /**
     * Cek kepemilikan data berdasarkan ponpes_id
     */
    private function checkOwnership(int $idPencapaian): bool
    {
        $userPonpesId = $this->getUserPonpesId();
        
        $pencapaian = DB::table('pencapaian')
            ->where('id_pencapaian', $idPencapaian)
            ->where('ponpes_id', $userPonpesId)
            ->first();
            
        return $pencapaian !== null;
    }

    /**
     * Menampilkan form edit pencapaian dengan proteksi
     */
    public function edit(int $id): View|RedirectResponse
    {
        $userPonpesId = $this->getUserPonpesId();

        /** @var \stdClass|null $pencapaian */
        $pencapaian = DB::table('pencapaian as p')
            ->join('santri as s', 's.id_santri', '=', 'p.id_santri')
            ->join('kelas as k', 'k.id_kelas', '=', 's.id_kelas')
            ->where('p.id_pencapaian', $id)
            ->where('p.ponpes_id', $userPonpesId)
            ->where('s.ponpes_id', $userPonpesId)
            ->select('p.*', 's.nama as nama_santri', 'k.nama_kelas')
            ->first();

        if (!$pencapaian) {
            return redirect()->route('santri.index')
                ->with('error', 'Data pencapaian tidak ditemukan atau tidak memiliki akses');
        }

        return view('pages.action.pencapaian_edit', compact('pencapaian'));
    }

    /**
     * Update data pencapaian dengan proteksi
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        // Cek kepemilikan data sebelum update
        if (!$this->checkOwnership($id)) {
            return redirect()->route('santri.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengupdate data ini');
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tipe' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'skor' => 'required|numeric|min:0|max:100'
        ]);

        $updated = DB::table('pencapaian')
            ->where('id_pencapaian', $id)
            ->where('ponpes_id', $this->getUserPonpesId())
            ->update([
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'tipe' => $request->tipe,
                'tanggal' => $request->tanggal,
                'skor' => $request->skor
            ]);

        if ($updated) {
            return redirect()->route('santri.index')->with('success', 'Data pencapaian berhasil diupdate');
        }

        return redirect()->back()->with('error', 'Gagal mengupdate data pencapaian');
    }

    /**
     * Hapus data pencapaian dengan proteksi
     */
    public function destroy(int $id): RedirectResponse
    {
        try {
            // Cek kepemilikan data sebelum delete
            if (!$this->checkOwnership($id)) {
                return redirect()->route('santri.index')
                    ->with('error', 'Anda tidak memiliki akses untuk menghapus data ini');
            }

            $deleted = DB::table('pencapaian')
                ->where('id_pencapaian', $id)
                ->where('ponpes_id', $this->getUserPonpesId())
                ->delete();

            if ($deleted) {
                return redirect()->back()->with('success', 'Data pencapaian berhasil dihapus');
            }

            return redirect()->back()->with('error', 'Data pencapaian tidak ditemukan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data pencapaian: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan data pencapaian dengan filter ponpes_id
     */
    public function index(): View
    {
        $userPonpesId = $this->getUserPonpesId();
        
        /** @var \Illuminate\Pagination\LengthAwarePaginator $pencapaian */
        $pencapaian = DB::table('pencapaian as p')
            ->join('santri as s', 's.id_santri', '=', 'p.id_santri')
            ->join('kelas as k', 'k.id_kelas', '=', 's.id_kelas')
            ->where('p.ponpes_id', $userPonpesId)
            ->where('s.ponpes_id', $userPonpesId)
            ->select('p.*', 's.nama as nama_santri', 'k.nama_kelas')
            ->orderBy('p.tanggal', 'desc')
            ->paginate(10);

        return view('pages.pencapaian.index', compact('pencapaian'));
    }

    /**
     * Menampilkan form create pencapaian
     */
    public function create(): View
    {
        $userPonpesId = $this->getUserPonpesId();
        
        /** @var \Illuminate\Support\Collection $santri */
        $santri = DB::table('santri')
            ->where('ponpes_id', $userPonpesId)
            ->get();

        return view('pages.action.pencapaian_create', compact('santri'));
    }

    /**
     * Simpan data pencapaian baru
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'id_santri' => 'required|exists:santri,id_santri',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tipe' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'skor' => 'required|numeric|min:0|max:100'
        ]);

        // Validasi tambahan: pastikan santri milik ponpes yang sama
        $userPonpesId = $this->getUserPonpesId();
        $santri = DB::table('santri')
            ->where('id_santri', $request->id_santri)
            ->where('ponpes_id', $userPonpesId)
            ->first();

        if (!$santri) {
            return redirect()->back()->with('error', 'Santri tidak valid atau tidak memiliki akses');
        }

        $inserted = DB::table('pencapaian')->insert([
            'ponpes_id' => $userPonpesId,
            'id_santri' => $request->id_santri,
            'user_id' => Auth::id(),
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'tipe' => $request->tipe,
            'tanggal' => $request->tanggal,
            'skor' => $request->skor,
            'created_at' => now()
        ]);

        if ($inserted) {
            return redirect()->route('pencapaian.index')->with('success', 'Data pencapaian berhasil ditambahkan');
        }

        return redirect()->back()->with('error', 'Gagal menambahkan data pencapaian');
    }

    /**
     * Get data untuk chart prestasi berdasarkan tipe pencapaian
     */
    public function getChartData(): JsonResponse
    {
        $userPonpesId = $this->getUserPonpesId();

        /** @var \Illuminate\Support\Collection $chartData */
        $chartData = DB::table('pencapaian')
            ->where('ponpes_id', $userPonpesId)
            ->whereNotNull('tipe')
            ->select('tipe', DB::raw('COUNT(*) as total'))
            ->groupBy('tipe')
            ->get();

        // Format data untuk chart
        $labels = [];
        $data = [];
        $colors = [
            'Akademik' => '#fb923c',
            'Non-Akademik' => '#2563eb',  
            'Tahfidz' => '#fed7aa',
            'Hafalan' => '#99f6e4',
            'Lainnya' => '#cbd5e1'
        ];

        $defaultData = [
            'Akademik' => 0,
            'Non-Akademik' => 0,
            'Tahfidz' => 0,
            'Hafalan' => 0,
            'Lainnya' => 0
        ];

        // Merge data dari database dengan default
        foreach ($chartData as $item) {
            $tipe = $item->tipe ?? 'Lainnya';
            $defaultData[$tipe] = $item->total;
        }

        // Siapkan data untuk chart
        foreach ($defaultData as $tipe => $total) {
            $labels[] = $tipe;
            $data[] = $total;
        }

        return response()->json([
            'labels' => $labels,
            'data' => $data,
            'colors' => array_values($colors),
            'total' => array_sum($data)
        ]);
    }

    /**
     * Get data chart dengan filter tanggal (opsional)
     */
    public function getChartDataFiltered(Request $request): JsonResponse
    {
        $userPonpesId = $this->getUserPonpesId();

        $query = DB::table('pencapaian')
            ->where('ponpes_id', $userPonpesId)
            ->whereNotNull('tipe');

        // Filter tanggal jika ada
        if ($request->has('start_date') && $request->start_date) {
            $query->where('tanggal', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date) {
            $query->where('tanggal', '<=', $request->end_date);
        }

        /** @var \Illuminate\Support\Collection $chartData */
        $chartData = $query->select('tipe', DB::raw('COUNT(*) as total'))
            ->groupBy('tipe')
            ->get();

        // Format data sama seperti method di atas
        $labels = [];
        $data = [];
        $colors = [
            'Akademik' => '#fb923c',
            'Non-Akademik' => '#2563eb', 
            'Tahfidz' => '#fed7aa',
            'Hafalan' => '#99f6e4',
            'Lainnya' => '#cbd5e1'
        ];

        $defaultData = [
            'Akademik' => 0,
            'Non-Akademik' => 0,
            'Tahfidz' => 0,
            'Hafalan' => 0,
            'Lainnya' => 0
        ];

        foreach ($chartData as $item) {
            $tipe = $item->tipe ?? 'Lainnya';
            $defaultData[$tipe] = $item->total;
        }

        foreach ($defaultData as $tipe => $total) {
            $labels[] = $tipe;
            $data[] = $total;
        }

        return response()->json([
            'labels' => $labels,
            'data' => $data,
            'colors' => array_values($colors),
            'total' => array_sum($data)
        ]);
    }
}