@extends('index')

@section('title', 'santri')

@section('content')
<div class="flex bg-gray-100 min-h-screen">

    <x-sidemenu title="PesantrenKita" />

    <main class="flex-1 p-4 h-full" x-data="{ currentTab: 'bio', selectedRow: null }">
        @if(session('error'))
        <div class="mb-4 mx-4">
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">
                            Gagal Menambah Data Santri
                        </h3>
                        <div class="mt-1 text-sm text-red-700">
                            {!! session('error') !!}
                        </div>
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
        <div class="mb-4 mx-4">
            <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-green-800">
                            Berhasil!
                        </h3>
                        <div class="mt-1 text-sm text-green-700">
                            {!! session('success') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        <div class="w-full h-full grid grid-cols-1 lg:grid-cols-12 gap-4">

            <!-- Konten Utama -->
            <div class="lg:col-span-9 flex flex-col gap-4">

                <!-- Aksi Cepat -->
                <div class="box-bg w-full p-4 bg-white rounded-xl">
                    <p class="text-2xl font-semibold text-gray-800 mb-4">Aksi Cepat</p>
                    <div class="flex flex-col md:flex-row gap-4 col-span-3">
                        <!-- Dropdown Pilih Kelas -->
                        <form action="{{ route('santri.index') }}" method="GET" id="kelasForm" class="flex-2">
                            <div class="relative group w-full">

                                <select
                                    name="kelas"
                                    id="kelasFilter"
                                    onchange="document.getElementById('kelasForm').submit()"
                                    class="appearance-none bg-white/90 backdrop-blur-sm border-2 border-emerald-200 rounded-2xl h-16 px-6 pr-12 cursor-pointer hover:border-emerald-300 transition-all duration-500 text-lg font-semibold text-gray-700 shadow-lg hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-emerald-200 focus:border-emerald-400 transform hover:scale-[1.02] w-full">

                                    <option value="" class="text-gray-400">
                                        Pilih Kelas
                                    </option>
                                    @foreach($kelas as $k)
                                    <option value="{{ $k->id_kelas }}" {{ $selectedKelas == $k->id_kelas ? 'selected' : '' }}>
                                        {{ $k->nama_kelas }}
                                    </option>
                                    @endforeach
                                </select>

                                <!-- Animated dropdown icon -->
                                <div class="pointer-events-none absolute top-1/2 right-4 transform -translate-y-1/2 text-emerald-600">
                                    <div class="transition-transform duration-500 group-hover:rotate-180">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <!-- Tombol Aksi 1 -->
                        <div class="flex-1">
                            <button @click="currentTab='bio'"
                                :class="currentTab==='bio' ? 'ring-4 ring-green-300' : ''"
                                class="bg-gradient-to-r from-[#2ECC71] to-[#17b459] rounded-xl shadow-lg p-6 text-white h-16 flex items-center justify-start px-4 hover:bg-green-600 transition cursor-pointer w-full">
                                <div class="flex items-center">
                                    <h2 class="text-lg font-semibold">Kelola Biodata</h2>
                                </div>
                            </button>
                        </div>

                        <!-- Tombol Aksi 2 -->
                        <div class="flex-1">
                            <button @click="currentTab='pencapaian'"
                                :class="currentTab==='pencapaian' ? 'ring-4 ring-green-300' : ''"
                                class="bg-[#344E41] rounded-2xl h-16 flex items-center justify-start px-4 hover:bg-[#2f423c] transition cursor-pointer w-full">
                                <div class="flex items-center">
                                    <h2 class="text-lg font-semibold text-white">Kelola Kompetensi</h2>
                                </div>
                            </button>
                        </div>
                    </div>
                    <div class="flex gap-4 mt-4">
                        <div class="w-full">
                            <a href="{{ route('kelas.index') }}"
                                class="w-full block text-center bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition duration-200">
                                Tambah Kelas
                            </a>
                        </div>

                        <div class="w-full">@include('pages.modal.create_santri')</div>
                    </div>

                </div>

                <!-- Tabel Santri -->
                <div class="box-bg w-full flex flex-col space-y-4 flex-1">
                    <!-- Tab Bio -->
                    <div x-show="currentTab==='bio'" class="flex-1 overflow-auto p-4 bg-white rounded-xl shadow-sm">
                        <x-dynamic-table :columns="$columnsbio" :rows="$rowsbio" />
                    </div>
                    <div x-show="currentTab==='pencapaian'" class="flex-1 overflow-auto p-4 bg-white rounded-xl shadow-sm">
                        <!-- Table Header dengan Info -->
                        <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800">Data Kompetensi Santri</h3>
                                    <p class="text-sm text-gray-600 mt-1">Total {{ count($pencapaian) }} data kompetensi</p>
                                </div>
                            </div>

                            <!-- Search Input dengan Debounce -->
                            <div class="mt-3 relative">
                                <input type="text"
                                    id="searchInputPencapaian"
                                    placeholder="Cari santri, kompetensi, atau kelas ..."
                                    class="w-full px-4 py-2 pl-10 pr-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                                <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <!-- Loading Indicator -->
                                <div id="searchLoadingPencapaian" class="absolute right-3 top-2.5 hidden">
                                    <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-600"></div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden"
                            x-data="pencapaianTable()">
                            <!-- Tabel Utama dengan Dropdown Aksi -->
                            <!-- Table Container -->
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <!-- Table Header -->
                                    <thead class="bg-gray-50/80 backdrop-blur-sm">
                                        <tr>
                                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200 rounded-tl-lg">
                                                <div class="flex items-center space-x-1">
                                                    <span>Santri</span>
                                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                                    </svg>
                                                </div>
                                            </th>
                                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200">
                                                Kelas
                                            </th>
                                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200">
                                                Pencapaian
                                            </th>
                                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200">
                                                Tipe
                                            </th>
                                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200">
                                                Tanggal
                                            </th>
                                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200">
                                                Skor
                                            </th>
                                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200 rounded-tr-lg">
                                                Aksi
                                            </th>
                                        </tr>
                                    </thead>

                                    <!-- Table Body -->
                                    <tbody class="divide-y divide-gray-100" id="tbodyDataPencapaian">
                                        @foreach($pencapaian as $index => $item)
                                        <tr class="group transition-all duration-200 hover:bg-gradient-to-r hover:from-blue-50/50 hover:to-indigo-50/50 
                      {{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50/30' }}">

<!-- Kolom Santri -->
<td class="px-6 py-4 whitespace-nowrap transition-colors duration-200 group-hover:text-gray-900 cursor-pointer"
    @click="showDetail('{{ $item->id_santri }}', '{{ $item->nama_santri }}')">
    <div class="flex items-center">
        <span class="font-semibold text-gray-900">{{ $item->nama_santri }}</span>
    </div>
</td>

<!-- Kolom Kelas -->
<td class="px-6 py-4 whitespace-nowrap text-sm transition-colors duration-200 group-hover:text-gray-900 cursor-pointer"
    @click="showDetail('{{ $item->id_santri }}', '{{ $item->nama_santri }}')">
    <div class="flex items-center">
        <span class="text-gray-700">{{ $item->nama_kelas }}</span>
    </div>
</td>

<!-- Kolom Pencapaian -->
<td class="px-6 py-4 transition-colors duration-200 cursor-pointer"
    @click="showDetail('{{ $item->id_santri }}', '{{ $item->nama_santri }}')">
    <div class="flex flex-col">
        <div class="text-sm font-medium text-gray-900">{{ $item->judul }}</div>
        <div class="text-xs text-gray-500 mt-1">{{ Str::limit($item->deskripsi, 40) }}</div>
    </div>
</td>

<!-- Kolom Tipe -->
<td class="px-6 py-4 whitespace-nowrap transition-colors duration-200 cursor-pointer"
    @click="showDetail('{{ $item->id_santri }}', '{{ $item->nama_santri }}')">
    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
        {{ $item->tipe }}
    </span>
</td>

<!-- Kolom Tanggal -->
<td class="px-6 py-4 whitespace-nowrap text-sm transition-colors duration-200 group-hover:text-gray-900 cursor-pointer"
    @click="showDetail('{{ $item->id_santri }}', '{{ $item->nama_santri }}')">
    <div class="flex items-center">
        <span class="text-gray-700">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</span>
    </div>
</td>

<!-- Kolom Skor -->
<td class="px-6 py-4 whitespace-nowrap text-sm font-medium transition-colors duration-200 cursor-pointer"
    @click="showDetail('{{ $item->id_santri }}', '{{ $item->nama_santri }}')">
    <span class="{{ $item->skor >= 80 ? 'text-green-600' : ($item->skor >= 60 ? 'text-orange-600' : 'text-red-600') }}">
        {{ $item->skor }}/100
    </span>
</td>
                                            <!-- Kolom Aksi -->
                                            <td class="px-6 py-4 whitespace-nowrap" onclick="event.stopPropagation()">
                                                <div class="flex justify-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                                    <a href="{{ route('pencapaian.edit', $item->id_pencapaian) }}"
                                                        class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg 
                                  hover:from-blue-600 hover:to-blue-700 transition-all duration-200 transform hover:scale-105 
                                  shadow-sm hover:shadow-md text-xs font-medium">
                                                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                        Edit
                                                    </a>
                                                    <button onclick="confirmDeletePencapaian('{{ $item->id_pencapaian }}', '{{ addslashes($item->judul) }}')"
                                                        class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg 
                                       hover:from-red-600 hover:to-red-700 transition-all duration-200 transform hover:scale-105 
                                       shadow-sm hover:shadow-md text-xs font-medium">
                                                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                        Hapus
                                                    </button>
                                                </div>
                                                <div class="flex justify-center space-x-2 opacity-100 group-hover:opacity-0 transition-opacity duration-200">
                                                    <span class="text-xs text-gray-400">Hover untuk aksi</span>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Empty State -->
                            @if(count($pencapaian) === 0)
                            <div class="px-6 py-12 text-center">
                                <div class="max-w-md mx-auto">
                                    <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl flex items-center justify-center">
                                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                    </div>
                                    <h4 class="text-lg font-medium text-gray-600 mb-2">Tidak ada data kompetensi</h4>
                                    <p class="text-gray-500 text-sm">Data kompetensi santri akan muncul di sini ketika tersedia</p>
                                </div>
                            </div>
                            @endif

                            <!-- Modal Detail -->
                            <div x-show="selectedSantri"
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0"
                                x-transition:enter-end="opacity-100"
                                x-transition:leave="transition ease-in duration-200"
                                x-transition:leave-start="opacity-100"
                                x-transition:leave-end="opacity-0"
                                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">

                                <div x-show="selectedSantri"
                                    x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0 transform scale-95"
                                    x-transition:enter-end="opacity-100 transform scale-100"
                                    x-transition:leave="transition ease-in duration-200"
                                    x-transition:leave-start="opacity-100 transform scale-100"
                                    x-transition:leave-end="opacity-0 transform scale-95"
                                    class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-hidden">

                                    <!-- Header Modal -->
                                    <div class="flex justify-between items-center p-6 border-b border-gray-200">
                                        <div>
                                            <h2 class="text-xl font-bold text-gray-800" x-text="'Kompetensi ' + selectedSantri.nama"></h2>
                                            <p class="text-sm text-gray-600" x-text="'Total: ' + detailData.length + ' kompetensi'"></p>
                                        </div>
                                        <button @click="closeDetail()" class="text-gray-400 hover:text-gray-600 transition">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>

                                    <!-- Loading State -->
                                    <div x-show="loading" class="p-8 text-center">
                                        <div class="inline-flex items-center">
                                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Memuat data kompetensi...
                                        </div>
                                    </div>

                                    <!-- Content -->
                                    <div x-show="!loading" class="p-6 overflow-y-auto">
                                        <div x-show="detailData.length === 0" class="text-center py-8 text-gray-500">
                                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <p>Tidak ada data kompetensi untuk santri ini</p>
                                        </div>

                                        <div x-show="detailData.length > 0">
                                            <!-- Header dengan Rata-rata -->
                                            <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                                                <div class="flex justify-between items-center">
                                                    <span class="text-sm font-medium text-gray-700">Total: <span x-text="detailData.length"></span> kompetensi</span>
                                                    <span class="text-sm font-medium text-gray-700">
                                                        Rata-rata: <span class="font-bold" x-text="(detailData.reduce((sum, item) => sum + parseFloat(item.skor), 0) / detailData.length).toFixed(1)"></span>/100
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- Tabel -->
                                            <table class="w-full">
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase border-b">Kompetensi</th>
                                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase border-b">Tipe</th>
                                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase border-b">Tanggal</th>
                                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase border-b">Skor</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-gray-200">
                                                    <template x-for="(item, index) in detailData" :key="index">
                                                        <tr class="hover:bg-gray-50">
                                                            <td class="px-4 py-3">
                                                                <div class="text-sm font-medium text-gray-900" x-text="item.judul"></div>
                                                                <div class="text-xs text-gray-500 mt-1" x-text="item.deskripsi"></div>
                                                            </td>
                                                            <td class="px-4 py-3">
                                                                <span class="text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-800" x-text="item.tipe"></span>
                                                            </td>
                                                            <td class="px-4 py-3 text-sm text-gray-600" x-text="new Date(item.tanggal).toLocaleDateString('id-ID')"></td>
                                                            <td class="px-4 py-3">
                                                                <span class="text-sm font-medium"
                                                                    :class="item.skor >= 80 ? 'text-green-600' : 'text-orange-600'"
                                                                    x-text="item.skor + '/100'"></span>
                                                            </td>
                                                        </tr>
                                                    </template>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Sidebar Info Kelas -->
            <div x-show="currentTab==='bio'" class="lg:col-span-3 flex flex-col gap-4">
                <div class="box-bg w-full p-4 box-bg flex flex-col justify-start h-full">
                    <div class="mb-2">
                        <div class="grid items-center space-x-3 pb-3 border-b border-[#E0E0E0]">
                            <svg xmlns="http://www.w3.org/2000/svg" height="46px" viewBox="0 -960 960 960" width="46px" fill="#1f1f1f">
                                <path d="M40-160v-160q0-34 23.5-57t56.5-23h131q20 0 38 10t29 27q29 39 71.5 61t90.5 22q49 0 91.5-22t70.5-61q13-17 30.5-27t36.5-10h131q34 0 57 23t23 57v160H640v-91q-35 25-75.5 38T480-200q-43 0-84-13.5T320-252v92H40Zm440-160q-38 0-72-17.5T351-386q-17-25-42.5-39.5T253-440q22-37 93-58.5T480-520q63 0 134 21.5t93 58.5q-29 0-55 14.5T609-386q-22 32-56 49t-73 17ZM160-440q-50 0-85-35t-35-85q0-51 35-85.5t85-34.5q51 0 85.5 34.5T280-560q0 50-34.5 85T160-440Zm640 0q-50 0-85-35t-35-85q0-51 35-85.5t85-34.5q51 0 85.5 34.5T920-560q0 50-34.5 85T800-440ZM480-560q-50 0-85-35t-35-85q0-51 35-85.5t85-34.5q51 0 85.5 34.5T600-680q0 50-34.5 85T480-560Z" />
                            </svg>
                            <div>
                                <h2 class="text-md font-medium text-black">
                                    {{ $selectedKelas ? $kelas->firstWhere('id_kelas', $selectedKelas)->nama_kelas : 'Semua Kelas' }}
                                </h2>
                                <p class="text-sm text-gray-600">
                                    {{ count($rowsbio) }} Santri | {{ count($kompetensi) }} Kompetensi
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h3 class="text-md font-semibold my-2">Kompetensi</h3>
                        <div class="flex flex-wrap gap-2 mb-4">
                            @forelse($kompetensi as $k)
                            <span class="px-3 py-1 bg-[#A8E6CF] text-[#344E41] rounded-full text-sm">{{ $k }}</span>
                            @empty
                            <span class="text-gray-400 text-sm">Belum ada data</span>
                            @endforelse
                        </div>
                    </div>

                    <div class="mb-4">
                        <h3 class="text-md font-semibold text-black mb-3">Kelas</h3>
                        <div class="flex flex-wrap gap-2">
                            @forelse($kelas as $k)
                            <span class="px-3 py-1 bg-[#A8E6CF] text-[#344E41] rounded-full text-sm">{{ $k->nama_kelas }}</span>
                            @empty
                            <span class="text-gray-400 text-sm">Belum ada data</span>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar untuk Tab Pencapaian -->
            <div x-show="currentTab==='pencapaian'" class="lg:col-span-3 flex flex-col gap-4">
                <div class="box-bg w-full p-4 bg-white rounded-xl">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Statistik Kompetensi</h3>
                        <button onclick="refreshChart()" class="text-blue-600 hover:text-blue-800 text-sm flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Refresh
                        </button>
                    </div>
                    <div class="flex justify-center items-center">
                        <div class="w-64 h-64">
                            <canvas id="grafikPrestasi"></canvas>
                        </div>
                    </div>
                    <div class="mt-4 space-y-2" id="chartStatistics">
                        <!-- Data statistik akan diisi secara dinamis oleh JavaScript -->
                        <div class="text-center text-gray-500 py-4">
                            Memuat data...
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="/kompetensi" class="w-full h-12 py-2 bg-[#2ECC71] text-white text-sm font-semibold rounded-lg hover:bg-green-600 transition inline-flex items-center justify-center">
                            <span>Tambah Kompetensi</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Chart initialization dengan data dari controller
    async function initializeChart() {
        const ctx = document.getElementById('grafikPrestasi');

        if (!ctx) {
            console.log('Canvas element not found, retrying...');
            return;
        }

        try {
            // Ambil data dari controller
            const response = await fetch('/pencapaian/chart-data');
            const chartData = await response.json();

            console.log('Data chart dari server:', chartData);

            // Hancurkan chart sebelumnya jika ada
            if (window.prestasiChart) {
                window.prestasiChart.destroy();
            }

            window.prestasiChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        data: chartData.data,
                        backgroundColor: chartData.colors,
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.parsed;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                    return `${label}: ${value} prestasi (${percentage}%)`;
                                }
                            }
                        }
                    },
                    animation: {
                        animateScale: true,
                        animateRotate: true
                    }
                }
            });

            // Update statistik di sidebar
            updateChartStatistics(chartData);
            console.log('Chart initialized successfully with live data');

        } catch (error) {
            console.error('Error initializing chart:', error);
            // Fallback ke data default jika error
            initializeFallbackChart();
        }
    }

    // Update statistik di sidebar dengan data real
    function updateChartStatistics(chartData) {
        const statisticsContainer = document.querySelector('.mt-4.space-y-2');
        if (!statisticsContainer) return;

        let html = '';

        chartData.labels.forEach((label, index) => {
            const count = chartData.data[index];
            const color = chartData.colors[index];

            html += `
                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center">
                        <div class="w-3 h-3 rounded-full mr-2" style="background-color: ${color}"></div>
                        <span>${label}</span>
                    </div>
                    <span class="font-medium">${count}</span>
                </div>
            `;
        });

        statisticsContainer.innerHTML = html;
    }

    // Fallback chart jika data tidak bisa diambil
    function initializeFallbackChart() {
        const ctx = document.getElementById('grafikPrestasi');
        if (!ctx) return;

        if (window.prestasiChart) {
            window.prestasiChart.destroy();
        }

        window.prestasiChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Tidak ada data'],
                datasets: [{
                    data: [1],
                    backgroundColor: ['#cbd5e1'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        enabled: false
                    }
                }
            }
        });
    }

    // Alpine.js Component untuk tabel pencapaian
    function pencapaianTable() {
        return {
            selectedSantri: null,
            detailData: [],
            loading: false,

            // Method yang bisa diakses dari template
            async showDetail(idSantri, namaSantri) {
                console.log('showDetail dipanggil:', idSantri, namaSantri);
                this.loading = true;
                this.selectedSantri = {
                    id: idSantri,
                    nama: namaSantri
                };

                try {
                    const response = await fetch(`/santri/${idSantri}/kompetensi`);
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    this.detailData = await response.json();
                    console.log('Data diterima:', this.detailData);
                } catch (error) {
                    console.error('Error fetching data:', error);
                    this.detailData = [];
                }

                this.loading = false;
            },

            closeDetail() {
                this.selectedSantri = null;
                this.detailData = [];
            }
        }
    }

    // Search functionality
    let searchTimeoutPencapaian = null;

    function performSearchPencapaian(searchTerm) {
        const searchTermLower = searchTerm.trim().toLowerCase();

        if (!searchTermLower) {
            // Show all rows if search is empty
            document.querySelectorAll('#tbodyDataPencapaian tr').forEach(row => {
                row.style.display = '';
            });
            return;
        }

        // Filter rows
        document.querySelectorAll('#tbodyDataPencapaian tr').forEach(row => {
            const rowText = row.textContent.toLowerCase();
            if (rowText.includes(searchTermLower)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    // Event handler untuk search input
    document.getElementById('searchInputPencapaian')?.addEventListener('input', function(e) {
        const searchTerm = e.target.value;

        if (searchTimeoutPencapaian) {
            clearTimeout(searchTimeoutPencapaian);
        }

        // Show loading
        const searchLoading = document.getElementById('searchLoadingPencapaian');
        if (searchLoading) searchLoading.classList.remove('hidden');

        searchTimeoutPencapaian = setTimeout(() => {
            performSearchPencapaian(searchTerm);
            if (searchLoading) searchLoading.classList.add('hidden');
        }, 500);
    });

    // Clear search function
    function clearSearchPencapaian() {
        const searchInput = document.getElementById('searchInputPencapaian');
        const searchLoading = document.getElementById('searchLoadingPencapaian');

        if (searchInput) searchInput.value = '';
        if (searchLoading) searchLoading.classList.add('hidden');

        if (searchTimeoutPencapaian) {
            clearTimeout(searchTimeoutPencapaian);
        }

        // Show all rows
        document.querySelectorAll('#tbodyDataPencapaian tr').forEach(row => {
            row.style.display = '';
        });
    }

    // Delete confirmation function
    function confirmDeletePencapaian(id, judul) {
        if (confirm(`Apakah Anda yakin ingin menghapus kompetensi "${judul}"?`)) {
            // Create and submit form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/pencapaian/${id}`;

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';

            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';

            form.appendChild(csrfToken);
            form.appendChild(methodField);
            document.body.appendChild(form);
            form.submit();
        }
    }

    // Inisialisasi chart ketika Alpine.js siap
    document.addEventListener('alpine:init', () => {
        setTimeout(initializeChart, 100);
    });

    // Juga inisialisasi saat tab diubah
    document.addEventListener('DOMContentLoaded', function() {
        // Initial chart setup
        setTimeout(initializeChart, 200);

        // Observer untuk mendeteksi perubahan tab
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'attributes' && mutation.attributeName === 'x-show') {
                    const tabPencapaian = document.querySelector('[x-show="currentTab===\'pencapaian\'"]');
                    if (tabPencapaian && !tabPencapaian.hasAttribute('hidden')) {
                        setTimeout(initializeChart, 50);
                    }
                }
            });
        });

        // Observe the main element
        const mainElement = document.querySelector('main[x-data]');
        if (mainElement) {
            observer.observe(mainElement, {
                attributes: true,
                attributeFilter: ['x-show']
            });
        }
    });

    // Refresh chart function (bisa dipanggil dari mana saja)
    window.refreshChart = initializeChart;
</script>

@endsection