<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Ponpes;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Tampilkan semua user
    public function index(Request $request)
    {
        // Get statistics
        $statistics = [
            'total_users' => User::count(),
            'active_users' => User::where('status', 'Aktif')->count(),
            'inactive_users' => User::where('status', 'Nonaktif')->count(),
            'by_role' => User::groupBy('role')->select('role', \DB::raw('COUNT(*) as count'))->pluck('count', 'role')->toArray(),
            'total_ponpes' => Ponpes::count()
        ];
        
        // Get ponpes list for filter dropdown
        $ponpesList = Ponpes::all();
        
        // Get paginated users with filters
        $query = User::query();
        
        if ($request->has('ponpes_id') && $request->ponpes_id != '') {
            $query->where('ponpes_id', $request->ponpes_id);
        }
        
        if ($request->has('role') && $request->role != '') {
            $query->where('role', $request->role);
        }
        
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) {
                $q->where('username', 'like', '%' . request('search') . '%')
                  ->orWhere('name', 'like', '%' . request('search') . '%')
                  ->orWhere('email', 'like', '%' . request('search') . '%');
            });
        }
        
        // Paginate LAST (important!)
        $users = $query->paginate(15)->withQueryString();
        
        return view('super.index', [
            'users' => $users,
            'statistics' => $statistics,
            'ponpesList' => $ponpesList
        ]);
    }

    // Form tambah user
    public function create()
    {
        return view('super.create');
    }

    // Simpan user baru
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'nullable|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:Admin,Pengajar,Keuangan,Super'
        ]);

        User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role
        ]);

        return redirect()->route('super.users.index')->with('success', 'User berhasil ditambahkan');
    }

    // Form edit
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('super.users.edit', compact('user'));
    }

    // Update user
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'role' => 'required|in:Admin,Pengajar,Keuangan,Super'
        ]);

        $data = [
            'username' => $request->username,
            'email' => $request->email,
            'role' => $request->role
        ];

        // Jika password diisi, update password
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('super.users.index')->with('success', 'User berhasil diupdate');
    }

    // Hapus user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('super.users.index')->with('success', 'User berhasil dihapus');
    }

    // Detail user (opsional)
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('super.show', compact('user'));
    }
}