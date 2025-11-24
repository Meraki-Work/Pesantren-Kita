@extends('index')

@section('title', 'Buat Notulen - PesantrenKita')

@section('content')
<div class="flex bg-gray-100">
    <x-sidemenu title="PesantrenKita" class="h-full min-h-screen" />

    <main class="flex-1 p-4 overflow-y-auto">
        <div class="p-6 box-bg">
            <h2 class="text-2xl font-semibold mb-6 text-gray-800">Notulensi Rapat</h2>

            <form action="{{ route('notulen.store') }}" method="POST" class="grid grid-cols-2 gap-6" enctype="multipart/form-data">
                @csrf

                {{-- Kolom Kiri --}}
                <div class="space-y-4">
                    {{-- Agenda Rapat --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Agenda Rapat <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="agenda" value="{{ old('agenda') }}"
                            class="w-full border border-green-300 rounded-md p-2 focus:outline-none focus:ring-1 focus:ring-green-400"
                            placeholder="Rapat Paripurna Internal Pondok Pesantren" required>
                        @error('agenda')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tanggal & Waktu --}}
                    <div class="flex space-x-4">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Hari/Tanggal <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}"
                                class="w-full border border-green-300 rounded-md p-2 focus:outline-none focus:ring-1 focus:ring-green-400" required>
                            @error('tanggal')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Waktu <span class="text-red-500">*</span>
                            </label>
                            <input type="time" name="waktu" value="{{ old('waktu', '08:00') }}"
                                class="w-full border border-green-300 rounded-md p-2 focus:outline-none focus:ring-1 focus:ring-green-400" required>
                            @error('waktu')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Tempat & Pimpinan --}}
                    <div class="flex space-x-4">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Tempat <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="tempat" value="{{ old('tempat') }}"
                                class="w-full border border-green-300 rounded-md p-2 focus:outline-none focus:ring-1 focus:ring-green-400"
                                placeholder="Tambahkan Tempat" required>
                            @error('tempat')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Pimpinan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="pimpinan" value="{{ old('pimpinan') }}"
                                class="w-full border border-green-300 rounded-md p-2 focus:outline-none focus:ring-1 focus:ring-green-400"
                                placeholder="Tambahkan Pimpinan" required>
                            @error('pimpinan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Peserta --}}
                    <div id="peserta-container" class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Peserta Rapat <span class="text-red-500">*</span>
                        </label>

                        <div class="flex space-x-2 items-center">
                            <div class="flex-1">
                                <input type="text" name="peserta[]"
                                    class="w-full border border-green-300 rounded-md p-2 focus:outline-none focus:ring-1 focus:ring-green-400"
                                    placeholder="Nama peserta" required>
                            </div>
                            <button type="button" onclick="addPeserta()"
                                class="bg-green-500 text-white rounded-full w-8 h-8 flex items-center justify-center mt-5 hover:bg-green-600 transition">
                                +
                            </button>
                        </div>

                        <div class="flex space-x-2 items-center">
                            <div class="flex-1">
                                <input type="text" name="peserta[]"
                                    class="w-full border border-green-300 rounded-md p-2 focus:outline-none focus:ring-1 focus:ring-green-400"
                                    placeholder="Nama peserta">
                            </div>
                            <button type="button" onclick="removeElement(this)"
                                class="bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center hover:bg-red-600 transition">
                                −
                            </button>
                        </div>
                    </div>
                    @error('peserta')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    {{-- Upload Gambar --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Upload Gambar/Dokumentasi
                        </label>

                        <!-- Error Notification Area -->
                        <div id="upload-error" class="hidden mb-3">
                            <div class="bg-red-50 border border-red-200 rounded-lg p-3 flex items-start">
                                <svg class="w-5 h-5 text-red-400 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-red-800" id="error-title">Format file tidak didukung</p>
                                    <p class="text-sm text-red-600 mt-1" id="error-message"></p>
                                </div>
                                <button type="button" onclick="hideError()" class="ml-auto text-red-400 hover:text-red-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Success Notification Area -->
                        <div id="upload-success" class="hidden mb-3">
                            <div class="bg-green-50 border border-green-200 rounded-lg p-3 flex items-start">
                                <svg class="w-5 h-5 text-green-400 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-green-800">File berhasil dipilih</p>
                                    <p class="text-sm text-green-600 mt-1" id="success-message"></p>
                                </div>
                                <button type="button" onclick="hideSuccess()" class="ml-auto text-green-400 hover:text-green-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Dropzone Area -->
                        <div class="border-2 border-dashed border-green-300 rounded-lg p-6 text-center hover:border-green-400 transition-colors duration-200 bg-green-50/50"
                            id="dropzone">
                            <input type="file" name="gambar[]" id="gambar" multiple
                                class="hidden"
                                accept=".jpg,.jpeg,.png,.gif,.svg,.webp,.bmp"
                                onchange="validateAndPreviewFiles(this)">

                            <div class="space-y-3">
                                <svg class="mx-auto h-12 w-12 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>

                                <div>
                                    <button type="button" onclick="document.getElementById('gambar').click()"
                                        class="text-green-600 hover:text-green-700 font-medium text-sm bg-white px-4 py-2 rounded-lg border border-green-200 hover:border-green-300 transition-colors">
                                        📁 Pilih File Gambar
                                    </button>
                                    <p class="text-xs text-gray-500 mt-2">atau drag & drop file di sini</p>
                                </div>

                                <div class="text-xs text-gray-500 space-y-1">
                                    <p class="font-medium">Format yang didukung:</p>
                                    <div class="flex flex-wrap justify-center gap-2 mt-1">
                                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">JPG</span>
                                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">JPEG</span>
                                        <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded text-xs">PNG</span>
                                        <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs">GIF</span>
                                        <span class="bg-orange-100 text-orange-800 px-2 py-1 rounded text-xs">SVG</span>
                                        <span class="bg-pink-100 text-pink-800 px-2 py-1 rounded text-xs">WEBP</span>
                                        <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-xs">BMP</span>
                                    </div>
                                    <p class="mt-2">Maksimal 5MB per file • Maksimal 10 file</p>
                                </div>
                            </div>
                        </div>

                        <!-- File Info & Preview -->
                        <div id="file-info" class="mt-3 hidden">
                            <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm font-medium text-gray-700">File Terpilih:</span>
                                    <span class="text-xs text-gray-500" id="file-count">0 files</span>
                                </div>
                                <div id="file-list" class="space-y-2 max-h-32 overflow-y-auto"></div>
                            </div>
                        </div>

                        <!-- Preview Container -->
                        <div id="preview-container" class="mt-4 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3"></div>

                        @error('gambar.*')
                        <div class="mt-2 bg-red-50 border border-red-200 rounded-lg p-3">
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        </div>
                        @enderror
                    </div>

                    {{-- Kolom Kanan --}}
                    <div class="space-y-4">
                        {{-- Jalannya Rapat (Alur Rapat) --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Jalannya Rapat <span class="text-red-500">*</span>
                            </label>
                            <textarea name="alur_rapat" rows="6"
                                class="w-full border border-green-300 rounded-md p-2 focus:outline-none focus:ring-1 focus:ring-green-400"
                                placeholder="Tuliskan jalannya rapat..." required>{{ old('alur_rapat') }}</textarea>
                            @error('alur_rapat')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Poin-Poin Rapat (Hasil) --}}
                        <div id="poin-container" class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Poin-Poin Rapat (Hasil) <span class="text-red-500">*</span>
                            </label>

                            <div class="flex space-x-2 items-center">
                                <div class="flex-1">
                                    <input type="text" name="hasil[]"
                                        class="w-full border border-green-300 rounded-md p-2 focus:outline-none focus:ring-1 focus:ring-green-400"
                                        placeholder="Tambah poin hasil rapat" required>
                                </div>
                                <button type="button" onclick="addPoin()"
                                    class="bg-green-500 text-white rounded-full w-8 h-8 flex items-center justify-center mt-5 hover:bg-green-600 transition">
                                    +
                                </button>
                            </div>

                            <div class="flex space-x-2 items-center">
                                <input type="text" name="hasil[]"
                                    class="flex-1 border border-green-300 rounded-md p-2 focus:outline-none focus:ring-1 focus:ring-green-400"
                                    placeholder="Tambah poin hasil rapat">
                                <button type="button" onclick="removeElement(this)"
                                    class="bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center hover:bg-red-600 transition">
                                    −
                                </button>
                            </div>
                        </div>
                        @error('hasil')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        {{-- Keterangan --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                            <textarea name="keterangan" rows="4"
                                class="w-full border border-green-300 rounded-md p-2 focus:outline-none focus:ring-1 focus:ring-green-400"
                                placeholder="Tuliskan keterangan rapat...">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Form Actions --}}
                    <div class="col-span-2 flex justify-end space-x-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('notulen.index') }}"
                            class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200 font-medium">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-200 font-medium">
                            Simpan Notulen
                        </button>
                    </div>

                    {{-- Hidden fields --}}
                    <input type="hidden" name="ponpes_id" value="{{ auth()->user()->ponpes_id ?? '' }}">
            </form>
        </div>

        <script>
            // Konfigurasi upload
            const UPLOAD_CONFIG = {
                maxFiles: 10,
                maxSize: 5 * 1024 * 1024, // 5MB
                allowedTypes: ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/svg+xml', 'image/webp', 'image/bmp'],
                allowedExtensions: ['.jpg', '.jpeg', '.png', '.gif', '.svg', '.webp', '.bmp']
            };

            // Element references
            const fileInput = document.getElementById('gambar');
            const errorDiv = document.getElementById('upload-error');
            const successDiv = document.getElementById('upload-success');
            const fileInfo = document.getElementById('file-info');
            const fileList = document.getElementById('file-list');
            const fileCount = document.getElementById('file-count');
            const previewContainer = document.getElementById('preview-container');

            // Fungsi untuk menampilkan error
            function showError(title, message) {
                document.getElementById('error-title').textContent = title;
                document.getElementById('error-message').textContent = message;
                errorDiv.classList.remove('hidden');
                successDiv.classList.add('hidden');

                // Auto hide setelah 5 detik
                setTimeout(hideError, 5000);
            }

            function hideError() {
                errorDiv.classList.add('hidden');
            }

            // Fungsi untuk menampilkan success
            function showSuccess(message) {
                document.getElementById('success-message').textContent = message;
                successDiv.classList.remove('hidden');
                errorDiv.classList.add('hidden');

                // Auto hide setelah 3 detik
                setTimeout(hideSuccess, 3000);
            }

            function hideSuccess() {
                successDiv.classList.add('hidden');
            }

            // Validasi file
            function validateFile(file) {
                const errors = [];

                // Cek tipe file
                if (!UPLOAD_CONFIG.allowedTypes.includes(file.type)) {
                    errors.push(`Format "${file.type}" tidak didukung`);
                }

                // Cek ekstensi file
                const fileExtension = '.' + file.name.split('.').pop().toLowerCase();
                if (!UPLOAD_CONFIG.allowedExtensions.includes(fileExtension)) {
                    errors.push(`Ekstensi "${fileExtension}" tidak diizinkan`);
                }

                // Cek ukuran file
                if (file.size > UPLOAD_CONFIG.maxSize) {
                    const sizeMB = (file.size / (1024 * 1024)).toFixed(2);
                    errors.push(`Ukuran file ${sizeMB}MB melebihi batas 5MB`);
                }

                return {
                    isValid: errors.length === 0,
                    errors: errors
                };
            }

            // Validasi dan preview files
            function validateAndPreviewFiles(input) {
                const files = Array.from(input.files);
                previewContainer.innerHTML = '';
                fileList.innerHTML = '';

                if (files.length === 0) {
                    fileInfo.classList.add('hidden');
                    return;
                }

                // Validasi jumlah file
                if (files.length > UPLOAD_CONFIG.maxFiles) {
                    showError('Terlalu Banyak File', `Maksimal ${UPLOAD_CONFIG.maxFiles} file yang diizinkan`);
                    input.value = '';
                    return;
                }

                let validFiles = [];
                let hasErrors = false;

                files.forEach((file, index) => {
                    const validation = validateFile(file);

                    if (validation.isValid) {
                        validFiles.push(file);
                        addFileToList(file, index);
                        createImagePreview(file, index);
                    } else {
                        hasErrors = true;
                        showError('File Tidak Valid', validation.errors.join(', '));
                    }
                });

                // Update file input dengan hanya file yang valid
                if (validFiles.length > 0) {
                    const dt = new DataTransfer();
                    validFiles.forEach(file => dt.items.add(file));
                    input.files = dt.files;

                    fileCount.textContent = `${validFiles.length} file terpilih`;
                    fileInfo.classList.remove('hidden');

                    if (!hasErrors) {
                        showSuccess(`${validFiles.length} file berhasil dipilih dan siap diupload`);
                    }
                } else {
                    input.value = '';
                    fileInfo.classList.add('hidden');
                }
            }

            // Tambah file ke list
            function addFileToList(file, index) {
                const fileItem = document.createElement('div');
                fileItem.className = 'flex items-center justify-between text-xs';
                fileItem.innerHTML = `
        <div class="flex items-center space-x-2 flex-1 min-w-0">
            <span class="w-2 h-2 bg-green-400 rounded-full flex-shrink-0"></span>
            <span class="truncate flex-1">${file.name}</span>
        </div>
        <div class="flex items-center space-x-2 text-gray-500 flex-shrink-0 ml-2">
            <span class="text-xs">${(file.size / 1024 / 1024).toFixed(2)}MB</span>
            <button type="button" onclick="removeFile(${index})" class="text-red-400 hover:text-red-600">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    `;
                fileList.appendChild(fileItem);
            }

            // Buat preview gambar
            function createImagePreview(file, index) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewDiv = document.createElement('div');
                    previewDiv.className = 'relative group bg-white rounded-lg border border-gray-200 p-2';
                    previewDiv.innerHTML = `
            <div class="aspect-square overflow-hidden rounded bg-gray-100">
                <img src="${e.target.result}" 
                     class="w-full h-full object-cover" 
                     alt="Preview ${file.name}">
            </div>
            <div class="mt-2 text-xs text-gray-600 truncate" title="${file.name}">
                ${file.name}
            </div>
            <button type="button" 
                    onclick="removeFile(${index})" 
                    class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition-opacity shadow-lg">
                ×
            </button>
        `;
                    previewContainer.appendChild(previewDiv);
                };
                reader.readAsDataURL(file);
            }

            // Hapus file
            function removeFile(index) {
                const files = Array.from(fileInput.files);
                files.splice(index, 1);

                const dt = new DataTransfer();
                files.forEach(file => dt.items.add(file));
                fileInput.files = dt.files;

                // Trigger ulang validasi dan preview
                validateAndPreviewFiles(fileInput);
            }

            // Drag and drop functionality
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropzone.addEventListener(eventName, preventDefaults, false);
                document.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                dropzone.addEventListener(eventName, () => {
                    dropzone.classList.add('border-green-500', 'bg-green-100', 'border-2');
                    dropzone.classList.remove('border-green-300');
                }, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropzone.addEventListener(eventName, () => {
                    dropzone.classList.remove('border-green-500', 'bg-green-100', 'border-2');
                    dropzone.classList.add('border-green-300');
                }, false);
            });

            dropzone.addEventListener('drop', function(e) {
                const files = e.dataTransfer.files;
                fileInput.files = files;
                validateAndPreviewFiles(fileInput);
            }, false);

            // Fungsi-fungsi lainnya (addPeserta, addPoin, removeElement) tetap sama
            function addPeserta() {
                const container = document.getElementById('peserta-container');
                const div = document.createElement('div');
                div.classList.add('flex', 'space-x-2', 'items-center');
                div.innerHTML = `
        <div class="flex-1">
            <input type="text" name="peserta[]" 
                   class="w-full border border-green-300 rounded-md p-2 focus:outline-none focus:ring-1 focus:ring-green-400" 
                   placeholder="Nama peserta">
        </div>
        <button type="button" onclick="removeElement(this)" 
                class="bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center hover:bg-red-600 transition">
            −
        </button>
    `;
                container.appendChild(div);
            }

            function addPoin() {
                const container = document.getElementById('poin-container');
                const div = document.createElement('div');
                div.classList.add('flex', 'space-x-2', 'items-center');
                div.innerHTML = `
        <input type="text" name="hasil[]" 
               class="flex-1 border border-green-300 rounded-md p-2 focus:outline-none focus:ring-1 focus:ring-green-400" 
               placeholder="Tambah poin hasil rapat">
        <button type="button" onclick="removeElement(this)" 
                class="bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center hover:bg-red-600 transition">
            −
        </button>
    `;
                container.appendChild(div);
            }

            function removeElement(btn) {
                btn.parentElement.remove();
            }

            // Auto-focus pada input pertama
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelector('input[name="agenda"]').focus();
            });
        </script>

        <script>
            function addPeserta() {
                const container = document.getElementById('peserta-container');
                const div = document.createElement('div');
                div.classList.add('flex', 'space-x-2', 'items-center');
                div.innerHTML = `
                    <div class="flex-1">
                        <input type="text" name="peserta[]" 
                               class="w-full border border-green-300 rounded-md p-2 focus:outline-none focus:ring-1 focus:ring-green-400" 
                               placeholder="Nama peserta">
                    </div>
                    <button type="button" onclick="removeElement(this)" 
                            class="bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center hover:bg-red-600 transition">
                        −
                    </button>
                `;
                container.appendChild(div);
            }

            function addPoin() {
                const container = document.getElementById('poin-container');
                const div = document.createElement('div');
                div.classList.add('flex', 'space-x-2', 'items-center');
                div.innerHTML = `
                    <input type="text" name="hasil[]" 
                           class="flex-1 border border-green-300 rounded-md p-2 focus:outline-none focus:ring-1 focus:ring-green-400" 
                           placeholder="Tambah poin hasil rapat">
                    <button type="button" onclick="removeElement(this)" 
                            class="bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center hover:bg-red-600 transition">
                        −
                    </button>
                `;
                container.appendChild(div);
            }

            function removeElement(btn) {
                btn.parentElement.remove();
            }

            // Preview gambar
            document.getElementById('gambar').addEventListener('change', function(e) {
                const previewContainer = document.getElementById('preview-container');
                previewContainer.innerHTML = '<p class="text-sm text-gray-600 col-span-3">Preview:</p>';
                previewContainer.classList.remove('hidden');

                const files = e.target.files;
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const previewDiv = document.createElement('div');
                            previewDiv.className = 'relative group';
                            previewDiv.innerHTML = `
                                <img src="${e.target.result}" class="w-full h-24 object-cover rounded-lg border border-gray-300">
                                <button type="button" onclick="removePreview(this)" 
                                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition-opacity">
                                    ×
                                </button>
                                <p class="text-xs text-gray-500 truncate mt-1">${file.name}</p>
                            `;
                            previewContainer.appendChild(previewDiv);
                        }
                        reader.readAsDataURL(file);
                    }
                }
            });

            // Remove preview gambar
            function removePreview(btn) {
                btn.closest('.relative').remove();
                // Juga hapus dari input file
                const fileInput = document.getElementById('gambar');
                const files = Array.from(fileInput.files);
                const previewIndex = Array.from(document.querySelectorAll('#preview-container .relative')).indexOf(btn.closest('.relative')) - 1; // -1 karena ada elemen teks "Preview"

                if (previewIndex >= 0) {
                    files.splice(previewIndex, 1);
                    const dt = new DataTransfer();
                    files.forEach(file => dt.items.add(file));
                    fileInput.files = dt.files;
                }

                // Sembunyikan container preview jika tidak ada gambar
                if (document.querySelectorAll('#preview-container .relative').length === 0) {
                    document.getElementById('preview-container').classList.add('hidden');
                }
            }

            // Drag and drop functionality
            const dropzone = document.getElementById('dropzone');
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropzone.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                dropzone.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropzone.addEventListener(eventName, unhighlight, false);
            });

            function highlight() {
                dropzone.classList.add('border-green-500', 'bg-green-50');
            }

            function unhighlight() {
                dropzone.classList.remove('border-green-500', 'bg-green-50');
            }

            dropzone.addEventListener('drop', handleDrop, false);

            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                document.getElementById('gambar').files = files;

                // Trigger change event untuk preview
                const event = new Event('change');
                document.getElementById('gambar').dispatchEvent(event);
            }

            // Auto-focus pada input pertama
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelector('input[name="agenda"]').focus();
            });
        </script>

        <style>
            .box-bg {
                background: white;
                border-radius: 12px;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            }

            #dropzone.dragover {
                border-color: #10B981;
                background-color: #ECFDF5;
            }
        </style>
    </main>
</div>
@endsection