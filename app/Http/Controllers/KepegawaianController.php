<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ponpes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KepegawaianController extends Controller
{
    public function index(Request $request)
    {
        $role = strtolower(Auth::user()->role ?? '');
        if (!in_array($role, ['admin', 'pengajar'])) {
            return redirect('/')->with('error', 'Akses ditolak');
        }

        $q = trim($request->query('q', ''));

        $usersQuery = User::select(
            'id_user',
            'username',
            'email',
            'role',
            'ponpes_id',
            'created_at',
            'status'
        )->where('ponpes_id', Auth::user()->ponpes_id);

        if ($q !== '') {
            $qLower = mb_strtolower($q);
            $usersQuery->where(function ($builder) use ($qLower) {
                $builder->whereRaw('LOWER(username) LIKE ?', ["%{$qLower}%"])
                        ->orWhereRaw('LOWER(email) LIKE ?', ["%{$qLower}%"])
                        ->orWhereRaw('LOWER(role) LIKE ?', ["%{$qLower}%"]);
            });
        }

        $users = $usersQuery->get();

        $statsQuery = User::where('ponpes_id', Auth::user()->ponpes_id);

        $ponpes = Ponpes::where('id_ponpes', Auth::user()->ponpes_id)->first();

        return view('pages.kepegawaian', [
            'aktif' => (clone $statsQuery)->where('status', 'active')->count(),
            'Tidakaktif' => (clone $statsQuery)->whereIn('status', ['pending', 'suspended'])->count(),
            'countPengajar' => (clone $statsQuery)->whereRaw('LOWER(role) = ?', ['pengajar'])->count(),
            'countAdmin' => (clone $statsQuery)->whereRaw('LOWER(role) = ?', ['admin'])->count(),
            'countKeuangan' => (clone $statsQuery)->whereRaw('LOWER(role) = ?', ['keuangan'])->count(),
            'total' => $statsQuery->count(),
            'users' => $users,
            'q' => $q,
            'filteredCount' => $q !== '' ? $users->count() : null,
            'ponpes' => $ponpes,
        ]);
    }

    public function update(Request $request, $id_user)
    {
        if (strtolower(Auth::user()->role ?? '') !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak'
            ], 403);
        }

        $user = User::where('id_user', $id_user)->firstOrFail();

        if ($user->ponpes_id !== Auth::user()->ponpes_id) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak'
            ], 403);
        }

        $data = $request->validate([
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'role' => ['required', 'in:Admin,Pengajar,Keuangan'],
            'status' => ['required', 'in:pending,active,suspended'],
        ]);

        $data['role'] = ucfirst(strtolower($data['role']));

        $user->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diupdate',
            'redirect' => route('kepegawaian.index')
        ]);
    }

    public function destroy($id_user)
    {
        if (strtolower(Auth::user()->role ?? '') !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak'
            ], 403);
        }

        $user = User::where('id_user', $id_user)->firstOrFail();

        if ($user->ponpes_id !== Auth::user()->ponpes_id) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak'
            ], 403);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus',
            'redirect' => route('kepegawaian.index')
        ]);
    }
}
