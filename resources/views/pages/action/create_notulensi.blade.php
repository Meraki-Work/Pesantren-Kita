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
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Upload Gambar/Dokumentasi
                            </label>
                            <div class="border-2 border-dashed border-green-300 rounded-lg p-6 text-center hover:border-green-400 transition-colors duration-200"
                                id="dropzone">
                                <input type="file" name="gambar[]" id="gambar" multiple
                                    class="hidden" accept="image/*" onchange="previewImages(this)">
                                <div class="space-y-2">
                                    <svg class="mx-auto h-12 w-12 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <p class="text-sm text-gray-600">
                                        <button type="button" onclick="document.getElementById('gambar').click()"
                                            class="text-green-600 hover:text-green-700 font-medium">
                                            Klik untuk upload
                                        </button>
                                        atau drag & drop
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        PNG, JPG, GIF hingga 2MB
                                    </p>
                                </div>
                            </div>

                            <!-- Preview Container -->
                            <div id="preview-container" class="mt-4 grid grid-cols-3 gap-2"></div>

                            @error('gambar.*')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Preview Gambar --}}
                        <div id="preview-container" class="mt-4 grid grid-cols-3 gap-2">
                            <p class="text-sm text-gray-600 col-span-3">Preview:</p>
                        </div>

                        @error('gambar.*')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
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