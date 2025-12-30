@extends('index')

@section('title', 'Tambah Founder/Pendiri')

@section('content')
<div class="min-h-screen bg-gray-50 p-4 md:p-6">
    <div class="max-w-7xl mx-auto">
        <div class="mb-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Tambah Founder/Pendiri
                    </h1>
                    <p class="text-gray-600 mt-1">Profil pendiri pesantren</p>
                </div>
                <a href="{{ route('admin.landing-content.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
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
                        <h2 class="text-lg font-semibold text-gray-900">Formulir Founder</h2>
                        <p class="text-sm text-gray-600 mt-1">Lengkapi informasi pendiri pesantren</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.landing-content.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                <input type="hidden" name="content_type" value="about_founder">
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Kolom Kiri - Upload Foto -->
                    <div class="lg:col-span-1">
                        <div class="border border-gray-200 rounded-lg overflow-hidden h-full">
                            <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                                <h3 class="font-semibold text-gray-900">Foto Founder 
                                    <span class="text-red-500">*</span>
                                </h3>
                            </div>
                            <div class="p-4 h-full flex flex-col">
                                <div class="mb-4">
                                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                                        Upload Foto
                                    </label>
                                    <input type="file" name="image" id="image" 
                                           class="block w-full text-sm text-gray-900 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 @error('image') border-red-300 @enderror"
                                           accept="image/*" required>
                                    @error('image')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <div class="mt-3 text-sm text-gray-500 space-y-1">
                                        <p>Format: JPG, PNG, GIF, WEBP</p>
                                        <p>Maksimal: 2MB</p>
                                        <p>Ukuran disarankan: 400×500 px</p>
                                    </div>
                                </div>
                                
                                <!-- Image Preview -->
                                <div class="flex-grow flex flex-col items-center justify-center">
                                    <div id="imagePreview" class="mb-4 hidden w-full">
                                        <img id="previewImage" class="w-full h-auto max-h-80 object-contain rounded-lg border border-gray-200">
                                    </div>
                                    <div id="imagePlaceholder" class="text-center p-6 border-2 border-dashed border-gray-300 rounded-lg bg-gray-50 w-full h-90 flex flex-col items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        <p class="mt-3 text-sm text-gray-500">Foto founder akan muncul di sini</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kolom Kanan - Form Informasi -->
                    <div class="lg:col-span-2">
                        <div class="border border-gray-200 rounded-lg overflow-hidden h-full">
                            <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                                <h3 class="font-semibold text-gray-900">Informasi Founder</h3>
                            </div>
                            <div class="p-6 space-y-6">
                                <!-- Pesantren (otomatis terisi dari akun) -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Pesantren</label>
                                    <input type="hidden" name="ponpes_id" value="{{ optional(Auth::user())->ponpes_id }}">
                                    <div class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-md text-gray-700">
                                        {{ optional($ponpesList->firstWhere('id_ponpes', optional(Auth::user())->ponpes_id))->nama_ponpes ?? 'Pesantren Anda' }}
                                    </div>
                                </div>

                                <!-- Nama dan Jabatan -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                            Nama Founder <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="title" id="title" 
                                               value="{{ old('title') }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('title') border-red-300 @enderror"
                                               placeholder="Contoh: KH. Ahmad Dahlan" required>
                                        @error('title')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div>
                                        <label for="position" class="block text-sm font-medium text-gray-700 mb-2">
                                            Jabatan/Posisi <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="position" id="position" 
                                               value="{{ old('position') }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('position') border-red-300 @enderror"
                                               placeholder="Contoh: Pendiri Pesantren" required>
                                        @error('position')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Biografi -->
                                <div>
                                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                        Biografi <span class="text-red-500">*</span>
                                    </label>
                                    <textarea name="description" id="description" rows="6"
                                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('description') border-red-300 @enderror"
                                              placeholder="Tuliskan biografi lengkap founder..." required>{{ old('description') }}</textarea>
                                    @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Link Sosial Media dan Urutan Tampilan -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="url" class="block text-sm font-medium text-gray-700 mb-2">
                                            Link Sosial Media (Opsional)
                                        </label>
                                        <input type="url" name="url" id="url" 
                                               value="{{ old('url') }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('url') border-red-300 @enderror"
                                               placeholder="https://instagram.com/username">
                                        @error('url')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                        <p class="mt-1 text-xs text-gray-500">Link ke profil sosial media founder</p>
                                    </div>
                                    
                                    <div>
                                        <label for="display_order" class="block text-sm font-medium text-gray-700 mb-2">
                                            Urutan Tampilan
                                        </label>
                                        <input type="number" name="display_order" id="display_order" 
                                               value="{{ old('display_order', $nextOrder ?? 1) }}" min="1"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('display_order') border-red-300 @enderror">
                                        @error('display_order')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                        <p class="mt-1 text-xs text-gray-500">Urutan berdasarkan konten terakhir +1.</p>
                                    </div>
                                </div>

                                <!-- Toggle Aktif -->
                                <div class="flex items-center">
                                    <input type="checkbox" name="is_active" id="is_active" value="1" 
                                           {{ old('is_active', true) ? 'checked' : '' }}
                                           class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                    <label for="is_active" class="ml-2 block text-sm font-medium text-gray-900">
                                        Tampilkan di Halaman
                                    </label>
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
                            class="inline-flex items-center px-6 py-3 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200 w-full sm:w-auto justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                        </svg>
                        Simpan Founder
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

    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            
            if (file) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                }
                
                reader.readAsDataURL(file);
            } else {
                previewContainer.classList.add('hidden');
                placeholder.classList.remove('hidden');
            }
        });
    }

    // Initialize preview if there's already an image (for edit page)
    if (previewImage && previewImage.src) {
        previewContainer.classList.remove('hidden');
        placeholder.classList.add('hidden');
    }
});
</script>
@endpush