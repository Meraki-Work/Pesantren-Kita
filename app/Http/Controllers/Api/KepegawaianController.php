<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class KepegawaianController extends Controller
{
    public function index(Request $request)
    {
        // Hanya Admin dan Pengajar yang boleh mengakses
        $role = strtolower(Auth::user()->role ?? '');
        if (!in_array($role, ['admin', 'pengajar'])) {
            return response()->json(['error' => 'Akses hanya untuk Admin & Pengajar'], 403);
        }

        $q = trim($request->query('q', ''));

        // Build base users query
        $usersQuery = User::select('id_user', 'username', 'email', 'role', 'ponpes_id', 'created_at', 'status');

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
            });
        }

        $users = $usersQuery->get();

        // Statistik berdasarkan ponpes_id untuk Admin dan Pengajar
        $statsQuery = User::query();
        if (in_array($role, ['admin', 'pengajar'])) {
            $statsQuery->where('ponpes_id', Auth::user()->ponpes_id);
        }

        // Statistik
        $aktif = (clone $statsQuery)->where('status', 'active')->count();
        $tidakAktif = (clone $statsQuery)->whereIn('status', ['pending', 'suspended'])->count();
        $total = $statsQuery->count();
        $countPengajar = (clone $statsQuery)->whereRaw("LOWER(role) = ?", ['pengajar'])->count();
        $countAdmin = (clone $statsQuery)->whereRaw("LOWER(role) = ?", ['admin'])->count();
        $countKeuangan = (clone $statsQuery)->whereRaw("LOWER(role) = ?", ['keuangan'])->count();

        return response()->json([
            'users' => $users,
            'stats' => [
                'aktif' => $aktif,
                'tidak_aktif' => $tidakAktif,
                'total' => $total,
                'count_pengajar' => $countPengajar,
                'count_admin' => $countAdmin,
                'count_keuangan' => $countKeuangan,
            ],
            'filtered_count' => $q !== '' ? $users->count() : null,
            'query' => $q,
        ]);
    }

    public function update(Request $request, $id_user)
    {
        // Hanya Admin yang boleh update
        if (strtolower(Auth::user()->role ?? '') !== 'admin') {
            return response()->json(['error' => 'Akses ditolak'], 403);
        }

        $user = User::where('id_user', $id_user)->firstOrFail();

        // Pastikan admin hanya bisa edit user di ponpes_id yang sama
        if ($user->ponpes_id !== Auth::user()->ponpes_id) {
            return response()->json(['error' => 'Akses ditolak'], 403);
        }

        // Validasi input
        $validator = Validator::make($request->all(), [
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'role' => ['required', 'in:Admin,Pengajar,Keuangan'],
            'status' => ['required', 'in:pending,active,suspended'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Validation failed', 'messages' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        // Normalisasi role
        $data['role'] = ucfirst(strtolower($data['role']));

        // Update user
        $user->update($data);

        return response()->json([
            'message' => 'Data berhasil diupdate',
            'user' => $user,
            'redirect' => route('kepegawaian.index'), // Jika diperlukan untuk frontend
        ]);
    }

    public function destroy($id_user)
    {
        // Hanya Admin yang boleh hapus
        if (strtolower(Auth::user()->role ?? '') !== 'admin') {
            return response()->json(['error' => 'Akses ditolak'], 403);
        }

        $user = User::where('id_user', $id_user)->firstOrFail();

        // Pastikan admin hanya bisa hapus user di ponpes_id yang sama
        if ($user->ponpes_id !== Auth::user()->ponpes_id) {
            return response()->json(['error' => 'Akses ditolak'], 403);
        }

        $user->delete();

        return response()->json([
            'message' => 'Data berhasil dihapus',
            'redirect' => route('kepegawaian.index'), // Jika diperlukan untuk frontend
        ]);
    }
}