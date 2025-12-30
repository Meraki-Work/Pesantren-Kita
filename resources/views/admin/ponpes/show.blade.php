@extends('index')

@section('title', 'Detail Pesantren')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Detail Pesantren</h1>
                    <p class="mt-1 text-sm text-gray-600">Informasi lengkap pesantren</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('admin.ponpes.edit', $ponpes->id_ponpes) }}" 
                       class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg shadow-sm hover:shadow-md transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit
                    </a>
                    <a href="{{ route('admin.ponpes.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg shadow-sm hover:shadow-md transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Main Info -->
            <div class="lg:col-span-2">
                <!-- Profile Card -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
                    <!-- Profile Header -->
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-start space-x-4">
                            @if($ponpes->logo_ponpes)
                            <div class="flex-shrink-0">
                                <img src="{{ Storage::url($ponpes->logo_ponpes) }}" 
                                     alt="Logo {{ $ponpes->nama_ponpes }}" 
                                     class="w-20 h-20 object-cover border-4 border-white">
                            </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <h2 class="text-xl font-bold text-gray-900 truncate">{{ $ponpes->nama_ponpes }}</h2>
                                <div class="mt-2 flex flex-wrap items-center gap-3">
                                    <span class="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-800 text-sm font-medium rounded-full">
                                        ID: {{ $ponpes->id_ponpes }}
                                    </span>
                                    <span class="inline-flex items-center px-3 py-1 {{ $ponpes->status == 'Aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} text-sm font-medium rounded-full">
                                        {{ $ponpes->status }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Details Table -->
                    <div class="p-6">
                        <div class="space-y-4">
                            <!-- Row 1 -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <p class="text-sm font-medium text-gray-500 mb-1">Alamat</p>
                                    <p class="text-gray-900">{{ $ponpes->alamat }}</p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <p class="text-sm font-medium text-gray-500 mb-1">Pimpinan</p>
                                    <p class="text-gray-900">{{ $ponpes->pimpinan }}</p>
                                </div>
                            </div>
                            
                            <!-- Row 2 -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <p class="text-sm font-medium text-gray-500 mb-1">Tahun Berdiri</p>
                                    <p class="text-gray-900 font-medium">{{ $ponpes->tahun_berdiri }}</p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <p class="text-sm font-medium text-gray-500 mb-1">Telepon</p>
                                    <p class="text-gray-900 font-medium">{{ $ponpes->telp }}</p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <p class="text-sm font-medium text-gray-500 mb-1">Email</p>
                                    <p class="text-gray-900 font-medium">{{ $ponpes->email }}</p>
                                </div>
                            </div>
                            
                            <!-- Row 3 -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <p class="text-sm font-medium text-gray-500 mb-1">Jumlah Santri</p>
                                    <p class="text-2xl font-bold text-blue-600">{{ number_format($ponpes->jumlah_santri) }} <span class="text-sm font-normal text-gray-600">orang</span></p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <p class="text-sm font-medium text-gray-500 mb-1">Jumlah Staf</p>
                                    <p class="text-2xl font-bold text-green-600">{{ number_format($ponpes->jumlah_staf) }} <span class="text-sm font-normal text-gray-600">orang</span></p>
                                </div>
                            </div>
                            
                            <!-- Row 4 -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <p class="text-sm font-medium text-gray-500 mb-1">Dibuat</p>
                                    <p class="text-gray-900">
                                        @if($ponpes->created_at)
                                            {{ $ponpes->created_at->format('d-m-Y H:i') }}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <p class="text-sm font-medium text-gray-500 mb-1">Diupdate</p>
                                    <p class="text-gray-900">
                                        @if($ponpes->updated_at)
                                            {{ $ponpes->updated_at->format('d-m-Y H:i') }}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics Grid -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Statistik</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <!-- Santri -->
                        <div class="bg-white rounded-xl shadow-sm p-5 text-center hover:shadow-md transition-shadow duration-200">
                            <div class="text-3xl font-bold text-blue-600 mb-2">{{ $statistics['total_santri'] ?? 0 }}</div>
                            <p class="text-sm text-gray-600">Santri</p>
                        </div>
                        
                        <!-- Konten Aktif -->
                        <div class="bg-white rounded-xl shadow-sm p-5 text-center hover:shadow-md transition-shadow duration-200">
                            <div class="text-3xl font-bold text-green-600 mb-2">{{ $statistics['active_contents'] ?? 0 }}</div>
                            <p class="text-sm text-gray-600">Konten Aktif</p>
                        </div>
                        
                        <!-- Gambar -->
                        <div class="bg-white rounded-xl shadow-sm p-5 text-center hover:shadow-md transition-shadow duration-200">
                            <div class="text-3xl font-bold text-purple-600 mb-2">{{ $statistics['total_gambar'] ?? 0 }}</div>
                            <p class="text-sm text-gray-600">Gambar</p>
                        </div>
                        
                        <!-- Total Keuangan -->
                        <div class="bg-white rounded-xl shadow-sm p-5 text-center hover:shadow-md transition-shadow duration-200">
                            <div class="text-3xl font-bold text-orange-600 mb-2">
                                Rp {{ number_format($statistics['total_keuangan'] ?? 0, 0, ',', '.') }}
                            </div>
                            <p class="text-sm text-gray-600">Total Keuangan</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Actions -->
            <div class="space-y-6">
                <!-- Quick Actions Card -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Aksi Cepat</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <a href="{{ route('admin.landing-content.index', ['ponpes_id' => $ponpes->id_ponpes]) }}" 
                           class="inline-flex items-center justify-center w-full px-4 py-3 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg border border-blue-200 hover:border-blue-300 transition-colors duration-200 group">
                            <svg class="w-5 h-5 mr-3 text-blue-600 group-hover:text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span class="font-medium">Kelola Konten</span>
                        </a>
                        
                        <form action="{{ route('admin.ponpes.destroy', $ponpes->id_ponpes) }}" 
                              method="POST" 
                              onsubmit="return confirm('Hapus pesantren ini? Semua data terkait akan hilang.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="inline-flex items-center justify-center w-full px-4 py-3 bg-red-50 hover:bg-red-100 text-red-700 rounded-lg border border-red-200 hover:border-red-300 transition-colors duration-200 group">
                                <svg class="w-5 h-5 mr-3 text-red-600 group-hover:text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                <span class="font-medium">Hapus Pesantren</span>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Landing Page Card -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Landing Page</h3>
                    </div>
                    <div class="p-6">
                        <p class="text-sm text-gray-600 mb-4">Lihat landing page pesantren ini:</p>
                        <a href="{{ route('landing.show', $ponpes->id_ponpes) }}" 
                           target="_blank" 
                           class="inline-flex items-center justify-center w-full px-4 py-3 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white rounded-lg shadow-md hover:shadow-lg transition-all duration-200">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <span class="font-medium">View Landing Page</span>
                        </a>
                    </div>
                </div>

                <!-- Additional Info -->
                <div class="bg-white rounded-xl shadow-sm p-5">
                    <h3 class="text-sm font-semibold text-gray-800 mb-3">Informasi Tambahan</h3>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-start">
                            <svg class="w-4 h-4 mr-2 text-blue-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Data diperbarui terakhir: {{ $ponpes->updated_at ? $ponpes->updated_at->format('d M Y') : '-' }}
                        </li>
                        <li class="flex items-start">
                            <svg class="w-4 h-4 mr-2 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Status: <span class="ml-1 font-medium {{ $ponpes->status == 'Aktif' ? 'text-green-600' : 'text-red-600' }}">{{ $ponpes->status }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection