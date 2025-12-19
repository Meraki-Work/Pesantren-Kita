@extends('index')

@section('title', 'Santri - Biodata')

@section('content')
<div class="flex bg-gray-100 min-h-screen">
    <x-sidemenu title="PesantrenKita" />
    
    <main class="flex-1 p-6 h-full">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Data Santri</h1>
                <p class="text-sm text-gray-600 mt-1">Manajemen biodata santri</p>
            </div>
            @include('pages.modal.create_santri')
        </div>

        @if(session('error'))
        <div class="mb-4">
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Gagal Menambah Data Santri</h3>
                        <div class="mt-1 text-sm text-red-700">{!! session('error') !!}</div>
                        <div class="mt-2 text-sm text-red-600">
                            <ul class="list-disc list-inside space-y-1">
                                <li>Pastikan NISN dan NIK belum digunakan oleh santri lain</li>
                                <li>Periksa kembali data yang dimasukkan</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if(session('success'))
        <div class="mb-4">
            <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-green-800">Berhasil!</h3>
                        <div class="mt-1 text-sm text-green-700">{!! session('success') !!}</div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Konten Utama -->
            <div class="lg:col-span-9 space-y-6">
                <!-- Filter dan Aksi -->
                <div class="box-bg bg-white rounded-xl p-4">
                    <div class="flex flex-col md:flex-row gap-4">
                        <!-- Dropdown Filter Kelas -->
                        <form action="{{ route('santri.index') }}" method="GET" id="kelasForm" class="flex-1">
                            <div class="relative group w-full">
                                <select name="kelas" id="kelasFilter" onchange="document.getElementById('kelasForm').submit()"
                                    class="appearance-none bg-white/90 backdrop-blur-sm border-2 border-emerald-200 rounded-xl h-14 px-4 pr-10 cursor-pointer hover:border-emerald-300 transition-all duration-300 text-base font-medium text-gray-700 shadow hover:shadow-md focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400 w-full">
                                    <option value="">Semua Kelas</option>
                                    @foreach($kelas as $k)
                                    <option value="{{ $k->id_kelas }}" {{ $selectedKelas == $k->id_kelas ? 'selected' : '' }}>
                                        {{ $k->nama_kelas }}
                                    </option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute top-1/2 right-3 transform -translate-y-1/2 text-emerald-600">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                        </form>

                        <!-- Tombol Tambah Kelas -->
                        <div class="flex-1">
                            <a href="{{ route('kelas.index') }}" 
                               class="w-full block text-center bg-gradient-to-r from-blue-500 to-blue-600 text-white px-4 py-3 rounded-xl hover:from-blue-600 hover:to-blue-700 transition duration-200 font-medium h-14 flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Tambah Kelas
                            </a>
                        </div>

                        <!-- Tombol Navigasi ke Kompetensi -->
                        <div class="flex-1">
                            <a href="{{ route('kompetensi.index') }}" 
                               class="w-full block text-center bg-gradient-to-r from-[#344E41] to-[#2a3d34] text-white px-4 py-3 rounded-xl hover:from-[#2a3d34] hover:to-[#213028] transition duration-200 font-medium h-14 flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                </svg>
                                Lihat Kompetensi
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Tabel Santri -->
                <div class="box-bg bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800">Biodata Santri</h3>
                                <p class="text-sm text-gray-600 mt-1">Total {{ count($rowsbio) }} santri</p>
                            </div>
                        </div>
                        
                        <!-- Search Input -->
                        <div class="mb-4 relative">
                            <input type="text" id="searchInputSantri" placeholder="Cari santri berdasarkan nama, NISN, atau NIK..." 
                                   class="w-full px-4 py-2 pl-10 pr-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                            <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>

                        <div class="overflow-x-auto">
                            <x-dynamic-table :columns="$columnsbio" :rows="$rowsbio" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Info -->
            <div class="lg:col-span-3 space-y-6">
                <!-- Info Kelas -->
                <div class="box-bg bg-white rounded-xl p-6">
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Info Kelas</h3>
                        <div class="flex items-center space-x-3 pb-3 border-b border-gray-200">
                            <svg xmlns="http://www.w3.org/2000/svg" height="40px" viewBox="0 -960 960 960" width="40px" fill="#4b5563">
                                <path d="M40-160v-160q0-34 23.5-57t56.5-23h131q20 0 38 10t29 27q29 39 71.5 61t90.5 22q49 0 91.5-22t70.5-61q13-17 30.5-27t36.5-10h131q34 0 57 23t23 57v160H640v-91q-35 25-75.5 38T480-200q-43 0-84-13.5T320-252v92H40Zm440-160q-38 0-72-17.5T351-386q-17-25-42.5-39.5T253-440q22-37 93-58.5T480-520q63 0 134 21.5t93 58.5q-29 0-55 14.5T609-386q-22 32-56 49t-73 17ZM160-440q-50 0-85-35t-35-85q0-51 35-85.5t85-34.5q51 0 85.5 34.5T280-560q0 50-34.5 85T160-440Zm640 0q-50 0-85-35t-35-85q0-51 35-85.5t85-34.5q51 0 85.5 34.5T920-560q0 50-34.5 85T800-440ZM480-560q-50 0-85-35t-35-85q0-51 35-85.5t85-34.5q51 0 85.5 34.5T600-680q0 50-34.5 85T480-560Z" />
                            </svg>
                            <div>
                                <h4 class="font-medium text-gray-800">
                                    {{ $selectedKelas ? $kelas->firstWhere('id_kelas', $selectedKelas)->nama_kelas : 'Semua Kelas' }}
                                </h4>
                                <p class="text-sm text-gray-600">
                                    {{ count($rowsbio) }} Santri
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h4 class="text-md font-semibold text-gray-800 mb-3">Daftar Kelas</h4>
                        <div class="space-y-2">
                            @forelse($kelas as $k)
                            <div class="flex items-center justify-between px-3 py-2 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                <span class="text-sm font-medium text-gray-700">{{ $k->nama_kelas }}</span>
                                @php
                                    $count = $santri->where('id_kelas', $k->id_kelas)->count();
                                @endphp
                                <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">{{ $count }} santri</span>
                            </div>
                            @empty
                            <div class="text-center py-4 text-gray-500">
                                <p class="text-sm">Belum ada kelas</p>
                                <a href="{{ route('kelas.index') }}" class="text-blue-600 hover:text-blue-800 text-xs">Buat kelas baru</a>
                            </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Statistik -->
                    <div class="pt-4 border-t border-gray-200">
                        <h4 class="text-md font-semibold text-gray-800 mb-3">Statistik</h4>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Total Santri</span>
                                <span class="font-semibold text-blue-600">{{ count($rowsbio) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Jumlah Kelas</span>
                                <span class="font-semibold text-green-600">{{ $kelas->count() }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Kompetensi</span>
                                <span class="font-semibold text-purple-600">{{ $kompetensi->count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kompetensi Tersedia -->
                <div class="box-bg bg-white rounded-xl p-6">
                    <h4 class="text-md font-semibold text-gray-800 mb-3">Kompetensi Tersedia</h4>
                    <div class="space-y-2">
                        @forelse($kompetensi as $k)
                        <div class="px-3 py-2 bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg border border-green-100">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-sm font-medium text-gray-700">{{ $k }}</span>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-4 text-gray-500">
                            <svg class="w-8 h-8 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                            <p class="text-sm">Belum ada kompetensi</p>
                            <a href="{{ route('kompetensi.index') }}" class="text-blue-600 hover:text-blue-800 text-xs inline-block mt-1">Tambah kompetensi</a>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Search functionality for santri
        const searchInput = document.getElementById('searchInputSantri');
        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                const tableRows = document.querySelectorAll('#santriTable tbody tr');
                
                tableRows.forEach(row => {
                    const rowText = row.textContent.toLowerCase();
                    if (rowText.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }
    });
</script>

<style>
    .box-bg {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }
</style>
@endsection