@extends('index')

@section('title', 'Riwayat Absensi')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header dengan Breadcrumb -->
    <div class="mb-6">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-emerald-600">
                        <i class="fas fa-home mr-2"></i>
                        Dashboard
                    </a>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400"></i>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Riwayat Absensi</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800">
                    <i class="fas fa-history mr-2 text-emerald-600"></i>Riwayat Absensi
                </h1>
                <p class="text-gray-600 mt-2">Daftar lengkap riwayat kehadiran Anda</p>
            </div>
            
            <!-- Quick Stats -->
            <div class="mt-4 md:mt-0 flex items-center space-x-2">
                <div class="relative" x-data="{ showTooltip: false }">
                    <button @mouseenter="showTooltip = true" @mouseleave="showTooltip = false"
                            class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition flex items-center">
                        <i class="fas fa-chart-pie mr-2"></i>
                        Statistik
                    </button>
                    <div x-show="showTooltip" x-transition class="absolute z-10 right-0 mt-2 w-64 bg-white rounded-lg shadow-lg border border-gray-200 p-4">
                        <div class="grid grid-cols-2 gap-3">
                            <div class="text-center">
                                <div class="text-xl font-bold text-emerald-600">{{ $totalAbsensi }}</div>
                                <div class="text-xs text-gray-500">Total Absensi</div>
                            </div>
                            <div class="text-center">
                                <div class="text-xl font-bold text-emerald-600">{{ $persentaseKehadiran }}%</div>
                                <div class="text-xs text-gray-500">Kehadiran Bulan Ini</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <a href="{{ route('dashboard') }}" 
                   class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    Absen Hari Ini
                </a>
            </div>
        </div>

        <!-- User Info Card -->
        <div class="bg-gradient-to-r from-emerald-50 to-blue-50 rounded-xl p-6 border border-emerald-100 shadow-sm mb-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-md">
                        <span class="text-2xl font-bold text-white">
                            {{ strtoupper(substr(Auth::user()->username, 0, 1)) }}
                        </span>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">{{ Auth::user()->username }}</h2>
                        <div class="flex items-center space-x-3 mt-2">
                            @php
                                $roleColors = [
                                    'Admin' => 'bg-red-100 text-red-800',
                                    'Pengajar' => 'bg-blue-100 text-blue-800',
                                    'Keuangan' => 'bg-yellow-100 text-yellow-800',
                                    'Super' => 'bg-purple-100 text-purple-800'
                                ];
                                $roleClass = $roleColors[Auth::user()->role] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="px-3 py-1 rounded-full text-sm font-medium {{ $roleClass }}">
                                <i class="fas fa-user-tag mr-1"></i> {{ Auth::user()->role }}
                            </span>
                            <span class="text-gray-500 text-sm">
                                <i class="fas fa-calendar-alt mr-1"></i>
                                {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4 md:mt-0">
                    <div class="text-right">
                        <p class="text-sm text-gray-500">Kehadiran Bulan Ini</p>
                        <div class="flex items-center justify-end mt-1">
                            <div class="w-48 bg-gray-200 rounded-full h-2.5">
                                <div class="bg-emerald-600 h-2.5 rounded-full" style="width: {{ min($persentaseKehadiran, 100) }}%"></div>
                            </div>
                            <span class="ml-3 text-lg font-semibold text-gray-700">
                                {{ $persentaseKehadiran }}%
                            </span>
                        </div>
                        <p class="text-xs text-gray-400 mt-1">
                            {{ $hadirBulanIni }} dari {{ $hariKerjaBulanIni }} hari kerja
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 text-center hover:shadow-md transition-shadow">
            <div class="text-3xl font-bold text-emerald-600 mb-1">{{ $hadir }}</div>
            <div class="text-sm text-gray-600 flex items-center justify-center">
                <div class="w-3 h-3 bg-emerald-500 rounded-full mr-2"></div>
                Hadir
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 text-center hover:shadow-md transition-shadow">
            <div class="text-3xl font-bold text-blue-600 mb-1">{{ $izin }}</div>
            <div class="text-sm text-gray-600 flex items-center justify-center">
                <div class="w-3 h-3 bg-blue-500 rounded-full mr-2"></div>
                Izin
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 text-center hover:shadow-md transition-shadow">
            <div class="text-3xl font-bold text-yellow-600 mb-1">{{ $sakit }}</div>
            <div class="text-sm text-gray-600 flex items-center justify-center">
                <div class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></div>
                Sakit
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 text-center hover:shadow-md transition-shadow">
            <div class="text-3xl font-bold text-red-600 mb-1">{{ $alpa ?? 0 }}</div>
            <div class="text-sm text-gray-600 flex items-center justify-center">
                <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                Alpa
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 md:mb-0">
                <i class="fas fa-filter mr-2 text-gray-400"></i>Filter Riwayat
            </h3>
            
            <div class="flex space-x-2">
                <button onclick="printReport()" 
                        class="flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    <i class="fas fa-print mr-2"></i> Cetak Laporan
                </button>
                <button onclick="exportToExcel()" 
                        class="flex items-center px-4 py-2 bg-emerald-50 border border-emerald-200 rounded-lg text-emerald-700 hover:bg-emerald-100 transition">
                    <i class="fas fa-file-excel mr-2"></i> Export Excel
                </button>
            </div>
        </div>
        
        <form method="GET" action="{{ route('dashboard.absensi.riwayat') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Month Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-calendar-month mr-1"></i> Bulan
                </label>
                <select name="month" class="w-full border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="">Semua Bulan</option>
                    @foreach(range(1, 12) as $month)
                        <option value="{{ $month }}" 
                                {{ request('month') == $month ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($month)->translatedFormat('F') }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Year Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-calendar-year mr-1"></i> Tahun
                </label>
                <select name="year" class="w-full border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="">Semua Tahun</option>
                    @foreach(range(date('Y') - 2, date('Y')) as $year)
                        <option value="{{ $year }}" 
                                {{ request('year') == $year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-check-circle mr-1"></i> Status
                </label>
                <select name="status" class="w-full border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="">Semua Status</option>
                    <option value="Hadir" {{ request('status') == 'Hadir' ? 'selected' : '' }}>Hadir</option>
                    <option value="Izin" {{ request('status') == 'Izin' ? 'selected' : '' }}>Izin</option>
                    <option value="Sakit" {{ request('status') == 'Sakit' ? 'selected' : '' }}>Sakit</option>
                    <option value="Alpa" {{ request('status') == 'Alpa' ? 'selected' : '' }}>Alpa</option>
                </select>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex items-end space-x-2">
                <button type="submit" 
                        class="w-full bg-emerald-600 text-white px-4 py-2.5 rounded-lg hover:bg-emerald-700 transition font-medium">
                    <i class="fas fa-filter mr-2"></i> Terapkan Filter
                </button>
                <a href="{{ route('dashboard.absensi.riwayat') }}" 
                   class="px-4 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                    <i class="fas fa-redo"></i>
                </a>
            </div>
        </form>

        <!-- Active Filters Badge -->
        @if(request()->has('month') || request()->has('year') || request()->has('status'))
        <div class="mt-4 pt-4 border-t border-gray-200">
            <div class="flex items-center">
                <span class="text-sm text-gray-600 mr-3">Filter Aktif:</span>
                <div class="flex flex-wrap gap-2">
                    @if(request('month'))
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs flex items-center">
                        Bulan: {{ \Carbon\Carbon::create()->month(request('month'))->translatedFormat('F') }}
                        <a href="{{ route('dashboard.absensi.riwayat', array_merge(request()->except('month'), ['month' => ''])) }}"
                           class="ml-2 text-blue-600 hover:text-blue-800">
                            <i class="fas fa-times"></i>
                        </a>
                    </span>
                    @endif
                    @if(request('year'))
                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs flex items-center">
                        Tahun: {{ request('year') }}
                        <a href="{{ route('dashboard.absensi.riwayat', array_merge(request()->except('year'), ['year' => ''])) }}"
                           class="ml-2 text-green-600 hover:text-green-800">
                            <i class="fas fa-times"></i>
                        </a>
                    </span>
                    @endif
                    @if(request('status'))
                    <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-xs flex items-center">
                        Status: {{ request('status') }}
                        <a href="{{ route('dashboard.absensi.riwayat', array_merge(request()->except('status'), ['status' => ''])) }}"
                           class="ml-2 text-purple-600 hover:text-purple-800">
                            <i class="fas fa-times"></i>
                        </a>
                    </span>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <!-- Table Header -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800">Detail Riwayat Absensi</h3>
                <span class="text-sm text-gray-500">
                    Menampilkan {{ $absensi->count() }} dari {{ $totalAbsensi }} data
                </span>
            </div>
        </div>
        
        <!-- Responsive Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-calendar-day mr-2"></i> Tanggal
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-clock mr-2"></i> Jam
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-tag mr-2"></i> Status
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-sticky-note mr-2"></i> Keterangan
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-cogs mr-2"></i> Aksi
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($formattedAbsensi as $item)
                    <tr class="hover:bg-gray-50 transition">
                        <!-- Date Column -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $item->tanggal }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $item->hari }}
                            </div>
                        </td>
                        
                        <!-- Time Column -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $item->jam }}
                            </div>
                        </td>
                        
                        <!-- Status Column -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusColors = [
                                    'Hadir' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                    'Izin' => 'bg-blue-100 text-blue-800 border-blue-200',
                                    'Sakit' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                    'Alpa' => 'bg-red-100 text-red-800 border-red-200'
                                ];
                                $statusClass = $statusColors[$item->status] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                            @endphp
                            <span class="px-3 py-1.5 rounded-full text-xs font-medium border {{ $statusClass }}">
                                @if($item->is_auto_alfa)
                                    <i class="fas fa-robot mr-1"></i>
                                @else
                                    <i class="fas fa-circle text-xs mr-1"></i>
                                @endif
                                {{ $item->status }}
                                @if($item->is_auto_alfa)
                                    <span class="ml-1 text-xs opacity-75">(Auto)</span>
                                @endif
                            </span>
                        </td>
                        
                        <!-- Keterangan Column -->
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 max-w-xs">
                                {{ $item->keterangan }}
                            </div>
                            @if($item->is_auto_alfa)
                            <div class="text-xs text-red-600 mt-1">
                                <i class="fas fa-info-circle mr-1"></i> Auto generated
                            </div>
                            @endif
                        </td>
                        
                        <!-- Action Column -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <button onclick="showDetail({{ $item->id_absensi }})"
                                        class="text-blue-600 hover:text-blue-900 transition p-1.5 rounded hover:bg-blue-50"
                                        title="Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                
                                <!-- Tombol untuk update Auto Alfa hari ini -->
                                @if($item->is_auto_alfa && \Carbon\Carbon::parse($absensi->first()->tanggal ?? null)->isToday())
                                <button onclick="updateAutoAlfa({{ $item->id_absensi }})"
                                        class="text-emerald-600 hover:text-emerald-900 transition p-1.5 rounded hover:bg-emerald-50"
                                        title="Update Status">
                                    <i class="fas fa-edit"></i>
                                </button>
                                @endif
                                
                                <!-- Tombol checkout untuk hari ini -->
                                @if($item->status == 'Hadir' && \Carbon\Carbon::parse($absensi->first()->tanggal ?? null)->isToday())
                                <button onclick="checkout({{ $item->id_absensi }})"
                                        class="text-red-600 hover:text-red-900 transition p-1.5 rounded hover:bg-red-50"
                                        title="Checkout">
                                    <i class="fas fa-sign-out-alt"></i>
                                </button>
                                @endif
                                
                                <!-- Tombol hapus untuk admin -->
                                @if(in_array(Auth::user()->role, ['Admin', 'Super']) && !\Carbon\Carbon::parse($absensi->first()->tanggal ?? null)->isToday())
                                <button onclick="confirmDelete({{ $item->id_absensi }})"
                                        class="text-gray-600 hover:text-red-900 transition p-1.5 rounded hover:bg-red-50"
                                        title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="text-gray-400 mb-4">
                                <i class="fas fa-clipboard-list text-5xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-500 mb-2">Belum ada data absensi</h3>
                            <p class="text-gray-400 mb-4">Data absensi akan muncul setelah Anda melakukan absensi pertama</p>
                            <a href="{{ route('dashboard') }}" 
                               class="inline-flex items-center px-5 py-2.5 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition font-medium">
                                <i class="fas fa-check-circle mr-2"></i> Absen Sekarang
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Table Footer dengan Pagination -->
        @if($absensi->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            <div class="flex flex-col md:flex-row md:items-center justify-between">
                <div class="text-sm text-gray-500 mb-4 md:mb-0">
                    Menampilkan {{ $absensi->firstItem() ?? 0 }} - {{ $absensi->lastItem() ?? 0 }} dari {{ $absensi->total() }} data
                </div>
                
                <!-- Pagination -->
                <div class="flex items-center space-x-1">
                    <!-- First Page -->
                    @if($absensi->onFirstPage())
                    <span class="px-3 py-1.5 border border-gray-300 text-gray-400 rounded-lg cursor-not-allowed">
                        <i class="fas fa-angle-double-left"></i>
                    </span>
                    @else
                    <a href="{{ $absensi->url(1) }}" 
                       class="px-3 py-1.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        <i class="fas fa-angle-double-left"></i>
                    </a>
                    @endif
                    
                    <!-- Previous Page -->
                    @if($absensi->onFirstPage())
                    <span class="px-3 py-1.5 border border-gray-300 text-gray-400 rounded-lg cursor-not-allowed">
                        <i class="fas fa-chevron-left"></i>
                    </span>
                    @else
                    <a href="{{ $absensi->previousPageUrl() }}" 
                       class="px-3 py-1.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                    @endif
                    
                    <!-- Page Numbers -->
                    @foreach(range(1, min(5, $absensi->lastPage())) as $page)
                        @if($page == $absensi->currentPage())
                        <span class="px-3 py-1.5 bg-emerald-600 text-white rounded-lg font-medium">
                            {{ $page }}
                        </span>
                        @else
                        <a href="{{ $absensi->url($page) }}" 
                           class="px-3 py-1.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                            {{ $page }}
                        </a>
                        @endif
                    @endforeach
                    
                    <!-- Next Page -->
                    @if($absensi->hasMorePages())
                    <a href="{{ $absensi->nextPageUrl() }}" 
                       class="px-3 py-1.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                    @else
                    <span class="px-3 py-1.5 border border-gray-300 text-gray-400 rounded-lg cursor-not-allowed">
                        <i class="fas fa-chevron-right"></i>
                    </span>
                    @endif
                    
                    <!-- Last Page -->
                    @if($absensi->hasMorePages())
                    <a href="{{ $absensi->url($absensi->lastPage()) }}" 
                       class="px-3 py-1.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        <i class="fas fa-angle-double-right"></i>
                    </a>
                    @else
                    <span class="px-3 py-1.5 border border-gray-300 text-gray-400 rounded-lg cursor-not-allowed">
                        <i class="fas fa-angle-double-right"></i>
                    </span>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Statistics Section -->
    <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Monthly Chart -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Statistik Bulan Ini</h3>
                <span class="text-sm text-gray-500">
                    {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}
                </span>
            </div>
            <div class="h-64">
                <canvas id="monthlyChart"></canvas>
            </div>
        </div>
        
        <!-- Quick Info Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Kehadiran</h3>
            
            <div class="space-y-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0 mt-1">
                        <i class="fas fa-clock text-emerald-600"></i>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-sm font-medium text-gray-900">Jam Absensi</h4>
                        <p class="mt-1 text-sm text-gray-600">
                            Absensi dapat dilakukan dari jam <span class="font-semibold">05:00 - 17:00 WIB</span>
                        </p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="flex-shrink-0 mt-1">
                        <i class="fas fa-robot text-red-600"></i>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-sm font-medium text-gray-900">Sistem Auto Alfa</h4>
                        <p class="mt-1 text-sm text-gray-600">
                            Jika tidak absen sampai jam 17:00, sistem akan otomatis mencatat sebagai <span class="font-semibold">Alpa</span>.
                            Status ini dapat diubah menjadi Hadir/Izin/Sakit.
                        </p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="flex-shrink-0 mt-1">
                        <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-sm font-medium text-gray-900">Catatan Penting</h4>
                        <ul class="mt-1 text-sm text-gray-600 list-disc list-inside space-y-1">
                            <li>Data absensi diperbarui secara realtime</li>
                            <li>Hanya admin yang dapat menghapus data lama</li>
                            <li>Auto Alfa hanya untuk hari yang bersangkutan</li>
                            <li>Hubungi admin untuk koreksi data</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Legend Section -->
    <div class="mt-6 bg-gray-50 rounded-xl p-6 border border-gray-200">
        <h4 class="text-sm font-semibold text-gray-700 mb-3">Keterangan Status:</h4>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <div class="flex items-center">
                <div class="w-3 h-3 bg-emerald-500 rounded-full mr-2"></div>
                <span class="text-sm text-gray-600">Hadir</span>
            </div>
            <div class="flex items-center">
                <div class="w-3 h-3 bg-blue-500 rounded-full mr-2"></div>
                <span class="text-sm text-gray-600">Izin</span>
            </div>
            <div class="flex items-center">
                <div class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></div>
                <span class="text-sm text-gray-600">Sakit</span>
            </div>
            <div class="flex items-center">
                <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                <span class="text-sm text-gray-600">Alpa</span>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Detail View -->
<div id="detailModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl w-full max-w-md max-h-[90vh] overflow-auto">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-800">Detail Absensi</h3>
                <button onclick="closeDetailModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="detailContent" class="space-y-4"></div>
        </div>
    </div>
</div>

<!-- Modal for Update Auto Alfa -->
<div id="updateModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl w-full max-w-md max-h-[90vh] overflow-auto">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-800">Update Status Absensi</h3>
                <button onclick="closeUpdateModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="updateContent" class="space-y-4">
                <p class="text-gray-600">Pilih status baru untuk absensi hari ini:</p>
                <div class="space-y-3">
                    <div class="flex items-center">
                        <input type="radio" id="statusHadir" name="status" value="Hadir" class="mr-3">
                        <label for="statusHadir" class="flex items-center">
                            <span class="w-3 h-3 bg-emerald-500 rounded-full mr-2"></span>
                            <span>Hadir</span>
                        </label>
                    </div>
                    <div class="flex items-center">
                        <input type="radio" id="statusIzin" name="status" value="Izin" class="mr-3">
                        <label for="statusIzin" class="flex items-center">
                            <span class="w-3 h-3 bg-blue-500 rounded-full mr-2"></span>
                            <span>Izin</span>
                        </label>
                    </div>
                    <div class="flex items-center">
                        <input type="radio" id="statusSakit" name="status" value="Sakit" class="mr-3">
                        <label for="statusSakit" class="flex items-center">
                            <span class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></span>
                            <span>Sakit</span>
                        </label>
                    </div>
                </div>
                <div>
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                    <textarea id="keterangan" rows="3" class="w-full border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500" 
                              placeholder="Masukkan keterangan (opsional)"></textarea>
                </div>
                <div class="flex justify-end space-x-3 pt-4">
                    <button onclick="closeUpdateModal()" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                        Batal
                    </button>
                    <button onclick="processUpdate()" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">
                        Update
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
    }
    .hover-scale {
        transition: transform 0.2s ease;
    }
    .hover-scale:hover {
        transform: translateY(-2px);
    }
    .page-link {
        transition: all 0.2s ease;
    }
    .page-link:hover {
        background-color: #f3f4f6;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
<script>
    // Global variables
    let currentAbsensiId = null;
    let monthlyChart = null;

    // Initialize Flatpickr for date inputs
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize date inputs if any
        const dateInputs = document.querySelectorAll('input[type="date"]');
        dateInputs.forEach(input => {
            flatpickr(input, {
                locale: "id",
                dateFormat: "Y-m-d",
                maxDate: "today"
            });
        });

        // Initialize Monthly Chart
        initMonthlyChart();
    });

    // Chart Functions
    function initMonthlyChart() {
        const ctx = document.getElementById('monthlyChart').getContext('2d');
        
        // Data from controller (mock data for now - in real app, get from API)
        const data = {
            labels: ['Hadir', 'Izin', 'Sakit', 'Alpa'],
            datasets: [{
                data: [{{ $hadirBulanIni }}, {{ $izin }}, {{ $sakit }}, {{ $alpa ?? 0 }}],
                backgroundColor: [
                    '#10b981', // emerald
                    '#3b82f6', // blue
                    '#f59e0b', // yellow
                    '#ef4444'  // red
                ],
                borderColor: [
                    '#059669',
                    '#2563eb',
                    '#d97706',
                    '#dc2626'
                ],
                borderWidth: 1
            }]
        };
        
        monthlyChart = new Chart(ctx, {
            type: 'doughnut',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                },
                cutout: '65%'
            }
        });
    }

    // Filter Functions
    function resetFilter() {
        window.location.href = "{{ route('dashboard.absensi.riwayat') }}";
    }

    function printReport() {
        const originalContent = document.body.innerHTML;
        const printContent = document.querySelector('.container').innerHTML;
        
        // Create print document
        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Laporan Absensi - {{ Auth::user()->username }}</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; color: #333; }
                    .print-header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 20px; }
                    .print-header h1 { margin: 0; color: #333; }
                    .print-header p { margin: 5px 0; color: #666; }
                    table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                    th { background-color: #f3f4f6; text-align: left; padding: 12px; border: 1px solid #ddd; }
                    td { padding: 12px; border: 1px solid #ddd; }
                    .status-badge { padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; }
                    .footer { text-align: center; margin-top: 40px; color: #666; font-size: 12px; }
                    .no-print { display: none; }
                    @media print {
                        body { margin: 0; }
                        .page-break { page-break-after: always; }
                    }
                </style>
            </head>
            <body>
                <div class="print-header">
                    <h1>Laporan Riwayat Absensi</h1>
                    <p>Nama: {{ Auth::user()->username }} | Role: {{ Auth::user()->role }}</p>
                    <p>Periode: {{ request('month') ? \Carbon\Carbon::create()->month(request('month'))->translatedFormat('F') : 'Semua Bulan' }} {{ request('year') ? request('year') : 'Semua Tahun' }}</p>
                    <p>Tanggal Cetak: {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }}</p>
                </div>
                ${printContent}
                <div class="footer">
                    <p>Dicetak dari Sistem Manajemen Ponpes - {{ config('app.name') }}</p>
                    <p>Halaman 1 dari 1</p>
                </div>
                <script>
                    window.onload = function() { window.print(); window.close(); }
                <\/script>
            </body>
            </html>
        `);
        printWindow.document.close();
    }

    function exportToExcel() {
        // Prepare data for export
        const rows = [];
        
        // Headers
        rows.push(['Tanggal', 'Hari', 'Jam', 'Status', 'Keterangan', 'Dicatat Pada']);
        
        // Data rows
        @foreach($formattedAbsensi as $item)
        rows.push([
            '{{ $item->tanggal }}',
            '{{ $item->hari }}',
            '{{ $item->jam }}',
            '{{ $item->status }}',
            '{{ str_replace(",", ";", $item->keterangan) }}',
            '{{ $item->created_at }}'
        ]);
        @endforeach
        
        // Convert to CSV
        const csvContent = "data:text/csv;charset=utf-8," + rows.map(row => row.join(",")).join("\n");
        const encodedUri = encodeURI(csvContent);
        const link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", `riwayat_absensi_{{ Auth::user()->username }}_{{ date('Y-m-d') }}.csv`);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    // Detail Functions
    function showDetail(absensiId) {
        currentAbsensiId = absensiId;
        
        // Show loading
        document.getElementById('detailContent').innerHTML = `
            <div class="text-center py-8">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-emerald-600 mx-auto"></div>
                <p class="mt-2 text-gray-500">Memuat data...</p>
            </div>
        `;
        
        document.getElementById('detailModal').classList.remove('hidden');
        
        // Fetch data
        fetch(`/api/absensi/${absensiId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const absensi = data.data;
                    const statusColor = getStatusColor(absensi.status);
                    
                    document.getElementById('detailContent').innerHTML = `
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500">Tanggal</p>
                                    <p class="font-medium text-gray-900">${absensi.tanggal_formatted}</p>
                                    <p class="text-xs text-gray-400">${absensi.hari}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Status</p>
                                    <span class="px-3 py-1 rounded-full text-xs font-medium ${statusColor}">
                                        ${absensi.status}
                                        ${absensi.is_auto_alfa ? ' (Auto)' : ''}
                                    </span>
                                </div>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-500">Jam Absensi</p>
                                <p class="font-medium text-gray-900">${absensi.jam}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-500">Keterangan</p>
                                <p class="font-medium text-gray-900 bg-gray-50 p-3 rounded-lg">${absensi.keterangan}</p>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-200">
                                <div>
                                    <p class="text-sm text-gray-500">Dibuat</p>
                                    <p class="text-xs text-gray-600">${absensi.created_at_formatted}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Diperbarui</p>
                                    <p class="text-xs text-gray-600">${absensi.updated_at_formatted}</p>
                                </div>
                            </div>
                            
                            ${absensi.is_auto_alfa && isToday(absensi.tanggal) ? `
                            <div class="pt-4 border-t border-gray-200">
                                <p class="text-sm text-gray-500 mb-2">Aksi</p>
                                <button onclick="updateAutoAlfa(${absensiId})"
                                        class="w-full px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">
                                    <i class="fas fa-edit mr-2"></i>Update Status
                                </button>
                            </div>
                            ` : ''}
                        </div>
                    `;
                } else {
                    document.getElementById('detailContent').innerHTML = `
                        <div class="text-center py-8">
                            <i class="fas fa-exclamation-triangle text-3xl text-red-500 mb-4"></i>
                            <p class="text-gray-700">${data.message}</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('detailContent').innerHTML = `
                    <div class="text-center py-8">
                        <i class="fas fa-exclamation-triangle text-3xl text-red-500 mb-4"></i>
                        <p class="text-gray-700">Terjadi kesalahan saat memuat data</p>
                    </div>
                `;
            });
    }

    function closeDetailModal() {
        document.getElementById('detailModal').classList.add('hidden');
    }

    // Update Auto Alfa Functions
    function updateAutoAlfa(absensiId) {
        currentAbsensiId = absensiId;
        document.getElementById('updateModal').classList.remove('hidden');
    }

    function closeUpdateModal() {
        document.getElementById('updateModal').classList.add('hidden');
        // Reset form
        document.querySelectorAll('input[name="status"]').forEach(radio => radio.checked = false);
        document.getElementById('keterangan').value = '';
    }

    function processUpdate() {
        const status = document.querySelector('input[name="status"]:checked');
        const keterangan = document.getElementById('keterangan').value;
        
        if (!status) {
            alert('Pilih status terlebih dahulu!');
            return;
        }
        
        if (!confirm('Apakah Anda yakin ingin mengupdate status absensi?')) {
            return;
        }
        
        // Show loading
        const updateBtn = document.querySelector('#updateContent button[onclick="processUpdate()"]');
        const originalText = updateBtn.innerHTML;
        updateBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...';
        updateBtn.disabled = true;
        
        // Send update request
        fetch(`/absensi/${currentAbsensiId}/update`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                status: status.value,
                keterangan: keterangan
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Status absensi berhasil diperbarui!');
                location.reload(); // Reload page to show updated data
            } else {
                alert(data.message || 'Gagal mengupdate status');
                updateBtn.innerHTML = originalText;
                updateBtn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengupdate status');
            updateBtn.innerHTML = originalText;
            updateBtn.disabled = false;
        });
    }

    // Checkout Function
    function checkout(absensiId) {
        if (!confirm('Apakah Anda yakin ingin checkout?')) {
            return;
        }
        
        fetch(`/absensi/${absensiId}/checkout`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Checkout berhasil!');
                location.reload();
            } else {
                alert(data.message || 'Gagal melakukan checkout');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat melakukan checkout');
        });
    }

    // Delete Function (for admin)
    function confirmDelete(absensiId) {
        if (!confirm('Apakah Anda yakin ingin menghapus data absensi ini?\n\nData yang dihapus tidak dapat dikembalikan!')) {
            return;
        }
        
        fetch(`/absensi/${absensiId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Data absensi berhasil dihapus!');
                location.reload();
            } else {
                alert(data.message || 'Gagal menghapus data');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus data');
        });
    }

    // Helper Functions
    function getStatusColor(status) {
        const colors = {
            'Hadir': 'bg-emerald-100 text-emerald-800',
            'Izin': 'bg-blue-100 text-blue-800',
            'Sakit': 'bg-yellow-100 text-yellow-800',
            'Alpa': 'bg-red-100 text-red-800'
        };
        return colors[status] || 'bg-gray-100 text-gray-800';
    }

    function isToday(dateString) {
        const today = new Date().toDateString();
        const compareDate = new Date(dateString).toDateString();
        return today === compareDate;
    }

    // Keyboard shortcuts
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeDetailModal();
            closeUpdateModal();
        }
    });

    // Close modals on outside click
    document.getElementById('detailModal').addEventListener('click', function(event) {
        if (event.target === this) {
            closeDetailModal();
        }
    });

    document.getElementById('updateModal').addEventListener('click', function(event) {
        if (event.target === this) {
            closeUpdateModal();
        }
    });
</script>
@endpush