@extends('index')

@section('title', 'Edit Data Keuangan')

@section('content')
<div class="flex bg-gray-100 min-h-screen">

    <!-- Sidebar -->
    @include('components.sidebar')

    <main class="flex-1 p-6">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-800">Edit Data Keuangan</h1>
                <p class="text-gray-600">Perbarui data transaksi keuangan pesantren</p>
            </div>

            <!-- Form Edit -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <form action="{{ route('keuangan.update', $keuangan->id_keuangan) }}" method="POST" id="editKeuanganForm">
                    @csrf
                    @method('PUT')

                    <!-- Alert Messages -->
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 p-3 rounded mb-4">
                            <ul class="list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Kolom Kiri -->
                        <div class="space-y-4">
                            <!-- Jumlah -->
                            <div>
                                <label for="jumlah" class="block text-sm font-medium text-gray-700 mb-2">
                                    Jumlah Transaksi <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">Rp</span>
                                    <input 
                                        type="number" 
                                        id="jumlah" 
                                        name="jumlah" 
                                        value="{{ old('jumlah', $keuangan->jumlah) }}"
                                        placeholder="0"
                                        min="0"
                                        step="1000"
                                        required
                                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200"
                                    >
                                </div>
                                @error('jumlah')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Kategori -->
                            <div>
                                <label for="id_kategori" class="block text-sm font-medium text-gray-700 mb-2">
                                    Kategori <span class="text-red-500">*</span>
                                </label>
                                <select 
                                    id="id_kategori" 
                                    name="id_kategori" 
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200"
                                >
                                    <option value="">Pilih Kategori</option>
                                    @foreach($kategories as $kategori)
                                        <option value="{{ $kategori->id_kategori }}" 
                                            {{ old('id_kategori', $keuangan->id_kategori) == $kategori->id_kategori ? 'selected' : '' }}>
                                            {{ $kategori->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_kategori')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Sumber Dana -->
                            <div>
                                <label for="sumber_dana" class="block text-sm font-medium text-gray-700 mb-2">
                                    Sumber Dana <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="sumber_dana" 
                                    name="sumber_dana" 
                                    value="{{ old('sumber_dana', $keuangan->sumber_dana) }}"
                                    placeholder="Masukkan sumber dana"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200"
                                >
                                @error('sumber_dana')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Kolom Kanan -->
                        <div class="space-y-4">
                            <!-- Status -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Status Transaksi <span class="text-red-500">*</span>
                                </label>
                                <div class="flex space-x-4">
                                    <label class="inline-flex items-center">
                                        <input 
                                            type="radio" 
                                            name="status" 
                                            value="Masuk" 
                                            {{ old('status', $keuangan->status) == 'Masuk' ? 'checked' : '' }}
                                            class="text-green-600 focus:ring-green-500"
                                        >
                                        <span class="ml-2 text-green-600 font-medium">Pemasukan</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input 
                                            type="radio" 
                                            name="status" 
                                            value="Keluar" 
                                            {{ old('status', $keuangan->status) == 'Keluar' ? 'checked' : '' }}
                                            class="text-red-600 focus:ring-red-500"
                                        >
                                        <span class="ml-2 text-red-600 font-medium">Pengeluaran</span>
                                    </label>
                                </div>
                                @error('status')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tanggal -->
                            <div>
                                <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tanggal Transaksi <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="date" 
                                    id="tanggal" 
                                    name="tanggal" 
                                    value="{{ old('tanggal', $keuangan->tanggal ? \Carbon\Carbon::parse($keuangan->tanggal)->format('Y-m-d') : '') }}"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200"
                                >
                                @error('tanggal')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Keterangan -->
                            <div>
                                <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                                    Keterangan
                                </label>
                                <textarea 
                                    id="keterangan" 
                                    name="keterangan" 
                                    rows="3"
                                    placeholder="Tambahkan keterangan transaksi (opsional)"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200 resize-none"
                                >{{ old('keterangan', $keuangan->keterangan) }}</textarea>
                                @error('keterangan')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mt-8 pt-6 border-t border-gray-200">
                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                            Pastikan data yang diinput sudah benar
                        </div>
                        
                        <div class="flex space-x-3">
                            <!-- Cancel Button -->
                            <a href="{{ route('keuangan.index') }}" 
                               class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200 font-medium">
                                Batal
                            </a>
                            
                            <!-- Submit Button -->
                            <button 
                                type="submit" 
                                id="submitButton"
                                class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-200 font-medium relative min-w-[120px]">
                                <span class="button-text">Simpan Perubahan</span>
                                <div class="loading-spinner hidden absolute inset-0 flex items-center justify-center">
                                    <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                    </svg>
                                    <span class="ml-2">Menyimpan...</span>
                                </div>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Info Card -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="text-sm text-blue-700">
                        <p class="font-medium">Informasi:</p>
                        <ul class="list-disc list-inside mt-1 space-y-1">
                            <li>Data terakhir diubah: {{ $keuangan->updated_at ? \Carbon\Carbon::parse($keuangan->updated_at)->format('d M Y H:i') : '-' }}</li>
                            <li>Pastikan jumlah transaksi sesuai dengan bukti fisik</li>
                            <li>Kategori harus sesuai dengan jenis transaksi</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
    // Format input jumlah dengan separator
    document.getElementById('jumlah').addEventListener('input', function(e) {
        let value = e.target.value.replace(/[^\d]/g, '');
        if (value) {
            e.target.value = parseInt(value).toLocaleString('id-ID');
        }
    });

    // Format jumlah saat load halaman
    document.addEventListener('DOMContentLoaded', function() {
        const jumlahInput = document.getElementById('jumlah');
        if (jumlahInput.value) {
            let value = jumlahInput.value.replace(/[^\d]/g, '');
            if (value) {
                jumlahInput.value = parseInt(value).toLocaleString('id-ID');
            }
        }
    });

    // Format jumlah sebelum submit
    document.getElementById('editKeuanganForm').addEventListener('submit', function(e) {
        const jumlahInput = document.getElementById('jumlah');
        let value = jumlahInput.value.replace(/[^\d]/g, '');
        if (value) {
            jumlahInput.value = value;
        }
    });

    // Loading state untuk form submission
    document.getElementById('editKeuanganForm').addEventListener('submit', function(e) {
        const submitButton = document.getElementById('submitButton');
        const buttonText = submitButton.querySelector('.button-text');
        const loadingSpinner = submitButton.querySelector('.loading-spinner');
        
        // Nonaktifkan tombol dan tampilkan loading
        submitButton.disabled = true;
        buttonText.style.display = 'none';
        loadingSpinner.classList.remove('hidden');
        submitButton.classList.add('opacity-50', 'cursor-not-allowed');
    });

    // Auto-format tanggal ke format Indonesia
    document.addEventListener('DOMContentLoaded', function() {
        const tanggalInput = document.getElementById('tanggal');
        if (!tanggalInput.value) {
            // Set default ke hari ini jika kosong
            const today = new Date().toISOString().split('T')[0];
            tanggalInput.value = today;
        }
    });

    // Validasi form sebelum submit
    document.getElementById('editKeuanganForm').addEventListener('submit', function(e) {
        const jumlah = document.getElementById('jumlah').value;
        const kategori = document.getElementById('id_kategori').value;
        const sumberDana = document.getElementById('sumber_dana').value;
        const status = document.querySelector('input[name="status"]:checked');
        const tanggal = document.getElementById('tanggal').value;

        if (!jumlah || !kategori || !sumberDana || !status || !tanggal) {
            e.preventDefault();
            alert('Harap lengkapi semua field yang wajib diisi!');
            return false;
        }

        // Validasi jumlah minimal
        const jumlahNum = parseInt(jumlah.replace(/[^\d]/g, ''));
        if (jumlahNum < 1000) {
            e.preventDefault();
            alert('Jumlah transaksi minimal Rp 1.000');
            return false;
        }

        return true;
    });
</script>

<style>
    /* Custom styling untuk radio buttons */
    input[type="radio"] {
        width: 18px;
        height: 18px;
    }

    /* Styling untuk disabled state */
    button:disabled {
        cursor: not-allowed;
        opacity: 0.6;
    }

    /* Loading animation */
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    .animate-spin {
        animation: spin 1s linear infinite;
    }
</style>
@endsection