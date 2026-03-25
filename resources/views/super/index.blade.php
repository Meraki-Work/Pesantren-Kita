@extends('index')

@section('title', 'Dashboard')

@section('content')
<div class="flex bg-gray-100">
    <x-sidemenu title="PesantrenKita" class="h-full min-h-screen" />

<main>
    <div class="px-4 py-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-users mr-2 text-blue-600"></i>Manajemen User
                </h1>
                <p class="text-gray-500 mt-1">Kelola semua user dari berbagai pondok pesantren</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('super.users.group-by-ponpes') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition flex items-center">
                    <i class="fas fa-building mr-2"></i> Group by Ponpes
                </a>
                <a href="{{ route('super.users.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition flex items-center">
                    <i class="fas fa-plus mr-2"></i> Tambah User
                </a>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow-md border-l-4 border-blue-500 p-4">
                <div class="text-xs font-semibold text-blue-600 uppercase">Total User</div>
                <div class="text-2xl font-bold text-gray-800">{{ $statistics['total_users'] ?? 0 }}</div>
                <i class="fas fa-users text-gray-300 text-2xl float-right mt-2"></i>
            </div>

            <div class="bg-white rounded-lg shadow-md border-l-4 border-green-500 p-4">
                <div class="text-xs font-semibold text-green-600 uppercase">Aktif</div>
                <div class="text-2xl font-bold text-gray-800">{{ $statistics['active_users'] ?? 0 }}</div>
                <i class="fas fa-check-circle text-gray-300 text-2xl float-right mt-2"></i>
            </div>

            <div class="bg-white rounded-lg shadow-md border-l-4 border-red-500 p-4">
                <div class="text-xs font-semibold text-red-600 uppercase">Nonaktif</div>
                <div class="text-2xl font-bold text-gray-800">{{ $statistics['inactive_users'] ?? 0 }}</div>
                <i class="fas fa-ban text-gray-300 text-2xl float-right mt-2"></i>
            </div>

            <div class="bg-white rounded-lg shadow-md border-l-4 border-indigo-500 p-4">
                <div class="text-xs font-semibold text-indigo-600 uppercase">Admin</div>
                <div class="text-2xl font-bold text-gray-800">{{ $statistics['by_role']['Admin'] ?? 0 }}</div>
                <i class="fas fa-user-shield text-gray-300 text-2xl float-right mt-2"></i>
            </div>

            <div class="bg-white rounded-lg shadow-md border-l-4 border-yellow-500 p-4">
                <div class="text-xs font-semibold text-yellow-600 uppercase">Pengajar</div>
                <div class="text-2xl font-bold text-gray-800">{{ $statistics['by_role']['Pengajar'] ?? 0 }}</div>
                <i class="fas fa-chalkboard-teacher text-gray-300 text-2xl float-right mt-2"></i>
            </div>

            <div class="bg-white rounded-lg shadow-md border-l-4 border-gray-700 p-4">
                <div class="text-xs font-semibold text-gray-700 uppercase">Total Ponpes</div>
                <div class="text-2xl font-bold text-gray-800">{{ $statistics['total_ponpes'] ?? 0 }}</div>
                <i class="fas fa-building text-gray-300 text-2xl float-right mt-2"></i>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="bg-white rounded-lg shadow-md mb-6">
            <div class="border-b border-gray-200 px-6 py-4">
                <h6 class="font-bold text-blue-600">
                    <i class="fas fa-filter mr-2"></i> Filter User
                </h6>
            </div>
            <div class="p-6">
                <form method="GET" action="{{ route('super.users.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pondok Pesantren</label>
                        <select name="ponpes_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Semua Ponpes</option>
                            @if(isset($ponpesList) && $ponpesList->count() > 0)
                            @foreach($ponpesList as $ponpes)
                            <option value="{{ $ponpes->id_ponpes }}" {{ request('ponpes_id') == $ponpes->id_ponpes ? 'selected' : '' }}>
                                {{ $ponpes->nama_ponpes }}
                            </option>
                            @endforeach
                            @else
                            <option value="" disabled>Tidak ada data pondok pesantren</option>
                            @endif
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                        <select name="role" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Semua Role</option>
                            <option value="Admin" {{ request('role') == 'Admin' ? 'selected' : '' }}>Admin</option>
                            <option value="Super" {{ request('role') == 'Super' ? 'selected' : '' }}>Super Admin</option>
                            <option value="Pengajar" {{ request('role') == 'Pengajar' ? 'selected' : '' }}>Pengajar</option>
                            <option value="Keuangan" {{ request('role') == 'Keuangan' ? 'selected' : '' }}>Keuangan</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Semua Status</option>
                            <option value="Aktif" {{ request('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="Nonaktif" {{ request('status') == 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cari</label>
                        <input type="text" name="search" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Username, Nama, Email..." value="{{ request('search') }}">
                    </div>

                    <div class="flex space-x-2 items-end">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition flex-1">
                            <i class="fas fa-search mr-2"></i> Filter
                        </button>
                        <a href="{{ route('super.users.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                            <i class="fas fa-redo"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Users Table -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="border-b border-gray-200 px-6 py-4 flex justify-between items-center">
                <h6 class="font-bold text-blue-600">
                    <i class="fas fa-table mr-2"></i> Daftar User
                </h6>
                <div class="flex space-x-2">
                    <button type="button" onclick="exportUsers()" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded-lg text-sm transition">
                        <i class="fas fa-file-excel mr-1"></i> Export
                    </button>
                    <button type="button" id="bulkDeleteBtn" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-lg text-sm transition hidden">
                        <i class="fas fa-trash mr-1"></i> Hapus Terpilih
                    </button>
                </div>
            </div>
            <div class="p-6 overflow-x-auto">
                <form id="bulkActionForm" method="POST" action="{{ route('super.bulk-action') }}">
                    @csrf
                    <input type="hidden" name="action" id="bulkAction" value="">

                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-4 py-3 text-left w-10">
                                    <input type="checkbox" id="selectAll" class="rounded border-gray-300">
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">#</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Foto</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Username</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Nama</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Email</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Role</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Pondok Pesantren</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Dibuat</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($users as $index => $user)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3 text-center">
                                    <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" class="userCheckbox rounded border-gray-300">
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700">
                                    {{ ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}
                                </td>
                                <td class="px-4 py-3">
                                    @if($user->foto)
                                    <img src="{{ Storage::url($user->foto) }}" class="rounded-full w-10 h-10 object-cover">
                                    @else
                                    <div class="rounded-full bg-gray-400 text-white w-10 h-10 flex items-center justify-center">
                                        {{ strtoupper(substr($user->username, 0, 1)) }}
                                    </div>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <strong>{{ $user->username }}</strong>
                                    @if($user->role == 'Super')
                                    <span class="ml-1 bg-red-500 text-white text-xs px-2 py-0.5 rounded">Super</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm">{{ $user->name }}</td>
                                <td class="px-4 py-3 text-sm">{{ $user->email }}</td>
                                <td class="px-4 py-3">
                                    @php
                                    $roleColors = [
                                    'Admin' => 'blue',
                                    'Super' => 'red',
                                    'Pengajar' => 'green',
                                    'Keuangan' => 'yellow'
                                    ];
                                    $color = $roleColors[$user->role] ?? 'gray';
                                    @endphp
                                    <span class="bg-{{ $color }}-100 text-{{ $color }}-800 px-2 py-1 rounded text-xs font-medium">
                                        <i class="fas fa-user-tag mr-1"></i> {{ $user->role }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    @if($user->ponpes)
                                    <span class="bg-indigo-100 text-indigo-800 px-2 py-1 rounded text-xs font-medium">
                                        <i class="fas fa-building mr-1"></i> {{ $user->ponpes->nama_ponpes }}
                                    </span>
                                    @else
                                    <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs">Tidak Terdaftar</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if($user->status == 'Aktif')
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-medium">
                                        <i class="fas fa-check-circle mr-1"></i> Aktif
                                    </span>
                                    @else
                                    <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs font-medium">
                                        <i class="fas fa-times-circle mr-1"></i> Nonaktif
                                    </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm">{{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y') }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex space-x-1">
                                        <a href="{{ route('super.users.show', $user) }}" class="bg-blue-500 hover:bg-blue-600 text-white p-1.5 rounded" title="Detail">
                                            <i class="fas fa-eye text-sm"></i>
                                        </a>
                                        <a href="{{ route('super.users.edit', $user) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white p-1.5 rounded" title="Edit">
                                            <i class="fas fa-edit text-sm"></i>
                                        </a>
                                        <button type="button" onclick="confirmDelete({{ $user->id }}, '{{ addslashes($user->username) }}')" class="bg-red-500 hover:bg-red-600 text-white p-1.5 rounded" title="Hapus">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                        <button type="button" onclick="resetPassword({{ $user->id }}, '{{ addslashes($user->username) }}')" class="bg-gray-500 hover:bg-gray-600 text-white p-1.5 rounded" title="Reset Password">
                                            <i class="fas fa-key text-sm"></i>
                                        </button>
                                        <button type="button" onclick="toggleStatus({{ $user->id }}, '{{ addslashes($user->username) }}', '{{ $user->status }}')"
                                            class="{{ $user->status == 'Aktif' ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} text-white p-1.5 rounded"
                                            title="{{ $user->status == 'Aktif' ? 'Nonaktifkan' : 'Aktifkan' }}">
                                            <i class="fas {{ $user->status == 'Aktif' ? 'fa-ban' : 'fa-check-circle' }} text-sm"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="11" class="px-4 py-12 text-center">
                                    <i class="fas fa-users text-5xl text-gray-300 mb-3"></i>
                                    <p class="text-gray-500">Belum ada data user</p>
                                    <a href="{{ route('super.users.create') }}" class="inline-block mt-3 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                                        <i class="fas fa-plus mr-1"></i> Tambah User
                                    </a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div class="flex justify-between items-center mt-4 pt-4 border-t border-gray-200">
                        <div class="text-sm text-gray-500">
                            @if($users->count() > 0)
                            Menampilkan {{ $users->firstItem() }} - {{ $users->lastItem() }} dari {{ $users->total() }} data
                            @else
                            Tidak ada data
                            @endif
                        </div>
                        <div>
                            {{ $users->appends(request()->query())->links() }}
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Reset Password -->
    <div id="resetPasswordModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg w-full max-w-md">
            <div class="flex justify-between items-center p-6 border-b border-gray-200">
                <h5 class="text-xl font-bold text-gray-800">Reset Password</h5>
                <button type="button" onclick="closeResetModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="resetPasswordForm">
                @csrf
                <div class="p-6 space-y-4">
                    <input type="hidden" name="user_id" id="resetUserId">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">User</label>
                        <input type="text" id="resetUsername" class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50" readonly>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                        <input type="password" name="new_password" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                        <input type="password" name="new_password_confirmation" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                </div>
                <div class="flex justify-end space-x-3 p-6 border-t border-gray-200">
                    <button type="button" onclick="closeResetModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                        Batal
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                        Reset Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        // Select All Checkbox
        document.getElementById('selectAll')?.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.userCheckbox');
            checkboxes.forEach(checkbox => checkbox.checked = this.checked);
            updateBulkDeleteButton();
        });

        document.querySelectorAll('.userCheckbox').forEach(checkbox => {
            checkbox.addEventListener('change', updateBulkDeleteButton);
        });

        function updateBulkDeleteButton() {
            const checkedCount = document.querySelectorAll('.userCheckbox:checked').length;
            const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
            if (bulkDeleteBtn) {
                bulkDeleteBtn.style.display = checkedCount > 0 ? 'inline-block' : 'none';
            }
        }

        function exportUsers() {
            const url = new URL(window.location.href);
            url.pathname = "{{ route('super.users.export') }}";
            window.location.href = url.toString();
        }

        function confirmDelete(userId, username) {
            if (confirm(`Apakah Anda yakin ingin menghapus user "${username}"?\n\nData yang dihapus tidak dapat dikembalikan!`)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `{{ url('admin/users') }}/${userId}`;
                form.innerHTML = `
                @csrf
                @method('DELETE')
            `;
                document.body.appendChild(form);
                form.submit();
            }
        }

        let currentUserId = null;

        function resetPassword(userId, username) {
            currentUserId = userId;
            document.getElementById('resetUserId').value = userId;
            document.getElementById('resetUsername').value = username;
            document.getElementById('resetPasswordModal').classList.remove('hidden');
        }

        function closeResetModal() {
            document.getElementById('resetPasswordModal').classList.add('hidden');
            document.getElementById('resetPasswordForm').reset();
        }

        document.getElementById('resetPasswordForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const userId = document.getElementById('resetUserId').value;
            const formData = new FormData(this);

            fetch(`{{ url('admin/users') }}/${userId}/reset-password`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        new_password: formData.get('new_password'),
                        new_password_confirmation: formData.get('new_password_confirmation')
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Password berhasil direset!');
                        closeResetModal();
                    } else {
                        alert(data.message || 'Gagal reset password');
                    }
                })
                .catch(error => {
                    alert('Terjadi kesalahan');
                });
        });

        function toggleStatus(userId, username, currentStatus) {
            const newStatus = currentStatus === 'Aktif' ? 'Nonaktif' : 'Aktif';
            const confirmMsg = `Apakah Anda yakin ingin ${newStatus === 'Aktif' ? 'mengaktifkan' : 'menonaktifkan'} user "${username}"?`;

            if (confirm(confirmMsg)) {
                fetch(`{{ url('admin/users') }}/${userId}/toggle-status`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert(data.message || 'Gagal mengubah status');
                        }
                    })
                    .catch(error => {
                        alert('Terjadi kesalahan');
                    });
            }
        }

        document.getElementById('bulkDeleteBtn')?.addEventListener('click', function() {
            const checked = document.querySelectorAll('.userCheckbox:checked');
            if (checked.length === 0) return;

            if (confirm(`Apakah Anda yakin ingin menghapus ${checked.length} user yang dipilih?`)) {
                document.getElementById('bulkAction').value = 'delete';
                document.getElementById('bulkActionForm').submit();
            }
        });
    </script>
    @endpush
</main>
</div>
@endsection