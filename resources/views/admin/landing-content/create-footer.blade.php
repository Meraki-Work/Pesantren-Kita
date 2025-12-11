@extends('index')

@section('title', 'Tambah Footer Link')

@section('content')
<div class="min-h-screen bg-gray-50 p-4 md:p-6">
    <div class="max-w-7xl mx-auto">
        <div class="mb-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                        </svg>
                        Tambah Footer Link
                    </h1>
                    <p class="text-gray-600 mt-1">Link penting di bagian footer website</p>
                </div>
                <a href="{{ route('admin.landing-content.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="border-b border-gray-200 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Formulir Footer Link</h2>
                        <p class="text-sm text-gray-600 mt-1">Tambah link untuk bagian footer pesantren</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.landing-content.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                <input type="hidden" name="content_type" value="footer">
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Kolom Kiri - Form Informasi -->
                    <div class="lg:col-span-2">
                        <div class="border border-gray-200 rounded-lg overflow-hidden h-full">
                            <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                                <h3 class="font-semibold text-gray-900">Informasi Link</h3>
                            </div>
                            <div class="p-6 space-y-6">
                                <!-- Pesantren Selection -->
                                <div>
                                    <label for="ponpes_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Pesantren <span class="text-red-500">*</span>
                                    </label>
                                    <select name="ponpes_id" id="ponpes_id" 
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 @error('ponpes_id') border-red-300 @enderror"
                                            required>
                                        <option value="">Pilih Pesantren</option>
                                        @foreach($ponpesList as $ponpes)
                                        <option value="{{ $ponpes->id_ponpes }}" 
                                                {{ old('ponpes_id') == $ponpes->id_ponpes ? 'selected' : '' }}>
                                            {{ $ponpes->nama_ponpes }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('ponpes_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Judul dan Kategori -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                            Judul Link <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="title" id="title" 
                                               value="{{ old('title') }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 @error('title') border-red-300 @enderror"
                                               placeholder="Contoh: Instagram Pesantren" required>
                                        @error('title')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div>
                                        <label for="position" class="block text-sm font-medium text-gray-700 mb-2">
                                            Posisi/Kategori
                                        </label>
                                        <select name="position" id="position" 
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 @error('position') border-red-300 @enderror">
                                            <option value="">Pilih Kategori</option>
                                            <option value="social" {{ old('position') == 'social' ? 'selected' : '' }}>Sosial Media</option>
                                            <option value="contact" {{ old('position') == 'contact' ? 'selected' : '' }}>Kontak</option>
                                            <option value="quick_link" {{ old('position') == 'quick_link' ? 'selected' : '' }}>Quick Link</option>
                                            <option value="legal" {{ old('position') == 'legal' ? 'selected' : '' }}>Legal</option>
                                            <option value="other" {{ old('position') == 'other' ? 'selected' : '' }}>Lainnya</option>
                                        </select>
                                        @error('position')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Deskripsi -->
                                <div>
                                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                        Deskripsi <span class="text-red-500">*</span>
                                    </label>
                                    <textarea name="description" id="description" rows="3"
                                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 @error('description') border-red-300 @enderror"
                                              placeholder="Deskripsi singkat link..." required>{{ old('description') }}</textarea>
                                    @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-xs text-gray-500">Contoh: Ikuti kami di Instagram untuk update terbaru</p>
                                </div>

                                <!-- URL Link -->
                                <div>
                                    <label for="url" class="block text-sm font-medium text-gray-700 mb-2">
                                        URL Link <span class="text-red-500">*</span>
                                    </label>
                                    <input type="url" name="url" id="url" 
                                           value="{{ old('url') }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 @error('url') border-red-300 @enderror"
                                           placeholder="https://instagram.com/pesantren_alkhair" required>
                                    @error('url')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-xs text-gray-500">Pastikan URL valid dan lengkap dengan https://</p>
                                </div>

                                <!-- Urutan dan Ikon -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="display_order" class="block text-sm font-medium text-gray-700 mb-2">
                                            Urutan Tampilan <span class="text-red-500">*</span>
                                        </label>
                                        <input type="number" name="display_order" id="display_order" 
                                               value="{{ old('display_order', 1) }}" min="1"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 @error('display_order') border-red-300 @enderror"
                                               required>
                                        @error('display_order')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                        <p class="mt-1 text-xs text-gray-500">Atur urutan tampil di footer</p>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Ikon (Opsional)
                                        </label>
                                        <select name="icon" 
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500">
                                            <option value="">Pilih Ikon</option>
                                            <option value="instagram" {{ old('icon') == 'instagram' ? 'selected' : '' }}>Instagram</option>
                                            <option value="facebook" {{ old('icon') == 'facebook' ? 'selected' : '' }}>Facebook</option>
                                            <option value="twitter" {{ old('icon') == 'twitter' ? 'selected' : '' }}>Twitter/X</option>
                                            <option value="youtube" {{ old('icon') == 'youtube' ? 'selected' : '' }}>YouTube</option>
                                            <option value="whatsapp" {{ old('icon') == 'whatsapp' ? 'selected' : '' }}>WhatsApp</option>
                                            <option value="envelope" {{ old('icon') == 'envelope' ? 'selected' : '' }}>Email</option>
                                            <option value="phone" {{ old('icon') == 'phone' ? 'selected' : '' }}>Telepon</option>
                                            <option value="location" {{ old('icon') == 'location' ? 'selected' : '' }}>Lokasi</option>
                                            <option value="link" {{ old('icon') == 'link' ? 'selected' : '' }}>Link Umum</option>
                                        </select>
                                        <p class="mt-1 text-xs text-gray-500">Ikon akan ditampilkan di samping link</p>
                                    </div>
                                </div>

                                <!-- Status Aktif -->
                                <div class="pt-4 border-t border-gray-100">
                                    <div class="flex items-center">
                                        <input type="checkbox" name="is_active" id="is_active" value="1" 
                                               {{ old('is_active', true) ? 'checked' : '' }}
                                               class="h-4 w-4 text-gray-600 focus:ring-gray-500 border-gray-300 rounded">
                                        <label for="is_active" class="ml-2 block text-sm font-medium text-gray-900">
                                            Aktifkan Link
                                        </label>
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">Nonaktifkan untuk menyembunyikan link dari footer</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kolom Kanan - Preview -->
                    <div class="lg:col-span-1">
                        <div class="border border-gray-200 rounded-lg overflow-hidden h-full">
                            <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                                <h3 class="font-semibold text-gray-900">Preview Footer</h3>
                            </div>
                            <div class="p-6 space-y-6">
                                <!-- Contoh Footer Link -->
                                <div>
                                    <h4 class="font-semibold text-gray-900 text-sm mb-3">Contoh Footer Link:</h4>
                                    <div class="space-y-3">
                                        <a href="#" class="block p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                                            <div class="flex items-start">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-pink-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                                </svg>
                                                <div class="flex-1">
                                                    <p class="font-medium text-gray-900">Instagram Pesantren</p>
                                                    <p class="text-xs text-gray-500 mt-1">Ikuti update terbaru</p>
                                                </div>
                                            </div>
                                        </a>
                                        <a href="#" class="block p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                                            <div class="flex items-start">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.074-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.76.982.998-3.675-.236-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.897 6.994c-.004 5.45-4.438 9.88-9.888 9.88m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.333.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.333 11.893-11.893 0-3.18-1.24-6.162-3.495-8.411"/>
                                                </svg>
                                                <div class="flex-1">
                                                    <p class="font-medium text-gray-900">WhatsApp Kontak</p>
                                                    <p class="text-xs text-gray-500 mt-1">Hubungi kami via WhatsApp</p>
                                                </div>
                                            </div>
                                        </a>
                                        <a href="#" class="block p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                                            <div class="flex items-start">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                </svg>
                                                <div class="flex-1">
                                                    <p class="font-medium text-gray-900">Email Resmi</p>
                                                    <p class="text-xs text-gray-500 mt-1">Kirim email ke kami</p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                
                                <!-- Tips Card -->
                                <div class="rounded-lg bg-blue-50 border border-blue-200 p-4">
                                    <div class="flex items-start">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <div>
                                            <h5 class="font-semibold text-blue-900 text-sm mb-2">Tips Footer Link:</h5>
                                            <ul class="text-xs text-blue-700 space-y-1">
                                                <li class="flex items-start">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    <span>Gunakan ikon yang sesuai dengan platform</span>
                                                </li>
                                                <li class="flex items-start">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    <span>Pastikan link aktif dan dapat diakses</span>
                                                </li>
                                                <li class="flex items-start">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    <span>Deskripsi harus jelas dan informatif</span>
                                                </li>
                                                <li class="flex items-start">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    <span>Kelompokkan link berdasarkan kategori</span>
                                                </li>
                                                <li class="flex items-start">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    <span>Maksimal 8-10 link per pesantren</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Link Populer -->
                                <div>
                                    <h4 class="font-semibold text-gray-900 text-sm mb-3">Link Populer:</h4>
                                    <div class="flex flex-wrap gap-2">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                            Instagram
                                        </span>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                            Facebook
                                        </span>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                            YouTube
                                        </span>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                            WhatsApp
                                        </span>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                            Email
                                        </span>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                            Kontak
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.landing-content.create') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200 w-full sm:w-auto justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali ke Pilihan
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-6 py-3 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200 w-full sm:w-auto justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                        </svg>
                        Simpan Footer Link
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // URL validation helper
    const urlInput = document.getElementById('url');
    if (urlInput) {
        urlInput.addEventListener('blur', function() {
            const value = this.value;
            if (value && !value.startsWith('http://') && !value.startsWith('https://')) {
                this.value = 'https://' + value;
            }
        });
    }
    
    // Display order validation
    const displayOrderInput = document.getElementById('display_order');
    if (displayOrderInput) {
        displayOrderInput.addEventListener('input', function() {
            if (this.value < 1) {
                this.value = 1;
            }
        });
    }
    
    // Icon preview functionality
    const iconSelect = document.querySelector('select[name="icon"]');
    if (iconSelect) {
        iconSelect.addEventListener('change', function() {
            const selectedIcon = this.value;
            console.log('Selected icon:', selectedIcon);
            // You could add icon preview logic here
        });
    }
});
</script>
@endpush