<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Ponpes;

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
        $usersQuery = User::select('id_user','username','email','role','ponpes_id','created_at','status');

        // Filter berdasarkan ponpes_id untuk Admin dan Pengajar
        if (in_array($role, ['admin', 'pengajar'])) {
            $usersQuery->where('ponpes_id', Auth::user()->ponpes_id);
        }
        
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

        // Statistik berdasarkan ponpes_id untuk Admin dan Pengajar
        $statsQuery = User::query();
        if (in_array($role, ['admin', 'pengajar'])) {
            $statsQuery->where('ponpes_id', Auth::user()->ponpes_id);
        }

        // Ambil data ponpes untuk header
        $ponpes = Ponpes::where('id_ponpes', Auth::user()->ponpes_id)->first();

        return view('pages.kepegawaian', [
            // Hitungan berdasarkan status enum
            'aktif' => (clone $statsQuery)->where('status', 'active')->count(),
            'Tidakaktif' => (clone $statsQuery)->whereIn('status', ['pending', 'suspended'])->count(),
            // role counts
            'countPengajar' => (clone $statsQuery)->whereRaw("LOWER(role) = ?", ['pengajar'])->count(),
            'countAdmin' => (clone $statsQuery)->whereRaw("LOWER(role) = ?", ['admin'])->count(),
            'countKeuangan' => (clone $statsQuery)->whereRaw("LOWER(role) = ?", ['keuangan'])->count(),
            'total' => $statsQuery->count(),
            'users' => $users,
            'q' => $q,
            'filteredCount' => $q !== '' ? $users->count() : null,
            'ponpes' => $ponpes,
        ]);
    }

    public function update(Request $request, $id_user)
    {
        // 🔒 Hanya Admin yang boleh update
        if (strtolower(Auth::user()->role ?? '') !== 'admin') {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Akses ditolak'], 403);
            }
            abort(403, 'Akses ditolak');
        }

        $user = User::where('id_user', $id_user)->firstOrFail();

        // Pastikan admin hanya bisa edit user di ponpes_id yang sama
        if ($user->ponpes_id !== Auth::user()->ponpes_id) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Akses ditolak'], 403);
            }
            abort(403, 'Akses ditolak');
        }

        // Validasi input
        $data = $request->validate([
            'username' => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255'],
            'role'     => ['required', 'in:Admin,Pengajar,Keuangan'],
            'status'   => ['required', 'in:pending,active,suspended'],
        ]);

        // Normalisasi role
        $data['role'] = ucfirst(strtolower($data['role']));

        // Simpan data
        $user->update([
            'username' => $data['username'],
            'role'     => $data['role'],
            'email'    => $data['email'],
            'status'   => $data['status'],
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Data berhasil diupdate',
                'user' => $user,
                'redirect' => route('kepegawaian.index'),
            ]);
        }

        return back()->with('success', 'Data berhasil diupdate')
                     ->with('close_edit', true);
    }

    public function destroy($id_user)
    {
        // 🔒 Hanya Admin yang boleh hapus
        if (strtolower(Auth::user()->role ?? '') !== 'admin') {
            if (request()->expectsJson()) {
                return response()->json(['error' => 'Akses ditolak'], 403);
            }
            abort(403, 'Akses ditolak');
        }

        User::where('id_user', $id_user)->delete();

        $user = User::where('id_user', $id_user)->firstOrFail();

        // Pastikan admin hanya bisa hapus user di ponpes_id yang sama
        if ($user->ponpes_id !== Auth::user()->ponpes_id) {
            if (request()->expectsJson()) {
                return response()->json(['error' => 'Akses ditolak'], 403);
            }
            abort(403, 'Akses ditolak');
        }

        $user->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'message' => 'Data berhasil dihapus',
                'redirect' => route('kepegawaian.index'),
            ]);
        }

        return back()->with('success', 'Data berhasil dihapus')
                     ->with('close_delete', true);
    }
}