<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Ponpes;
use App\Models\Absensi;
use App\Models\SystemLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class UserManagementController extends Controller
{
    /**
     * Display a listing of all users
     */
    public function index(Request $request)
    {
        // Get filter parameters
        $ponpesId = $request->get('ponpes_id');
        $role = $request->get('role');
        $status = $request->get('status');
        $search = $request->get('search');
        
        // Query users with relationships
        $usersQuery = User::with('ponpes')->orderBy('created_at', 'desc');
        
        // Apply filters
        if ($ponpesId) {
            $usersQuery->where('id_ponpes', $ponpesId);
        }
        
        if ($role) {
            $usersQuery->where('role', $role);
        }
        
        if ($status) {
            $usersQuery->where('status', $status);
        }
        
        if ($search) {
            $usersQuery->where(function($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }
        
        // Get paginated results
        $users = $usersQuery->paginate(20);
        
        // Get all ponpes for filter dropdown
        $ponpesList = Ponpes::orderBy('nama_ponpes')->get();
        
        // Get statistics - PASTIKAN INI ADA
        $statistics = [
            'total_users' => User::count(),
            'total_ponpes' => Ponpes::count(),
            'active_users' => User::where('status', 'Aktif')->count(),
            'inactive_users' => User::where('status', 'Nonaktif')->count(),
            'by_role' => [
                'Admin' => User::where('role', 'Admin')->count(),
                'Super' => User::where('role', 'Super')->count(),
                'Pengajar' => User::where('role', 'Pengajar')->count(),
                'Keuangan' => User::where('role', 'Keuangan')->count(),
            ]
        ];
        
        // Log activity
        $this->logActivity(
            'VIEW_ALL_USERS',
            'Melihat halaman manajemen user',
            $request,
            [
                'ponpes_id' => $ponpesId,
                'role' => $role,
                'status' => $status,
                'search' => $search,
                'total_users' => $users->total()
            ]
        );
        
        // KIRIM VARIABEL KE VIEW
        return view('super.index', compact('users', 'ponpesList', 'statistics'));
    }
    
    /**
     * Show form to create new user
     */
    public function create()
    {
        $ponpesList = Ponpes::where('status', 'Aktif')->orderBy('nama_ponpes')->get();
        $roles = ['Admin', 'Super', 'Pengajar', 'Keuangan'];
        
        return view('super.create', compact('ponpesList', 'roles'));
    }
    
    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:Admin,Super,Pengajar,Keuangan',
            'id_ponpes' => 'required|exists:ponpes,id_ponpes',
            'status' => 'required|in:Aktif,Nonaktif',
            'no_telp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        // Handle photo upload
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('user-photos', 'public');
        }
        
        // Create user
        $user = User::create([
            'username' => $validated['username'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'id_ponpes' => $validated['id_ponpes'],
            'status' => $validated['status'],
            'no_telp' => $validated['no_telp'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
            'foto' => $fotoPath,
        ]);
        
        // Log activity
        $this->logActivity(
            'CREATE_USER',
            'Menambahkan user baru: ' . $user->username,
            $request,
            [
                'user_id' => $user->id,
                'username' => $user->username,
                'role' => $user->role,
                'ponpes' => $user->ponpes->nama_ponpes ?? 'N/A'
            ]
        );
        
        return redirect()->route('super.users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }
    
    /**
     * Display user details
     */
    public function show($id)
    {
        $user = User::with('ponpes')->findOrFail($id);
        
        // Get user statistics
        $statistics = [
            'total_absensi' => Absensi::where('user_id', $user->id)->count(),
            'hadir' => Absensi::where('user_id', $user->id)->where('status', 'Hadir')->count(),
            'izin' => Absensi::where('user_id', $user->id)->where('status', 'Izin')->count(),
            'sakit' => Absensi::where('user_id', $user->id)->where('status', 'Sakit')->count(),
            'alpa' => Absensi::where('user_id', $user->id)->where('status', 'Alpa')->count(),
            'absensi_bulan_ini' => Absensi::where('user_id', $user->id)
                ->whereMonth('tanggal', Carbon::now()->month)
                ->whereYear('tanggal', Carbon::now()->year)
                ->count(),
            'recent_absensi' => Absensi::where('user_id', $user->id)
                ->orderBy('tanggal', 'desc')
                ->limit(5)
                ->get(),
            'activity_logs' => SystemLog::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
        ];
        
        // Log activity
        $this->logActivity(
            'VIEW_USER_DETAIL',
            'Melihat detail user: ' . $user->username,
            request(),
            ['user_id' => $user->id]
        );
        
        return view('super.show', compact('user', 'statistics'));
    }
    
    /**
     * Show form to edit user
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $ponpesList = Ponpes::where('status', 'Aktif')->orderBy('nama_ponpes')->get();
        $roles = ['Admin', 'Super', 'Pengajar', 'Keuangan'];
        
        return view('super.edit', compact('user', 'ponpesList', 'roles'));
    }
    
    /**
     * Update user
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6|confirmed',
            'role' => 'required|in:Admin,Super,Pengajar,Keuangan',
            'id_ponpes' => 'required|exists:ponpes,id_ponpes',
            'status' => 'required|in:Aktif,Nonaktif',
            'no_telp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'remove_photo' => 'nullable|boolean'
        ]);
        
        // Handle photo
        if ($request->has('remove_photo') && $request->remove_photo) {
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }
            $validated['foto'] = null;
        }
        
        if ($request->hasFile('foto')) {
            // Delete old photo
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }
            $validated['foto'] = $request->file('foto')->store('user-photos', 'public');
        }
        
        // Update password if provided
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }
        
        $user->update($validated);
        
        // Log activity
        $this->logActivity(
            'UPDATE_USER',
            'Mengupdate data user: ' . $user->username,
            $request,
            [
                'user_id' => $user->id,
                'username' => $user->username,
                'changes' => array_keys($validated)
            ]
        );
        
        return redirect()->route('super.users.index')
            ->with('success', 'User berhasil diperbarui.');
    }
    
    /**
     * Delete user
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent deleting super admin if only one
        if ($user->role == 'Super' && User::where('role', 'Super')->count() <= 1) {
            return redirect()->route('super.users.index')
                ->with('error', 'Tidak dapat menghapus satu-satunya Super Admin.');
        }
        
        // Delete user photo
        if ($user->foto && Storage::disk('public')->exists($user->foto)) {
            Storage::disk('public')->delete($user->foto);
        }
        
        $userData = [
            'id' => $user->id,
            'username' => $user->username,
            'role' => $user->role,
            'ponpes' => $user->ponpes->nama_ponpes ?? 'N/A'
        ];
        
        $user->delete();
        
        $this->logActivity(
            'DELETE_USER',
            'Menghapus user: ' . $userData['username'],
            request(),
            ['deleted_data' => $userData]
        );
        
        return redirect()->route('super.users.index')
            ->with('success', 'User berhasil dihapus.');
    }
    
    /**
     * Show users grouped by ponpes
     */
    public function groupByPonpes(Request $request)
    {
        $ponpesId = $request->get('ponpes_id');
        
        // Get all ponpes with their users
        $ponpesList = Ponpes::with(['users' => function($query) {
            $query->orderBy('username');
        }])->orderBy('nama_ponpes');
        
        if ($ponpesId) {
            $ponpesList->where('id_ponpes', $ponpesId);
        }
        
        $ponpes = $ponpesList->get();
        
        // Filter only ponpes that have users
        $ponpes = $ponpes->filter(function($item) {
            return $item->users->count() > 0;
        });
        
        $statistics = [
            'total_ponpes_with_users' => $ponpes->count(),
            'total_users' => User::count(),
            'total_active_users' => User::where('status', 'Aktif')->count(),
        ];
        
        return view('super.group-by-ponpes', compact('ponpes', 'statistics'));
    }
    
    /**
     * Export users to CSV
     */
    public function export(Request $request)
    {
        $ponpesId = $request->get('ponpes_id');
        $role = $request->get('role');
        
        $usersQuery = User::with('ponpes');
        
        if ($ponpesId) {
            $usersQuery->where('id_ponpes', $ponpesId);
        }
        
        if ($role) {
            $usersQuery->where('role', $role);
        }
        
        $users = $usersQuery->orderBy('username')->get();
        
        $filename = 'users_export_' . Carbon::now()->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            
            fputcsv($file, [
                'Username',
                'Nama',
                'Email',
                'Role',
                'Pondok Pesantren',
                'Status',
                'No Telepon',
                'Alamat',
                'Dibuat Pada'
            ]);
            
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->username,
                    $user->name,
                    $user->email,
                    $user->role,
                    $user->ponpes->nama_ponpes ?? 'Tidak Terdaftar',
                    $user->status,
                    $user->no_telp ?? '-',
                    $user->alamat ?? '-',
                    Carbon::parse($user->created_at)->format('d F Y')
                ]);
            }
            
            fclose($file);
        };
        
        $this->logActivity(
            'EXPORT_USERS',
            'Mengekspor data user ke CSV',
            $request,
            ['total_users' => $users->count()]
        );
        
        return response()->stream($callback, 200, $headers);
    }
    
    /**
     * Reset user password
     */
    public function resetPassword(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'new_password' => 'required|string|min:6|confirmed',
        ]);
        
        $user->password = Hash::make($request->new_password);
        $user->save();
        
        $this->logActivity(
            'RESET_PASSWORD',
            'Merest password user: ' . $user->username,
            $request,
            ['user_id' => $user->id]
        );
        
        return response()->json([
            'success' => true,
            'message' => 'Password berhasil direset'
        ]);
    }
    
    /**
     * Activate/Deactivate user
     */
    public function toggleStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $newStatus = $user->status == 'Aktif' ? 'Nonaktif' : 'Aktif';
        $user->status = $newStatus;
        $user->save();
        
        $this->logActivity(
            'TOGGLE_USER_STATUS',
            'Mengubah status user ' . $user->username . ' menjadi ' . $newStatus,
            $request,
            [
                'user_id' => $user->id,
                'old_status' => $user->status == 'Aktif' ? 'Nonaktif' : 'Aktif',
                'new_status' => $newStatus
            ]
        );
        
        return response()->json([
            'success' => true,
            'message' => 'Status user berhasil diubah',
            'new_status' => $newStatus
        ]);
    }
    
    /**
     * Bulk action for users
     */
    public function bulkAction(Request $request)
    {
        $action = $request->get('action');
        $userIds = $request->get('user_ids', []);
        
        if (empty($userIds)) {
            return redirect()->back()->with('error', 'Tidak ada user yang dipilih.');
        }
        
        switch ($action) {
            case 'activate':
                User::whereIn('id', $userIds)->update(['status' => 'Aktif']);
                $message = count($userIds) . ' user berhasil diaktifkan.';
                break;
                
            case 'deactivate':
                User::whereIn('id', $userIds)->update(['status' => 'Nonaktif']);
                $message = count($userIds) . ' user berhasil dinonaktifkan.';
                break;
                
            case 'delete':
                // Check for super admin
                $superAdmins = User::whereIn('id', $userIds)->where('role', 'Super')->count();
                if ($superAdmins > 0 && User::where('role', 'Super')->count() <= $superAdmins) {
                    return redirect()->back()->with('error', 'Tidak dapat menghapus semua Super Admin.');
                }
                
                User::whereIn('id', $userIds)->delete();
                $message = count($userIds) . ' user berhasil dihapus.';
                break;
                
            default:
                return redirect()->back()->with('error', 'Aksi tidak valid.');
        }
        
        $this->logActivity(
            'BULK_USER_ACTION',
            'Melakukan aksi bulk: ' . $action . ' pada ' . count($userIds) . ' user',
            $request,
            ['action' => $action, 'user_ids' => $userIds]
        );
        
        return redirect()->back()->with('success', $message);
    }
    
    /**
     * Helper function to log activity
     */
    private function logActivity($action, $description, $request, $data = null)
    {
        try {
            SystemLog::create([
                'user_id' => auth()->id(),
                'username' => auth()->user()->username,
                'role' => auth()->user()->role,
                'action' => $action,
                'description' => $description,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'data' => $data ? json_encode($data) : null,
                'created_at' => Carbon::now()
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to log activity: ' . $e->getMessage());
        }
    }
}