@extends('index')

@section('title', 'Edit Inventaris - PesantrenKita')

@section('content')
<div class="flex bg-gray-100 min-h-screen">
    <x-sidemenu title="PesantrenKita" />

    <main class="flex-1 p-6 overflow-hidden">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Edit Data Inventaris</h1>
                <p class="text-gray-600">Perbarui data inventaris pesantren</p>
            </div>

            <!-- Form Edit -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <form action="{{ route('inventaris.update', $inventaris->id_inventaris) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Nama Barang -->
                    <div class="mb-4">
                        <label for="nama_barang" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Barang <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="nama_barang" 
                               name="nama_barang" 
                               value="{{ old('nama_barang', $inventaris->nama_barang) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nama_barang') border-red-500 @enderror"
                               placeholder="Masukkan nama barang"
                               required>
                        @error('nama_barang')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kategori -->
                    <div class="mb-4">
                        <label for="kategori" class="block text-sm font-medium text-gray-700 mb-2">
                            Kategori <span class="text-red-500">*</span>
                        </label>
                        <select id="kategori" 
                                name="kategori" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('kategori') border-red-500 @enderror"
                                required>
                            <option value="">Pilih Kategori</option>
                            @foreach($kategories as $kategori)
                                <option value="{{ $kategori }}" 
                                    {{ old('kategori', $inventaris->kategori) == $kategori ? 'selected' : '' }}>
                                    {{ $kategori }}
                                </option>
                            @endforeach
                            <option value="Lainnya" 
                                {{ !in_array(old('kategori', $inventaris->kategori), $kategories->toArray()) && old('kategori', $inventaris->kategori) ? 'selected' : '' }}>
                                Lainnya
                            </option>
                        </select>
                        @error('kategori')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Input Kategori Baru (muncul jika pilih Lainnya) -->
                    <div class="mb-4 hidden" id="kategoriBaruContainer">
                        <label for="kategori_baru" class="block text-sm font-medium text-gray-700 mb-2">
                            Kategori Baru <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="kategori_baru" 
                               name="kategori_baru" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Masukkan kategori baru">
                    </div>

                    <!-- Kondisi -->
                    <div class="mb-4">
                        <label for="kondisi" class="block text-sm font-medium text-gray-700 mb-2">
                            Kondisi <span class="text-red-500">*</span>
                        </label>
                        <select id="kondisi" 
                                name="kondisi" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('kondisi') border-red-500 @enderror"
                                required>
                            <option value="">Pilih Kondisi</option>
                            @foreach($kondisis as $kondisiOption)
                                <option value="{{ $kondisiOption }}" 
                                    {{ old('kondisi', $inventaris->kondisi) == $kondisiOption ? 'selected' : '' }}>
                                    {{ $kondisiOption }}
                                </option>
                            @endforeach
                        </select>
                        @error('kondisi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jumlah -->
                    <div class="mb-4">
                        <label for="jumlah" class="block text-sm font-medium text-gray-700 mb-2">
                            Jumlah <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               id="jumlah" 
                               name="jumlah" 
                               value="{{ old('jumlah', $inventaris->jumlah) }}"
                               min="1"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('jumlah') border-red-500 @enderror"
                               placeholder="Masukkan jumlah barang"
                               required>
                        @error('jumlah')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Lokasi -->
                    <div class="mb-4">
                        <label for="lokasi" class="block text-sm font-medium text-gray-700 mb-2">
                            Lokasi <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="lokasi" 
                               name="lokasi" 
                               value="{{ old('lokasi', $inventaris->lokasi) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('lokasi') border-red-500 @enderror"
                               placeholder="Masukkan lokasi penyimpanan"
                               required>
                        @error('lokasi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Beli -->
                    <div class="mb-4">
                        <label for="tanggal_beli" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Pembelian
                        </label>
                        <input type="date" 
                               id="tanggal_beli" 
                               name="tanggal_beli" 
                               value="{{ old('tanggal_beli', $inventaris->tanggal_beli ? \Carbon\Carbon::parse($inventaris->tanggal_beli)->format('Y-m-d') : '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('tanggal_beli') border-red-500 @enderror">
                        @error('tanggal_beli')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Keterangan -->
                    <div class="mb-6">
                        <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                            Keterangan
                        </label>
                        <textarea id="keterangan" 
                                  name="keterangan" 
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('keterangan') border-red-500 @enderror"
                                  placeholder="Masukkan keterangan tambahan">{{ old('keterangan', $inventaris->keterangan) }}</textarea>
                        @error('keterangan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('inventaris.index') }}" 
                           class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 transition duration-200">
                            Batal
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Info Data -->
            <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-sm text-blue-700">
                        Data ini terakhir diupdate: {{ $inventaris->created_at ? \Carbon\Carbon::parse($inventaris->created_at)->format('d M Y H:i') : 'Tidak tersedia' }}
                    </p>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const kategoriSelect = document.getElementById('kategori');
    const kategoriBaruContainer = document.getElementById('kategoriBaruContainer');
    const kategoriBaruInput = document.getElementById('kategori_baru');

    function toggleKategoriBaru() {
        if (kategoriSelect.value === 'Lainnya') {
            kategoriBaruContainer.classList.remove('hidden');
            kategoriBaruInput.setAttribute('required', 'required');
        } else {
            kategoriBaruContainer.classList.add('hidden');
            kategoriBaruInput.removeAttribute('required');
            kategoriBaruInput.value = '';
        }
    }

    // Handle kategori change
    kategoriSelect.addEventListener('change', toggleKategoriBaru);

    // Handle form submission untuk kategori baru
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        if (kategoriSelect.value === 'Lainnya' && kategoriBaruInput.value) {
            kategoriSelect.value = kategoriBaruInput.value;
        }
    });

    // Initialize on page load
    toggleKategoriBaru();

    // Jika kategori tidak ada di dropdown, set ke Lainnya
    const currentKategori = '{{ old('kategori', $inventaris->kategori) }}';
    const kategoriOptions = Array.from(kategoriSelect.options).map(option => option.value);
    
    if (currentKategori && !kategoriOptions.includes(currentKategori) && currentKategori !== 'Lainnya') {
        kategoriSelect.value = 'Lainnya';
        kategoriBaruInput.value = currentKategori;
        toggleKategoriBaru();
    }
});
</script>
@endsection