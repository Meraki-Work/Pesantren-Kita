@extends('index')

@section('title', 'Edit Kategori')

@section('content')
<div class="flex bg-gray-100 min-h-screen">

    <x-sidemenu title="PesantrenKita" />

    <main class="flex-1 p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Edit Kategori</h1>
                <p class="text-gray-600">Perbarui informasi kategori keuangan</p>
            </div>
            <a href="{{ route('kategori.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center transition duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>

        <!-- Content Card -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden max-w-2xl mx-auto">
            <div class="p-6">
                <form action="{{ route('kategori.update', $kategori->id_kategori) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <!-- Form Header -->
                    <div class="flex items-center mb-6 pb-4 border-b border-gray-200">
                        <div class="flex-shrink-0 w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-lg font-semibold text-gray-800">Edit Kategori</h2>
                            <p class="text-sm text-gray-600">Perbarui detail kategori "{{ $kategori->nama_kategori }}"</p>
                        </div>
                    </div>

                    <!-- Current Kategori Info -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Informasi Saat Ini</h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-500">ID Kategori:</span>
                                <span class="font-medium text-gray-900">{{ $kategori->id_kategori }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500">Ponpes ID:</span>
                                <span class="font-medium text-gray-900">{{ $kategori->ponpes_id }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Nama Kategori Field -->
                    <div class="mb-6">
                        <label for="nama_kategori" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Kategori <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                            </div>
                            <input type="text" 
                                   id="nama_kategori" 
                                   name="nama_kategori" 
                                   value="{{ old('nama_kategori', $kategori->nama_kategori) }}"
                                   class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nama_kategori') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror"
                                   placeholder="Masukkan nama kategori baru"
                                   required
                                   maxlength="100">
                        </div>
                        @error('nama_kategori')
                            <div class="mt-2 flex items-center text-sm text-red-600">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $message }}
                            </div>
                        @enderror
                        <div class="mt-2 text-sm text-gray-500">
                            Maksimal 100 karakter. Nama kategori harus unik untuk ponpes Anda.
                        </div>
                    </div>

                    <!-- Warning Card -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">Perhatian</h3>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <p>Perubahan nama kategori akan mempengaruhi semua data keuangan yang menggunakan kategori ini.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                        <a href="{{ route('kategori.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg flex items-center transition duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Batal
                        </a>
                        <div class="flex space-x-3">
                            <button type="button" onclick="resetForm()" class="bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-lg flex items-center transition duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Reset
                            </button>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg flex items-center transition duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Perbarui Kategori
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const namaInput = document.getElementById('nama_kategori');
        const originalValue = "{{ $kategori->nama_kategori }}";
        
        // Auto capitalize first letter of each word
        namaInput.addEventListener('input', function() {
            this.value = this.value.replace(/\b\w/g, l => l.toUpperCase());
        });

        // Character counter
        const charCounter = document.createElement('div');
        charCounter.className = 'text-right text-sm text-gray-500 mt-1';
        charCounter.innerHTML = `<span id="charCount">0</span>/100 karakter`;
        namaInput.parentNode.appendChild(charCounter);

        namaInput.addEventListener('input', function() {
            document.getElementById('charCount').textContent = this.value.length;
        });

        // Initialize counter
        document.getElementById('charCount').textContent = namaInput.value.length;
    });

    function resetForm() {
        const originalValue = "{{ $kategori->nama_kategori }}";
        document.getElementById('nama_kategori').value = originalValue;
        document.getElementById('charCount').textContent = originalValue.length;
        
        // Show reset confirmation
        Swal.fire({
            title: 'Form Direset!',
            text: 'Form telah dikembalikan ke nilai semula',
            icon: 'info',
            timer: 1500,
            showConfirmButton: false
        });
    }
</script>
@endsection