<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class KepegawaianController extends Controller
{
    public function index(Request $request)
    {
        // Hanya Admin dan Pengajar yang boleh mengakses halaman ini
        $role = strtolower(Auth::user()->role ?? '');
        if (! in_array($role, ['admin', 'pengajar'])) {
            return redirect('/')->with('error', 'Akses hanya untuk Admin & Pengajar');
        }
        $q = trim($request->query('q', ''));

        // Build base users query - TIDAK MENYERTAKAN STATUS
        $usersQuery = User::select('id_user','username','email','role','ponpes_id','created_at');
        
        if ($q !== '') {
            $qLower = mb_strtolower($q);
            $usersQuery->where(function($builder) use ($qLower) {
                $builder->whereRaw('LOWER(username) LIKE ?', ["%{$qLower}%"])
                        ->orWhereRaw('LOWER(email) LIKE ?', ["%{$qLower}%"])
                        ->orWhereRaw('LOWER(role) LIKE ?', ["%{$qLower}%"]);
                // Hapus pencarian berdasarkan status
            });
        }

        $users = $usersQuery->get();

        return view('pages.kepegawaian', [
            // Hapus hitungan berdasarkan status atau gunakan default
            'aktif' => User::count(), // Default semua aktif jika tidak ada status
            'Tidakaktif' => 0, // Default 0 jika tidak ada status
            // role counts
            'countPengajar' => User::whereRaw("LOWER(role) = ?", ['pengajar'])->count(),
            'countAdmin' => User::whereRaw("LOWER(role) = ?", ['admin'])->count(),
            'countKeuangan' => User::whereRaw("LOWER(role) = ?", ['keuangan'])->count(),
            'total' => User::count(),
            'users' => $users,
            'q' => $q,
            'filteredCount' => $q !== '' ? $users->count() : null,
        ]);
    }

    public function update(Request $request, $id_user)
    {
        // 🔒 Hanya Admin yang boleh update
        if (strtolower(Auth::user()->role ?? '') !== 'admin') {
            abort(403, 'Akses ditolak');
        }

        $user = User::where('id_user', $id_user)->firstOrFail();

        // Validasi input - HAPUS status dari validasi
        $data = $request->validate([
            'username' => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255'],
            'role'     => ['required', 'in:Admin,Pengajar,Keuangan'],
            // Hapus status dari validasi
        ]);

        // Normalisasi role
        $data['role'] = ucfirst(strtolower($data['role']));

        // Simpan data - HAPUS status
        $user->update([
            'username' => $data['username'],
            'role'     => $data['role'],
            'email'    => $data['email'],
            // Hapus status
        ]);

        return back()->with('success', 'Data berhasil diupdate')
                     ->with('close_edit', true);
    }

    public function destroy($id_user)
    {
        // 🔒 Hanya Admin yang boleh hapus
        if (strtolower(Auth::user()->role ?? '') !== 'admin') {
            abort(403, 'Akses ditolak');
        }

        User::where('id_user', $id_user)->delete();

        return back()->with('success', 'Data berhasil dihapus')
                     ->with('close_delete', true);
    }
}