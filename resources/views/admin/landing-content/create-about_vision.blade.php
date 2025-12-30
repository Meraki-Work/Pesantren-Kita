@extends('index')

@section('title', 'Tambah Visi Pesantren')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Tambah Visi Pesantren</h2>
                        <p class="mt-1 text-sm text-gray-500">Isi visi pesantren untuk ditampilkan di landing page</p>
                    </div>
                    <a href="{{ route('admin.landing-content.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>

            <!-- Form -->
            <div class="bg-white shadow rounded-lg">
                <form action="{{ route('admin.landing-content.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Hidden fields -->
                    <input type="hidden" name="content_type" value="about_vision">
                    
                    @if(optional(Auth::user())->role === 'admin' || optional(Auth::user())->role === 'super_admin')
                    <div class="p-6 border-b border-gray-200">
                        <label for="ponpes_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Pilih Pesantren <span class="text-red-500">*</span>
                        </label>
                        <select id="ponpes_id" name="ponpes_id" required
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                            <option value="">-- Pilih Pesantren --</option>
                            @foreach($ponpesList as $ponpes)
                            <option value="{{ $ponpes->id_ponpes }}" {{ old('ponpes_id') == $ponpes->id_ponpes ? 'selected' : '' }}>
                                {{ $ponpes->nama_ponpes }}
                            </option>
                            @endforeach
                        </select>
                        @error('ponpes_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    @else
                    <input type="hidden" name="ponpes_id" value="{{ optional(Auth::user())->ponpes_id }}">
                    @endif

                    <div class="p-6 space-y-6">
                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                Judul Visi <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="title" name="title" required
                                   value="{{ old('title') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   placeholder="Contoh: Visi Pondok Pesantren">
                            @error('title')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Isi Visi <span class="text-red-500">*</span>
                            </label>
                            <textarea id="description" name="description" rows="6" required
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                      placeholder="Tuliskan visi pesantren secara lengkap...">{{ old('description') }}</textarea>
                            <p class="mt-1 text-sm text-gray-500">
                                Gunakan paragraf yang jelas dan inspiratif untuk menggambarkan visi pesantren
                            </p>
                            @error('description')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Image -->
                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                                Upload Foto <span class="text-red-500">*</span>
                            </label>

                            <!-- Image Preview -->
                            <div class="mb-4 hidden" id="imagePreviewContainer">
                                <div class="relative inline-block">
                                    <img id="imagePreview"
                                        class="h-48 w-auto rounded-lg shadow-md border border-gray-200 object-cover">
                                    <button type="button"
                                        onclick="removeImage()"
                                        class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- File Input -->
                            <div class="mt-1 flex items-center">
                                <label for="image" class="cursor-pointer w-full">
                                    <div id="dropZone" class="px-4 py-3 border-2 border-dashed border-gray-300 rounded-md hover:border-blue-400 hover:bg-blue-50 transition-colors duration-200 text-center">
                                        <div class="space-y-1">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <div class="text-sm text-gray-600">
                                                <span class="font-medium text-blue-600">Klik untuk mengunggah</span> atau drag & drop
                                            </div>
                                            <p class="text-xs text-gray-500">
                                                JPG, PNG, GIF, WebP hingga 2MB. Rekomendasi: 800×600 px
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <input type="file"
                                        name="image"
                                        id="image"
                                        accept="image/*"
                                        required
                                        class="sr-only"
                                        onchange="previewImage(event)">
                                </label>
                            </div>

                            @error('image')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Display Order -->
                        <div>
                            <label for="display_order" class="block text-sm font-medium text-gray-700 mb-2">
                                Urutan Tampil <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="display_order" name="display_order" required min="1"
                                   value="{{ old('display_order', $nextOrder ?? 1) }}" min="1"
                                   class="w-32 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <p class="mt-1 text-sm text-gray-500">
                                Urutan berdasarkan konten terakhir +1.
                            </p>
                            @error('display_order')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- URL -->
                        <div>
                            <label for="url" class="block text-sm font-medium text-gray-700 mb-2">
                                URL Tambahan (Opsional)
                            </label>
                            <input type="url" id="url" name="url"
                                   value="{{ old('url') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   placeholder="https://example.com">
                            <p class="mt-1 text-sm text-gray-500">
                                Link eksternal terkait visi (opsional)
                            </p>
                            @error('url')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Active Status -->
                        <div class="flex items-center">
                            <input type="checkbox" id="is_active" name="is_active" value="1"
                                   {{ old('is_active', true) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                Aktifkan konten ini
                            </label>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between">
                        <div class="text-sm text-gray-500">
                            <p>Visinya akan ditampilkan di bagian "Visi & Misi" di landing page</p>
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('admin.landing-content.index') }}" 
                               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Batal
                            </a>
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Simpan Visi
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Tips Section -->
            <div class="mt-6">
                <div class="rounded-lg bg-blue-50 p-6 border border-blue-200">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-blue-800 mb-2">Tips Menulis Visi</h3>
                            <ul class="list-disc pl-5 space-y-1 text-sm text-blue-700">
                                <li>Visi harus menggambarkan tujuan jangka panjang pesantren</li>
                                <li>Gunakan bahasa yang jelas, singkat, dan inspiratif</li>
                                <li>Visi harus mencerminkan nilai-nilai islami</li>
                                <li>Berorientasi pada masa depan (5-10 tahun ke depan)</li>
                                <li>Dapat diukur dan realistis</li>
                                <li>Selaras dengan misi-misi yang ada</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Image preview functionality
    function previewImage(event) {
        const input = event.target;
        const previewContainer = document.getElementById('imagePreviewContainer');
        const preview = document.getElementById('imagePreview');
        const dropZone = document.getElementById('dropZone');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                previewContainer.classList.remove('hidden');
                dropZone.classList.add('hidden');
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    // Remove image
    function removeImage() {
        const input = document.getElementById('image');
        const previewContainer = document.getElementById('imagePreviewContainer');
        const preview = document.getElementById('imagePreview');
        const dropZone = document.getElementById('dropZone');

        input.value = '';
        preview.src = '';
        previewContainer.classList.add('hidden');
        dropZone.classList.remove('hidden');
    }

    // Drag and drop functionality
    document.addEventListener('DOMContentLoaded', function() {
        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('image');

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, unhighlight, false);
        });

        function highlight() {
            dropZone.classList.add('border-blue-500', 'bg-blue-50');
            dropZone.classList.remove('border-gray-300');
        }

        function unhighlight() {
            dropZone.classList.remove('border-blue-500', 'bg-blue-50');
            dropZone.classList.add('border-gray-300');
        }

        dropZone.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;

            if (files.length > 0) {
                fileInput.files = files;

                // Trigger preview
                const event = new Event('change', {
                    bubbles: true
                });
                fileInput.dispatchEvent(event);
            }
        }
    });
</script>
@endpush

@endsection