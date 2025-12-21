@extends('index')

@section('title', 'Keuangan-PesantrenKita')

@section('content')
<div class="flex bg-gray-100">
    <x-sidemenu title="PesantrenKita" />

    <main class="flex-1 p-6 overflow-hidden">
        <div class="">
            {{ $slot ?? '' }}

            {{-- Card Total Kas, Pemasukan, dan Saldo --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-2 mb-4">
                <!-- Total Kas -->
                <div class="bg-[#344E41] from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-blue-100 text-sm font-medium mb-1">Total Kas</p>
                            <p class="text-2xl font-bold">Rp {{ number_format($saldo ?? 0, 0, ',', '.') }}</p>
                            <p class="text-blue-100 text-xs mt-2">Saldo saat ini</p>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center">
                        <span class="text-blue-100 text-sm bg-white/20 px-2 py-1 rounded-full">
                            {{ $totalTransaksi ?? 0 }} transaksi
                        </span>
                    </div>
                </div>

                <!-- Total Pemasukan -->
                <div class="bg-gradient-to-r from-[#2ECC71] to-[#17b459] rounded-xl shadow-lg p-6 text-white">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-green-100 text-sm font-medium mb-1">Total Pemasukan</p>
                            <p class="text-2xl font-bold">Rp {{ number_format($totalPemasukan ?? 0, 0, ',', '.') }}</p>
                            <p class="text-green-100 text-xs mt-2">Pendapatan bulan ini</p>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center">
                        <span class="text-green-100 text-sm bg-white/20 px-2 py-1 rounded-full">
                            {{-- Hitung transaksi masuk --}}
                            @php
                            $transaksiMasuk = 0;
                            if (isset($tableData)) {
                            $transaksiMasuk = $tableData->where('status', 'Masuk')->count();
                            }
                            @endphp
                            {{ $transaksiMasuk }} transaksi masuk
                        </span>
                    </div>
                </div>
                <!-- Total Pengeluaran -->
                <div class="bg-gradient-to-r from-[#E74C3C] to-[#C0392B] rounded-xl shadow-lg p-6 text-white">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-red-100 text-sm font-medium mb-1">Total Pengeluaran</p>
                            <p class="text-2xl font-bold">Rp {{ number_format($totalPengeluaran ?? 0, 0, ',', '.') }}</p>
                            <p class="text-red-100 text-xs mt-2">Biaya bulan ini</p>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center">
                        <span class="text-red-100 text-sm bg-white/20 px-2 py-1 rounded-full">
                            {{-- Hitung transaksi keluar --}}
                            @php
                            $transaksiKeluar = 0;
                            if (isset($tableData)) {
                            $transaksiKeluar = $tableData->where('status', 'Keluar')->count();
                            }
                            @endphp
                            {{ $transaksiKeluar }} transaksi keluar
                        </span>
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Halaman Keuangan</h1>
                        <p class="text-gray-600 mt-1">Catat setiap transaksi dan kategorikan sumber dana dengan efisien.</p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <!-- Tombol View Landing Page -->
                        <a href="{{ route('keuangan.create') }}"
                            class="bg-[#2ECC71] text-white text-sm font-semibold rounded-lg hover:bg-green-600 transition inline-flex items-center justify-center px-4 py-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Keuangan
                        </a>
                        <a href="{{ route('kategori.create') }}"
                            class="bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition inline-flex items-center justify-center px-4 py-2">
                            Tambah Kategori
                        </a>
                        <a href="{{ route('keuangan.import.form') }}"
                            class="bg-white border border-indigo-600 text-indigo-700 text-sm hover:bg-gray-100 font-semibold rounded-lg  transition inline-flex items-center justify-center px-4 py-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 20v-8.15M8.5 15.35L12 11.85l3.5 3.5M4 8V6a2 2 0 012-2h12a2 2 0 012 2v2" />
                            </svg>
                            Import Keuangan
                        </a>
                    </div>
                </div>

                <!-- Info Ponpes -->

            </div>


            {{-- Card Info Kategori --}}
            @php
            $count = isset($labels) ? count($labels) : 0;
            $hasData = $count > 0;
            @endphp

            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @if (!$hasData)
                {{-- Jika kosong total --}}
                @for ($i = 0; $i < 1; $i++)
                    <div class="flex flex-col rounded-xl box-bg p-4 hover:shadow-md transition">
                    <div class="flex justify-between items-center">
                        <label class="text-lg sm:text-xl lg:text-sm">Klik untuk menambahkan Kategori</label>
                        <button class="circle-add flex justify-center items-center">
                            <a href="{{ route('kategori.create')}}">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px"
                                    viewBox="0 -960 960 960" width="24px" fill="#fdfdfd">
                                    <path d="M440-440H200v-80h240v-240h80v240h240v80H520v240h-80v-240Z" />
                                </svg>
                            </a>
                        </button>
                    </div>
            </div>
            @endfor
            @else
            {{-- Render data yang ada --}}
            @php
            // Pastikan semua array memiliki length yang sama
            $maxItems = min($count, 4);
            $slicedLabels = array_slice($labels ?? [], 0, $maxItems);
            $slicedValues = array_slice($values ?? [], 0, $maxItems);
            $slicedSumber = array_slice($sumber_dana ?? [], 0, $maxItems);
            @endphp

            @for ($i = 0; $i < $maxItems; $i++)
                <div class="flex flex-col rounded-xl box-bg p-4 hover:shadow-md transition">
                <div class="flex justify-between items-center">
                    <label class="{{ strlen($slicedLabels[$i] ?? '') > 14 ? 'text-base sm:text-lg' : 'text-lg sm:text-xl lg:text-2xl' }}">
                        {{ $slicedLabels[$i] ?? '-' }}
                    </label>
                </div>
                <div class="">
                    <span class="font-medium text-lg sm:text-xl lg:text-2xl">
                        Rp. {{ isset($slicedValues[$i]) ? number_format($slicedValues[$i], 0, ',', '.') : '0' }},00
                    </span>
                    <div class="grid">
                        <span class="text-xs sm:text-sm lg:text-sm">{{ $slicedSumber[$i] ?? '-' }}</span>
                    </div>
                </div>
        </div>
        @endfor

        {{-- Tambah placeholder jika < 4 --}}
        @for ($i = $maxItems; $i < 4; $i++)
            <div class="flex flex-col rounded-xl box-bg p-4 hover:shadow-md transition">
            <div class="flex justify-between items-center">
                <label class="text-lg sm:text-xl lg:text-2xl">-</label>
                <button class="circle-add flex justify-center items-center">
                    <a href="{{ route('kategori.create')}}">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px"
                            viewBox="0 -960 960 960" width="24px" fill="#fdfdfd">
                            <path d="M440-440H200v-80h240v-240h80v240h240v80H520v240h-80v-240Z" />
                        </svg>
                    </a>
                </button>
            </div>
            <div class="my-2">
                <span class="font-medium text-lg sm:text-xl lg:text-2xl">Rp. -</span>
                <div class="grid">
                    <span class="text-mint text-xs sm:text-sm lg:text-base">Sumber</span>
                    <span class="text-xs sm:text-sm lg:text-base">-</span>
                </div>
            </div>
</div>
@endfor
@endif
</div>

{{-- Grafik Flex dan sisanya... --}}
<div class="flex flex-col lg:flex-row gap-2 mt-6">
    {{-- CASH FLOW --}}
    <div class="flex-[0.65] box-bg p-6 relative">
        <div class="flex justify-between items-center mb-4">
            <h2 class="font-semibold text-gray-800 text-2xl">Cash Flow</h2>
            <form id="filterForm" method="GET" action="{{ route('keuangan.index') }}">
                <select name="filter"
                    onchange="document.getElementById('filterForm').submit()"
                    class="border border-gray-300 text-gray-700 rounded-lg text-sm px-2 py-1 focus:outline-none focus:ring-2 focus:ring-mint">
                    <option value="hari-ini" {{ ($filter ?? '1-tahun') == 'hari-ini' ? 'selected' : '' }}>Hari Ini</option>
                    <option value="minggu-ini" {{ ($filter ?? '1-tahun') == 'minggu-ini' ? 'selected' : '' }}>Minggu Ini</option>
                    <option value="1-bulan" {{ ($filter ?? '1-tahun') == '1-bulan' ? 'selected' : '' }}>1 Bulan</option>
                    <option value="3-bulan" {{ ($filter ?? '1-tahun') == '3-bulan' ? 'selected' : '' }}>3 Bulan</option>
                    <option value="6-bulan" {{ ($filter ?? '1-tahun') == '6-bulan' ? 'selected' : '' }}>6 Bulan</option>
                    <option value="1-tahun" {{ ($filter ?? '1-tahun') == '1-tahun' ? 'selected' : '' }}>1 Tahun</option>
                    <option value="5-tahun" {{ ($filter ?? '1-tahun') == '5-tahun' ? 'selected' : '' }}>5 Tahun</option>
                </select>
            </form>
        </div>
        <div class="relative w-full h-[50vh]">
            @if(!empty($dates) && !empty($dailyFlow))
            <canvas id="cashFlowChart"></canvas>
            @else
            <div class="flex items-center justify-center h-full">
                <div class="text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <p class="mt-2 text-gray-500">Tidak ada data cash flow untuk periode yang dipilih</p>
                    <p class="text-sm text-gray-400">Coba pilih periode lain atau tambah transaksi baru</p>
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- ALOKASI ASSET --}}
    <div class="flex-[0.35] box-bg p-6 flex flex-col justify-between">
        <div class="flex justify-between items-center mb-4">
            <h2 class="font-semibold text-gray-800 text-2xl">Alokasi Asset</h2>
            <select
                class="border border-gray-300 text-gray-700 rounded-lg text-sm px-2 py-1 focus:outline-none focus:ring-2 focus:ring-mint">
                <option>6 months</option>
                <option>1 year</option>
            </select>
        </div>

        <div class="relative w-full h-[40vh]">
            @if(!empty($labels) && !empty($values))
            <canvas id="polarCanvas"></canvas>
            @else
            <div class="flex items-center justify-center h-full">
                <div class="text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                    </svg>
                    <p class="mt-2 text-gray-500">Tidak ada data alokasi</p>
                    <p class="text-sm text-gray-400">Tambah transaksi dengan kategori untuk melihat data</p>
                </div>
            </div>
            @endif
        </div>
        <!-- ALOKASI DANA -->
        <div class="mt-4 text-center">
            @php
            $totalAlokasi = isset($values) ? array_sum($values) : 0;
            @endphp
            <p class="font-semibold text-gray-700">Total Alokasi: Rp {{ number_format($totalAlokasi, 0, ',', '.') }}</p>
        </div>
    </div>
</div>

<div class="box-bg p-4 mt-2">
    @if(isset($columns) && isset($rows) && !empty($rows))
    <x-uang-table :columns="$columns" :rows="$rows" />
    @else
    <div class="text-center py-8">
        <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
        </svg>
        <p class="mt-2 text-lg font-medium text-gray-900">Belum ada transaksi</p>
        <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan transaksi keuangan pertama Anda</p>
        <a href="{{ route('keuangan.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Transaksi
        </a>
    </div>
    @endif

    <!-- Pagination Section - FIXED -->
    @if(isset($tableData) && $tableData->lastPage() > 1)
    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200" style="display: block !important;">
        <div class="flex flex-col sm:flex-row items-center justify-between space-y-3 sm:space-y-0">
            <!-- Info Jumlah Data -->
            <div class="text-sm text-gray-600">
                Menampilkan
                <span class="font-medium">{{ $tableData->firstItem() }}</span>
                -
                <span class="font-medium">{{ $tableData->lastItem() }}</span>
                dari
                <span class="font-medium">{{ $tableData->total() }}</span>
                transaksi
            </div>

            <!-- Simple Pagination -->
            <div class="flex items-center space-x-2">
                <!-- Previous Button -->
                @if($tableData->onFirstPage())
                <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 border border-gray-300 rounded-lg cursor-not-allowed inline-flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Sebelumnya
                </span>
                @else
                <a href="{{ $tableData->previousPageUrl() }}"
                    class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-200 inline-flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Sebelumnya
                </a>
                @endif

                <!-- Page Info -->
                <span class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg">
                    Halaman {{ $tableData->currentPage() }} dari {{ $tableData->lastPage() }}
                </span>

                <!-- Next Button -->
                @if($tableData->hasMorePages())
                <a href="{{ $tableData->nextPageUrl() }}"
                    class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-200 inline-flex items-center">
                    Selanjutnya
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
                @else
                <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 border border-gray-300 rounded-lg cursor-not-allowed inline-flex items-center">
                    Selanjutnya
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </span>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>

{{-- Chart.js --}}
@if((!empty($dates) && !empty($dailyFlow)) || (!empty($labels) && !empty($values)))
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const chartData = {
            dates: @json($dates ?? []),
            values: @json($values ?? []),
            labels: @json($labels ?? []),
            dailyFlow: @json($dailyFlow ?? [])
        };

        console.log('Chart Data Loaded:', chartData);

        // ========== CASH FLOW CHART ==========
        const cashFlowCtx = document.getElementById('cashFlowChart');
        if (cashFlowCtx && chartData.dates.length > 0 && chartData.dailyFlow.length > 0) {
            new Chart(cashFlowCtx, {
                type: 'line',
                data: {
                    labels: chartData.dates,
                    datasets: [{
                        label: 'Saldo Akumulatif',
                        data: chartData.dailyFlow,
                        borderColor: '#2ECC71',
                        backgroundColor: 'rgba(46, 204, 113, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#2ECC71',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Saldo: Rp ' + context.raw.toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }
            });
        }

        // ========== POLAR CHART ==========
        const ctxPolar = document.getElementById('polarCanvas');
        if (ctxPolar && chartData.labels.length > 0 && chartData.values.length > 0) {
            new Chart(ctxPolar, {
                type: 'polarArea',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        data: chartData.values,
                        backgroundColor: [
                            'rgba(147, 197, 253, 0.8)',
                            'rgba(110, 231, 183, 0.8)',
                            'rgba(216, 180, 254, 0.8)',
                            'rgba(253, 224, 71, 0.8)',
                            'rgba(248, 113, 113, 0.8)',
                            'rgba(156, 163, 175, 0.8)'
                        ],
                        borderColor: [
                            'rgb(147, 197, 253)',
                            'rgb(110, 231, 183)',
                            'rgb(216, 180, 254)',
                            'rgb(253, 224, 71)',
                            'rgb(248, 113, 113)',
                            'rgb(156, 163, 175)'
                        ],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        r: {
                            ticks: {
                                display: false
                            },
                            grid: {
                                color: '#f1f1f1'
                            },
                            angleLines: {
                                color: '#e5e7eb'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                pointStyle: 'circle',
                                color: '#444',
                                padding: 15
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return label + ': Rp ' + value.toLocaleString('id-ID') + ' (' + percentage + '%)';
                                }
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endif

</main>
</div>
@endsection