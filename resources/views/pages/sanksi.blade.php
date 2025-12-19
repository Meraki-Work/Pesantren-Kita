@extends('index')

@section('title', 'Sanksi')

@section('content')
<div class="flex bg-gray-100 min-h-screen">

    <x-sidemenu title="PesantrenKita" />

    <main class="flex-1 p-4 h-full">
        <div class="bg-white rounded-lg shadow-md p-6">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Data Sanksi User</h1>
                <a href="{{ route('sanksi.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Tambah Sanksi
                </a>
            </div>

            <!-- Session Messages -->
            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {!! session('success') !!}
            </div>
            @endif

            @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
            @endif

            <!-- Statistics Cards -->
            @isset($statistics)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="box-bg p-4">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-100 rounded-lg mr-4">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold ">{{ $statistics['total'] }}</p>
                            <p class="text-sm font-medium">Total Sanksi</p>
                        </div>
                    </div>
                </div>

                <div class="box-bg p-4">
                    <div class="flex items-center">
                        <div class="p-2 bg-yellow-100 rounded-lg mr-4">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold">{{ $statistics['aktif'] }}</p>
                            <p class="text-sm font-medium ">Sanksi Aktif</p>
                        </div>
                    </div>
                </div>

                <div class="box-bg p-4">
                    <div class="flex items-center">
                        <div class="p-2 bg-green-100 rounded-lg mr-4">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold">{{ $statistics['selesai'] }}</p>
                            <p class="text-sm font-medium ">Sanksi Selesai</p>
                        </div>
                    </div>
                </div>
            </div>
            @endisset

            <!-- Filter Section -->
            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                <form method="GET" action="{{ route('sanksi.index') }}">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Semua Status</option>
                                <option value="Aktif" {{ request('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Sanksi</label>
                            <select name="jenis" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Semua Jenis</option>
                                <option value="Ringan" {{ request('jenis') == 'Ringan' ? 'selected' : '' }}>Ringan</option>
                                <option value="Sedang" {{ request('jenis') == 'Sedang' ? 'selected' : '' }}>Sedang</option>
                                <option value="Berat" {{ request('jenis') == 'Berat' ? 'selected' : '' }}>Berat</option>
                                <option value="Lainnya" {{ request('jenis') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                            <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                            <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Cari User</label>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Username atau email..." class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                    <div class="flex justify-end mt-4 space-x-2">
                        <a href="{{ route('sanksi.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
                            Reset
                        </a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200">
                            Terapkan Filter
                        </button>
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="w-full table-auto min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50/80 backdrop-blur-sm">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama User</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Sanksi</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hukuman</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($sanksi as $item)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="px-4 py-3 text-sm text-gray-900">{{ ($sanksi->currentPage() - 1) * $sanksi->perPage() + $loop->iteration }}</td>
                            <td class="px-4 py-3">
                                @if($item->user)
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold mr-3">
                                        {{ strtoupper(substr($item->user->username, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $item->user->username }}</div>
                                        <div class="text-xs text-gray-500">{{ $item->user->email ?? 'No email' }}</div>
                                    </div>
                                </div>
                                @else
                                <span class="text-gray-400 text-sm">User tidak ditemukan</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    @if($item->jenis == 'Ringan') bg-green-100 text-green-800
                                    @elseif($item->jenis == 'Sedang') bg-yellow-100 text-yellow-800
                                    @elseif($item->jenis == 'Berat') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ $item->jenis }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900 max-w-xs truncate" title="{{ $item->hukuman }}">
                                {{ $item->hukuman }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900">
                                @if($item->tanggal)
                                {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                                @else
                                <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    @if($item->status == 'Aktif') bg-yellow-100 text-yellow-800
                                    @else bg-green-100 text-green-800
                                    @endif">
                                    {{ $item->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex space-x-2">
                                    <a href="{{ route('sanksi.show', $item->id_sanksi) }}"
                                        class="text-blue-600 hover:text-blue-800 transition duration-150"
                                        title="Detail">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('sanksi.edit', $item->id_sanksi) }}"
                                        class="text-green-600 hover:text-green-800 transition duration-150"
                                        title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <form action="{{ route('sanksi.destroy', $item->id_sanksi) }}" method="POST" class="inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                            class="text-red-600 hover:text-red-800 transition duration-150 delete-btn"
                                            title="Hapus"
                                            data-id="{{ $item->id_sanksi }}"
                                            data-username="{{ $item->user->username ?? 'N/A' }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="text-lg font-medium text-gray-600 mb-2">Tidak ada data sanksi</p>
                                    <p class="text-sm text-gray-500 mb-4">Mulai dengan menambahkan sanksi baru</p>
                                    <a href="{{ route('sanksi.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg inline-flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        Tambah Sanksi
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($sanksi->hasPages())
            <div class="flex flex-col md:flex-row justify-between items-center mt-6 space-y-4 md:space-y-0">
                <div class="text-sm text-gray-600">
                    Menampilkan {{ $sanksi->firstItem() ?? 0 }} - {{ $sanksi->lastItem() ?? 0 }} dari {{ $sanksi->total() }} data
                </div>
                <div class="flex space-x-1">
                    {{ $sanksi->links() }}
                </div>
            </div>
            @endif
        </div>
    </main>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 mt-2">Konfirmasi Hapus</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500" id="deleteMessage">Apakah Anda yakin ingin menghapus data sanksi ini?</p>
            </div>
            <div class="items-center px-4 py-3">
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-24 shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300 mr-2 transition duration-200">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md w-24 shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300 transition duration-200">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Delete Confirmation
    document.addEventListener('DOMContentLoaded', function() {
        const deleteButtons = document.querySelectorAll('.delete-btn');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const username = this.getAttribute('data-username');
                const form = this.closest('.delete-form');

                document.getElementById('deleteMessage').textContent =
                    `Apakah Anda yakin ingin menghapus sanksi untuk user "${username}"? Tindakan ini tidak dapat dibatalkan.`;

                document.getElementById('deleteForm').action = `/sanksi/${id}`;
                document.getElementById('deleteModal').classList.remove('hidden');
            });
        });
    });

    function closeModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('deleteModal');
        if (event.target === modal) {
            closeModal();
        }
    }
</script>
@endsection