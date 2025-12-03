@extends('layouts.admin')

@section('title', 'Kelola Konten Landing Page')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow">
        <div class="px-4 py-6 sm:px-6 lg:px-8 max-w-7xl mx-auto">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Konten Landing Page</h1>
                    <p class="mt-1 text-sm text-gray-600">Kelola semua konten yang ditampilkan di halaman depan</p>
                </div>
                <a href="{{ route('admin.landing-content.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Konten
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="px-4 py-8 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <!-- Filter Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Filter & Pencarian</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Filter Pesantren -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Filter Pesantren</label>
                        <select id="filterPonpes"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                            <option value="">Semua Pesantren</option>
                            @foreach($ponpesList as $ponpes)
                            <option value="{{ $ponpes->id_ponpes }}"
                                {{ request('ponpes_id') == $ponpes->id_ponpes ? 'selected' : '' }}>
                                {{ $ponpes->nama_ponpes }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Tipe Konten -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Filter Tipe Konten</label>
                        <select id="filterType"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                            <option value="">Semua Tipe</option>
                            <option value="carousel" {{ request('content_type') == 'carousel' ? 'selected' : '' }}>Carousel</option>
                            <option value="about_founder" {{ request('content_type') == 'about_founder' ? 'selected' : '' }}>Founder</option>
                            <option value="about_leader" {{ request('content_type') == 'about_leader' ? 'selected' : '' }}>Leader</option>
                            <option value="footer" {{ request('content_type') == 'footer' ? 'selected' : '' }}>Footer</option>
                            <option value="section_title" {{ request('content_type') == 'section_title' ? 'selected' : '' }}>Section Title</option>
                        </select>
                    </div>

                    <!-- Pencarian -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pencarian</label>
                        <div class="flex gap-2">
                            <input type="text"
                                id="searchInput"
                                value="{{ request('search') }}"
                                placeholder="Cari judul atau deskripsi..."
                                class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                            <button id="searchBtn"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </button>
                            @if(request()->hasAny(['ponpes_id', 'content_type', 'search']))
                            <a href="{{ route('admin.landing-content.index') }}"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Konten -->
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl p-6 border border-blue-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-500 rounded-lg p-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Konten</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Aktif -->
            <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-xl p-6 border border-green-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-500 rounded-lg p-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Aktif</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['active'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Carousel -->
            <div class="bg-gradient-to-r from-cyan-50 to-cyan-100 rounded-xl p-6 border border-cyan-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-cyan-500 rounded-lg p-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Carousel</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['carousel_count'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Founder & Leader -->
            <div class="bg-gradient-to-r from-amber-50 to-amber-100 rounded-xl p-6 border border-amber-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-amber-500 rounded-lg p-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13 1a6 6 0 01-12 0m12 0a6 6 0 00-12 0" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Founder & Leader</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['founder_count'] + $stats['leader_count'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="mb-8">
            <!-- Content Cards dengan Section Terpisah -->
            <div class="mb-8">
                <!-- Section untuk Carousel -->
                @php($carouselContents = $contents->where('content_type', 'carousel'))
                @if($carouselContents->count() > 0)
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Carousel Images
                            <span class="ml-2 bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                {{ $carouselContents->count() }}
                            </span>
                        </h3>
                        <a href="{{ route('admin.landing-content.create') }}?type=carousel"
                            class="text-sm text-blue-600 hover:text-blue-800 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Carousel
                        </a>
                    </div>
                    <!-- Carousel menggunakan grid-cols-2 untuk gambar landscape -->
                    <div id="carouselGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-3">
                        @foreach($carouselContents as $content)
                        <!-- Carousel Card - Optimized for Landscape Images -->
                        <div class="group bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-lg transition-all duration-300 overflow-hidden hover:-translate-y-1 flex flex-col">
                            <!-- Image Section - Landscape Optimized -->
                            <div class="relative bg-gradient-to-br from-gray-100 to-gray-200 overflow-hidden aspect-[16/9]">
                                @if($content->image)
                                <img src="{{ Storage::url($content->image) }}"
                                    alt="{{ $content->title }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                <div class="absolute top-3 right-3">
                                    <span class="bg-blue-600/90 text-white px-3 py-1.5 rounded-full text-xs font-medium backdrop-blur-sm">
                                        <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        Carousel
                                    </span>
                                </div>
                                @else
                                <div class="w-full h-full flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span class="mt-3 bg-blue-100 text-blue-800 text-xs font-medium px-3 py-1 rounded-full">Carousel</span>
                                </div>
                                @endif
                            </div>

                            <!-- Content Section -->
                            <div class="p-3 flex-grow flex flex-col">
                                <div class="grid justify-between items-start mb-3">
                                    <h6 class="font-bold text-gray-900 truncate pr-2 flex-grow" title="{{ $content->title }}">
                                        {{ $content->title ? Str::limit($content->title, 40) : 'Tanpa Judul' }}
                                    </h6>
                                    <!-- Status Toggle -->
                                    <label class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                                        <input type="checkbox"
                                            data-id="{{ $content->id_content }}"
                                            class="status-toggle sr-only peer"
                                            {{ $content->is_active ? 'checked' : '' }}>
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    </label>
                                </div>

                                @if($content->subtitle)
                                <div class="mb-4 flex-grow">
                                    <p class="text-sm text-gray-600 line-clamp-2">
                                        {{ $content->subtitle }}
                                    </p>
                                </div>
                                @endif

                                <!-- Info -->
                                <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                                    <div class="flex items-center flex-shrink-0 max-w-[50%]">
                                        <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        <span class="truncate" title="{{ $content->ponpes->nama_ponpes ?? 'N/A' }}">
                                            {{ $content->ponpes->nama_ponpes ?? 'N/A' }}
                                        </span>
                                    </div>
                                    <div class="flex items-center flex-shrink-0">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                        </svg>
                                        {{ $content->display_order }}
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex items-center justify-between pt-4 border-t border-gray-100 mt-auto">
                                    <span class="text-xs text-gray-500">
                                        <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $content->created_at->diffForHumans() }}
                                    </span>
                                    <div class="flex items-center space-x-1">
                                        <button class="quick-preview-btn p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                            data-id="{{ $content->id_content }}"
                                            title="Preview">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>
                                        <a href="{{ route('admin.landing-content.edit', $content->id_content) }}"
                                            class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors"
                                            title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            </button>
                                            <button class="delete-btn p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                data-id="{{ $content->id_content }}"
                                                data-title="{{ $content->title }}"
                                                title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Section untuk Founder -->
                @php($founderContents = $contents->where('content_type', 'about_founder'))
                @if($founderContents->count() > 0)
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Founder Profile
                            <span class="ml-2 bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                {{ $founderContents->count() }}
                            </span>
                        </h3>
                        <a href="{{ route('admin.landing-content.create') }}?type=about_founder"
                            class="text-sm text-green-600 hover:text-green-800 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Founder
                        </a>
                    </div>
                    <!-- Founder menggunakan grid-cols-4 karena card lebih kecil -->
                    <div id="founderGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3">
                        @foreach($founderContents as $content)
                        <!-- Founder Card -->
                        <div class="group bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-lg transition-all duration-300 overflow-hidden hover:-translate-y-1">
                            <div class="p-4">
                                <!-- Profile Section -->
                                <div class="relative mb-3">
                                    <div class="mx-auto relative aspect-square max-w-[120px]">
                                        @if($content->image)
                                        <div class="w-full h-full overflow-hidden rounded-full border-4 border-green-500">
                                            <img src="{{ Storage::url($content->image) }}"
                                                alt="{{ $content->title }}"
                                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        </div>
                                        @else
                                        <div class="w-full h-full rounded-full border-4 border-green-200 bg-green-50 flex items-center justify-center">
                                            <svg class="w-8 h-8 text-green-500"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                        @endif

                                        <!-- Type Badge -->
                                        <span class="absolute top-0 right-0 transform translate-x-1/4 -translate-y-1/4 bg-white rounded-full p-1 shadow-md">
                                            <span class="block w-6 h-6 rounded-full bg-green-500 flex items-center justify-center">
                                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </span>
                                        </span>

                                        <!-- Status Toggle -->
                                        <div class="absolute bottom-0 right-0 transform translate-x-1/4 translate-y-1/4">
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input type="checkbox"
                                                    data-id="{{ $content->id_content }}"
                                                    class="status-toggle sr-only peer"
                                                    {{ $content->is_active ? 'checked' : '' }}>
                                                <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-600"></div>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Name & Position -->
                                <div class="text-center mb-3">
                                    <div>
                                        <h5 class="font-bold text-gray-900 text-sm mb-1 truncate" title="{{ $content->title ?? 'Tanpa Nama' }}">
                                            {{ $content->title ?? 'Tanpa Nama' }}
                                        </h5>
                                    </div>
                                    <div class="mt-2">
                                        <p class="text-xs text-gray-600 truncate" title="{{ $content->position ?? 'Tidak ada jabatan' }}">
                                            {{ $content->position ?? 'Tidak ada jabatan' }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Description -->
                                @if($content->subtitle || $content->description)
                                <div class="mb-4">
                                    <p class="text-xs text-gray-500 text-center line-clamp-2">
                                        {{ $content->subtitle ?? Str::limit($content->description, 80) }}
                                    </p>
                                </div>
                                @endif

                                <!-- Info -->
                                <div class="flex items-center justify-between text-xs text-gray-500 mb-4">
                                    <div class="flex items-center max-w-[60%]">
                                        <svg class="w-3 h-3 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        <span class="truncate text-xs">
                                            {{ $content->ponpes->nama_ponpes ?? 'N/A' }}
                                        </span>
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                        </svg>
                                        {{ $content->display_order }}
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex space-x-1.5">
                                    <button class="quick-preview-btn flex-1 py-1.5 px-2 bg-blue-50 text-blue-700 hover:bg-blue-100 rounded-lg font-medium text-xs transition-colors flex items-center justify-center"
                                        data-id="{{ $content->id_content }}">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Lihat
                                    </button>
                                    <a href="{{ route('admin.landing-content.edit', $content->id_content) }}"
                                        class="flex-1 py-1.5 px-2 bg-amber-50 text-amber-700 hover:bg-amber-100 rounded-lg font-medium text-xs transition-colors flex items-center justify-center">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit
                                    </a>
                                    <button class="delete-btn flex-1 py-1.5 px-2 bg-red-50 text-red-700 hover:bg-red-100 rounded-lg font-medium text-xs transition-colors flex items-center justify-center"
                                        data-id="{{ $content->id_content }}"
                                        data-title="{{ $content->title }}">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Section untuk Leader -->
                @php($leaderContents = $contents->where('content_type', 'about_leader'))
                @if($leaderContents->count() > 0)
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Leadership Team
                            <span class="ml-2 bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                {{ $leaderContents->count() }}
                            </span>
                        </h3>
                        <a href="{{ route('admin.landing-content.create') }}?type=about_leader"
                            class="text-sm text-blue-600 hover:text-blue-800 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Leader
                        </a>
                    </div>
                    <!-- Leader menggunakan grid-cols-4 karena card lebih kecil -->
                    <div id="leaderGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3">
                        @foreach($leaderContents as $content)
                        <!-- Leader Card -->
                        <div class="group bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-lg transition-all duration-300 overflow-hidden hover:-translate-y-1">
                            <div class="p-4">
                                <!-- Profile Section -->
                                <div class="relative mb-3">
                                    <div class="mx-auto relative aspect-square max-w-[120px]">
                                        @if($content->image)
                                        <div class="w-full h-full overflow-hidden rounded-full border-4 border-blue-500">
                                            <img src="{{ Storage::url($content->image) }}"
                                                alt="{{ $content->title }}"
                                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        </div>
                                        @else
                                        <div class="w-full h-full rounded-full border-4 border-blue-200 bg-blue-50 flex items-center justify-center">
                                            <svg class="w-8 h-8 text-blue-500"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                        @endif

                                        <!-- Type Badge -->
                                        <span class="absolute top-0 right-0 transform translate-x-1/4 -translate-y-1/4 bg-white rounded-full p-1 shadow-md">
                                            <span class="block w-6 h-6 rounded-full bg-blue-500 flex items-center justify-center">
                                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                                </svg>
                                            </span>
                                        </span>

                                        <!-- Status Toggle -->
                                        <div class="absolute bottom-0 right-0 transform translate-x-1/4 translate-y-1/4">
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input type="checkbox"
                                                    data-id="{{ $content->id_content }}"
                                                    class="status-toggle sr-only peer"
                                                    {{ $content->is_active ? 'checked' : '' }}>
                                                <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-600"></div>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Name & Position -->
                                <div class="text-center mb-3">
                                    <div>
                                        <h5 class="font-bold text-gray-900 text-sm mb-1 truncate" title="{{ $content->title ?? 'Tanpa Nama' }}">
                                            {{ $content->title ?? 'Tanpa Nama' }}
                                        </h5>
                                    </div>
                                    <div class="mt-2">
                                        <p class="text-xs text-gray-600 truncate" title="{{ $content->position ?? 'Tidak ada jabatan' }}">
                                            {{ $content->position ?? 'Tidak ada jabatan' }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Description -->
                                @if($content->subtitle || $content->description)
                                <div class="mb-4">
                                    <p class="text-xs text-gray-500 text-center line-clamp-2">
                                        {{ $content->subtitle ?? Str::limit($content->description, 80) }}
                                    </p>
                                </div>
                                @endif

                                <!-- Info -->
                                <div class="flex items-center justify-between text-xs text-gray-500 mb-4">
                                    <div class="flex items-center max-w-[60%]">
                                        <svg class="w-3 h-3 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        <span class="truncate text-xs">
                                            {{ $content->ponpes->nama_ponpes ?? 'N/A' }}
                                        </span>
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                        </svg>
                                        {{ $content->display_order }}
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex space-x-1.5">
                                    <button class="quick-preview-btn flex-1 py-1.5 px-2 bg-blue-50 text-blue-700 hover:bg-blue-100 rounded-lg font-medium text-xs transition-colors flex items-center justify-center"
                                        data-id="{{ $content->id_content }}">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Lihat
                                    </button>
                                    <a href="{{ route('admin.landing-content.edit', $content->id_content) }}"
                                        class="flex-1 py-1.5 px-2 bg-amber-50 text-amber-700 hover:bg-amber-100 rounded-lg font-medium text-xs transition-colors flex items-center justify-center">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit
                                    </a>
                                    <button class="delete-btn flex-1 py-1.5 px-2 bg-red-50 text-red-700 hover:bg-red-100 rounded-lg font-medium text-xs transition-colors flex items-center justify-center"
                                        data-id="{{ $content->id_content }}"
                                        data-title="{{ $content->title }}">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Section untuk Footer -->
                @php($footerContents = $contents->where('content_type', 'footer'))
                @if($footerContents->count() > 0)
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                            </svg>
                            Footer Links
                            <span class="ml-2 bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                {{ $footerContents->count() }}
                            </span>
                        </h3>
                        <a href="{{ route('admin.landing-content.create') }}?type=footer"
                            class="text-sm text-gray-600 hover:text-gray-800 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Footer Link
                        </a>
                    </div>
                    <!-- Footer menggunakan grid-cols-2 karena card lebih besar -->
                    <div id="footerGrid" class="grid grid-cols-1 lg:grid-cols-2 gap-3">
                        @foreach($footerContents as $content)
                        <!-- Footer Link Card -->
                        <div class="group bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-lg transition-all duration-300 overflow-hidden hover:-translate-y-1">
                            <div class="p-6">
                                <!-- Icon Section -->
                                <div class="flex justify-between items-start mb-6">
                                    <div class="bg-gray-100 rounded-xl p-4 group-hover:scale-110 transition-transform duration-300">
                                        <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                        </svg>
                                    </div>
                                    <!-- Status Toggle -->
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox"
                                            data-id="{{ $content->id_content }}"
                                            class="status-toggle sr-only peer"
                                            {{ $content->is_active ? 'checked' : '' }}>
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    </label>
                                </div>

                                <!-- Title -->
                                <h3 class="font-bold text-gray-900 text-lg mb-3 truncate" title="{{ $content->title ?? 'Tanpa Judul' }}">
                                    {{ $content->title ?? 'Tanpa Judul' }}
                                </h3>

                                <!-- Description -->
                                @if($content->description)
                                <p class="text-sm text-gray-600 mb-4 line-clamp-2">
                                    {{ $content->description }}
                                </p>
                                @endif

                                <!-- URL -->
                                @if($content->url)
                                <div class="mb-6 p-3 bg-blue-50 rounded-lg border border-blue-100">
                                    <a href="{{ $content->url }}" target="_blank"
                                        class="text-blue-600 hover:text-blue-800 text-sm flex items-center group/link">
                                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                        </svg>
                                        <span class="truncate">{{ Str::limit($content->url, 35) }}</span>
                                        <svg class="w-3 h-3 ml-1 opacity-0 group-hover/link:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                    </a>
                                </div>
                                @endif

                                <!-- Info -->
                                <div class="flex items-center justify-between text-sm text-gray-500 mb-6">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                        </svg>
                                        Footer Link
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                        </svg>
                                        {{ $content->display_order }}
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex space-x-2">
                                    <button class="quick-preview-btn flex-1 py-2 px-4 bg-blue-50 text-blue-700 hover:bg-blue-100 rounded-lg font-medium text-sm transition-colors flex items-center justify-center"
                                        data-id="{{ $content->id_content }}">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Preview
                                    </button>
                                    <a href="{{ route('admin.landing-content.edit', $content->id_content) }}"
                                        class="flex-1 py-2 px-4 bg-amber-50 text-amber-700 hover:bg-amber-100 rounded-lg font-medium text-sm transition-colors flex items-center justify-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit
                                    </a>
                                </div>

                                <!-- Ponpes Info -->
                                <div class="mt-6 pt-4 border-t border-gray-100">
                                    <div class="flex items-center text-sm text-gray-500">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        <span class="truncate">{{ $content->ponpes->nama_ponpes ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Section untuk Section Title -->
                @php($sectionContents = $contents->where('content_type', 'section_title'))
                @if($sectionContents->count() > 0)
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                            </svg>
                            Section Titles
                            <span class="ml-2 bg-amber-100 text-amber-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                {{ $sectionContents->count() }}
                            </span>
                        </h3>
                        <a href="{{ route('admin.landing-content.create') }}?type=section_title"
                            class="text-sm text-amber-600 hover:text-amber-800 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Section Title
                        </a>
                    </div>
                    <!-- Section Title menggunakan grid-cols-2 karena card sedang -->
                    <div id="sectionGrid" class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($sectionContents as $content)
                        <!-- Section Title Card -->
                        <div class="group bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-lg transition-all duration-300 overflow-hidden hover:-translate-y-1">
                            <div class="p-6">
                                <!-- Icon Section -->
                                <div class="flex justify-between items-start mb-6">
                                    <div class="bg-amber-50 rounded-xl p-4 group-hover:scale-110 transition-transform duration-300">
                                        <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                        </svg>
                                    </div>
                                    <!-- Status Toggle -->
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox"
                                            data-id="{{ $content->id_content }}"
                                            class="status-toggle sr-only peer"
                                            {{ $content->is_active ? 'checked' : '' }}>
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    </label>
                                </div>

                                <!-- Title -->
                                <h3 class="font-bold text-gray-900 text-lg mb-3 truncate" title="{{ $content->title ?? 'Tanpa Judul' }}">
                                    {{ $content->title ?? 'Tanpa Judul' }}
                                </h3>

                                <!-- Subtitle -->
                                @if($content->subtitle)
                                <p class="text-gray-600 mb-4 line-clamp-2">
                                    {{ $content->subtitle }}
                                </p>
                                @endif

                                <!-- Position -->
                                @if($content->position)
                                <div class="mb-6">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-amber-100 text-amber-800 truncate max-w-full">
                                        <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        {{ $content->position }}
                                    </span>
                                </div>
                                @endif

                                <!-- Info -->
                                <div class="flex items-center justify-between text-sm text-gray-500 mb-6">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                        </svg>
                                        Section Title
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                        </svg>
                                        {{ $content->display_order }}
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex space-x-2">
                                    <button class="quick-preview-btn flex-1 py-2 px-4 bg-blue-50 text-blue-700 hover:bg-blue-100 rounded-lg font-medium text-sm transition-colors flex items-center justify-center"
                                        data-id="{{ $content->id_content }}">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Detail
                                    </button>
                                    <a href="{{ route('admin.landing-content.edit', $content->id_content) }}"
                                        class="flex-1 py-2 px-4 bg-amber-50 text-amber-700 hover:bg-amber-100 rounded-lg font-medium text-sm transition-colors flex items-center justify-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit
                                    </a>
                                </div>

                                <!-- Footer Info -->
                                <div class="mt-6 pt-4 border-t border-gray-100">
                                    <div class="flex justify-between items-center text-sm text-gray-500">
                                        <div class="flex items-center max-w-[50%]">
                                            <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                            </svg>
                                            <span class="truncate">{{ $content->ponpes->nama_ponpes ?? 'N/A' }}</span>
                                        </div>
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            {{ $content->created_at->format('d/m/Y') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Empty State jika tidak ada konten sama sekali -->
                @if($contents->count() == 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-12 text-center">
                        <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-6">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada konten</h3>
                        <p class="text-gray-600 mb-6">Mulai dengan menambahkan konten pertama Anda</p>
                        <a href="{{ route('admin.landing-content.create') }}"
                            class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-lg font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Konten
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
        <!-- Pagination -->
        @if($contents->hasPages())
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="text-sm text-gray-700">
                    <span>Menampilkan</span>
                    <span class="font-medium">{{ $contents->firstItem() }}</span>
                    <span>hingga</span>
                    <span class="font-medium">{{ $contents->lastItem() }}</span>
                    <span>dari</span>
                    <span class="font-medium">{{ $contents->total() }}</span>
                    <span>konten</span>
                </div>
                <div class="flex justify-center sm:justify-end">
                    {{ $contents->withQueryString()->onEachSide(1)->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Modals -->
<!-- Include modals partial if exists -->
@if(View::exists('admin.landing-content.partials.modals'))
@include('admin.landing-content.partials.modals')
@endif

@endsection

@push('styles')
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Grid/List view toggle
        const gridViewBtn = document.getElementById('gridViewBtn');
        const listViewBtn = document.getElementById('listViewBtn');
        const contentGrid = document.getElementById('contentGrid');

        if (gridViewBtn && listViewBtn) {
            gridViewBtn.addEventListener('click', function() {
                contentGrid.classList.remove('grid-cols-1');
                contentGrid.classList.add('grid-cols-1', 'md:grid-cols-2', 'lg:grid-cols-3', 'xl:grid-cols-4');
                gridViewBtn.classList.remove('text-gray-500', 'hover:text-gray-700', 'hover:bg-gray-50');
                gridViewBtn.classList.add('bg-blue-100', 'text-blue-700');
                listViewBtn.classList.remove('bg-blue-100', 'text-blue-700');
                listViewBtn.classList.add('text-gray-500', 'hover:text-gray-700', 'hover:bg-gray-50');
            });

            listViewBtn.addEventListener('click', function() {
                contentGrid.classList.remove('md:grid-cols-2', 'lg:grid-cols-3', 'xl:grid-cols-4');
                contentGrid.classList.add('grid-cols-1');
                listViewBtn.classList.remove('text-gray-500', 'hover:text-gray-700', 'hover:bg-gray-50');
                listViewBtn.classList.add('bg-blue-100', 'text-blue-700');
                gridViewBtn.classList.remove('bg-blue-100', 'text-blue-700');
                gridViewBtn.classList.add('text-gray-500', 'hover:text-gray-700', 'hover:bg-gray-50');
            });
        }

        // Filter handling
        const filterPonpes = document.getElementById('filterPonpes');
        const filterType = document.getElementById('filterType');
        const searchBtn = document.getElementById('searchBtn');
        const searchInput = document.getElementById('searchInput');

        function applyFilters() {
            const params = new URLSearchParams();

            if (filterPonpes && filterPonpes.value) {
                params.set('ponpes_id', filterPonpes.value);
            }

            if (filterType && filterType.value) {
                params.set('content_type', filterType.value);
            }

            if (searchInput && searchInput.value) {
                params.set('search', searchInput.value);
            }

            window.location.href = '{{ route("admin.landing-content.index") }}?' + params.toString();
        }

        if (filterPonpes) {
            filterPonpes.addEventListener('change', applyFilters);
        }

        if (filterType) {
            filterType.addEventListener('change', applyFilters);
        }

        if (searchBtn) {
            searchBtn.addEventListener('click', applyFilters);
        }

        if (searchInput) {
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    applyFilters();
                }
            });
        }

        // Status toggle
        document.querySelectorAll('.status-toggle').forEach(toggle => {
            toggle.addEventListener('change', function() {
                const contentId = this.dataset.id;
                const isActive = this.checked ? 1 : 0;
                const card = this.closest('.group');

                fetch(`/admin/landing-content/${contentId}/toggle-status`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            is_active: isActive
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: 'Status konten berhasil diubah',
                                timer: 1500,
                                showConfirmButton: false
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Terjadi kesalahan saat mengubah status'
                        }).then(() => {
                            location.reload();
                        });
                    });
            });
        });

        // Quick Preview
        document.querySelectorAll('.quick-preview-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const contentId = this.dataset.id;
                window.location.href = `/admin/landing-content/${contentId}`;
            });
        });

        // Delete confirmation
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const contentId = this.dataset.id;
                const contentTitle = this.dataset.title;

                Swal.fire({
                    title: 'Hapus Konten?',
                    html: `Anda yakin ingin menghapus <strong>"${contentTitle}"</strong>?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/admin/landing-content/${contentId}`, {
                                method: 'DELETE',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Terhapus!',
                                        text: 'Konten berhasil dihapus.',
                                        timer: 1500,
                                        showConfirmButton: false
                                    }).then(() => {
                                        location.reload();
                                    });
                                }
                            })
                            .catch(error => {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: 'Terjadi kesalahan saat menghapus konten.'
                                });
                            });
                    }
                });
            });
        });
    });
</script>
@endpush