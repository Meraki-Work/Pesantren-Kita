@extends('index')

@section('title', 'Keuangan-PesantrenKita')

@section('content')
<div class="flex bg-gray-100">
    <x-sidemenu title="PesantrenKita" />

    <main class="flex-1 p-6 overflow-hidden">
        <div class="">
            {{ $slot ?? '' }}

            {{-- Card Info --}}
            @php $count = $data->count(); @endphp

            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @if ($count === 0)
                {{-- Jika kosong total --}}
                @for ($i = 0; $i < 4; $i++)
                    <div class="flex flex-col rounded-xl box-bg p-4 hover:shadow-md transition">
                    <div class="flex justify-between items-center">
                        <label class="text-lg sm:text-xl lg:text-2xl">-</label>
                        <button class="circle-add flex justify-center items-center">
                            <a href="#">
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
            @else
            {{-- Render data yang ada --}}
            {{-- Render data yang ada --}}
            @foreach ($data->sortByDesc('jumlah')->take(4) as $item)
            <div class="flex flex-col rounded-xl box-bg p-4 hover:shadow-md transition">
                <div class="flex justify-between items-center">
                    <label class="{{ strlen($item->Kategori->nama_kategori ?? '') > 9 ? 'text-base sm:text-lg' : 'text-lg sm:text-xl lg:text-2xl' }}">
                        {{ $item->Kategori->nama_kategori ?? '-' }}
                    </label>

                    <button class="p-3 circle-add flex justify-center items-center">
                        <a href="#">
                            <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="#fdfdfd">
                                <path d="M440-440H200v-80h240v-240h80v240h240v80H520v240h-80v-240Z" />
                            </svg>
                        </a>
                    </button>
                </div>
                <div class="my-2">
                    <span class="font-medium text-lg sm:text-xl lg:text-2xl">
                        Rp. {{ number_format($item->jumlah, 0, ',', '.') }},00
                    </span>
                    <div class="grid">
                        <span class="text-mint text-xs sm:text-sm lg:text-base">Sumber</span>
                        <span class="text-xs sm:text-sm lg:text-base">
                            {{ $item->sumber_dana ?? '-' }}
                        </span>
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
                        <a href="#">
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
{{-- Grafik Flex --}}
<div class="flex flex-col lg:flex-row gap-2 mt-6">
    {{-- CASH FLOW --}}
    <div class="flex-[0.65] box-bg p-6 relative">
        <div class="flex justify-between items-center mb-4">
            <h2 class="font-semibold text-gray-800 text-2xl">Cash Flow</h2>
            <select
                class="border border-gray-300 text-gray-700 rounded-lg text-sm px-2 py-1 focus:outline-none focus:ring-2 focus:ring-mint">
                <option>1 Bulan</option>
                <option>3 Bulan</option>
                <option>6 Bulan</option>
                <option>1 Tahun</option>
            </select>
        </div>
        <div class="relative w-full h-[50vh]">
            <canvas id="myChart"></canvas>
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

        <div class="mt-4 text-center">
            <p class="font-semibold text-gray-700">Rp. 40.121.481,00</p>
        </div>
    </div>
</div>

<div class="box-bg p-4 mt-2">
    <h1 class="text-xl font-bold mb-4">Data Keuangan</h1>
    <x-dynamic-table :columns="$columns" :rows="$rows" />

</div>
{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const chartData = JSON.parse(`{!! json_encode([
                'labels' => $labels ?? [],
                'values' => $values ?? [],
                'dates' => $dates ?? []
            ]) !!}`);

        // ========== CASH FLOW ==========
        const ctx = document.getElementById('myChart');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData.dates.length ? chartData.dates : ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                datasets: [{
                    label: 'Total Cash',
                    data: chartData.values.length ? chartData.values : [15000000, 32000000, 48000000, 62000000, 58000000, 67000000],
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16,185,129,0.1)',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#10b981',
                    pointRadius: 5,
                    pointHoverRadius: 8
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
                        displayColors: false,
                        backgroundColor: '#fff',
                        titleColor: '#111',
                        bodyColor: '#1E3932',
                        borderColor: '#10b981',
                        borderWidth: 1,
                        callbacks: {
                            title: (context) => 'Total Kas: ' + context[0].label,
                            label: (ctx) => 'Rp ' + ctx.parsed.y.toLocaleString('id-ID') + ',00'
                        }
                    }
                },
                scales: {
                    y: {
                        grid: {
                            color: '#f1f1f1'
                        },
                        ticks: {
                            color: '#555',
                            callback: function(value) {
                                // ubah angka jadi format Rupiah
                                return 'Rp ' + value.toLocaleString('id-ID', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                });
                            }

                        }
                    },
                    x: {
                        ticks: {
                            color: '#555'
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // ========== POLAR CHART ==========
        const ctxPolar = document.getElementById('polarCanvas');
        new Chart(ctxPolar, {
            type: 'polarArea',
            data: {
                labels: chartData.labels.length ? chartData.labels : ['Pendidikan', 'Aset', 'Inventaris', 'Katering'],
                datasets: [{
                    data: chartData.values.length ? chartData.values : [20, 25, 30, 25],
                    backgroundColor: [
                        'rgba(147, 197, 253, 0.8)',
                        'rgba(110, 231, 183, 0.8)',
                        'rgba(216, 180, 254, 0.8)',
                        'rgba(253, 224, 71, 0.8)',
                    ],
                    borderColor: [
                        'rgb(147, 197, 253)',
                        'rgb(110, 231, 183)',
                        'rgb(216, 180, 254)',
                        'rgb(253, 224, 71)'
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
                            color: '#444'
                        }
                    }
                }
            }
        });
    });
</script>

</main>
</div>
@endsection