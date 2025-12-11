@extends('index')

@section('title', 'Tambah Section Title')

@section('content')
<div class="min-h-screen bg-gray-50 p-4 md:p-6">
    <div class="max-w-7xl mx-auto">
        <div class="mb-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                        </svg>
                        Tambah Section Title
                    </h1>
                    <p class="text-gray-600 mt-1">Judul dan deskripsi untuk setiap bagian halaman</p>
                </div>
                <a href="{{ route('admin.landing-content.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-colors duration-200">
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
                        <h2 class="text-lg font-semibold text-gray-900">Formulir Section Title</h2>
                        <p class="text-sm text-gray-600 mt-1">Tambah judul untuk bagian-bagian halaman pesantren</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.landing-content.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                <input type="hidden" name="content_type" value="section_title">
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Kolom Kiri - Form Informasi -->
                    <div class="lg:col-span-2">
                        <div class="border border-gray-200 rounded-lg overflow-hidden h-full">
                            <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                                <h3 class="font-semibold text-gray-900">Informasi Section</h3>
                            </div>
                            <div class="p-6 space-y-6">
                                <!-- Pesantren Selection -->
                                <div>
                                    <label for="ponpes_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Pesantren <span class="text-red-500">*</span>
                                    </label>
                                    <select name="ponpes_id" id="ponpes_id" 
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('ponpes_id') border-red-300 @enderror"
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

                                <!-- Judul Section -->
                                <div>
                                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                        Judul Section <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="title" id="title" 
                                           value="{{ old('title') }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('title') border-red-300 @enderror"
                                           placeholder="Contoh: Tentang Pesantren" required>
                                    @error('title')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Subjudul -->
                                <div>
                                    <label for="subtitle" class="block text-sm font-medium text-gray-700 mb-2">
                                        Subjudul/Deskripsi Singkat
                                    </label>
                                    <textarea name="subtitle" id="subtitle" rows="2"
                                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('subtitle') border-red-300 @enderror"
                                              placeholder="Deskripsi singkat tentang section">{{ old('subtitle') }}</textarea>
                                    @error('subtitle')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Posisi dan Urutan -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="position" class="block text-sm font-medium text-gray-700 mb-2">
                                            Posisi/Section ID
                                        </label>
                                        <select name="position" id="position" 
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('position') border-red-300 @enderror">
                                            <option value="">Pilih Posisi</option>
                                            <option value="about" {{ old('position') == 'about' ? 'selected' : '' }}>Tentang Kami</option>
                                            <option value="program" {{ old('position') == 'program' ? 'selected' : '' }}>Program</option>
                                            <option value="facility" {{ old('position') == 'facility' ? 'selected' : '' }}>Fasilitas</option>
                                            <option value="founder" {{ old('position') == 'founder' ? 'selected' : '' }}>Founder</option>
                                            <option value="leader" {{ old('position') == 'leader' ? 'selected' : '' }}>Pengurus</option>
                                            <option value="testimonial" {{ old('position') == 'testimonial' ? 'selected' : '' }}>Testimoni</option>
                                            <option value="gallery" {{ old('position') == 'gallery' ? 'selected' : '' }}>Galeri</option>
                                            <option value="contact" {{ old('position') == 'contact' ? 'selected' : '' }}>Kontak</option>
                                            <option value="header" {{ old('position') == 'header' ? 'selected' : '' }}>Header</option>
                                            <option value="hero" {{ old('position') == 'hero' ? 'selected' : '' }}>Hero Section</option>
                                            <option value="custom" {{ old('position') == 'custom' ? 'selected' : '' }}>Custom</option>
                                        </select>
                                        @error('position')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                        <p class="mt-1 text-xs text-gray-500">Untuk mengelompokkan section yang sama</p>
                                    </div>
                                    
                                    <div>
                                        <label for="display_order" class="block text-sm font-medium text-gray-700 mb-2">
                                            Urutan Tampilan <span class="text-red-500">*</span>
                                        </label>
                                        <input type="number" name="display_order" id="display_order" 
                                               value="{{ old('display_order', 1) }}" min="1"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('display_order') border-red-300 @enderror"
                                               required>
                                        @error('display_order')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                        <p class="mt-1 text-xs text-gray-500">Atur urutan munculnya section di halaman</p>
                                    </div>
                                </div>

                                <!-- Konten Tambahan -->
                                <div>
                                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                        Konten Tambahan (Opsional)
                                    </label>
                                    <textarea name="description" id="description" rows="4"
                                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 @error('description') border-red-300 @enderror"
                                              placeholder="Konten tambahan untuk section">{{ old('description') }}</textarea>
                                    @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-xs text-gray-500">Untuk konten yang lebih panjang di bawah judul</p>
                                </div>

                                <!-- Status Aktif -->
                                <div class="pt-4 border-t border-gray-100">
                                    <div class="flex items-center">
                                        <input type="checkbox" name="is_active" id="is_active" value="1" 
                                               {{ old('is_active', true) ? 'checked' : '' }}
                                               class="h-4 w-4 text-amber-600 focus:ring-amber-500 border-gray-300 rounded">
                                        <label for="is_active" class="ml-2 block text-sm font-medium text-gray-900">
                                            Tampilkan Section
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kolom Kanan - Preview -->
                    <div class="lg:col-span-1">
                        <div class="border border-gray-200 rounded-lg overflow-hidden h-full">
                            <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                                <h3 class="font-semibold text-gray-900">Preview Section</h3>
                            </div>
                            <div class="p-6 space-y-6">
                                <!-- Live Preview -->
                                <div class="bg-gradient-to-br from-amber-50 to-yellow-50 rounded-lg p-6 border border-amber-200 text-center">
                                    <h3 id="previewTitle" class="text-2xl font-bold text-gray-900 mb-3">
                                        {{ old('title', 'Tentang Pesantren') }}
                                    </h3>
                                    <p id="previewSubtitle" class="text-gray-600 mb-4">
                                        {{ old('subtitle', 'Deskripsi singkat tentang section ini') }}
                                    </p>
                                    <div class="w-24 h-1 mx-auto bg-gradient-to-r from-amber-400 to-yellow-500 rounded-full"></div>
                                </div>

                                <!-- Contoh Section -->
                                <div>
                                    <h4 class="font-semibold text-gray-900 text-sm mb-3">Contoh Section:</h4>
                                    <div class="space-y-3">
                                        <div class="p-3 border border-gray-200 rounded-lg">
                                            <div class="flex justify-between items-center">
                                                <div>
                                                    <p class="font-medium text-gray-900">Tentang Kami</p>
                                                    <p class="text-xs text-gray-500 mt-1">position: about</p>
                                                </div>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                                    #1
                                                </span>
                                            </div>
                                        </div>
                                        <div class="p-3 border border-gray-200 rounded-lg">
                                            <div class="flex justify-between items-center">
                                                <div>
                                                    <p class="font-medium text-gray-900">Program Unggulan</p>
                                                    <p class="text-xs text-gray-500 mt-1">position: program</p>
                                                </div>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                                    #2
                                                </span>
                                            </div>
                                        </div>
                                        <div class="p-3 border border-gray-200 rounded-lg">
                                            <div class="flex justify-between items-center">
                                                <div>
                                                    <p class="font-medium text-gray-900">Fasilitas</p>
                                                    <p class="text-xs text-gray-500 mt-1">position: facility</p>
                                                </div>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                                    #3
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tips Card -->
                                <div class="rounded-lg bg-amber-50 border border-amber-200 p-4">
                                    <div class="flex items-start">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-600 mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <div>
                                            <h5 class="font-semibold text-amber-900 text-sm mb-2">Tips Section Title:</h5>
                                            <ul class="text-xs text-amber-700 space-y-1">
                                                <li class="flex items-start">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    <span>Judul harus jelas dan deskriptif</span>
                                                </li>
                                                <li class="flex items-start">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    <span>Gunakan subjudul untuk penjelasan tambahan</span>
                                                </li>
                                                <li class="flex items-start">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    <span>Atur position untuk grouping yang tepat</span>
                                                </li>
                                                <li class="flex items-start">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    <span>Urutan menentukan posisi di halaman</span>
                                                </li>
                                                <li class="flex items-start">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    <span>Gunakan konten tambahan jika diperlukan</span>
                                                </li>
                                            </ul>
                                        </div>
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
                            class="inline-flex items-center px-6 py-3 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-colors duration-200 w-full sm:w-auto justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                        </svg>
                        Simpan Section Title
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
    // Live preview untuk title dan subtitle
    const titleInput = document.getElementById('title');
    const subtitleInput = document.getElementById('subtitle');
    const previewTitle = document.getElementById('previewTitle');
    const previewSubtitle = document.getElementById('previewSubtitle');
    
    if (titleInput && previewTitle) {
        titleInput.addEventListener('input', function(e) {
            previewTitle.textContent = e.target.value || 'Tentang Pesantren';
        });
    }
    
    if (subtitleInput && previewSubtitle) {
        subtitleInput.addEventListener('input', function(e) {
            previewSubtitle.textContent = e.target.value || 'Deskripsi singkat tentang section ini';
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
    
    // Position select change - bisa ditambahkan logika custom untuk "custom" position
    const positionSelect = document.getElementById('position');
    let customPositionInput = null;
    
    if (positionSelect) {
        positionSelect.addEventListener('change', function() {
            if (this.value === 'custom') {
                if (!customPositionInput) {
                    const parentDiv = this.parentElement;
                    customPositionInput = document.createElement('div');
                    customPositionInput.className = 'mt-2';
                    customPositionInput.innerHTML = `
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Custom Position Name
                        </label>
                        <input type="text" name="custom_position_name" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                               placeholder="Masukkan nama position custom">
                    `;
                    parentDiv.parentElement.appendChild(customPositionInput);
                } else {
                    customPositionInput.classList.remove('hidden');
                }
            } else if (customPositionInput) {
                customPositionInput.classList.add('hidden');
            }
        });
        
        // Trigger on load if "custom" is already selected
        if (positionSelect.value === 'custom') {
            positionSelect.dispatchEvent(new Event('change'));
        }
    }
});
</script>
@endpush

@push('styles')
<style>
/* Custom styles untuk preview */
.preview-container {
    background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
}
</style>
@endpush