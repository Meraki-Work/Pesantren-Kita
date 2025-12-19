<?php

namespace App\Http\Controllers;

use App\Models\Sanksi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SanksiController extends Controller
{
    /**
     * Get user's ponpes_id
     */
    private function getUserPonpesId()
    {
        return Auth::user()->ponpes_id;
    }

    /**
     * Check if sanksi belongs to user's ponpes
     */
    private function checkSanksiOwnership($sanksiId)
    {
        $userPonpesId = $this->getUserPonpesId();

        $sanksi = Sanksi::where('id_sanksi', $sanksiId)
            ->where('ponpes_id', $userPonpesId)
            ->first();

        if (!$sanksi) {
            Log::warning('Akses sanksi ditolak', [
                'user_id' => Auth::id(),
                'ponpes_id' => $userPonpesId,
                'sanksi_id' => $sanksiId,
                'ip' => request()->ip()
            ]);
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mengakses data ini.');
        }

        return $sanksi;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            Log::info('Mengakses halaman sanksi index', [
                'user_id' => Auth::id(),
                'ponpes_id' => $this->getUserPonpesId()
            ]);

            $userPonpesId = $this->getUserPonpesId();

            $query = Sanksi::with(['user'])
                ->where('ponpes_id', $userPonpesId);

            // Filter by status
            if ($request->has('status') && $request->status != '') {
                $query->where('status', $request->status);
            }

            // Filter by jenis
            if ($request->has('jenis') && $request->jenis != '') {
                $query->where('jenis', $request->jenis);
            }

            // Filter by date range
            if ($request->has('start_date') && $request->start_date != '') {
                $query->where('tanggal', '>=', $request->start_date);
            }

            if ($request->has('end_date') && $request->end_date != '') {
                $query->where('tanggal', '<=', $request->end_date);
            }

            // Search by user name
            if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('username', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            }

            // 🔥 PERBAIKAN: Hapus orderBy created_at karena tidak ada di tabel
            $sanksi = $query->orderBy('tanggal', 'desc')
                ->orderBy('id_sanksi', 'desc') // Gunakan ID sebagai fallback
                ->paginate(10);

            // Statistics for dashboard
            $statistics = [
                'total' => Sanksi::where('ponpes_id', $userPonpesId)->count(),
                'aktif' => Sanksi::where('ponpes_id', $userPonpesId)->where('status', 'Aktif')->count(),
                'selesai' => Sanksi::where('ponpes_id', $userPonpesId)->where('status', 'Selesai')->count(),
            ];

            Log::info('Berhasil memuat data sanksi', [
                'user_id' => Auth::id(),
                'total_sanksi' => $sanksi->total(),
                'filters' => $request->all()
            ]);

            return view('pages.sanksi', compact('sanksi', 'statistics'));
        } catch (\Exception $e) {
            Log::error('Error pada sanksi index', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat data sanksi.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            Log::info('Mengakses form create sanksi', [
                'user_id' => Auth::id(),
                'ponpes_id' => $this->getUserPonpesId()
            ]);

            $userPonpesId = $this->getUserPonpesId();

            // Hanya ambil users yang milik ponpes user
            $users = User::where('ponpes_id', $userPonpesId)
                ->where('status', 'active')
                ->orderBy('username')
                ->get();

            if ($users->isEmpty()) {
                Log::warning('Tidak ada user aktif untuk sanksi', [
                    'user_id' => Auth::id(),
                    'ponpes_id' => $userPonpesId
                ]);
                return redirect()->route('sanksi.index')
                    ->with('error', 'Tidak ada user aktif yang tersedia. Silakan tambahkan user terlebih dahulu.');
            }

            Log::debug('Data untuk form create sanksi', [
                'users_count' => $users->count()
            ]);

            return view('pages.action.create_sanksi', compact('users'));
        } catch (\Exception $e) {
            Log::error('Error pada sanksi create form', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return redirect()->route('sanksi.index')->with('error', 'Terjadi kesalahan saat memuat form tambah data.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            Log::info('Memproses store sanksi', [
                'user_id' => Auth::id(),
                'ponpes_id' => $this->getUserPonpesId(),
                'input_data' => $request->except(['_token'])
            ]);

            $userPonpesId = $this->getUserPonpesId();

            // Validasi bahwa user yang dipilih milik ponpes user
            $selectedUser = User::where('id_user', $request->user_id)
                ->where('ponpes_id', $userPonpesId)
                ->first();

            if (!$selectedUser) {
                Log::warning('User tidak valid untuk sanksi', [
                    'user_id' => Auth::id(),
                    'selected_user_id' => $request->user_id
                ]);
                return redirect()->back()
                    ->with('error', 'User yang dipilih tidak valid atau tidak memiliki akses.')
                    ->withInput();
            }

            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:user,id_user',
                'jenis' => 'required|string|max:50|in:Ringan,Sedang,Berat,Lainnya',
                'deskripsi' => 'required|string|max:1000',
                'hukuman' => 'required|string|max:255',
                'tanggal' => 'required|date|before_or_equal:today',
                'status' => 'required|in:Aktif,Selesai'
            ], [
                'user_id.required' => 'Pilih user harus diisi.',
                'user_id.exists' => 'User yang dipilih tidak valid.',
                'jenis.required' => 'Jenis sanksi harus diisi.',
                'deskripsi.required' => 'Deskripsi pelanggaran harus diisi.',
                'hukuman.required' => 'Hukuman harus diisi.',
                'tanggal.required' => 'Tanggal sanksi harus diisi.',
                'tanggal.before_or_equal' => 'Tanggal sanksi tidak boleh melebihi hari ini.',
                'status.required' => 'Status harus dipilih.',
            ]);

            if ($validator->fails()) {
                Log::warning('Validasi gagal pada store sanksi', [
                    'user_id' => Auth::id(),
                    'errors' => $validator->errors()->toArray()
                ]);
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $data = $validator->validated();
            $data['ponpes_id'] = $userPonpesId;

            $sanksi = Sanksi::create($data);

            Log::info('Berhasil membuat sanksi baru', [
                'user_id' => Auth::id(),
                'sanksi_id' => $sanksi->id_sanksi,
                'user_target' => $sanksi->user_id,
                'jenis' => $sanksi->jenis
            ]);

            return redirect()->route('sanksi.index')
                ->with('success', 'Data sanksi untuk <strong>' . $selectedUser->username . '</strong> berhasil ditambahkan!');
        } catch (\Exception $e) {
            Log::error('Error pada sanksi store', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menambah data sanksi.')
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            Log::info('Mengakses detail sanksi', [
                'user_id' => Auth::id(),
                'sanksi_id' => $id
            ]);

            // Cek kepemilikan data sanksi
            $sanksi = $this->checkSanksiOwnership($id);
            $sanksi->load(['user']);

            Log::debug('Data sanksi detail', [
                'sanksi_id' => $sanksi->id_sanksi,
                'user' => $sanksi->user->username
            ]);

            return view('pages.action.show_sanksi', compact('sanksi'));
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            Log::warning('Akses tidak diizinkan untuk show sanksi', [
                'user_id' => Auth::id(),
                'sanksi_id' => $id,
                'error' => $e->getMessage()
            ]);
            return redirect()->route('sanksi.index')->with('error', $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Error pada sanksi show_sanksi', [
                'user_id' => Auth::id(),
                'sanksi_id' => $id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return redirect()->route('sanksi.index')->with('error', 'Terjadi kesalahan saat memuat detail sanksi.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            Log::info('Mengakses form edit sanksi', [
                'user_id' => Auth::id(),
                'sanksi_id' => $id
            ]);

            $userPonpesId = $this->getUserPonpesId();

            // Cek kepemilikan data sanksi
            $sanksi = $this->checkSanksiOwnership($id);

            // Hanya ambil users yang milik ponpes user
            $users = User::where('ponpes_id', $userPonpesId)
                ->where('status', 'active')
                ->orderBy('username')
                ->get();

            Log::debug('Data untuk form edit sanksi', [
                'sanksi_id' => $sanksi->id_sanksi,
                'users_count' => $users->count()
            ]);

            return view('pages.action.edit_sanksi', compact('sanksi', 'users'));
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            Log::warning('Akses tidak diizinkan untuk edit sanksi', [
                'user_id' => Auth::id(),
                'sanksi_id' => $id,
                'error' => $e->getMessage()
            ]);
            return redirect()->route('sanksi.index')->with('error', $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Error pada sanksi edit', [
                'user_id' => Auth::id(),
                'sanksi_id' => $id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return redirect()->route('sanksi.index')->with('error', 'Terjadi kesalahan saat memuat form edit.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            Log::info('Memproses update sanksi', [
                'user_id' => Auth::id(),
                'sanksi_id' => $id,
                'input_data' => $request->except(['_token', '_method'])
            ]);

            $userPonpesId = $this->getUserPonpesId();

            // Cek kepemilikan data sanksi
            $sanksi = $this->checkSanksiOwnership($id);

            // Validasi bahwa user yang dipilih milik ponpes user
            $selectedUser = User::where('id_user', $request->user_id)
                ->where('ponpes_id', $userPonpesId)
                ->first();

            if (!$selectedUser) {
                Log::warning('User tidak valid untuk update sanksi', [
                    'user_id' => Auth::id(),
                    'selected_user_id' => $request->user_id
                ]);
                return redirect()->back()
                    ->with('error', 'User yang dipilih tidak valid atau tidak memiliki akses.')
                    ->withInput();
            }

            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:user,id_user',
                'jenis' => 'required|string|max:50|in:Ringan,Sedang,Berat,Lainnya',
                'deskripsi' => 'required|string|max:1000',
                'hukuman' => 'required|string|max:255',
                'tanggal' => 'required|date|before_or_equal:today',
                'status' => 'required|in:Aktif,Selesai'
            ], [
                'user_id.required' => 'Pilih user harus diisi.',
                'user_id.exists' => 'User yang dipilih tidak valid.',
                'jenis.required' => 'Jenis sanksi harus diisi.',
                'deskripsi.required' => 'Deskripsi pelanggaran harus diisi.',
                'hukuman.required' => 'Hukuman harus diisi.',
                'tanggal.required' => 'Tanggal sanksi harus diisi.',
                'tanggal.before_or_equal' => 'Tanggal sanksi tidak boleh melebihi hari ini.',
                'status.required' => 'Status harus dipilih.',
            ]);

            if ($validator->fails()) {
                Log::warning('Validasi gagal pada update sanksi', [
                    'user_id' => Auth::id(),
                    'sanksi_id' => $id,
                    'errors' => $validator->errors()->toArray()
                ]);
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $data = $validator->validated();

            $sanksi->update($data);

            Log::info('Berhasil update sanksi', [
                'user_id' => Auth::id(),
                'sanksi_id' => $sanksi->id_sanksi,
                'user_target' => $sanksi->user_id
            ]);

            return redirect()->route('sanksi.index')
                ->with('success', 'Data sanksi untuk <strong>' . $selectedUser->username . '</strong> berhasil diperbarui!');
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            Log::warning('Akses tidak diizinkan untuk update sanksi', [
                'user_id' => Auth::id(),
                'sanksi_id' => $id,
                'error' => $e->getMessage()
            ]);
            return redirect()->route('sanksi.index')->with('error', $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Error pada sanksi update', [
                'user_id' => Auth::id(),
                'sanksi_id' => $id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui data sanksi.')
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            Log::info('Memproses delete sanksi', [
                'user_id' => Auth::id(),
                'sanksi_id' => $id
            ]);

            // Cek kepemilikan data sanksi
            $sanksi = $this->checkSanksiOwnership($id);

            $sanksiData = [
                'id_sanksi' => $sanksi->id_sanksi,
                'user_id' => $sanksi->user_id,
                'jenis' => $sanksi->jenis
            ];

            $sanksi->delete();

            Log::info('Berhasil delete sanksi', [
                'user_id' => Auth::id(),
                'deleted_sanksi' => $sanksiData
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data sanksi berhasil dihapus'
            ]);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            Log::warning('Akses tidak diizinkan untuk delete sanksi', [
                'user_id' => Auth::id(),
                'sanksi_id' => $id,
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 403);
        } catch (\Exception $e) {
            Log::error('Error pada sanksi delete', [
                'user_id' => Auth::id(),
                'sanksi_id' => $id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data sanksi.'
            ], 500);
        }
    }

    /**
     * Quick update status sanksi
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            Log::info('Memproses update status sanksi', [
                'user_id' => Auth::id(),
                'sanksi_id' => $id,
                'status' => $request->status
            ]);

            // Cek kepemilikan data sanksi
            $sanksi = $this->checkSanksiOwnership($id);

            $request->validate([
                'status' => 'required|in:Aktif,Selesai'
            ]);

            $sanksi->update(['status' => $request->status]);

            Log::info('Berhasil update status sanksi', [
                'user_id' => Auth::id(),
                'sanksi_id' => $sanksi->id_sanksi,
                'status' => $sanksi->status
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status sanksi berhasil diperbarui',
                'status' => $sanksi->status
            ]);
        } catch (\Exception $e) {
            Log::error('Error pada update status sanksi', [
                'user_id' => Auth::id(),
                'sanksi_id' => $id,
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui status.'
            ], 500);
        }
    }
}
