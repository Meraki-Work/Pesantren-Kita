@extends('index')

@section('title', 'Edit Pesantren')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Edit Pesantren</h1>
                    <p class="mt-1 text-sm text-gray-600">Perbarui informasi pesantren</p>
                </div>
                <a href="{{ route('admin.ponpes.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white shadow-lg rounded-xl overflow-hidden">
            <div class="border-b border-gray-200 px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-800">Form Edit Pesantren</h2>
            </div>
            
            <form action="{{ route('admin.ponpes.update', $ponpes->id_ponpes) }}" method="POST" enctype="multipart/form-data" class="px-6 py-8">
                @csrf
                @method('PUT')
                
                <!-- Grid Container -->
                <div class="space-y-6">
                    <!-- Row 1: ID dan Nama Pesantren -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- ID Pesantren -->
                        <div>
                            <label for="id_ponpes" class="block text-sm font-medium text-gray-700 mb-2">
                                ID Pesantren
                            </label>
                            <input type="text" 
                                   id="id_ponpes" 
                                   name="id_ponpes"
                                   value="{{ old('id_ponpes', $ponpes->id_ponpes) }}"
                                   readonly
                                   class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-gray-500 cursor-not-allowed focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <p class="mt-1 text-xs text-gray-500">ID tidak dapat diubah</p>
                        </div>
                        
                        <!-- Nama Pesantren -->
                        <div>
                            <label for="nama_ponpes" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Pesantren <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="nama_ponpes" 
                                   name="nama_ponpes"
                                   value="{{ old('nama_ponpes', $ponpes->nama_ponpes) }}"
                                   required
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors @error('nama_ponpes') border-red-500 @enderror">
                            @error('nama_ponpes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Alamat -->
                    <div>
                        <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">
                            Alamat <span class="text-red-500">*</span>
                        </label>
                        <textarea id="alamat" 
                                  name="alamat"
                                  rows="3"
                                  required
                                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors @error('alamat') border-red-500 @enderror">{{ old('alamat', $ponpes->alamat) }}</textarea>
                        @error('alamat')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Row 2: Tahun, Telepon, Email -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Tahun Berdiri -->
                        <div>
                            <label for="tahun_berdiri" class="block text-sm font-medium text-gray-700 mb-2">
                                Tahun Berdiri <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                   id="tahun_berdiri" 
                                   name="tahun_berdiri"
                                   value="{{ old('tahun_berdiri', $ponpes->tahun_berdiri) }}"
                                   min="1900" 
                                   max="2100"
                                   required
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors @error('tahun_berdiri') border-red-500 @enderror">
                            @error('tahun_berdiri')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Telepon -->
                        <div>
                            <label for="telp" class="block text-sm font-medium text-gray-700 mb-2">
                                Telepon <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="telp" 
                                   name="telp"
                                   value="{{ old('telp', $ponpes->telp) }}"
                                   required
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors @error('telp') border-red-500 @enderror">
                            @error('telp')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" 
                                   id="email" 
                                   name="email"
                                   value="{{ old('email', $ponpes->email) }}"
                                   required
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Row 3: Logo dan Jumlah -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Logo Pesantren -->
                        <div>
                            <label for="logo_ponpes" class="block text-sm font-medium text-gray-700 mb-2">
                                Logo Pesantren
                            </label>
                            
                            @if($ponpes->logo_ponpes)
                            <div class="mb-4">
                                <div class="relative inline-block">
                                    <img src="{{ Storage::url($ponpes->logo_ponpes) }}" 
                                         alt="Logo" 
                                         class="w-32 h-32 object-cover rounded-lg border border-gray-300 shadow-sm">
                                </div>
                                <div class="mt-3 flex items-center">
                                    <input type="checkbox" 
                                           id="remove_logo" 
                                           name="remove_logo" 
                                           value="1"
                                           class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                                    <label for="remove_logo" class="ml-2 text-sm font-medium text-red-600 cursor-pointer">
                                        Hapus logo ini
                                    </label>
                                </div>
                            </div>
                            @endif
                            
                            <input type="file" 
                                   id="logo_ponpes" 
                                   name="logo_ponpes"
                                   accept="image/*"
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            @error('logo_ponpes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, GIF. Maks: 2MB</p>
                        </div>
                        
                        <!-- Jumlah Santri & Staf -->
                        <div>
                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <label for="jumlah_santri" class="block text-sm font-medium text-gray-700 mb-2">
                                        Jumlah Santri
                                    </label>
                                    <input type="number" 
                                           id="jumlah_santri" 
                                           name="jumlah_santri"
                                           value="{{ old('jumlah_santri', $ponpes->jumlah_santri) }}"
                                           min="0"
                                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors @error('jumlah_santri') border-red-500 @enderror">
                                    @error('jumlah_santri')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="jumlah_staf" class="block text-sm font-medium text-gray-700 mb-2">
                                        Jumlah Staf
                                    </label>
                                    <input type="number" 
                                           id="jumlah_staf" 
                                           name="jumlah_staf"
                                           value="{{ old('jumlah_staf', $ponpes->jumlah_staf) }}"
                                           min="0"
                                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors @error('jumlah_staf') border-red-500 @enderror">
                                    @error('jumlah_staf')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Row 4: Pimpinan dan Status -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Pimpinan -->
                        <div>
                            <label for="pimpinan" class="block text-sm font-medium text-gray-700 mb-2">
                                Pimpinan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="pimpinan" 
                                   name="pimpinan"
                                   value="{{ old('pimpinan', $ponpes->pimpinan) }}"
                                   required
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors @error('pimpinan') border-red-500 @enderror">
                            @error('pimpinan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select id="status" 
                                    name="status"
                                    required
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors @error('status') border-red-500 @enderror">
                                <option value="Aktif" {{ old('status', $ponpes->status) == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="Nonaktif" {{ old('status', $ponpes->status) == 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex flex-col-reverse sm:flex-row justify-between items-center pt-8 mt-8 border-t border-gray-200 space-y-4 sm:space-y-0">
                    <div>
                        <a href="{{ route('admin.ponpes.show', $ponpes->id_ponpes) }}" 
                           class="inline-flex items-center px-5 py-2.5 bg-blue-100 hover:bg-blue-200 text-blue-800 rounded-lg transition-colors duration-200 font-medium">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            Lihat Detail
                        </a>
                    </div>
                    <div>
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-lg shadow-md hover:shadow-lg transition-all duration-200 font-semibold">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                            </svg>
                            Update Pesantren
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Toggle remove logo checkbox
const removeLogoCheckbox = document.getElementById('remove_logo');
if (removeLogoCheckbox) {
    removeLogoCheckbox.addEventListener('change', function() {
        const logoInput = document.getElementById('logo_ponpes');
        if (this.checked) {
            logoInput.disabled = true;
            logoInput.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            logoInput.disabled = false;
            logoInput.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    });
}

// Form validation enhancement
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const requiredInputs = form.querySelectorAll('[required]');
    
    requiredInputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (!this.value.trim()) {
                this.classList.add('border-red-500');
            } else {
                this.classList.remove('border-red-500');
            }
        });
    });
});
</script>
@endpush
@endsection