@extends('index')

@section('title', 'Kompetensi Santri')

@section('content')
<div class="flex bg-gray-100 min-h-screen">
    <x-sidemenu title="PesantrenKita" />
    
    <main class="flex-1 p-6 h-full">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Kompetensi Santri</h1>
                <p class="text-sm text-gray-600 mt-1">Manajemen pencapaian dan kompetensi santri</p>
            </div>
            <a href="{{ route('kompetensi.index') }}?action=create" 
               class="bg-gradient-to-r from-green-600 to-emerald-600 text-white px-4 py-2 rounded-lg hover:from-green-700 hover:to-emerald-700 transition font-medium inline-flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Kompetensi
            </a>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Konten Utama -->
            <div class="lg:col-span-9 space-y-6">
                <!-- Filter dan Statistik -->
                <div class="box-bg bg-white rounded-xl p-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Filter Kelas -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Filter Kelas</label>
                            <select id="filterKelas" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                                <option value="">Semua Kelas</option>
                                @foreach($kelas as $k)
                                <option value="{{ $k->id_kelas }}">{{ $k->nama_kelas }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filter Tipe -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Filter Tipe</label>
                            <select id="filterTipe" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                                <option value="">Semua Tipe</option>
                                <option value="Akademik">Akademik</option>
                                <option value="Non-Akademik">Non-Akademik</option>
                                <option value="Spiritual">Spiritual</option>
                                <option value="Keterampilan">Keterampilan</option>
                            </select>
                        </div>

                        <!-- Filter Tanggal -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Filter Bulan</label>
                            <select id="filterBulan" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                                <option value="">Semua Bulan</option>
                                @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}">{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                                @endfor
                            </select>
                        </div>

                        <!-- Reset Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">&nbsp;</label>
                            <button id="resetFilter" class="w-full px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium">
                                Reset Filter
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Tabel Kompetensi -->
                <div class="box-bg bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800">Data Kompetensi</h3>
                                <p class="text-sm text-gray-600 mt-1">Total {{ count($pencapaian) }} data kompetensi</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button onclick="exportToExcel()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium text-sm">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Export
                                </button>
                            </div>
                        </div>

                        <!-- Search Input -->
                        <div class="mb-4 relative">
                            <input type="text" id="searchInputPencapaian" placeholder="Cari santri, kompetensi, atau kelas..." 
                                   class="w-full px-4 py-2 pl-10 pr-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                            <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>

                        <!-- Table Container -->
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50/80 backdrop-blur-sm">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Santri</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Kelas</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Pencapaian</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Tipe</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Tanggal</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Skor</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200" id="tbodyDataPencapaian">
                                    @foreach($pencapaian as $item)
                                    <tr class="hover:bg-gray-50 transition duration-150" data-kelas="{{ $item->nama_kelas }}" data-tipe="{{ $item->tipe }}" data-bulan="{{ date('n', strtotime($item->tanggal)) }}">
                                        <!-- Kolom Santri -->
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-gray-900">{{ $item->nama_santri }}</div>
                                        </td>
                                        
                                        <!-- Kolom Kelas -->
                                        <td class="px-6 py-4">
                                            <span class="text-sm text-gray-700">{{ $item->nama_kelas }}</span>
                                        </td>
                                        
                                        <!-- Kolom Pencapaian -->
                                        <td class="px-6 py-4">
                                            <div class="flex flex-col">
                                                <div class="text-sm font-medium text-gray-900">{{ $item->judul }}</div>
                                                <div class="text-xs text-gray-500 mt-1">{{ Str::limit($item->deskripsi, 40) }}</div>
                                            </div>
                                        </td>
                                        
                                        <!-- Kolom Tipe -->
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $item->tipe }}
                                            </span>
                                        </td>
                                        
                                        <!-- Kolom Tanggal -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-700">
                                                {{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}
                                            </div>
                                        </td>
                                        
                                        <!-- Kolom Skor -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="{{ $item->skor >= 80 ? 'text-green-600 font-semibold' : ($item->skor >= 60 ? 'text-orange-600' : 'text-red-600') }}">
                                                {{ $item->skor }}/100
                                            </span>
                                        </td>
                                        
                                        <!-- Kolom Aksi -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('pencapaian.edit', $item->id_pencapaian) }}"
                                                   class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 text-xs font-medium">
                                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                    Edit
                                                </a>
                                                <button onclick="confirmDeletePencapaian('{{ $item->id_pencapaian }}', '{{ addslashes($item->judul) }}')"
                                                        class="inline-flex items-center px-3 py-1.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-200 text-xs font-medium">
                                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    Hapus
                                                </button>
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
                                <a href="{{ route('kompetensi.index') }}?action=create" class="mt-4 inline-block bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition font-medium text-sm">
                                    Tambah Kompetensi Pertama
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar untuk Statistik -->
            <div class="lg:col-span-3 space-y-6">
                <!-- Statistik Chart -->
                <div class="box-bg bg-white rounded-xl p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Statistik Kompetensi</h3>
                        <button onclick="refreshChart()" class="text-blue-600 hover:text-blue-800 text-sm flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Refresh
                        </button>
                    </div>
                    <div class="flex justify-center items-center mb-6">
                        <div class="w-56 h-56">
                            <canvas id="grafikPrestasi"></canvas>
                        </div>
                    </div>
                    
                    <!-- Statistik Detail -->
                    <div class="space-y-4">
                        <div>
                            <h4 class="text-sm font-semibold text-gray-700 mb-2">Distribusi Tipe</h4>
                            <div class="space-y-2">
                                @if(isset($distribusiTipe) && count($distribusiTipe) > 0)
                                    @foreach($distribusiTipe as $tipe => $data)
                                    <div class="flex items-center justify-between text-sm">
                                        <div class="flex items-center">
                                            <div class="w-3 h-3 rounded-full mr-2 bg-blue-500"></div>
                                            <span>{{ $tipe }}</span>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="font-medium">{{ $data['count'] }}</span>
                                            <span class="text-gray-500 text-xs">({{ $data['percentage'] }}%)</span>
                                        </div>
                                    </div>
                                    @endforeach
                                @else
                                    <p class="text-sm text-gray-500">Belum ada data tipe</p>
                                @endif
                            </div>
                        </div>
                        
                        <div class="pt-4 border-t border-gray-200">
                            <h4 class="text-sm font-semibold text-gray-700 mb-2">Ringkasan</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Total Santri</span>
                                    <span class="font-medium">{{ $statistics['total_santri'] ?? 0 }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Total Kompetensi</span>
                                    <span class="font-medium">{{ $statistics['total_pencapaian'] ?? 0 }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Rata-rata Skor</span>
                                    <span class="font-medium text-green-600">{{ $statistics['rata_rata_skor'] ?? 0 }}/100</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Santri Berprestasi</span>
                                    <span class="font-medium text-blue-600">{{ $statistics['santri_berprestasi'] ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="box-bg bg-white rounded-xl p-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">Aksi Cepat</h4>
                    <div class="space-y-3">
                        <a href="{{ route('santri.index') }}" 
                           class="w-full block text-center bg-gradient-to-r from-emerald-500 to-green-500 text-white px-4 py-3 rounded-lg hover:from-emerald-600 hover:to-green-600 transition font-medium">
                            <div class="flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Kelola Santri
                            </div>
                        </a>
                        <a href="{{ route('kelas.index') }}" 
                           class="w-full block text-center bg-gradient-to-r from-blue-500 to-indigo-500 text-white px-4 py-3 rounded-lg hover:from-blue-600 hover:to-indigo-600 transition font-medium">
                            <div class="flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5 6.75v-1a6 6 0 00-9-5.197" />
                                </svg>
                                Kelola Kelas
                            </div>
                        </a>
                        <a href="{{ route('kompetensi.index') }}?action=create" 
                           class="w-full block text-center bg-gradient-to-r from-purple-500 to-pink-500 text-white px-4 py-3 rounded-lg hover:from-purple-600 hover:to-pink-600 transition font-medium">
                            <div class="flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Tambah Kompetensi
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Chart initialization
    async function initializeChart() {
        const ctx = document.getElementById('grafikPrestasi');
        if (!ctx) return;

        try {
            const response = await fetch('/pencapaian/chart-data');
            const chartData = await response.json();

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
                    cutout: '60%',
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
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });

        } catch (error) {
            console.error('Error initializing chart:', error);
            initializeFallbackChart();
        }
    }

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
                    backgroundColor: ['#e5e7eb'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '60%',
                plugins: {
                    legend: { display: false }
                }
            }
        });
    }

    // Filter functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize chart
        setTimeout(initializeChart, 200);

        // Filter elements
        const filterKelas = document.getElementById('filterKelas');
        const filterTipe = document.getElementById('filterTipe');
        const filterBulan = document.getElementById('filterBulan');
        const resetFilter = document.getElementById('resetFilter');
        const searchInput = document.getElementById('searchInputPencapaian');
        const tableRows = document.querySelectorAll('#tbodyDataPencapaian tr');

        function applyFilters() {
            const kelasValue = filterKelas.value;
            const tipeValue = filterTipe.value;
            const bulanValue = filterBulan.value;
            const searchValue = searchInput ? searchInput.value.toLowerCase() : '';

            tableRows.forEach(row => {
                const kelasMatch = !kelasValue || row.getAttribute('data-kelas') === kelasValue;
                const tipeMatch = !tipeValue || row.getAttribute('data-tipe') === tipeValue;
                const bulanMatch = !bulanValue || row.getAttribute('data-bulan') === bulanValue;
                const searchMatch = !searchValue || row.textContent.toLowerCase().includes(searchValue);
                
                if (kelasMatch && tipeMatch && bulanMatch && searchMatch) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Event listeners
        if (filterKelas) filterKelas.addEventListener('change', applyFilters);
        if (filterTipe) filterTipe.addEventListener('change', applyFilters);
        if (filterBulan) filterBulan.addEventListener('change', applyFilters);
        if (resetFilter) {
            resetFilter.addEventListener('click', function() {
                if (filterKelas) filterKelas.value = '';
                if (filterTipe) filterTipe.value = '';
                if (filterBulan) filterBulan.value = '';
                if (searchInput) searchInput.value = '';
                applyFilters();
            });
        }
        if (searchInput) {
            searchInput.addEventListener('input', applyFilters);
        }
    });

    // Delete confirmation function
    function confirmDeletePencapaian(id, judul) {
        if (confirm(`Apakah Anda yakin ingin menghapus kompetensi "${judul}"?`)) {
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

    // Export to Excel
    function exportToExcel() {
        alert('Fitur export ke Excel akan diimplementasikan');
        // Implementasi export bisa menggunakan library seperti SheetJS
    }

    // Refresh chart function
    window.refreshChart = initializeChart;
</script>

<style>
    .box-bg {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }
</style>
@endsection