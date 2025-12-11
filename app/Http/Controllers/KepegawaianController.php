<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class KepegawaianController extends Controller
{
    public function index(Request $request)
    {
        // Hanya Admin dan Pengajar yang boleh mengakses halaman ini
        $userRole = strtolower(Auth::user()->role ?? '');
        if (!in_array($userRole, ['admin', 'pengajar'])) {
            return redirect('/')->with('error', 'Akses hanya untuk Admin & Pengajar');
        }

        $q = trim($request->query('q', ''));

        // Dapatkan ponpes_id dari user yang login
        $ponpesId = Auth::user()->ponpes_id;

        // Build base users query - hanya user dengan ponpes_id yang sama
        $usersQuery = User::select('id_user', 'username', 'email', 'role', 'status', 'ponpes_id', 'created_at')
            ->where('ponpes_id', $ponpesId)
            ->orderBy('role')
            ->orderBy('username');

        // Filter pencarian
        if ($q !== '') {
            $qLower = mb_strtolower($q);
            $usersQuery->where(function($builder) use ($qLower) {
                $builder->whereRaw('LOWER(username) LIKE ?', ["%{$qLower}%"])
                        ->orWhereRaw('LOWER(email) LIKE ?', ["%{$qLower}%"])
                        ->orWhereRaw('LOWER(role) LIKE ?', ["%{$qLower}%"])
                        ->orWhereRaw('LOWER(status) LIKE ?', ["%{$qLower}%"]);
            });
        }

        $users = $usersQuery->get();

        // Hitung statistik berdasarkan ponpes_id user yang login
        $queryStats = User::where('ponpes_id', $ponpesId);

        // Statistik untuk Admin
        $statsForAdmin = [
            'aktif' => $queryStats->clone()->where('status', 'aktif')->count(),
            'Tidakaktif' => $queryStats->clone()->where('status', 'tidak aktif')->count(),
            'total' => $queryStats->clone()->count(),
        ];

        // Statistik untuk Pengajar (berdasarkan role)
        $statsForPengajar = [
            'countPengajar' => $queryStats->clone()->where('role', 'Pengajar')->count(),
            'countAdmin' => $queryStats->clone()->where('role', 'Admin')->count(),
            'countKeuangan' => $queryStats->clone()->where('role', 'Keuangan')->count(),
        ];

        return view('pages.kepegawaian', array_merge([
            'users' => $users,
            'q' => $q,
            'filteredCount' => $q !== '' ? $users->count() : null,
        ], 
        // Sesuaikan statistik berdasarkan role user
        $userRole === 'admin' ? $statsForAdmin : $statsForPengajar));
    }

    public function update(Request $request, $id_user)
    {
        // 🔒 Hanya Admin yang boleh update
        if (strtolower(Auth::user()->role ?? '') !== 'admin') {
            abort(403, 'Akses ditolak');
        }

        $user = User::where('id_user', $id_user)->firstOrFail();

        // Validasi input
        $data = $request->validate([
            'username' => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255'],
            'role'     => ['required', 'in:Admin,Pengajar,Keuangan'],
            'status'   => ['required', 'in:aktif,tidak aktif'],
        ]);

        // Normalisasi role dan status
        $data['role'] = ucfirst(strtolower($data['role']));
        $data['status'] = strtolower($data['status']);

        // Cek email unique untuk ponpes yang sama (kecuali untuk user ini)
        $emailExists = User::where('email', $data['email'])
            ->where('ponpes_id', $user->ponpes_id)
            ->where('id_user', '!=', $id_user)
            ->exists();

        if ($emailExists) {
            return back()->with('error', 'Email sudah digunakan oleh pegawai lain di pesantren ini.');
        }

        // Update data
        $user->update([
            'username' => $data['username'],
            'role'     => $data['role'],
            'email'    => $data['email'],
            'status'   => $data['status'],
        ]);

        return back()->with('success', 'Data pegawai berhasil diperbarui')
                     ->with('close_edit', true);
    }

    public function store(Request $request)
    {
        // 🔒 Hanya Admin yang boleh menambahkan
        if (strtolower(Auth::user()->role ?? '') !== 'admin') {
            abort(403, 'Akses ditolak');
        }

        // Validasi input
        $data = $request->validate([
            'username' => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255'],
            'role'     => ['required', 'in:Admin,Pengajar,Keuangan'],
            'password' => ['required', 'string', 'min:6'],
            'status'   => ['required', 'in:aktif,tidak aktif'],
        ]);

        // Normalisasi role dan status
        $data['role'] = ucfirst(strtolower($data['role']));
        $data['status'] = strtolower($data['status']);

        // Cek email unique untuk ponpes yang sama
        $emailExists = User::where('email', $data['email'])
            ->where('ponpes_id', Auth::user()->ponpes_id)
            ->exists();

        if ($emailExists) {
            return back()->with('error', 'Email sudah digunakan oleh pegawai lain di pesantren ini.');
        }

        // Create new user
        User::create([
            'username' => $data['username'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => $data['role'],
            'status'   => $data['status'],
            'ponpes_id' => Auth::user()->ponpes_id,
        ]);

        return back()->with('success', 'Pegawai baru berhasil ditambahkan');
    }

    public function destroy($id_user)
    {
        // 🔒 Hanya Admin yang boleh hapus
        if (strtolower(Auth::user()->role ?? '') !== 'admin') {
            abort(403, 'Akses ditolak');
        }

        $user = User::where('id_user', $id_user)->firstOrFail();

        // Cegah menghapus diri sendiri
        if ($user->id_user == Auth::id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        $user->delete();

        return back()->with('success', 'Pegawai berhasil dihapus')
                     ->with('close_delete', true);
    }

    public function toggleStatus($id_user)
    {
        // 🔒 Hanya Admin yang boleh mengubah status
        if (strtolower(Auth::user()->role ?? '') !== 'admin') {
            abort(403, 'Akses ditolak');
        }

        $user = User::where('id_user', $id_user)->firstOrFail();
        
        $newStatus = $user->status == 'aktif' ? 'tidak aktif' : 'aktif';
        
        $user->update(['status' => $newStatus]);

        return response()->json([
            'success' => true,
            'message' => 'Status berhasil diubah',
            'new_status' => $newStatus
        ]);
    }

    public function checkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'id_user' => 'nullable|exists:user,id_user'
        ]);

        $query = User::where('email', $request->email)
            ->where('ponpes_id', Auth::user()->ponpes_id);

        if ($request->id_user) {
            $query->where('id_user', '!=', $request->id_user);
        }

        $exists = $query->exists();

        return response()->json([
            'exists' => $exists,
            'message' => $exists ? 'Email sudah digunakan' : 'Email tersedia'
        ]);
    }

    public function export()
    {
        // 🔒 Hanya Admin yang boleh export
        if (strtolower(Auth::user()->role ?? '') !== 'admin') {
            abort(403, 'Akses ditolak');
        }

        $users = User::where('ponpes_id', Auth::user()->ponpes_id)
            ->select('username', 'email', 'role', 'status', 'created_at')
            ->orderBy('role')
            ->orderBy('username')
            ->get();

        // Header CSV
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="data-pegawai-' . date('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fwrite($file, "\xEF\xBB\xBF");
            
            // Header
            fputcsv($file, ['Nama', 'Email', 'Jabatan', 'Status', 'Tanggal Bergabung']);
            
            // Data
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->username,
                    $user->email,
                    $user->role,
                    ucfirst($user->status),
                    $user->created_at->format('d/m/Y')
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}