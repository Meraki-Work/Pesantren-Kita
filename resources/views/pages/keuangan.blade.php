@extends('index')

@section('title', 'Keuangan-PesantrenKita')

@section('content')
<div class="flex bg-gray-100">
    <x-sidemenu title="PesantrenKita" />

    <main class="flex-1 p-6 overflow-hidden">
        <div class="">
            {{ $slot ?? '' }}

            {{-- Card Total Kas, Pemasukan, dan Saldo --}}
            @php
            // Hitung total pemasukan dan pengeluaran
            $totalPemasukan = $data->where('status', 'Masuk')->sum('jumlah');
            $totalPengeluaran = $data->where('status', 'Keluar')->sum('jumlah');
            $saldo = $totalPemasukan - $totalPengeluaran;
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-3 gap-2 mb-4">
                <!-- Total Kas -->
                <div class="bg-[#344E41] from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-blue-100 text-sm font-medium mb-1">Total Kas</p>
                            <p class="text-2xl font-bold">Rp {{ number_format($saldo, 0, ',', '.') }}</p>
                            <p class="text-blue-100 text-xs mt-2">Saldo saat ini</p>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center">
                        <span class="text-blue-100 text-sm bg-white/20 px-2 py-1 rounded-full">
                            {{ $data->count() }} transaksi
                        </span>
                    </div>
                </div>

                <!-- Total Pemasukan -->
                <div class="bg-gradient-to-r from-[#2ECC71] to-[#17b459] rounded-xl shadow-lg p-6 text-white">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-green-100 text-sm font-medium mb-1">Total Pemasukan</p>
                            <p class="text-2xl font-bold">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</p>
                            <p class="text-green-100 text-xs mt-2">Pendapatan bulan ini</p>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center">
                        <span class="text-green-100 text-sm bg-white/20 px-2 py-1 rounded-full">
                            {{ $data->where('status', 'Masuk')->count() }} transaksi masuk
                        </span>
                    </div>
                </div>
                <!-- Total Pengeluaran -->
                <div class="bg-gradient-to-r from-[#E74C3C] to-[#C0392B] rounded-xl shadow-lg p-6 text-white">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-red-100 text-sm font-medium mb-1">Total Pengeluaran</p>
                            <p class="text-2xl font-bold">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
                            <p class="text-red-100 text-xs mt-2">Biaya bulan ini</p>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center">
                        <span class="text-red-100 text-sm bg-white/20 px-2 py-1 rounded-full">
                            {{ $data->where('status', 'Keluar')->count() }} transaksi keluar
                        </span>
                    </div>
                </div>
            </div>

            {{-- Card Info Kategori --}}
            @php $count = $data->count(); @endphp

            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @if ($count === 0)
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
            @foreach (array_slice(array_map(null, $labels, $values, $sumber_dana), 0, 4) as [$kategori, $total, $sumber])
            <div class="flex flex-col rounded-xl box-bg p-4 hover:shadow-md transition">
                <div class="flex justify-between items-center">
                    <label class="{{ strlen($kategori ?? '') > 14 ? 'text-base sm:text-lg' : 'text-lg sm:text-xl lg:text-2xl' }}">
                        {{ $kategori ?? '-' }}
                    </label>
                </div>
                <div class="">
                    <span class="font-medium text-lg sm:text-xl lg:text-2xl">
                        Rp. {{ number_format($total, 0, ',', '.') }},00
                    </span>
                    <div class="grid">
                        <span class="text-xs sm:text-sm lg:text-sm">{{ $sumber ?? '-' }}</span>
                    </div>
                </div>
            </div>
            @endforeach

            {{-- Tambah placeholder jika < 4 --}}
            @for ($i = $count; $i < 4; $i++)
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
                    <option value="1-bulan" {{ ($filter ?? '1-tahun') == '1-bulan' ? 'selected' : '' }}>1 Bulan</option>
                    <option value="3-bulan" {{ ($filter ?? '1-tahun') == '3-bulan' ? 'selected' : '' }}>3 Bulan</option>
                    <option value="6-bulan" {{ ($filter ?? '1-tahun') == '6-bulan' ? 'selected' : '' }}>6 Bulan</option>
                    <option value="1-tahun" {{ ($filter ?? '1-tahun') == '1-tahun' ? 'selected' : '' }}>1 Tahun</option>
                    <option value="5-tahun" {{ ($filter ?? '1-tahun') == '5-tahun' ? 'selected' : '' }}>5 Tahun</option>
                </select>
            </form>
        </div>
        <div class="relative w-full h-[50vh]">
            <canvas id="cashFlowChart"></canvas>
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
            <canvas id="polarCanvas"></canvas>
        </div>
        <!-- ALOKASI DANA -->
        <div class="mt-4 text-center">
            <p class="font-semibold text-gray-700">Total Alokasi: Rp {{ number_format(array_sum($values ?? []), 0, ',', '.') }}</p>
        </div>
    </div>
</div>

<div class="box-bg p-4 mt-2">
    <x-uang-table :columns="$columns" :rows="$rows" />
    <!-- Pagination Section - FIXED -->
@if($tableData && $tableData->lastPage() > 1)
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const chartData = JSON.parse(`{!! json_encode([
    'dates' => $dates ?? [],
    'values' => $values ?? [],
    'labels' => $labels ?? [],
    'dailyFlow' => $dailyFlow ?? []
]) !!}`);


        console.log('Chart Data:', chartData);

        // ========== CASH FLOW CHART ==========
        const cashFlowCtx = document.getElementById('cashFlowChart');
        if (cashFlowCtx) {
            if (chartData.dates && chartData.dates.length > 0 && chartData.dailyFlow && chartData.dailyFlow.length > 0) {
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
            } else {
                cashFlowCtx.parentElement.innerHTML += '<p class="text-gray-500 text-center py-8">Tidak ada data cash flow untuk periode yang dipilih</p>';
            }
        }

        // ========== POLAR CHART ==========
        const ctxPolar = document.getElementById('polarCanvas');
        if (ctxPolar) {
            // Gunakan data real jika ada, otherwise use dummy data
            const polarLabels = chartData.labels && chartData.labels.length > 0 ? chartData.labels : ['', '', '', ''];
            const polarData = chartData.values && chartData.values.length > 0 ? chartData.values : [];
            
            new Chart(ctxPolar, {
                type: 'polarArea',
                data: {
                    labels: polarLabels,
                    datasets: [{
                        data: polarData,
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

</main>
</div>
@endsection