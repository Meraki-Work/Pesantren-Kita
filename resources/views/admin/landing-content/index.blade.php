@extends('index')

@section('title', 'Kelola Konten Landing Page')

@section('content')
<div class="flex">
    <x-sidemenu title="PesantrenKita" class="h-full min-h-screen" />
    <main class="flex-1 p-4 md:p-6 overflow-y-auto">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Konten Landing Page</h1>
                        <p class="text-gray-600 mt-1">Kelola semua konten yang ditampilkan di halaman depan</p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <!-- Tombol View Landing Page -->
                        @if($userPonpes && $userPonpes->id_ponpes)
                        <a href="{{ route('landing.index', ['ponpes_id' => $userPonpes->id_ponpes]) }}"
                            target="_blank"
                            class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            Lihat Landing Page
                        </a>
                        @endif

                        <a href="{{ route('admin.landing-content.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Konten
                        </a>
                    </div>
                </div>

                <!-- Info Ponpes -->

            </div>

            <!-- Info Ponpes dengan Logo -->
            @if($userPonpes)
            <div class="mt-4 p-3 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        @if($userPonpes->logo_ponpes)
                        <div class="relative group mr-4">
                            <img src="{{ asset('storage/' . $userPonpes->logo_ponpes) }}"
                                alt="{{ $userPonpes->nama_ponpes }}"
                                class="h-16 w-16 rounded-full border-4 border-white shadow-md object-cover hover:scale-105 transition-transform duration-200">
                            <!-- Upload/Edit Button -->
                            <div class="absolute inset-0 rounded-full bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex items-center justify-center">
                                <button type="button"
                                    onclick="openLogoModal()"
                                    class="p-2 bg-white rounded-full text-blue-600 hover:bg-blue-50 transition-colors"
                                    title="Ubah Logo">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        @else
                        <div class="relative group mr-4">
                            <div class="h-16 w-16 rounded-full border-4 border-white shadow-md bg-gradient-to-r from-blue-100 to-indigo-100 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                <!-- Upload Button -->
                                <div class="absolute inset-0 rounded-full bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex items-center justify-center">
                                    <button type="button"
                                        onclick="openLogoModal()"
                                        class="p-2 bg-white rounded-full text-blue-600 hover:bg-blue-50 transition-colors"
                                        title="Upload Logo">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div>
                            <p class="text-sm font-medium text-gray-900">Anda mengelola konten untuk:</p>
                            <p class="text-lg font-bold text-blue-700">{{ $userPonpes->nama_ponpes }}</p>
                            <div class="flex items-center mt-1 space-x-3">
                                @if($userPonpes->logo_ponpes)
                                <button type="button"
                                    onclick="openLogoModal()"
                                    class="text-xs text-blue-600 hover:text-blue-800 hover:bg-blue-50 px-2 py-1 rounded transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit Logo
                                </button>
                                @else
                                <button type="button"
                                    onclick="openLogoModal()"
                                    class="text-xs text-blue-600 hover:text-blue-800 hover:bg-blue-50 px-2 py-1 rounded transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Upload Logo
                                </button>
                                @endif

                                @if($userPonpes->logo_ponpes)
                                <button type="button"
                                    onclick="showLogoPreview()"
                                    class="text-xs text-green-600 hover:text-green-800 hover:bg-green-50 px-2 py-1 rounded transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Lihat Logo
                                </button>

                                <button type="button"
                                    onclick="confirmDeleteLogo()"
                                    class="text-xs text-red-600 hover:text-red-800 hover:bg-red-50 px-2 py-1 rounded transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Hapus Logo
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <a href="{{ route('admin.ponpes.edit', $userPonpes->id_ponpes) }}" 
                           class="inline-flex items-center px-3 py-1.5 bg-white border border-blue-300 text-blue-700 rounded-lg text-sm hover:bg-blue-50 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Edit Profil Pesantren
                        </a>
                    </div>
                </div>
            </div>
            @endif

            <!-- Logo Upload/Edit Modal -->
            @if($userPonpes)
            <div id="logoModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
                <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                    <div class="mt-3">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                {{ $userPonpes->logo_ponpes ? 'Ubah Logo' : 'Upload Logo' }}
                            </h3>
                            <button onclick="closeLogoModal()" class="text-gray-400 hover:text-gray-600">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <form id="logoForm" action="{{ route('admin.ponpes.update-logo', $userPonpes->id_ponpes) }}"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')

                            <!-- Logo Preview -->
                            <div class="mb-6 text-center">
                                <div class="mx-auto w-48 h-48 relative">
                                    <div id="logoPreviewContainer" class="{{ $userPonpes->logo_ponpes ? '' : 'hidden' }}">
                                        <img id="logoPreview"
                                            src="{{ $userPonpes->logo_ponpes ? asset('storage/' . $userPonpes->logo_ponpes) : '' }}"
                                            alt="Logo Preview"
                                            class="w-full h-full object-cover rounded-lg border-2 border-gray-300 shadow-sm">
                                        <button type="button"
                                            onclick="removeLogoPreview()"
                                            class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>

                                    <div id="logoPlaceholder" class="{{ $userPonpes->logo_ponpes ? 'hidden' : '' }}">
                                        <div class="w-full h-full border-2 border-dashed border-gray-300 rounded-lg flex flex-col items-center justify-center bg-gray-50">
                                            <svg class="h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span class="mt-2 text-sm text-gray-500">Preview Logo</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- File Input -->
                            <div class="mb-4">
                                <label for="logo_ponpes" class="block text-sm font-medium text-gray-700 mb-2">
                                    Pilih File Logo <span class="text-red-500">*</span>
                                </label>
                                <input type="file"
                                    name="logo_ponpes"
                                    id="logo_ponpes"
                                    accept="image/*"
                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                    onchange="previewLogo(event)">
                                <p class="mt-1 text-xs text-gray-500">
                                    Format: JPG, PNG, GIF (Maks. 2MB). Ukuran disarankan: 300×300px
                                </p>
                                @error('logo_ponpes')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Remove Logo Option -->
                            @if($userPonpes->logo_ponpes)
                            <div class="mb-4">
                                <label class="flex items-center">
                                    <input type="checkbox"
                                        name="remove_logo"
                                        id="remove_logo"
                                        class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-700">Hapus logo saat ini</span>
                                </label>
                            </div>
                            @endif

                            <!-- Form Actions -->
                            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                                <button type="button"
                                    onclick="closeLogoModal()"
                                    class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Batal
                                </button>
                                <button type="submit"
                                    id="submitLogoBtn"
                                    class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="animate-spin h-4 w-4 text-white hidden mr-2" id="logoSpinner" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span id="submitLogoText">
                                        {{ $userPonpes->logo_ponpes ? 'Simpan Perubahan' : 'Upload Logo' }}
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Logo Preview Modal -->
            <div id="logoPreviewModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-90 overflow-y-auto h-full w-full z-50">
                <div class="relative top-10 mx-auto p-5 w-auto">
                    <div class="bg-white rounded-lg shadow-xl">
                        <div class="flex justify-between items-center p-4 border-b">
                            <h3 class="text-lg font-medium text-gray-900">Logo {{ $userPonpes->nama_ponpes }}</h3>
                            <button onclick="closeLogoPreview()" class="text-gray-400 hover:text-gray-600">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <div class="p-8">
                            @if($userPonpes->logo_ponpes)
                            <img src="{{ asset('storage/' . $userPonpes->logo_ponpes) }}"
                                alt="{{ $userPonpes->nama_ponpes }}"
                                class="max-w-full max-h-96 mx-auto rounded-lg shadow-lg">
                            @endif
                        </div>
                        <div class="flex justify-end p-4 border-t">
                            <button onclick="closeLogoPreview()"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Filter Section -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
                <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-lg font-semibold text-gray-900">Filter & Pencarian</h2>
                </div>
                <div class="p-4">
                    <form method="GET" action="{{ route('admin.landing-content.index') }}" id="filterForm">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Filter Pesantren -->
                            @if(Auth::user()->role === 'admin' || Auth::user()->role === 'super_admin')
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Filter Pesantren</label>
                                <select name="ponpes_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="filterPonpes">
                                    <option value="">Semua Pesantren</option>
                                    @foreach($ponpesList as $ponpes)
                                    <option value="{{ $ponpes->id_ponpes }}"
                                        {{ request('ponpes_id') == $ponpes->id_ponpes ? 'selected' : '' }}>
                                        {{ $ponpes->nama_ponpes }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            @else
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Pesantren</label>
                                <div class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-md text-gray-700">
                                    {{ $userPonpes ? $userPonpes->nama_ponpes : 'Pesantren Anda' }}
                                </div>
                            </div>
                            @endif
                            <!-- Filter Tipe Konten -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Filter Tipe Konten</label>
                                <select name="content_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="filterType">
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
                                    <input type="text" name="search"
                                        class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="Cari judul atau deskripsi..."
                                        value="{{ request('search') }}"
                                        id="searchInput">
                                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </button>
                                    @if(request()->hasAny(['ponpes_id', 'content_type', 'search']))
                                    <a href="{{ route('admin.landing-content.index') }}"
                                        class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Quick Actions -->
            @if($userPonpes)
            <div class="mb-6">
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 rounded-lg p-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between">
                        <div class="mb-4 sm:mb-0">
                            <h3 class="text-lg font-semibold text-gray-900">Aksi Cepat</h3>
                            <p class="text-sm text-gray-600">Kelola konten landing page Anda dengan cepat</p>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @if($userPonpes->id_ponpes)
                            <!-- Section Copy Link & Share -->
                            <div class="flex flex-col sm:flex-row gap-2">
                                <!-- Copy Link Button -->
                                <div class="relative group">
                                    <button type="button"
                                        id="copyLinkBtn"
                                        data-link="{{ route('landing.public.show', $userPonpes->id_ponpes) }}"
                                        class="inline-flex items-center px-4 py-2 bg-white border border-indigo-600 text-indigo-700 rounded-lg hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors duration-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg>
                                        Salin Link
                                    </button>
                                    <!-- Tooltip -->
                                    <div class="hidden group-hover:block absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2">
                                        <div class="bg-gray-900 text-white text-xs rounded py-1 px-2 whitespace-nowrap">
                                            Klik untuk menyalin link
                                        </div>
                                    </div>
                                </div>

                                <!-- Share Button with Dropdown -->
                                <div class="relative group">
                                    <button type="button"
                                        id="shareBtn"
                                        class="inline-flex items-center px-4 py-2 bg-white border border-purple-600 text-purple-700 rounded-lg hover:bg-purple-50 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-colors duration-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                                        </svg>
                                        Bagikan
                                    </button>

                                    <!-- Share Dropdown Menu -->
                                    <div id="shareDropdown" class="hidden absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                                        <div class="p-3">
                                            <h4 class="text-sm font-semibold text-gray-900 mb-2">Bagikan Landing Page</h4>

                                            <!-- Share Options -->
                                            <div class="space-y-2">
                                                <!-- WhatsApp -->
                                                <a href="javascript:void(0)"
                                                    onclick="shareToWhatsApp()"
                                                    class="flex items-center p-2 rounded hover:bg-green-50 text-gray-700 hover:text-green-700 transition-colors">
                                                    <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center mr-3">
                                                        <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                                            <path d="M20.5 3.5a12.1 12.1 0 0 1 3.5 8.5 12 12 0 0 1-12 12 12.1 12.1 0 0 1-8.5-3.5L1.5 24l3.5-2.5A12.1 12.1 0 0 1 1 12a12 12 0 0 1 12-12 12.1 12.1 0 0 1 8.5 3.5z" />
                                                        </svg>
                                                    </div>
                                                    <span class="text-sm">WhatsApp</span>
                                                </a>

                                                <!-- Facebook -->
                                                <a href="javascript:void(0)"
                                                    onclick="shareToFacebook()"
                                                    class="flex items-center p-2 rounded hover:bg-blue-50 text-gray-700 hover:text-blue-700 transition-colors">
                                                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                                        <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                                        </svg>
                                                    </div>
                                                    <span class="text-sm">Facebook</span>
                                                </a>

                                                <!-- Telegram -->
                                                <a href="javascript:void(0)"
                                                    onclick="shareToTelegram()"
                                                    class="flex items-center p-2 rounded hover:bg-sky-50 text-gray-700 hover:text-sky-700 transition-colors">
                                                    <div class="w-8 h-8 rounded-full bg-sky-100 flex items-center justify-center mr-3">
                                                        <svg class="w-4 h-4 text-sky-600" fill="currentColor" viewBox="0 0 24 24">
                                                            <path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z" />
                                                        </svg>
                                                    </div>
                                                    <span class="text-sm">Telegram</span>
                                                </a>

                                                <!-- Email -->
                                                <a href="javascript:void(0)"
                                                    onclick="shareViaEmail()"
                                                    class="flex items-center p-2 rounded hover:bg-red-50 text-gray-700 hover:text-red-700 transition-colors">
                                                    <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center mr-3">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                        </svg>
                                                    </div>
                                                    <span class="text-sm">Email</span>
                                                </a>

                                                <!-- QR Code -->
                                                <button type="button"
                                                    onclick="showQRCode()"
                                                    class="w-full flex items-center p-2 rounded hover:bg-gray-100 text-gray-700 hover:text-gray-900 transition-colors">
                                                    <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center mr-3">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                                        </svg>
                                                    </div>
                                                    <span class="text-sm">Tampilkan QR Code</span>
                                                </button>
                                            </div>

                                            <!-- Copy Link in Dropdown -->
                                            <div class="mt-3 pt-3 border-t border-gray-200">
                                                <div class="flex items-center">
                                                    <input type="text"
                                                        id="shareableLink"
                                                        readonly
                                                        value="{{ route('landing.public.show', $userPonpes->id_ponpes) }}"
                                                        class="flex-grow px-3 py-2 text-sm border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                                    <button type="button"
                                                        onclick="copyLinkFromInput()"
                                                        class="px-3 py-2 text-sm bg-indigo-600 text-white rounded-r-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                                        Salin
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Preview Button -->
                            <a href="{{ route('landing.public.show', $userPonpes->id_ponpes) }}"
                                target="_blank"
                                class="inline-flex items-center px-4 py-2 bg-white border border-green-600 text-green-700 rounded-lg hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                                Preview Halaman Publikasi
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- QR Code Modal -->
            <div id="qrCodeModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
                <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                    <div class="mt-3 text-center">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">QR Code Landing Page</h3>

                        <!-- QR Code Container -->
                        <div class="mb-4 flex justify-center" id="qrcode"></div>

                        <p class="text-sm text-gray-500 mb-4">
                            Scan QR code di atas untuk mengakses landing page di perangkat mobile
                        </p>

                        <div class="flex items-center justify-center px-4 py-3">
                            <button id="downloadQRBtn"
                                class="px-4 py-2 bg-indigo-600 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                                Download QR Code
                            </button>
                        </div>

                        <div class="items-center px-4 py-3">
                            <button id="closeQRModal"
                                class="px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Success Notification -->
            <div id="copySuccess" class="hidden fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-lg z-50">
                <div class="flex items-center">
                    <svg class="h-5 w-5 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    <span>Link berhasil disalin ke clipboard!</span>
                </div>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.0/build/qrcode.min.js"></script>
            <script>
                @if($userPonpes)
                // Logo management functions
                function openLogoModal() {
                    document.getElementById('logoModal').classList.remove('hidden');
                }

                function closeLogoModal() {
                    document.getElementById('logoModal').classList.add('hidden');
                    document.getElementById('logoForm').reset();

                    // Reset preview to original logo
                    const originalLogo = "{{ $userPonpes->logo_ponpes ? asset('storage/' . $userPonpes->logo_ponpes) : '' }}";
                    const previewContainer = document.getElementById('logoPreviewContainer');
                    const placeholder = document.getElementById('logoPlaceholder');

                    if (originalLogo) {
                        document.getElementById('logoPreview').src = originalLogo;
                        previewContainer.classList.remove('hidden');
                        placeholder.classList.add('hidden');
                    } else {
                        previewContainer.classList.add('hidden');
                        placeholder.classList.remove('hidden');
                    }
                }

                function previewLogo(event) {
                    const input = event.target;
                    const preview = document.getElementById('logoPreview');
                    const previewContainer = document.getElementById('logoPreviewContainer');
                    const placeholder = document.getElementById('logoPlaceholder');

                    if (input.files && input.files[0]) {
                        const reader = new FileReader();

                        reader.onload = function(e) {
                            preview.src = e.target.result;
                            previewContainer.classList.remove('hidden');
                            placeholder.classList.add('hidden');
                        };

                        reader.readAsDataURL(input.files[0]);
                    }
                }

                function removeLogoPreview() {
                    const input = document.getElementById('logo_ponpes');
                    const previewContainer = document.getElementById('logoPreviewContainer');
                    const placeholder = document.getElementById('logoPlaceholder');

                    input.value = '';
                    previewContainer.classList.add('hidden');
                    placeholder.classList.remove('hidden');

                    // Check remove logo checkbox
                    const removeCheckbox = document.getElementById('remove_logo');
                    if (removeCheckbox) {
                        removeCheckbox.checked = true;
                    }
                }

                function showLogoPreview() {
                    document.getElementById('logoPreviewModal').classList.remove('hidden');
                }

                function closeLogoPreview() {
                    document.getElementById('logoPreviewModal').classList.add('hidden');
                }

                function confirmDeleteLogo() {
                    Swal.fire({
                        title: 'Hapus Logo?',
                        text: 'Anda yakin ingin menghapus logo pesantren?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            deleteLogo();
                        }
                    });
                }

                function deleteLogo() {
                    const formData = new FormData();
                    formData.append('_token', '{{ csrf_token() }}');
                    formData.append('_method', 'PATCH');
                    formData.append('remove_logo', '1');

                    const submitBtn = document.getElementById('submitLogoBtn');
                    const spinner = document.getElementById('logoSpinner');
                    const submitText = document.getElementById('submitLogoText');

                    submitBtn.disabled = true;
                    spinner.classList.remove('hidden');
                    submitText.textContent = 'Menghapus...';

                    fetch('{{ route("admin.ponpes.update-logo", $userPonpes->id_ponpes) }}', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: 'Logo berhasil dihapus',
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                throw new Error(data.message || 'Gagal menghapus logo');
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: error.message
                            });
                        })
                        .finally(() => {
                            submitBtn.disabled = false;
                            spinner.classList.add('hidden');
                            submitText.textContent = '{{ $userPonpes->logo_ponpes ? "Simpan Perubahan" : "Upload Logo" }}';
                        });
                }

                // Handle logo form submission
                const logoForm = document.getElementById('logoForm');
                if (logoForm) {
                    logoForm.addEventListener('submit', function(e) {
                        e.preventDefault();

                        const formData = new FormData(this);
                        const submitBtn = document.getElementById('submitLogoBtn');
                        const spinner = document.getElementById('logoSpinner');
                        const submitText = document.getElementById('submitLogoText');

                        submitBtn.disabled = true;
                        spinner.classList.remove('hidden');
                        submitText.textContent = 'Menyimpan...';

                        fetch(this.action, {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: data.message || 'Logo berhasil diperbarui',
                                        timer: 1500,
                                        showConfirmButton: false
                                    }).then(() => {
                                        closeLogoModal();
                                        location.reload();
                                    });
                                } else {
                                    throw new Error(data.message || 'Gagal menyimpan logo');
                                }
                            })
                            .catch(error => {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: error.message
                                });
                            })
                            .finally(() => {
                                submitBtn.disabled = false;
                                spinner.classList.add('hidden');
                                submitText.textContent = '{{ $userPonpes->logo_ponpes ? "Simpan Perubahan" : "Upload Logo" }}';
                            });
                    });
                }

                // Close modal when clicking outside
                window.addEventListener('click', function(e) {
                    const logoModal = document.getElementById('logoModal');
                    const logoPreviewModal = document.getElementById('logoPreviewModal');

                    if (e.target === logoModal) {
                        closeLogoModal();
                    }

                    if (e.target === logoPreviewModal) {
                        closeLogoPreview();
                    }
                });
                @endif

                // DOM Elements
                document.addEventListener('DOMContentLoaded', function() {
                    // Initialize all elements
                    const copyLinkBtn = document.getElementById('copyLinkBtn');
                    const shareBtn = document.getElementById('shareBtn');
                    const shareDropdown = document.getElementById('shareDropdown');
                    const shareableLink = document.getElementById('shareableLink');
                    const qrCodeModal = document.getElementById('qrCodeModal');
                    const copySuccess = document.getElementById('copySuccess');
                    const closeQRModal = document.getElementById('closeQRModal');
                    const downloadQRBtn = document.getElementById('downloadQRBtn');

                    // Get landing page URL
                    @if($userPonpes)
                    const landingUrl = "{{ route('landing.public.show', $userPonpes->id_ponpes) }}";
                    const ponpesName = "{{ $userPonpes->nama_ponpes ?? 'Pondok Pesantren' }}";
                    @else
                    const landingUrl = "";
                    const ponpesName = "Pondok Pesantren";
                    @endif

                    // Debug logging
                    console.log('copyLinkBtn found:', copyLinkBtn);
                    console.log('Landing URL:', landingUrl);

                    // Copy Link Function
                    function copyToClipboard(text) {
                        // Fallback method untuk browser yang tidak support clipboard API
                        if (!navigator.clipboard) {
                            fallbackCopyToClipboard(text);
                            return;
                        }

                        navigator.clipboard.writeText(text).then(() => {
                            // Show success notification
                            if (copySuccess) {
                                copySuccess.classList.remove('hidden');
                                setTimeout(() => {
                                    copySuccess.classList.add('hidden');
                                }, 3000);
                            }
                            // Juga ubah tombol sementara
                            if (copyLinkBtn) {
                                const originalHTML = copyLinkBtn.innerHTML;
                                copyLinkBtn.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Tersalin!
                `;
                                copyLinkBtn.classList.add('bg-green-50', 'border-green-600', 'text-green-700');
                                copyLinkBtn.classList.remove('bg-white', 'border-indigo-600', 'text-indigo-700');

                                setTimeout(() => {
                                    copyLinkBtn.innerHTML = originalHTML;
                                    copyLinkBtn.classList.remove('bg-green-50', 'border-green-600', 'text-green-700');
                                    copyLinkBtn.classList.add('bg-white', 'border-indigo-600', 'text-indigo-700');
                                }, 2000);
                            }
                        }).catch(err => {
                            console.error('Failed to copy: ', err);
                            fallbackCopyToClipboard(text);
                        });
                    }

                    // Fallback copy method
                    function fallbackCopyToClipboard(text) {
                        const textArea = document.createElement('textarea');
                        textArea.value = text;
                        textArea.style.position = 'fixed';
                        textArea.style.left = '-999999px';
                        textArea.style.top = '-999999px';
                        document.body.appendChild(textArea);
                        textArea.focus();
                        textArea.select();

                        try {
                            document.execCommand('copy');
                            // Show success
                            if (copySuccess) {
                                copySuccess.classList.remove('hidden');
                                setTimeout(() => {
                                    copySuccess.classList.add('hidden');
                                }, 3000);
                            }
                            console.log('Copied using fallback method');
                        } catch (err) {
                            console.error('Fallback copy failed: ', err);
                            alert('Gagal menyalin link. Silakan salin manual:\n\n' + text);
                        }

                        document.body.removeChild(textArea);
                    }

                    // Copy link from main button
                    if (copyLinkBtn) {
                        copyLinkBtn.addEventListener('click', function(e) {
                            e.preventDefault();
                            console.log('Copy button clicked');

                            // Gunakan data-link attribute atau langsung dari variable
                            const link = this.getAttribute('data-link') || landingUrl;
                            console.log('Copying link:', link);

                            copyToClipboard(link);
                        });
                    } else {
                        console.error('copyLinkBtn element not found!');
                    }

                    // Copy link from input field
                    function copyLinkFromInput() {
                        if (shareableLink) {
                            copyToClipboard(shareableLink.value);
                        }
                    }

                    // Share Functions
                    function shareToWhatsApp() {
                        const text = `Landing Page ${ponpesName}: ${landingUrl}`;
                        const url = `https://wa.me/?text=${encodeURIComponent(text)}`;
                        window.open(url, '_blank');
                    }

                    function shareToFacebook() {
                        const url = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(landingUrl)}`;
                        window.open(url, '_blank', 'width=600,height=400');
                    }

                    function shareToTelegram() {
                        const text = `Landing Page ${ponpesName}`;
                        const url = `https://t.me/share/url?url=${encodeURIComponent(landingUrl)}&text=${encodeURIComponent(text)}`;
                        window.open(url, '_blank');
                    }

                    function shareViaEmail() {
                        const subject = `Landing Page ${ponpesName}`;
                        const body = `Halo,\n\nBerikut adalah link landing page ${ponpesName}:\n${landingUrl}\n\nSalam,\n${ponpesName}`;
                        const url = `mailto:?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
                        window.location.href = url;
                    }

                    // Toggle share dropdown
                    if (shareBtn && shareDropdown) {
                        shareBtn.addEventListener('click', function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            shareDropdown.classList.toggle('hidden');
                        });
                    }

                    // Close dropdown when clicking outside
                    if (shareBtn && shareDropdown) {
                        document.addEventListener('click', function(e) {
                            if (!shareBtn.contains(e.target) && !shareDropdown.contains(e.target)) {
                                shareDropdown.classList.add('hidden');
                            }
                        });
                    }

                    // QR Code Functions
                    let qrCodeInstance = null;

                    function showQRCode() {
                        // Hide share dropdown
                        if (shareDropdown) {
                            shareDropdown.classList.add('hidden');
                        }

                        // Clear previous QR code
                        const qrContainer = document.getElementById('qrcode');
                        if (qrContainer) {
                            qrContainer.innerHTML = '';

                            // Generate new QR code
                            qrCodeInstance = new QRCode(qrContainer, {
                                text: landingUrl,
                                width: 200,
                                height: 200,
                                colorDark: "#000000",
                                colorLight: "#ffffff",
                                correctLevel: QRCode.CorrectLevel.H
                            });
                        }

                        // Show modal
                        if (qrCodeModal) {
                            qrCodeModal.classList.remove('hidden');
                        }
                    }

                    function downloadQRCode() {
                        if (!qrCodeInstance) return;

                        const canvas = document.querySelector('#qrcode canvas');
                        if (canvas) {
                            const link = document.createElement('a');
                            link.download = `qr-code-${ponpesName.toLowerCase().replace(/\s+/g, '-')}.png`;
                            link.href = canvas.toDataURL('image/png');
                            link.click();
                        }
                    }

                    // Close QR Modal
                    if (closeQRModal && qrCodeModal) {
                        closeQRModal.addEventListener('click', function() {
                            qrCodeModal.classList.add('hidden');
                        });
                    }

                    // Close modal on outside click
                    if (qrCodeModal) {
                        qrCodeModal.addEventListener('click', function(e) {
                            if (e.target === this) {
                                this.classList.add('hidden');
                            }
                        });
                    }

                    // Initialize QR Code library check
                    if (typeof QRCode === 'undefined') {
                        console.error('QRCode library not loaded');
                    }
                });
                // Download QR Code
                if (downloadQRBtn) {
                    downloadQRBtn.addEventListener('click', downloadQRCode);
                }

                // Close modal on outside click
                window.addEventListener('click', function(e) {
                    if (e.target === qrCodeModal) {
                        qrCodeModal.classList.add('hidden');
                    }
                });

                // Web Share API (for mobile devices)
                if (navigator.share) {
                    // Update share button to use Web Share API
                    shareBtn.addEventListener('click', async function(e) {
                        e.preventDefault();

                        try {
                            await navigator.share({
                                title: `Landing Page ${ponpesName}`,
                                text: `Kunjungi landing page ${ponpesName}`,
                                url: landingUrl,
                            });
                        } catch (err) {
                            console.log('Error sharing:', err);
                            // Fallback to custom dropdown
                            shareDropdown.classList.toggle('hidden');
                        }
                    });
                }

                // Refresh button functionality
                const refreshBtn = document.getElementById('refreshBtn');
                if (refreshBtn) {
                    refreshBtn.addEventListener('click', function() {
                        location.reload();
                    });
                }

                // Add CSS for animations
                const style = document.createElement('style');
                style.textContent = `
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        #shareDropdown {
            animation: fadeIn 0.2s ease-out;
        }
        
        #copySuccess {
            animation: slideInRight 0.3s ease-out;
        }
        
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        .group:hover .group-hover\\:block {
            display: block !important;
        }
    `;
                document.head.appendChild(style);
            </script>
            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="box-bg p-4">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-100 rounded-lg mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-900">{{ $contents->total() }}</p>
                            <p class="text-sm text-gray-600">Total Konten</p>
                        </div>
                    </div>
                </div>

                <div class="box-bg p-4">
                    <div class="flex items-center">
                        <div class="p-2 bg-green-100 rounded-lg mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['active'] ?? $contents->where('is_active', true)->count() }}</p>
                            <p class="text-sm text-gray-600">Aktif</p>
                        </div>
                    </div>
                </div>

                <div class="box-bg p-4">
                    <div class="flex items-center">
                        <div class="p-2 bg-cyan-100 rounded-lg mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-cyan-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['carousel_count'] ?? $contents->where('content_type', 'carousel')->count() }}</p>
                            <p class="text-sm text-gray-600">Carousel</p>
                        </div>
                    </div>
                </div>

                <div class="box-bg p-4">
                    <div class="flex items-center">
                        <div class="p-2 bg-amber-100 rounded-lg mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13 1a6 6 0 01-12 0m12 0a6 6 0 00-12 0" />
                            </svg>
                        </div>
                        <div>
                            @php
                            $founderCount = $contents->where('content_type', 'about_founder')->count();
                            $leaderCount = $contents->where('content_type', 'about_leader')->count();
                            $totalTeam = $founderCount + $leaderCount;
                            @endphp
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['founder_count'] + $stats['leader_count'] ?? $totalTeam }}</p>
                            <p class="text-sm text-gray-600">Founder & Leader</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Table View (Default) -->
            <div id="tableView" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Preview</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Konten</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pesantren</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Urutan</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($contents as $content)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $loop->iteration + (($contents->currentPage() - 1) * $contents->perPage()) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="image-preview-wrapper">
                                        @if($content->image)
                                        <img src="{{ Storage::url($content->image) }}"
                                            alt="{{ $content->title }}"
                                            class="w-16 h-16 object-cover rounded-lg border border-gray-200 cursor-pointer hover:scale-105 transition-transform duration-200"
                                            data-bs-toggle="modal"
                                            data-bs-target="#imageModal"
                                            data-image="{{ Storage::url($content->image) }}"
                                            data-title="{{ $content->title }}">
                                        @else
                                        <div class="w-16 h-16 bg-gray-100 rounded-lg border border-gray-200 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <p class="text-sm font-medium text-gray-900 truncate max-w-xs" title="{{ $content->title }}">
                                            {{ $content->title }}
                                        </p>
                                        @if($content->subtitle)
                                        <p class="text-xs text-gray-500 truncate max-w-xs">
                                            {{ Str::limit($content->subtitle, 60) }}
                                        </p>
                                        @endif
                                        <p class="text-xs text-blue-600 mt-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ $content->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                    $typeColors = [
                                    'carousel' => 'bg-blue-100 text-blue-800',
                                    'about_founder' => 'bg-green-100 text-green-800',
                                    'about_leader' => 'bg-cyan-100 text-cyan-800',
                                    'footer' => 'bg-gray-100 text-gray-800',
                                    'section_title' => 'bg-amber-100 text-amber-800'
                                    ];
                                    $color = $typeColors[$content->content_type] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $color }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                        </svg>
                                        {{ ucfirst(str_replace('_', ' ', $content->content_type)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        <span class="text-sm text-gray-900 truncate max-w-xs" title="{{ $content->ponpes->nama_ponpes ?? 'N/A' }}">
                                            {{ $content->ponpes->nama_ponpes ?? 'N/A' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <input type="number"
                                            class="w-16 px-2 py-1 text-center border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 order-input"
                                            value="{{ $content->display_order }}"
                                            data-id="{{ $content->id_content }}"
                                            min="1">
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox"
                                            data-id="{{ $content->id_content }}"
                                            class="sr-only peer status-toggle"
                                            {{ $content->is_active ? 'checked' : '' }}>
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    </label>
                                    <span class="ml-3 text-sm text-gray-600">
                                        {{ $content->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <button type="button"
                                            class="quick-preview-btn p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-colors duration-200"
                                            title="Preview"
                                            data-id="{{ $content->id_content }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>
                                        <a href="{{ route('admin.landing-content.edit', $content->id_content) }}"
                                            class="p-2 text-amber-600 hover:text-amber-800 hover:bg-amber-50 rounded-lg transition-colors duration-200"
                                            title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <button type="button"
                                            class="delete-btn p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors duration-200"
                                            data-id="{{ $content->id_content }}"
                                            data-title="{{ $content->title }}"
                                            title="Hapus">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="px-6 py-16 text-center">
                                    <div class="text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                        <h3 class="mt-4 text-lg font-medium text-gray-900">Belum ada konten</h3>
                                        <p class="mt-1 text-gray-500">Mulai dengan menambahkan konten pertama Anda</p>
                                        <div class="mt-6">
                                            <a href="{{ route('admin.landing-content.create') }}"
                                                class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                </svg>
                                                Tambah Konten
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Footer dengan pagination -->
                <div class="border-t border-gray-200 px-4 py-4 sm:px-6">
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                        <!-- Info jumlah -->
                        <div class="text-sm text-gray-700">
                            Menampilkan <span class="font-medium">{{ $contents->firstItem() }}</span> -
                            <span class="font-medium">{{ $contents->lastItem() }}</span> dari
                            <span class="font-medium">{{ $contents->total() }}</span> konten
                        </div>

                        <!-- Pagination -->
                        @if($contents->hasPages())
                        <nav class="flex items-center space-x-2">
                            {{ $contents->withQueryString()->onEachSide(1)->links('vendor.pagination.tailwind') }}
                        </nav>
                        @endif

                        <!-- Order controls -->
                        <div class="flex items-center gap-3">
                            <button type="button"
                                id="saveOrder"
                                class="px-4 py-2 bg-green-600 text-white rounded-md text-sm font-medium hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                Simpan Urutan
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card View (Hidden by Default) -->
            <div id="cardView" class="hidden">
                <!-- Content organized by type sections -->
                @php
                $carouselContents = $contents->where('content_type', 'carousel');
                $founderContents = $contents->where('content_type', 'about_founder');
                $leaderContents = $contents->where('content_type', 'about_leader');
                $footerContents = $contents->where('content_type', 'footer');
                $sectionContents = $contents->where('content_type', 'section_title');
                @endphp

                <!-- Carousel Section -->
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
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4">
                        @foreach($carouselContents as $content)
                        <div class="group bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-lg transition-all duration-300 overflow-hidden hover:-translate-y-1 flex flex-col">
                            <div class="relative bg-gradient-to-br from-gray-100 to-gray-200 overflow-hidden aspect-[16/9]">
                                @if($content->image)
                                <img src="{{ Storage::url($content->image) }}"
                                    alt="{{ $content->title }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                @else
                                <div class="w-full h-full flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span class="mt-3 bg-blue-100 text-blue-800 text-xs font-medium px-3 py-1 rounded-full">Carousel</span>
                                </div>
                                @endif
                            </div>

                            <div class="p-4 flex-grow flex flex-col">
                                <div class="flex justify-between items-start mb-3">
                                    <h6 class="font-bold text-gray-900 truncate pr-2 flex-grow" title="{{ $content->title }}">
                                        {{ $content->title ? Str::limit($content->title, 40) : 'Tanpa Judul' }}
                                    </h6>
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
                                        </a>

                                        <button class="delete-btn p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                            data-id="{{ $content->id_content }}"
                                            data-title="{{ $content->title }}"
                                            title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Founder Section -->
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
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                        @foreach($founderContents as $content)
                        <div class="group bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-lg transition-all duration-300 overflow-hidden hover:-translate-y-1">
                            <div class="p-4">
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
                                            <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                        @endif

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

                                <div class="text-center mb-3">
                                    <h5 class="font-bold text-gray-900 text-sm mb-1 truncate" title="{{ $content->title ?? 'Tanpa Nama' }}">
                                        {{ $content->title ?? 'Tanpa Nama' }}
                                    </h5>
                                    <p class="text-xs text-gray-600 truncate" title="{{ $content->position ?? 'Tidak ada jabatan' }}">
                                        {{ $content->position ?? 'Tidak ada jabatan' }}
                                    </p>
                                </div>

                                @if($content->subtitle || $content->description)
                                <div class="mb-4">
                                    <p class="text-xs text-gray-500 text-center line-clamp-2">
                                        {{ $content->subtitle ?? Str::limit($content->description, 80) }}
                                    </p>
                                </div>
                                @endif

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

                                <div class="flex space-x-2">
                                    <button class="quick-preview-btn flex-1 py-2 bg-blue-50 text-blue-700 hover:bg-blue-100 rounded-lg font-medium text-xs transition-colors flex items-center justify-center"
                                        data-id="{{ $content->id_content }}">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Lihat
                                    </button>
                                    <a href="{{ route('admin.landing-content.edit', $content->id_content) }}"
                                        class="flex-1 py-2 bg-amber-50 text-amber-700 hover:bg-amber-100 rounded-lg font-medium text-xs transition-colors flex items-center justify-center">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit
                                    </a>
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
                        <div class="flex flex-col sm:flex-row gap-3 justify-center">
                            <a href="{{ route('admin.landing-content.create') }}"
                                class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-lg font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Tambah Konten
                            </a>
                            @if($userPonpes && $userPonpes->id_ponpes)
                            <a href="{{ route('landing.index', ['ponpes_id' => $userPonpes->id_ponpes]) }}"
                                target="_blank"
                                class="inline-flex items-center px-6 py-3 bg-green-600 border border-transparent rounded-lg font-semibold text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Lihat Landing Page
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Pagination for Card View -->
            @if($contents->hasPages())
            <div id="cardPagination" class="hidden bg-white rounded-lg shadow-sm border border-gray-200 p-4 mt-6">
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
                        {{ $contents->withQueryString()->onEachSide(1)->links('vendor.pagination.tailwind') }}
                    </div>
                </div>
            </div>
            @endif
        </div>
    </main>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // View Toggle
        $('#tableViewBtn').click(function() {
            $('#tableView').show();
            $('#cardView').hide();
            $('#cardPagination').hide();
            $(this).removeClass('text-gray-500 hover:text-gray-700 hover:bg-gray-50')
                .addClass('bg-blue-100 text-blue-700');
            $('#cardViewBtn').removeClass('bg-blue-100 text-blue-700')
                .addClass('text-gray-500 hover:text-gray-700 hover:bg-gray-50');
        });

        $('#cardViewBtn').click(function() {
            $('#tableView').hide();
            $('#cardView').show();
            $('#cardPagination').show();
            $(this).removeClass('text-gray-500 hover:text-gray-700 hover:bg-gray-50')
                .addClass('bg-blue-100 text-blue-700');
            $('#tableViewBtn').removeClass('bg-blue-100 text-blue-700')
                .addClass('text-gray-500 hover:text-gray-700 hover:bg-gray-50');
        });

        // Refresh Button
        $('#refreshBtn').click(function() {
            location.reload();
        });

        // Quick Preview Modal
        $('.quick-preview-btn').click(function(e) {
            e.preventDefault();
            e.stopPropagation();

            const contentId = $(this).data('id');
            loadContentDetail(contentId);
        });

        function loadContentDetail(contentId) {
            $.ajax({
                url: '/admin/landing-content/' + contentId + '/detail',
                method: 'GET',
                dataType: 'json',
                success: function(content) {
                    populateDetailModal(content);
                    $('#detailPreviewModal').modal('show');
                },
                error: function(xhr, status, error) {
                    console.error('Error loading content:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Tidak dapat memuat detail konten'
                    });
                }
            });
        }

        function populateDetailModal(content) {
            $('#detailModalTitle').text('Preview: ' + (content.title || 'Tanpa Judul'));

            if (content.image_url) {
                $('#detailImage').attr('src', content.image_url).show();
                $('#noImageDetail').hide();
            } else {
                $('#detailImage').hide();
                $('#noImageDetail').show();
            }

            const typeColors = {
                'carousel': 'bg-blue-100 text-blue-800',
                'about_founder': 'bg-green-100 text-green-800',
                'about_leader': 'bg-cyan-100 text-cyan-800',
                'footer': 'bg-gray-100 text-gray-800',
                'section_title': 'bg-amber-100 text-amber-800'
            };
            const colorClass = typeColors[content.content_type] || 'bg-gray-100 text-gray-800';
            $('#detailType').attr('class', `inline-block px-3 py-1 rounded-full text-xs font-medium ${colorClass}`)
                .text(content.content_type.replace('_', ' '));

            $('#detailPonpes').text(content.ponpes ? content.ponpes.nama_ponpes : 'Tidak ada pesantren');
            $('#detailStatus').attr('class', `inline-block px-3 py-1 rounded-full text-xs font-medium ${content.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}`)
                .text(content.is_active ? 'Aktif' : 'Nonaktif');
            $('#detailOrder').text(content.display_order);
            $('#detailCreated').text(content.created_at_formatted);
            $('#detailUpdated').text(content.updated_at_formatted);

            $('#detailTitle').text(content.title || 'Tanpa Judul');
            $('#detailSubtitle').text(content.subtitle || 'Tidak ada subjudul');
            $('#detailPosition').text(content.position || 'Tidak ada jabatan/posisi');

            if (content.description) {
                $('#detailDescription').html(content.description.replace(/\n/g, '<br>'));
            } else {
                $('#detailDescription').html('<p class="text-gray-500 italic">Tidak ada deskripsi</p>');
            }

            if (content.url) {
                $('#detailUrl').attr('href', content.url).show();
                $('#urlText').text(content.url);
                $('#noUrlDetail').hide();
            } else {
                $('#detailUrl').hide();
                $('#noUrlDetail').show();
            }

            $('#editBtn').attr('href', '/admin/landing-content/' + content.id_content + '/edit');
            $('#fullViewBtn').attr('href', '/admin/landing-content/' + content.id_content);
        }

        // Filter handling
        $('#filterPonpes, #filterType').change(function() {
            $('#filterForm').submit();
        });

        $('#searchInput').keypress(function(e) {
            if (e.which === 13) {
                $('#filterForm').submit();
            }
        });

        // Status toggle
        $('.status-toggle').change(function() {
            const contentId = $(this).data('id');
            const isActive = this.checked ? 1 : 0;
            const card = this.closest('.group') || this.closest('tr');

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

        // Save order
        $('#saveOrder').click(function() {
            const orders = [];
            $('.order-input').each(function() {
                orders.push({
                    id: $(this).data('id'),
                    order: $(this).val()
                });
            });

            const $button = $(this);
            $button.prop('disabled', true).html(`
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 animate-spin mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Menyimpan...
        `);

            $.ajax({
                url: '/admin/landing-content/update-order',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    items: orders
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Urutan berhasil disimpan',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Terjadi kesalahan saat menyimpan urutan'
                    });
                    $button.prop('disabled', false).html('Simpan Urutan');
                }
            });
        });

        // Image modal
        $('#imageModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const imageUrl = button.data('image');
            const title = button.data('title');

            $(this).find('#modalImage').attr('src', imageUrl).attr('alt', title);
            $(this).find('.modal-title').text('Preview: ' + title);
        });

        // Delete confirmation
        $('.delete-btn').click(function() {
            const contentId = $(this).data('id');
            const contentTitle = $(this).data('title');

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
</script>
@endpush