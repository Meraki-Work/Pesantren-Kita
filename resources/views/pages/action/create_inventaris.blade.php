@extends('index')

@section('title', 'Tambah Inventaris - PesantrenKita')

@section('content')
<div class="flex bg-gray-100 min-h-screen">
    <x-sidemenu title="PesantrenKita" />

    <main class="flex-1 p-6 overflow-hidden">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden mb-6">
                <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-800">Tambah Data Inventaris</h2>
                            <p class="text-sm text-gray-600 mt-1">Tambahkan barang baru ke dalam sistem inventaris</p>
                        </div>
                        <a href="{{ route('inventaris.index') }}"
                            class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            ← Kembali ke Daftar Inventaris
                        </a>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <form action="{{ route('inventaris.store') }}" method="POST">
                    @csrf

                    <div class="p-6 space-y-6">
                        <!-- Nama Barang -->
                        <div>
                            <label for="nama_barang" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Barang <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                name="nama_barang"
                                id="nama_barang"
                                value="{{ old('nama_barang') }}"
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                placeholder="Contoh: Meja Belajar, Kursi, Lemari, dll">
                            @error('nama_barang')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Kategori -->
                            <div>
                                <label for="kategori" class="block text-sm font-medium text-gray-700 mb-2">
                                    Kategori <span class="text-red-500">*</span>
                                </label>
                                <select name="kategori"
                                    id="kategori"
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                                    <option value="">Pilih Kategori</option>
                                    <option value="Furniture" {{ old('kategori') == 'Furniture' ? 'selected' : '' }}>Furniture</option>
                                    <option value="Elektronik" {{ old('kategori') == 'Elektronik' ? 'selected' : '' }}>Elektronik</option>
                                    <option value="Alat Tulis" {{ old('kategori') == 'Alat Tulis' ? 'selected' : '' }}>Alat Tulis</option>
                                    <option value="Perlengkapan" {{ old('kategori') == 'Perlengkapan' ? 'selected' : '' }}>Perlengkapan</option>
                                    <option value="Kendaraan" {{ old('kategori') == 'Kendaraan' ? 'selected' : '' }}>Kendaraan</option>
                                    <option value="Lainnya" {{ old('kategori') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                                @error('kategori')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Kondisi -->
                            <div>
                                <label for="kondisi" class="block text-sm font-medium text-gray-700 mb-2">
                                    Kondisi <span class="text-red-500">*</span>
                                </label>
                                <select name="kondisi"
                                    id="kondisi"
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                                    <option value="">Pilih Kondisi</option>
                                    <option value="Baik" {{ old('kondisi') == 'Baik' ? 'selected' : '' }}>Baik</option>
                                    <option value="Rusak" {{ old('kondisi') == 'Rusak' ? 'selected' : '' }}>Rusak</option>
                                    <option value="Hilang" {{ old('kondisi') == 'Hilang' ? 'selected' : '' }}>Hilang</option>
                                </select>
                                @error('kondisi')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Jumlah -->
                            <div>
                                <label for="jumlah" class="block text-sm font-medium text-gray-700 mb-2">
                                    Jumlah <span class="text-red-500">*</span>
                                </label>
                                <input type="number"
                                    name="jumlah"
                                    id="jumlah"
                                    value="{{ old('jumlah') }}"
                                    min="1"
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                    placeholder="0">
                                @error('jumlah')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tanggal Beli -->
                            <div>
                                <label for="tanggal_beli" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tanggal Pembelian
                                </label>
                                <input type="date"
                                    name="tanggal_beli"
                                    id="tanggal_beli"
                                    value="{{ old('tanggal_beli') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                                @error('tanggal_beli')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Lokasi -->
                        <div>
                            <label for="lokasi" class="block text-sm font-medium text-gray-700 mb-2">
                                Lokasi Penyimpanan <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                name="lokasi"
                                id="lokasi"
                                value="{{ old('lokasi') }}"
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                placeholder="Contoh: Ruang Kelas 1, Gudang A, Perpustakaan">
                            @error('lokasi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Keterangan -->
                        <div>
                            <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                                Keterangan
                            </label>
                            <textarea name="keterangan"
                                id="keterangan"
                                rows="4"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                placeholder="Deskripsi barang, spesifikasi, atau catatan tambahan...">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Ponpes ID (hidden) -->
                        <input type="hidden" name="ponpes_id" value="{{ auth()->user()->ponpes_id ?? '' }}">
                    </div>

                    <!-- Form Actions -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('inventaris.index') }}"
                                class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200 font-medium">
                                Batal
                            </a>
                            <button type="submit"
                                class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-200 font-medium">
                                Simpan Data Inventaris
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Quick Tips -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-2xl p-4">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="text-sm text-blue-700">
                        <strong>Tips:</strong> Pastikan data yang dimasukkan akurat. Data inventaris yang lengkap membantu dalam pengelolaan aset pesantren.
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
<script>
    // Auto-format tanggal ke hari ini jika tidak diisi
    document.addEventListener('DOMContentLoaded', function() {
        const tanggalBeli = document.getElementById('tanggal_beli');
        if (!tanggalBeli.value) {
            const today = new Date().toISOString().split('T')[0];
            tanggalBeli.value = today;
        }

        // Focus ke input pertama
        document.getElementById('nama_barang').focus();
    });
</script>

@endsection