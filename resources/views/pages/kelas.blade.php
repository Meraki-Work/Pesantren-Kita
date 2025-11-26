@extends('index')

@section('title', 'Data Kelas')

@section('content')
<div class="flex bg-gray-100 min-h-screen">

    <x-sidemenu title="PesantrenKita" />

    <main class="flex-1 p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center space-x-4">
                <!-- Tombol Kembali -->
                <a href="{{ route('santri.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center transition duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Data Kelas</h1>
                    <p class="text-gray-600">Kelola kelas ponpes Anda</p>
                </div>
            </div>
        </div>

        <!-- Alert Messages -->
        @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            {{ session('success') }}
        </div>
        @endif

        @if (session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
            </svg>
            {{ session('error') }}
        </div>
        @endif

        <!-- Grid Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Form Section -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4" id="formTitle">
                        {{ isset($editingKelas) ? 'Edit Kelas' : 'Tambah Kelas Baru' }}
                    </h2>
                    
                    <form method="POST" 
                          action="{{ isset($editingKelas) ? route('kelas.update', $editingKelas->id_kelas) : route('kelas.store') }}" 
                          id="kelasForm">
                        @csrf
                        @if(isset($editingKelas))
                            @method('PUT')
                        @endif

                        <!-- Nama Kelas -->
                        <div class="mb-4">
                            <label for="nama_kelas" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Kelas <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="nama_kelas" 
                                   name="nama_kelas" 
                                   value="{{ old('nama_kelas', $editingKelas->nama_kelas ?? '') }}"
                                   placeholder="Masukkan nama kelas"
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                            @error('nama_kelas')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tingkat -->
                        <div class="mb-6">
                            <label for="tingkat" class="block text-sm font-medium text-gray-700 mb-2">
                                Tingkat <span class="text-red-500">*</span>
                            </label>
                            <select id="tingkat" 
                                    name="tingkat" 
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                                <option value="">Pilih Tingkat</option>
                                <option value="Ula" {{ old('tingkat', $editingKelas->tingkat ?? '') == 'Ula' ? 'selected' : '' }}>Ula</option>
                                <option value="Wusta" {{ old('tingkat', $editingKelas->tingkat ?? '') == 'Wusta' ? 'selected' : '' }}>Wusta</option>
                                <option value="Ulya" {{ old('tingkat', $editingKelas->tingkat ?? '') == 'Ulya' ? 'selected' : '' }}>Ulya</option>
                            </select>
                            @error('tingkat')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex space-x-3">
                            <button type="submit" 
                                    class="flex-1 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center justify-center transition duration-200 font-medium">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                {{ isset($editingKelas) ? 'Update Kelas' : 'Simpan Kelas' }}
                            </button>
                            
                            @if(isset($editingKelas))
                            <a href="{{ route('kelas.index') }}" 
                               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center justify-center transition duration-200 font-medium">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Batal
                            </a>
                            @endif
                        </div>
                    </form>
                </div>

                <!-- Quick Stats -->
                @if($kelas->count() > 0)
                <div class="bg-white rounded-lg shadow-md p-6 mt-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Statistik Kelas</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Total Kelas</span>
                            <span class="text-lg font-bold text-blue-600">{{ $kelas->total() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Kelas Ula</span>
                            <span class="text-lg font-bold text-green-600">{{ $kelas->where('tingkat', 'Ula')->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Kelas Wusta</span>
                            <span class="text-lg font-bold text-yellow-600">{{ $kelas->where('tingkat', 'Wusta')->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Kelas Ulya</span>
                            <span class="text-lg font-bold text-purple-600">{{ $kelas->where('tingkat', 'Ulya')->count() }}</span>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Table Section -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <!-- Table Header -->
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-semibold text-gray-800">Daftar Kelas</h3>
                            <div class="text-sm text-gray-600">
                                Total: <span class="font-medium">{{ $kelas->total() }}</span> kelas
                            </div>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        No
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nama Kelas
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tingkat
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($kelas as $item)
                                <tr class="hover:bg-gray-50 transition duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $loop->iteration + ($kelas->currentPage() - 1) * $kelas->perPage() }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                                </svg>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $item->nama_kelas }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $item->tingkat == 'Ula' ? 'bg-green-100 text-green-800' : 
                                               ($item->tingkat == 'Wusta' ? 'bg-yellow-100 text-yellow-800' : 'bg-purple-100 text-purple-800') }}">
                                            {{ $item->tingkat }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <!-- Edit Button -->
                                            <a href="{{ route('kelas.edit', $item->id_kelas) }}" 
                                               class="bg-yellow-100 hover:bg-yellow-200 text-yellow-700 px-3 py-1 rounded-md flex items-center transition duration-200">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                Edit
                                            </a>
                                            
                                            <!-- Delete Button -->
                                            <form action="{{ route('kelas.destroy', $item->id_kelas) }}" 
                                                  method="POST" 
                                                  class="delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="bg-red-100 hover:bg-red-200 text-red-700 px-3 py-1 rounded-md flex items-center transition duration-200"
                                                        data-kelas-name="{{ $item->nama_kelas }}">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center text-gray-500">
                                            <svg class="w-16 h-16 mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                            </svg>
                                            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada kelas</h3>
                                            <p class="text-gray-500 mb-4">Mulai dengan menambahkan kelas pertama Anda menggunakan form di samping</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($kelas->hasPages())
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-700">
                                Menampilkan 
                                <span class="font-medium">{{ $kelas->firstItem() }}</span>
                                sampai 
                                <span class="font-medium">{{ $kelas->lastItem() }}</span>
                                dari 
                                <span class="font-medium">{{ $kelas->total() }}</span>
                                hasil
                            </div>
                            <div class="flex space-x-2">
                                {{ $kelas->links() }}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </main>
</div>

@section('scripts')
<script>
    // Sweet alert untuk konfirmasi delete
    document.addEventListener('DOMContentLoaded', function() {
        const deleteForms = document.querySelectorAll('.delete-form');
        
        deleteForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const button = this.querySelector('button[type="submit"]');
                const kelasName = button.getAttribute('data-kelas-name');
                
                Swal.fire({
                    title: 'Hapus Kelas?',
                    text: `Apakah Anda yakin ingin menghapus kelas "${kelasName}"?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Tampilkan loading state
                        button.disabled = true;
                        button.innerHTML = `
                            <svg class="animate-spin w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2v4m0 12v4m8-10h-4M6 12H2"></path>
                            </svg>
                            Menghapus...
                        `;
                        
                        // Submit form
                        this.submit();
                    }
                });
            });
        });
    });

    // Auto-focus pada form input ketika dalam mode edit
    document.addEventListener('DOMContentLoaded', function() {
        @if(isset($editingKelas))
        document.getElementById('nama_kelas').focus();
        @endif
    });

    // Reset form setelah submit berhasil (untuk add mode)
    @if(session('success') && !isset($editingKelas))
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('kelasForm').reset();
    });
    @endif
</script>

<style>
    /* Animasi untuk loading spinner */
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    .animate-spin {
        animation: spin 1s linear infinite;
    }
    
    /* Hover effects untuk tombol aksi */
    .bg-yellow-100:hover, .bg-red-100:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    /* Transition untuk semua elemen interaktif */
    a, button {
        transition: all 0.2s ease-in-out;
    }
    
    /* Styling khusus untuk tombol kembali */
    .bg-gray-500:hover {
        transform: translateX(-2px);
    }
</style>
@endsection