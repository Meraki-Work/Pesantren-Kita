<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        // Build base users query and apply search filter when ?q= is present
        $usersQuery = User::select('id_user','username','email','role','status');
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

        return view('pages.kepegawaian', [
            // Use case-insensitive checks so stored casing doesn't break the counts
            'aktif' => User::whereRaw("LOWER(status) = ?", ['aktif'])->count(),
            'Tidakaktif' => User::whereRaw("LOWER(status) = ?", ['tidak aktif'])->count(),
            // role counts used for role-specific cards
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

        // Validasi input agar hanya menerima nilai yang diharapkan
        $data = $request->validate([
            'username' => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255'],
            'role'     => ['required', 'in:Admin,Pengajar'],
            // status menggunakan nilai lowercase 'aktif' / 'tidak aktif' sesuai form
            'status'   => ['required', 'in:aktif,tidak aktif'],
        ]);

        // Normalisasi - pastikan role menggunakan huruf kapital pada awal kata (Admin / Pengajar)
        $data['role'] = ucfirst(strtolower($data['role']));

        // Simpan data ke model
        $user->update([
            'username' => $data['username'],
            'role'     => $data['role'],
            'email'    => $data['email'],
            'status'   => $data['status'],
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
