@extends('index')

@section('title', 'Kepegawaian - PesantrenKita')

@section('content')

<div class="flex bg-gray-100 min-h-screen">
    <x-sidemenu title="PesantrenKita" />

    <main class="flex-1 p-6 overflow-y-auto">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Kepegawaian</h1>
            <p class="text-gray-600 mt-1">Kelola data pegawai dan pengajar</p>
        </div>

        {{-- Alerts: sukses / error --}}
        @if(session('success'))
        <div class="mb-6">
            <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg shadow-sm flex items-center justify-between" role="alert">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <div>
                        <strong class="font-semibold text-green-800">Berhasil!</strong>
                        <p class="text-green-700 text-sm mt-1">{{ session('success') }}</p>
                    </div>
                </div>
                <button onclick="this.parentElement.style.display='none'" class="text-green-600 hover:text-green-800 font-bold px-2">&times;</button>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="mb-6">
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm flex items-center justify-between" role="alert">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    <div>
                        <strong class="font-semibold text-red-800">Gagal!</strong>
                        <p class="text-red-700 text-sm mt-1">{{ session('error') }}</p>
                    </div>
                </div>
                <button onclick="this.parentElement.style.display='none'" class="text-red-600 hover:text-red-800 font-bold px-2">&times;</button>
            </div>
        </div>
        @endif

        {{-- Cards Statistik --}}
        @if(auth()->user()->role === 'Admin')
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            {{-- Card Total --}}
            <div class="box-bg p-6 rounded-xl">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-xl mr-4">
                        <div class="w-8 h-8 flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $total }}</p>
                        <p class="text-sm font-medium text-gray-600">Total Pegawai</p>
                    </div>
                </div>
            </div>

            {{-- Card Aktif --}}
            <div class="box-bg p-6 rounded-xl">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-xl mr-4">
                        <div class="w-8 h-8 flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $aktif }}</p>
                        <p class="text-sm font-medium text-gray-600">Pegawai Aktif</p>
                    </div>
                </div>
            </div>

            {{-- Card NonAktif --}}
            <div class="box-bg p-6 rounded-xl">
                <div class="flex items-center">
                    <div class="p-3 bg-red-100 rounded-xl mr-4">
                        <div class="w-8 h-8 flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $Tidakaktif }}</p>
                        <p class="text-sm font-medium text-gray-600">Pegawai Non-Aktif</p>
                    </div>
                </div>
            </div>
        </div>

        @elseif(auth()->user()->role === 'Pengajar')
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            {{-- Card: Jumlah Pengajar --}}
            <div class="box-bg p-6 rounded-xl">
                <div class="flex items-center">
                    <div class="p-3 bg-indigo-100 rounded-xl mr-4">
                        <div class="w-8 h-8 flex items-center justify-center">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $countPengajar }}</p>
                        <p class="text-sm font-medium text-gray-600">Pengajar</p>
                    </div>
                </div>
            </div>

            {{-- Card: Jumlah Admin --}}
            <div class="box-bg p-6 rounded-xl">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-xl mr-4">
                        <div class="w-8 h-8 flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $countAdmin }}</p>
                        <p class="text-sm font-medium text-gray-600">Admin</p>
                    </div>
                </div>
            </div>

            {{-- Card: Jumlah Keuangan --}}
            <div class="box-bg p-6 rounded-xl">
                <div class="flex items-center">
                    <div class="p-3 bg-teal-100 rounded-xl mr-4">
                        <div class="w-8 h-8 flex items-center justify-center">
                            <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $countKeuangan }}</p>
                        <p class="text-sm font-medium text-gray-600">Keuangan</p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="mt-6 mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <!-- Search Bar -->
            <div class="w-full sm:w-auto">
                <div class="relative max-w-md">
                    <form method="GET" action="{{ route('kepegawaian.index') }}" id="searchForm">
                        <input type="search"
                            name="q"
                            value="{{ $q ?? '' }}"
                            placeholder="Cari pegawai (nama, email, role, status)..."
                            class="w-full px-4 py-3 pl-12 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 text-sm">
                        <svg class="absolute left-4 top-3.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <!-- Clear Button (visible when there's search query) -->
                        @if(!empty($q))
                        <button type="button"
                            onclick="window.location.href='{{ route('kepegawaian.index') }}'"
                            class="absolute right-3 top-3.5 text-gray-400 hover:text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                        @endif
                        <!-- Submit button (hidden but form submits on Enter) -->
                        <button type="submit" class="hidden">Cari</button>
                    </form>

                    <!-- Loading Indicator -->
                    <div id="searchLoading" class="absolute right-3 top-3.5 hidden">
                        <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-600"></div>
                    </div>
                </div>

                <!-- Search Results Info -->
                @if(!empty($q))
                <p class="text-sm text-gray-500 mt-2">
                    Menampilkan <span class="font-medium">{{ $filteredCount ?? $users->total() }}</span> hasil untuk
                    "<span class="font-medium text-blue-600">{{ $q }}</span>"
                    <a href="{{ route('kepegawaian.index') }}" class="text-blue-600 hover:text-blue-800 ml-2 hover:underline">
                        Bersihkan pencarian
                    </a>
                </p>
                @endif
            </div>

        </div>
        {{-- Table Section --}}
        <div class="bg-white rounded-xl overflow-hidden shadow-md">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                            @if(auth()->user()->role === 'Admin')
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                            @endif
                        </tr>

                        {{-- modals will be included per-row below (so $user is defined) --}}
                    </thead>

                    <tbody class="divide-y divide-gray-200">
                        @forelse ($users as $user)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-blue-600 font-medium text-sm">
                                            {{ strtoupper(substr($user->username, 0, 1)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $user->username }}</p>
                                        @if($user->nama_lengkap)
                                        <p class="text-xs text-gray-500">{{ $user->nama_lengkap }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1.5 text-xs font-medium rounded-full 
                                        {{ $user->role === 'Admin' ? 'bg-purple-100 text-purple-700' : 
                                          ($user->role === 'Pengajar' ? 'bg-indigo-100 text-indigo-700' : 
                                          'bg-teal-100 text-teal-700') }}">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ $user->email }}</td>
                            <td class="px-6 py-4">
                                @php
                                    $statusLabel = $user->status === 'active' ? 'Aktif' : ($user->status === 'pending' ? 'Pending' : 'Tidak aktif');
                                    $statusClass = $user->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700';
                                @endphp
                                <span class="px-3 py-1.5 text-xs font-medium rounded-full {{ $statusClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            @if(auth()->user()->role === 'Admin')
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <button
                                        onclick="window.dispatchEvent(new CustomEvent('open-edit-{{ $user->id_user }}'))"
                                        class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors duration-150"
                                        title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    <button
                                        onclick="window.dispatchEvent(new CustomEvent('open-delete-{{ $user->id_user }}'))"
                                        class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors duration-150"
                                        title="Hapus">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                            @endif
                        </tr>
                        {{-- Include modal for this user so Alpine events target an existing component --}}
                        @include('pages.modal.edit_kepegawaian', ['user' => $user])
                        @empty
                        <tr>
                            <td colspan="{{ auth()->user()->role === 'Admin' ? 5 : 4 }}" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                    </svg>
                                    <p class="text-lg font-medium text-gray-400 mb-2">Tidak ada data pegawai</p>
                                    @if(!empty($q))
                                    <p class="text-sm text-gray-500">Coba gunakan kata kunci pencarian yang berbeda</p>
                                    @else
                                    <p class="text-sm text-gray-500">Mulai tambahkan pegawai baru</p>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

@endsection

{{-- AUTO CLOSE MODAL --}}
@if(session('close_edit'))
<script>
    window.dispatchEvent(new Event('close-edit'));
</script>
@endif

@if(session('close_delete'))

<!-- JavaScript for Search Loading -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.getElementById('searchForm');
    const searchLoading = document.getElementById('searchLoading');
    
    if (searchForm) {
        searchForm.addEventListener('submit', function() {
            searchLoading.classList.remove('hidden');
        });
        
        // Show loading when typing (optional debounce)
        const searchInput = searchForm.querySelector('input[name="q"]');
        let typingTimer;
        
        searchInput.addEventListener('input', function() {
            clearTimeout(typingTimer);
            if (this.value.trim() !== '') {
                searchLoading.classList.remove('hidden');
                typingTimer = setTimeout(() => {
                    searchLoading.classList.add('hidden');
                }, 1000);
            }
        });
    }
});
</script>

<script>
    window.dispatchEvent(new Event('close-delete'));
</script>
@endif