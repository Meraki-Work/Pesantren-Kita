@extends('index')

@section('title', 'Dashboard')

@section('content')
<div class="flex bg-gray-100 min-h-screen">

    <x-sidemenu title="PesantrenKita" />

    <main class="flex-1 p-4 h-full" x-data="{ currentTab: 'bio', selectedRow: null }">

        <div class="w-full h-full grid grid-cols-1 lg:grid-cols-12 gap-4">

            <!-- Konten Utama -->
            <div class="lg:col-span-9 flex flex-col gap-4">

                <!-- Aksi Cepat -->
                <div class="box-bg w-full p-4 bg-white rounded-xl shadow-sm">
                    <p class="text-2xl font-semibold text-gray-800 mb-4">Aksi Cepat</p>
                    <div class="flex flex-col md:flex-row gap-4 w-full">

                        <!-- Dropdown Pilih Kelas -->
                        <form action="{{ route('santri.index') }}" method="GET" id="kelasForm" class="flex-1">
                            <select name="kelas" id="kelasFilter"
                                onchange="document.getElementById('kelasForm').submit()"
                                class="bg-[#A8E6CF] rounded-2xl h-16 px-4 py-2 cursor-pointer hover:bg-green-300 transition w-full text-lg font-medium">
                                <option value="">-- Semua Kelas --</option>
                                @foreach($kelas as $k)
                                <option value="{{ $k->id_kelas }}" {{ $selectedKelas == $k->id_kelas ? 'selected' : '' }}>
                                    {{ $k->nama_kelas }}
                                </option>
                                @endforeach
                            </select>
                        </form>

                        <!-- Tombol Aksi -->
                        <div class="flex flex-col sm:flex-row gap-4 flex-1">
                            <button @click="currentTab='bio'"
                                :class="currentTab==='bio' ? 'ring-4 ring-green-300' : ''"
                                class="bg-[#2ECC71] rounded-2xl h-16 flex items-center justify-start px-4 hover:bg-green-600 transition cursor-pointer flex-1">
                                <div class="flex items-center">
                                    <img src="{{ asset('assets/img/laundry.png') }}" alt="Laundry Icon" class="w-10 h-10 mr-3">
                                    <h2 class="text-lg font-semibold text-gray-900">Biodata Santri</h2>
                                </div>
                            </button>

                            <button @click="currentTab='pencapaian'"
                                :class="currentTab==='pencapaian' ? 'ring-4 ring-green-300' : ''"
                                class="bg-[#344E41] rounded-2xl h-16 flex items-center justify-start px-4 hover:bg-[#2f423c] transition cursor-pointer flex-1">
                                <div class="flex items-center">
                                    <img src="{{ asset('assets/img/kompetensi.png') }}" alt="Kompetensi Icon" class="w-9 h-9 mr-3">
                                    <h2 class="text-lg font-semibold text-white">Pencapaian Santri</h2>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="flex gap-4">
                    @include('pages.modal.create_kelas')
                    @include('pages.modal.create_santri')
                </div>
                <!-- Tabel Santri -->
                <div class="box-bg w-full flex flex-col space-y-4 flex-1">
                    <!-- Tab Bio -->
                    <div x-show="currentTab==='bio'" class="flex-1 overflow-auto p-4 bg-white rounded-xl shadow-sm">
                        <h2 class="text-lg font-bold text-black mb-4">Bio Data Santri</h2>
                        <x-dynamic-table :columns="$columnsbio" :rows="$rowsbio" />
                    </div>
                    <div x-show="currentTab==='pencapaian'" class="flex-1 overflow-auto p-4 bg-white rounded-xl shadow-sm">
                        <h2 class="text-lg font-bold text-black mb-4">Kompetensi Data Santri</h2>
                        <!-- Tabel Minimalis -->
                        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden"
                            x-data="pencapaianTable()">
                            <!-- Tabel Utama dengan Dropdown Aksi -->
                            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                                <table class="w-full">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 border-b">Santri</th>
                                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 border-b">Kelas</th>
                                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 border-b">Pencapaian</th>
                                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 border-b">Tipe</th>
                                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 border-b">Tanggal</th>
                                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 border-b">Skor</th>
                                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 border-b">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @foreach($pencapaian as $item)
                                        <tr class="hover:bg-gray-50">
                                            <!-- Kolom yang bisa di-click untuk detail -->
                                            <td class="px-4 py-3 text-sm text-gray-900 font-medium cursor-pointer"
                                                @click="showDetail('{{ $item->id_santri }}', '{{ $item->nama_santri }}')">
                                                {{ $item->nama_santri }}
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-600 cursor-pointer"
                                                @click="showDetail('{{ $item->id_santri }}', '{{ $item->nama_santri }}')">
                                                {{ $item->nama_kelas }}
                                            </td>
                                            <td class="px-4 py-3 cursor-pointer"
                                                @click="showDetail('{{ $item->id_santri }}', '{{ $item->nama_santri }}')">
                                                <div class="text-sm font-medium text-gray-900">{{ $item->judul }}</div>
                                                <div class="text-xs text-gray-500">{{ Str::limit($item->deskripsi, 40) }}</div>
                                            </td>
                                            <td class="px-4 py-3 cursor-pointer"
                                                @click="showDetail('{{ $item->id_santri }}', '{{ $item->nama_santri }}')">
                                                <span class="text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-800">
                                                    {{ $item->tipe }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-600 cursor-pointer"
                                                @click="showDetail('{{ $item->id_santri }}', '{{ $item->nama_santri }}')">
                                                {{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}
                                            </td>
                                            <td class="px-4 py-3 text-sm font-medium cursor-pointer"
                                                @click="showDetail('{{ $item->id_santri }}', '{{ $item->nama_santri }}')">
                                                <span class="{{ $item->skor >= 80 ? 'text-green-600' : 'text-orange-600' }}">
                                                    {{ $item->skor }}/100
                                                </span>
                                            </td>
                                            <!-- Kolom aksi TIDAK bisa di-click untuk detail -->
                                            <td class="px-4 py-3" @click.stop>
                                                <div class="relative inline-block text-left" x-data="{ open: false }">
                                                    <!-- Tombol Trigger -->
                                                    <button @click="open = !open; $event.stopPropagation()"
                                                        class="inline-flex justify-center w-8 h-8 p-1 text-gray-400 hover:text-gray-600 rounded-full hover:bg-gray-200 transition">
                                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                                        </svg>
                                                    </button>

                                                    <!-- Dropdown Menu -->
                                                    <div x-show="open"
                                                        x-cloak
                                                        @click.outside="open = false"
                                                        class="absolute right-0 z-10 mt-1 w-28 bg-white rounded-md shadow-lg border border-gray-200">
                                                        <div class="py-1">
                                                            <!-- Tombol Edit -->
                                                            <a href="{{ route('pencapaian.edit', $item->id_pencapaian) }}"
                                                                class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                                </svg>
                                                                Edit
                                                            </a>

                                                            <!-- Tombol Hapus -->
                                                            <form action="{{ route('pencapaian.destroy', $item->id_pencapaian) }}" method="POST" class="inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="button"
                                                                    onclick="confirmDelete('{{ $item->id_pencapaian }}', '{{ $item->judul }}')"
                                                                    class="flex items-center px-3 py-2 text-sm text-red-600 hover:bg-gray-100 w-full text-left">
                                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                                    </svg>
                                                                    Hapus
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                @if($pencapaian->count() === 0)
                                <div class="text-center py-8 text-gray-500">
                                    Tidak ada data pencapaian yang ditemukan
                                </div>
                                @endif
                            </div>

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
            <div class="lg:col-span-3 flex flex-col gap-4">
                <div class="box-bg w-full p-4 bg-white rounded-xl shadow-sm flex flex-col justify-start h-full">
                    <div class="mb-6">
                        <div class="flex items-center space-x-3 mb-3">
                            <img src="{{ asset('assets/img/Frame.png') }}" alt="Group Icon" class="w-12 h-12">
                            <div>
                                <h2 class="text-xl font-bold text-black">
                                    {{ $selectedKelas ? $kelas->firstWhere('id_kelas', $selectedKelas)->nama_kelas : 'Semua Kelas' }}
                                </h2>
                                <p class="text-sm text-gray-600">
                                    {{ count($rowsbio) }} Santri | {{ count($kompetensi) }} Kompetensi
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-md font-semibold text-black mb-3">Kompetensi</h3>
                        <div class="flex flex-wrap gap-2 mb-4">
                            @forelse($kompetensi as $k)
                            <span class="px-3 py-1 bg-[#A8E6CF] text-[#344E41] rounded-full text-sm">{{ $k }}</span>
                            @empty
                            <span class="text-gray-400 text-sm">Belum ada data</span>
                            @endforelse
                        </div>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-md font-semibold text-black mb-3">Kelas</h3>
                        <div class="flex flex-wrap gap-2">
                            @forelse($kelas as $k)
                            <span class="px-3 py-1 bg-[#A8E6CF] text-[#344E41] rounded-full text-sm">{{ $k->nama_kelas }}</span>
                            @empty
                            <span class="text-gray-400 text-sm">Belum ada data</span>
                            @endforelse
                        </div>
                    </div>

                    <div class="mt-auto">
                        <a href="/kompetensi" class="w-full h-12 py-2 bg-[#2ECC71] text-white text-sm font-semibold rounded-lg hover:bg-green-600 transition inline-flex items-center justify-center">
                            <span>Tambah Kompetensi</span>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </main>
</div>

<script>
    function confirmDelete(id, judul) {
        if (confirm(`Apakah Anda yakin ingin menghapus pencapaian "${judul}"?`)) {
            event.target.closest('form').submit();
        }
    }

    function pencapaianTable() {
        return {
            selectedSantri: null,
            detailData: [],
            loading: false,

            async showDetail(idSantri, namaSantri) {
                this.loading = true;
                this.selectedSantri = {
                    id: idSantri,
                    nama: namaSantri
                };

                try {
                    const response = await fetch(`/santri/${idSantri}/kompetensi`);
                    this.detailData = await response.json();
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
</script>

@endsection