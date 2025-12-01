@extends('index')

@section('title', 'Kepegawaian - PesantrenKita')

@section('content')

<div class="flex bg-gray-100">
    <x-sidemenu title="PesantrenKita" class="h-full min-h-screen" />

    <main class="flex-1 p-4 overflow-y-auto">
        <div class="flex items-center space-x-4 mb-4">
            <img src="assets/img/orang.jpg" class="ml-4 w-16 h-16 rounded-full object-cover border-1 border-white" />
            <div>
                <h2 class="text-lg font-semibold text-gray-800">AL-AmalBatam</h2>
            </div>
        </div>

        {{-- Alerts: sukses / error --}}
        @if(session('success'))
            <div class="max-w-3xl mx-4 mb-4">
                <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-lg shadow-sm flex items-center justify-between" role="alert">
                    <div class="flex items-center gap-3">
                        <strong class="font-semibold">Berhasil!</strong>
                        <span class="text-sm">{{ session('success') }}</span>
                    </div>
                    <button onclick="this.parentElement.style.display='none'" class="text-green-700 font-bold">&times;</button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="max-w-3xl mx-4 mb-4">
                <div class="bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded-lg shadow-sm flex items-center justify-between" role="alert">
                    <div class="flex items-center gap-3">
                        <strong class="font-semibold">Gagal!</strong>
                        <span class="text-sm">{{ session('error') }}</span>
                    </div>
                    <button onclick="this.parentElement.style.display='none'" class="text-red-700 font-bold">&times;</button>
                </div>
            </div>
        @endif

        {{-- Cards Statistik (berbeda menurut role) --}}
        @if(auth()->user()->role === 'Admin')
        <div class="grid grid-cols-4 gap-6 mb-10">
            {{-- Card Aktif --}}
            <div class="flex items-center border border-gray-300 rounded-lg px-5 py-4 bg-white shadow-sm">
                <div>
                    <!-- Unified icon: 3 users (green) -->
                    <svg class="h-8 w-8 text-green-700" fill="none" stroke="currentColor">
                        <circle cx="12" cy="12" r="10"></circle>
                    </svg>
                </div>
                <div class="flex items-center ml-4 space-x-3">
                    <div class="w-1 h-10 bg-green-600 rounded"></div>
                    <div>
                        <p class="text-xs text-gray-700 font-semibold">Pegawai Aktif</p>
                        <p class="text-lg font-bold text-gray-900">{{ $aktif }}</p>
                    </div>
                </div>
            </div>

            {{-- Card NonAktif --}}
            <div class="flex items-center border border-gray-300 rounded-lg px-5 py-4 bg-white shadow-sm">
                <div>
                    <!-- Unified icon: 3 users (green) -->
                    <svg class="h-8 w-8 text-green-700" fill="none" stroke="currentColor">
                        <circle cx="12" cy="12" r="10"></circle>
                    </svg>
                </div>
                <div class="flex items-center ml-4 space-x-3">
                    <div class="w-1 h-10 bg-green-600 rounded"></div>
                    <div>
                        <p class="text-xs text-gray-700 font-semibold">Pegawai Non-Aktif</p>
                        <p class="text-lg font-bold text-gray-900">{{ $Tidakaktif }}</p>
                    </div>
                </div>
            </div>

            {{-- Card Total --}}
            <div class="flex items-center border border-gray-300 rounded-lg px-5 py-4 bg-white shadow-sm">
                <div>
                    <!-- Unified icon: 3 users (green) -->
                    <svg class="h-8 w-8 text-green-700" fill="none" stroke="currentColor">
                        <circle cx="12" cy="12" r="10"></circle>
                    </svg>
                </div>
                <div class="flex items-center ml-4 space-x-3">
                    <div class="w-1 h-10 bg-green-600 rounded"></div>
                    <div>
                        <p class="text-xs text-gray-700 font-semibold">Total Pegawai</p>
                        <p class="text-lg font-bold text-gray-900">{{ $total }}</p>
                    </div>
                </div>
            </div>

        </div>
        @elseif(auth()->user()->role === 'Pengajar')
        <div class="grid grid-cols-3 gap-6 mb-10">

            {{-- Card: Jumlah Pengajar --}}
            <div class="flex items-center border border-gray-300 rounded-lg px-5 py-4 bg-white shadow-sm">
                <div>
                    <!-- Unified icon: 3 users (green) for Pengajar -->
                    <svg class="h-8 w-8 text-green-700" fill="none" stroke="currentColor">
                        <circle cx="12" cy="12" r="10"></circle>
                    </svg>
                </div>
                <div class="flex items-center ml-4 space-x-3">
                    <div class="w-1 h-10 bg-green-600 rounded"></div>
                    <div>
                        <p class="text-xs text-gray-700 font-semibold">Pengajar</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $countPengajar }}</p>
                    </div>
                </div>
            </div>

            {{-- Card: Jumlah Admin --}}
            <div class="flex items-center border border-gray-300 rounded-lg px-5 py-4 bg-white shadow-sm">
                <div>
                    <svg class="h-8 w-8 text-green-700" fill="none" stroke="currentColor">
                        <circle cx="12" cy="12" r="10"></circle>
                    </svg>
                </div>
                <div class="flex items-center ml-4 space-x-3">
                    <div class="w-1 h-10 bg-green-600 rounded"></div>
                    <div>
                        <p class="text-xs text-gray-700 font-semibold">Admin</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $countAdmin }}</p>
                    </div>
                </div>
            </div>

            {{-- Card: Jumlah Keuangan --}}
            <div class="flex items-center border border-gray-300 rounded-lg px-5 py-4 bg-white shadow-sm">
                <div>
                    <svg class="h-8 w-8 text-green-700" fill="none" stroke="currentColor">
                        <circle cx="12" cy="12" r="10"></circle>
                    </svg>
                </div>
                <div class="flex items-center ml-4 space-x-3">
                    <div class="w-1 h-10 bg-green-600 rounded"></div>
                    <div>
                        <p class="text-xs text-gray-700 font-semibold">Keuangan</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $countKeuangan }}</p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Search --}}
        <div class="mb-6">
            <form method="GET" action="{{ route('kepegawaian.index') }}" class="flex items-center bg-white border border-gray-300 rounded-lg px-3 py-2 w-full max-w-xl shadow-sm">
                <svg class="h-5 w-5 text-gray-400 mr-3" fill="none" stroke="currentColor">
                    <circle cx="11" cy="11" r="7"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>

                <input type="search" name="q" value="{{ $q ?? '' }}"
                    placeholder="Cari pegawai (nama, email, role, status)..."
                    class="flex-grow outline-none text-sm text-gray-700 placeholder-gray-400"
                />

                <div class="ml-3 flex items-center gap-2">
                    @if(!empty($q))
                        <span class="text-xs text-gray-500">Menampilkan {{ $filteredCount }} hasil untuk "{{ $q }}"</span>
                        <a href="{{ route('kepegawaian.index') }}" class="text-xs text-gray-500 hover:underline">Bersihkan</a>
                    @endif
                    <button type="submit" class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 text-sm">Cari</button>
                </div>
            </form>
        </div>

        {{-- Table --}}
        <div class="bg-white border rounded-xl overflow-hidden shadow-sm">
            <table class="w-full text-sm">
                <thead class="bg-[#1B4332] text-white">
                    <tr>
                        <th class="px-3 py-3 text-left">Nama</th>
                        <th class="px-6 py-3 text-left">Role</th>
                        <th class="px-6 py-3 text-left">Email</th>
                        <th class="px-6 py-3 text-left">Status</th>

                        {{-- Kolom aksi tetap ada tapi kosong untuk pengajar --}}
                        @if(auth()->user()->role === 'Admin')
                        <th class="px-6 py-3 text-center w-20">Aksi</th>
                        @endif
                    </tr>
                </thead>

                <tbody>
                    @foreach ($users as $user)
                        <tr class="border-b">
                            <td class="px-3 py-3">{{ $user->username }}</td>
                            <td class="px-6 py-3">{{ $user->role }}</td>
                            <td class="px-6 py-3">{{ $user->email }}</td>

                            <td class="px-6 py-3">
                                <span class="px-3 py-1 rounded {{ strtolower($user->status) == 'aktif' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $user->status }}
                                </span>
                            </td>

                            {{-- Jika Admin → tampilkan dropdown --}}
                            @if(auth()->user()->role === 'Admin')
                            <td class="px-6 py-4 text-center">
                                <div 
                                    class="relative" 
                                    x-data="{ open:false, dropUp:false }"
                                    x-init="
                                        let rect = $el.getBoundingClientRect();
                                        if (window.innerHeight - rect.bottom < 150) dropUp = true;
                                    "
                                >

                                    <button
                                        @click="open = !open"
                                        class="p-2 rounded-full bg-gray-200 hover:bg-gray-300"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-5 w-5"
                                            fill="currentColor">
                                            <path d="M10 3a1.5 1.5 0 110 3 1.5 1.5 0 010-3zm0 5a1.5 1.5 0 110 3 1.5 1.5 0 010-3zm0 5a1.5 1.5 0 110 3 1.5 1.5 0 010-3z"/>
                                        </svg>
                                    </button>

                                    <div
                                        x-show="open"
                                        @click.away="open = false"
                                        x-transition
                                        :class="dropUp ? 'absolute right-0 bottom-full mb-2' : 'absolute right-0 mt-2'"
                                        class="bg-white shadow-lg border rounded-md w-32 py-2 z-50"
                                    >
                                        <button
                                            @click.stop="$dispatch('open-edit-{{ $user->id_user }}')"
                                            class="block w-full text-center px-4 py-2 text-sm hover:bg-gray-100"
                                        >
                                            Edit
                                        </button>

                                        <button
                                            @click.stop="$dispatch('open-delete-{{ $user->id_user }}')"
                                            class="block w-full text-center px-4 py-2 text-sm hover:bg-gray-100"
                                        >
                                            Hapus
                                        </button>
                                    </div>

                                </div>

                            </td>

                            @endif
                        </tr>

                        {{-- Include Modal (only for Admin) --}}
                        @if(auth()->user()->role === 'Admin')
                            @include('pages.modal.edit_kepegawaian', ['user' => $user])
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>
</div>

@endsection


{{-- AUTO CLOSE MODAL --}}
@if(session('close_edit'))
<script> window.dispatchEvent(new Event('close-edit')); </script>
@endif

@if(session('close_delete'))
<script> window.dispatchEvent(new Event('close-delete')); </script>
@endif
