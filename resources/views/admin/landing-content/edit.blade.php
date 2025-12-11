@extends('index')

@section('title', 'Edit Konten Landing Page')

@section('content')
<div class="min-h-screen bg-gray-50 p-4 md:p-6">
    <div class="max-w-6xl mx-auto">
        <div class="mb-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">
                        Edit Konten Landing Page
                    </h1>
                    <p class="text-gray-600 mt-1">Update konten untuk halaman depan pesantren</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.landing-content.show', $landingContent->id_content) }}" 
                       class="inline-flex items-center px-4 py-2 border border-blue-300 rounded-md shadow-sm text-sm font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        Lihat Detail
                    </a>
                    <a href="{{ route('admin.landing-content.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="border-b border-gray-200 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Form Edit Konten</h2>
                        <p class="text-sm text-gray-600 mt-1">Update informasi konten landing page</p>
                    </div>
                    <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        ID: {{ $landingContent->id_content }}
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.landing-content.update', $landingContent->id_content) }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    <!-- Pesantren dan Tipe Konten -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="ponpes_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Pesantren <span class="text-red-500">*</span>
                            </label>
                            <select name="ponpes_id" id="ponpes_id" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('ponpes_id') border-red-300 @enderror"
                                    required>
                                <option value="">Pilih Pesantren</option>
                                @foreach($ponpesList as $ponpes)
                                <option value="{{ $ponpes->id_ponpes }}" 
                                        {{ old('ponpes_id', $landingContent->ponpes_id) == $ponpes->id_ponpes ? 'selected' : '' }}>
                                    {{ $ponpes->nama_ponpes }}
                                </option>
                                @endforeach
                            </select>
                            @error('ponpes_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="content_type" class="block text-sm font-medium text-gray-700 mb-2">
                                Tipe Konten <span class="text-red-500">*</span>
                            </label>
                            <select name="content_type" id="content_type" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('content_type') border-red-300 @enderror"
                                    required>
                                <option value="">Pilih Tipe Konten</option>
                                @foreach($contentTypes as $value => $label)
                                <option value="{{ $value }}" 
                                        {{ old('content_type', $landingContent->content_type) == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                                @endforeach
                            </select>
                            @error('content_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Judul dan Posisi -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                Judul
                            </label>
                            <input type="text" name="title" id="title" 
                                   value="{{ old('title', $landingContent->title) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-300 @enderror"
                                   placeholder="Masukkan judul konten">
                            @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="position" class="block text-sm font-medium text-gray-700 mb-2">
                                Posisi/Jabatan
                            </label>
                            <input type="text" name="position" id="position" 
                                   value="{{ old('position', $landingContent->position) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('position') border-red-300 @enderror"
                                   placeholder="Contoh: Founder, Ketua Yayasan">
                            @error('position')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Subjudul -->
                    <div>
                        <label for="subtitle" class="block text-sm font-medium text-gray-700 mb-2">
                            Subjudul
                        </label>
                        <textarea name="subtitle" id="subtitle" rows="2"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('subtitle') border-red-300 @enderror">{{ old('subtitle', $landingContent->subtitle) }}</textarea>
                        @error('subtitle')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Deskripsi
                        </label>
                        <textarea name="description" id="description" rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-300 @enderror">{{ old('description', $landingContent->description) }}</textarea>
                        @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Gambar dan URL -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Gambar -->
                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                                Gambar
                            </label>
                            
                            @if($landingContent->image)
                            <div class="mb-4">
                                <div class="mb-3">
                                    <p class="text-sm font-medium text-gray-700 mb-2">Gambar Saat Ini:</p>
                                    <img src="{{ Storage::url($landingContent->image) }}" 
                                         alt="Current Image" 
                                         class="w-full max-w-xs h-auto rounded-lg border border-gray-200">
                                </div>
                                
                                <div class="flex items-center">
                                    <input type="checkbox" name="remove_image" id="remove_image" value="1"
                                           class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                    <label for="remove_image" class="ml-2 text-sm font-medium text-red-600">
                                        Hapus gambar ini
                                    </label>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Centang untuk menghapus gambar saat ini</p>
                            </div>
                            @endif
                            
                            <div class="mt-2">
                                <input type="file" name="image" id="image" 
                                       class="block w-full text-sm text-gray-900 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 @error('image') border-red-300 @enderror"
                                       accept="image/*">
                                @error('image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Biarkan kosong jika tidak ingin mengubah gambar</p>
                                
                                <!-- Image Preview -->
                                <div id="imagePreview" class="mt-3 hidden">
                                    <p class="text-sm font-medium text-gray-700 mb-2">Preview Gambar Baru:</p>
                                    <img id="previewImage" class="w-full max-w-xs h-auto rounded-lg border border-gray-200">
                                </div>
                            </div>
                        </div>
                        
                        <!-- URL dan Urutan -->
                        <div class="space-y-6">
                            <div>
                                <label for="url" class="block text-sm font-medium text-gray-700 mb-2">
                                    URL/Link
                                </label>
                                <input type="url" name="url" id="url" 
                                       value="{{ old('url', $landingContent->url) }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('url') border-red-300 @enderror"
                                       placeholder="https://example.com">
                                @error('url')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="display_order" class="block text-sm font-medium text-gray-700 mb-2">
                                    Urutan Tampilan
                                </label>
                                <input type="number" name="display_order" id="display_order" 
                                       value="{{ old('display_order', $landingContent->display_order) }}" 
                                       min="0"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('display_order') border-red-300 @enderror">
                                @error('display_order')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Status Aktif -->
                    <div class="pt-4 border-t border-gray-100">
                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" id="is_active" value="1" 
                                   {{ old('is_active', $landingContent->is_active) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="is_active" class="ml-2 block text-sm font-medium text-gray-900">
                                Aktif
                            </label>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Nonaktifkan untuk menyembunyikan konten dari halaman</p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.landing-content.index') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200 w-full sm:w-auto justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Kembali ke Daftar
                        </a>
                        
                        <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                            <a href="{{ route('admin.landing-content.show', $landingContent->id_content) }}" 
                               class="inline-flex items-center px-4 py-2 border border-blue-300 rounded-md shadow-sm text-sm font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200 w-full sm:w-auto justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Lihat Detail
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center px-6 py-3 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200 w-full sm:w-auto justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                                </svg>
                                Update Konten
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Image Preview
    const imageInput = document.getElementById('image');
    const previewImage = document.getElementById('previewImage');
    const previewContainer = document.getElementById('imagePreview');
    
    if (imageInput && previewImage && previewContainer) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            
            if (file) {
                // Validate file type
                const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                if (!validTypes.includes(file.type)) {
                    alert('Format file tidak didukung. Gunakan JPG, PNG, GIF, atau WEBP.');
                    this.value = '';
                    return;
                }
                
                // Validate file size (2MB max)
                const maxSize = 2 * 1024 * 1024;
                if (file.size > maxSize) {
                    alert('Ukuran file terlalu besar. Maksimal 2MB.');
                    this.value = '';
                    return;
                }
                
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                };
                
                reader.readAsDataURL(file);
            } else {
                previewContainer.classList.add('hidden');
            }
        });
    }
    
    // Toggle remove image checkbox
    const removeImageCheckbox = document.getElementById('remove_image');
    if (removeImageCheckbox) {
        removeImageCheckbox.addEventListener('change', function() {
            const imageInput = document.getElementById('image');
            if (this.checked) {
                imageInput.disabled = true;
                imageInput.classList.add('opacity-50', 'cursor-not-allowed');
                previewContainer.classList.add('hidden');
            } else {
                imageInput.disabled = false;
                imageInput.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        });
    }
    
    // Display order validation
    const displayOrderInput = document.getElementById('display_order');
    if (displayOrderInput) {
        displayOrderInput.addEventListener('input', function() {
            if (this.value < 0) {
                this.value = 0;
            }
        });
    }
    
    // URL auto-correction
    const urlInput = document.getElementById('url');
    if (urlInput) {
        urlInput.addEventListener('blur', function() {
            const value = this.value;
            if (value && !value.startsWith('http://') && !value.startsWith('https://')) {
                this.value = 'https://' + value;
            }
        });
    }
    
    // Content type change - show/hide relevant fields
    const contentTypeSelect = document.getElementById('content_type');
    if (contentTypeSelect) {
        contentTypeSelect.addEventListener('change', function() {
            const selectedType = this.value;
            console.log('Content type changed to:', selectedType);
            
            // You can add logic here to show/hide fields based on content type
            // For example, hide image field for footer and section_title
            if (selectedType === 'footer' || selectedType === 'section_title') {
                const imageField = document.querySelector('[for="image"]').parentElement;
                imageField.classList.add('hidden');
            }
        });
        
        // Trigger on load
        if (contentTypeSelect.value) {
            contentTypeSelect.dispatchEvent(new Event('change'));
        }
    }
});
</script>
@endpush