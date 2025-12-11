@extends('index')

@section('title', 'Tambah Carousel')

@section('content')
<div class="min-h-screen bg-gray-50 p-4 md:p-6">
    <div class="max-w-7xl mx-auto">
        <div class="mb-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Tambah Carousel
                    </h1>
                    <p class="text-gray-600 mt-1">Gambar slider utama di halaman depan</p>
                </div>
                <a href="{{ route('admin.landing-content.index') }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
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
                        <h2 class="text-lg font-semibold text-gray-900">Formulir Carousel</h2>
                        <p class="text-sm text-gray-600 mt-1">Tambah slider untuk halaman depan pesantren</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.landing-content.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                <input type="hidden" name="content_type" value="carousel">

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Kolom Kiri - Form Informasi -->
                    <div class="lg:col-span-2">
                        <div class="border border-gray-200 rounded-lg overflow-hidden h-full">
                            <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                                <h3 class="font-semibold text-gray-900">Informasi Carousel</h3>
                            </div>
                            <div class="p-6 space-y-6">
                                <!-- Pesantren Selection -->
                                <div>
                                    <label for="ponpes_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Pesantren <span class="text-red-500">*</span>
                                    </label>
                                    <select name="ponpes_id" id="ponpes_id"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('ponpes_id') border-red-300 @enderror"
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

                                <!-- Judul -->
                                <div>
                                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                        Judul Carousel
                                    </label>
                                    <input type="text" name="title" id="title"
                                        value="{{ old('title') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('title') border-red-300 @enderror"
                                        placeholder="Contoh: Selamat Datang di Pesantren Al-Ikhlas">
                                    @error('title')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Subjudul -->
                                <div>
                                    <label for="subtitle" class="block text-sm font-medium text-gray-700 mb-2">
                                        Subjudul
                                    </label>
                                    <textarea name="subtitle" id="subtitle" rows="2"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('subtitle') border-red-300 @enderror"
                                        placeholder="Deskripsi singkat">{{ old('subtitle') }}</textarea>
                                    @error('subtitle')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Deskripsi Lengkap -->
                                <div>
                                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                        Deskripsi Lengkap
                                    </label>
                                    <textarea name="description" id="description" rows="4"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('description') border-red-300 @enderror"
                                        placeholder="Deskripsi detail tentang pesantren">{{ old('description') }}</textarea>
                                    @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Link dan Urutan -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="url" class="block text-sm font-medium text-gray-700 mb-2">
                                            Link Tujuan (Opsional)
                                        </label>
                                        <input type="url" name="url" id="url"
                                            value="{{ old('url') }}"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('url') border-red-300 @enderror"
                                            placeholder="https://example.com">
                                        @error('url')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="display_order" class="block text-sm font-medium text-gray-700 mb-2">
                                            Urutan Tampilan <span class="text-red-500">*</span>
                                        </label>
                                        <input type="number" name="display_order" id="display_order"
                                            value="{{ old('display_order', 1) }}" min="1"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('display_order') border-red-300 @enderror"
                                            required>
                                        @error('display_order')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                        <p class="mt-1 text-xs text-gray-500">Angka kecil muncul lebih dulu</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kolom Kanan - Upload Gambar dan Tips -->
                    <div class="lg:col-span-1 space-y-6">
                        <!-- Upload Gambar Card -->
                        <div class="border border-gray-200 rounded-lg overflow-hidden">
                            <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                                <h3 class="font-semibold text-gray-900">
                                    Gambar Carousel <span class="text-red-500">*</span>
                                </h3>
                            </div>
                            <div class="p-4">
                                <div class="mb-4">
                                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                                        Upload Gambar
                                    </label>
                                    <input type="file" name="image" id="image"
                                        class="block w-full text-sm text-gray-900 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 @error('image') border-red-300 @enderror"
                                        accept="image/*" required>
                                    @error('image')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <div class="mt-2 text-sm text-gray-500 space-y-1">
                                        <p>Format: JPG, PNG, GIF, WEBP</p>
                                        <p>Maksimal: 2MB</p>
                                        <p>Ukuran disarankan: 1280×720 px</p>
                                    </div>
                                </div>

                                <!-- Image Preview -->
                                <div class="mb-4">
                                    <div id="imagePreview" class="hidden">
                                        <img id="previewImage" class="w-full h-auto max-h-48 object-cover rounded-lg border border-gray-200">
                                    </div>
                                    <div id="imagePlaceholder" class="text-center p-6 border-2 border-dashed border-gray-300 rounded-lg bg-gray-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <p class="mt-3 text-sm text-gray-500">Preview gambar akan muncul di sini</p>
                                    </div>
                                </div>

                                <!-- Status Toggle -->
                                <div class="pt-4 border-t border-gray-100">
                                    <div class="flex items-center">
                                        <input type="checkbox" name="is_active" id="is_active" value="1"
                                            {{ old('is_active', true) ? 'checked' : '' }}
                                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                        <label for="is_active" class="ml-2 block text-sm font-medium text-gray-900">
                                            Aktifkan Carousel
                                        </label>
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">Nonaktifkan untuk menyembunyikan carousel</p>
                                </div>
                            </div>
                        </div>

                        <!-- Tips Card -->
                        <div class="border border-gray-200 rounded-lg overflow-hidden">
                            <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                                <h3 class="font-semibold text-gray-900">Tips Carousel</h3>
                            </div>
                            <div class="p-4">
                                <ul class="space-y-3">
                                    <li class="flex items-start">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span class="text-sm text-gray-700">Gunakan gambar berkualitas tinggi</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span class="text-sm text-gray-700">Judul harus menarik perhatian</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span class="text-sm text-gray-700">Maksimal 5-7 carousel per pesantren</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span class="text-sm text-gray-700">Atur urutan yang logis</span>
                                    </li>
                                    <li class="flex items-start">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span class="text-sm text-gray-700">Pastikan konten relevan</span>
                                    </li>
                                </ul>
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
                        class="inline-flex items-center px-6 py-3 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200 w-full sm:w-auto justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                        </svg>
                        Simpan Carousel
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
        const imageInput = document.getElementById('image');
        const previewImage = document.getElementById('previewImage');
        const previewContainer = document.getElementById('imagePreview');
        const placeholder = document.getElementById('imagePlaceholder');

        console.log('Script loaded');
        console.log('imageInput:', imageInput);
        console.log('previewImage:', previewImage);
        console.log('previewContainer:', previewContainer);
        console.log('placeholder:', placeholder);

        if (imageInput && previewImage && previewContainer && placeholder) {
            console.log('All elements found');
            
            imageInput.addEventListener('change', function(e) {
                console.log('File input changed');
                const file = e.target.files[0];

                if (file) {
                    console.log('File selected:', file.name, file.type, file.size);
                    
                    // Validate file type
                    const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                    if (!validTypes.includes(file.type)) {
                        alert('Format file tidak didukung. Gunakan JPG, PNG, GIF, atau WEBP.');
                        this.value = '';
                        console.log('Invalid file type');
                        return;
                    }

                    // Validate file size (2MB max)
                    const maxSize = 2 * 1024 * 1024;
                    if (file.size > maxSize) {
                        alert('Ukuran file terlalu besar. Maksimal 2MB.');
                        this.value = '';
                        console.log('File too large');
                        return;
                    }

                    const reader = new FileReader();

                    reader.onload = function(e) {
                        console.log('FileReader onload triggered');
                        previewImage.src = e.target.result;
                        previewContainer.classList.remove('hidden');
                        placeholder.classList.add('hidden');
                        console.log('Preview shown, placeholder hidden');
                    };

                    reader.onerror = function() {
                        console.error('FileReader error');
                        alert('Gagal membaca file gambar.');
                        previewContainer.classList.add('hidden');
                        placeholder.classList.remove('hidden');
                    };

                    reader.readAsDataURL(file);
                } else {
                    console.log('No file selected');
                    previewContainer.classList.add('hidden');
                    placeholder.classList.remove('hidden');
                }
            });
        } else {
            console.error('One or more image preview elements not found');
        }

        // For edit pages - show existing image if src exists
        if (previewImage && previewImage.src) {
            const currentSrc = previewImage.src;
            // Check if src is not empty and not the current page URL
            if (currentSrc && currentSrc !== 'about:blank' && currentSrc !== window.location.href) {
                console.log('Existing image found, showing preview');
                previewContainer.classList.remove('hidden');
                placeholder.classList.add('hidden');
            }
        }

        // Display Order validation
        const displayOrderInput = document.getElementById('display_order');
        if (displayOrderInput) {
            displayOrderInput.addEventListener('input', function() {
                if (this.value < 1) {
                    this.value = 1;
                }
            });
        }
    });
</script>
@endpush