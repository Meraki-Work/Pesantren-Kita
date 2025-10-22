{{-- resources/views/pages/santri/edit.blade.php --}}
@extends('index')

@section('title', 'Edit Santri')

@section('content')
<div class="min-h-screen bg-gray-50 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('santri.index') }}"
                        class="flex items-center text-gray-600 hover:text-gray-900 transition duration-150 ease-in-out">
                        <i class="fas fa-arrow-left mr-2"></i>
                        <span>Kembali ke Data Santri</span>
                    </a>
                    <div class="h-6 w-px bg-gray-300"></div>
                    <h1 class="text-2xl font-bold text-gray-900">Edit Data Santri</h1>
                </div>
                <div class="text-sm text-gray-500">
                    ID: {{ $santri->id_santri }}
                </div>
            </div>
        </div>

        <!-- Form Edit -->
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <form action="{{ route('santri.update', $santri->id_santri) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                <!-- Pesan Success/Error -->
                @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('success') }}
                    </div>
                </div>
                @endif

                @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <strong>Terjadi kesalahan:</strong>
                    </div>
                    <ul class="list-disc list-inside text-sm">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Kolom Kiri -->
                    <div class="space-y-4">
                        <!-- Nama -->
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">
                                Nama Santri <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                name="nama"
                                id="nama"
                                value="{{ old('nama', $santri->nama) }}"
                                class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                                required
                                maxlength="100"
                                placeholder="Masukkan nama lengkap santri">
                        </div>

                        <!-- NISN -->
                        <div>
                            <label for="nisn" class="block text-sm font-medium text-gray-700 mb-1">
                                NISN <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                name="nisn"
                                id="nisn"
                                value="{{ old('nisn', $santri->nisn) }}"
                                class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                                required
                                maxlength="20"
                                placeholder="Nomor Induk Siswa Nasional">
                        </div>

                        <!-- NIK -->
                        <div>
                            <label for="nik" class="block text-sm font-medium text-gray-700 mb-1">NIK</label>
                            <input type="text"
                                name="nik"
                                id="nik"
                                value="{{ old('nik', $santri->nik) }}"
                                class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                                maxlength="20"
                                placeholder="Nomor Induk Kependudukan">
                        </div>

                        <!-- Kelas -->
                        <div>
                            <label for="id_kelas" class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                            <select name="id_kelas"
                                id="id_kelas"
                                class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
                                <option value="">Pilih Kelas</option>
                                @foreach($kelas as $kela)
                                <option value="{{ $kela->id_kelas }}"
                                    {{ old('id_kelas', $santri->id_kelas) == $kela->id_kelas ? 'selected' : '' }}>
                                    {{ $kela->nama_kelas }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Status Ujian -->
                        <div>
                            <label for="status_ujian" class="block text-sm font-medium text-gray-700 mb-1">Status Ujian</label>
                            <select name="status_ujian"
                                id="status_ujian"
                                class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
                                <option value="">Pilih Status</option>
                                <option value="Lulus" {{ old('status_ujian', $santri->status_ujian) == 'Lulus' ? 'selected' : '' }}>Lulus</option>
                                <option value="Belum Lulus" {{ old('status_ujian', $santri->status_ujian) == 'Belum Lulus' ? 'selected' : '' }}>Belum Lulus</option>
                            </select>
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="space-y-4">
                        <!-- Tahun Masuk -->
                        <div>
                            <label for="tahun_masuk" class="block text-sm font-medium text-gray-700 mb-1">Tahun Masuk</label>
                            <input type="number"
                                name="tahun_masuk"
                                id="tahun_masuk"
                                value="{{ old('tahun_masuk', $santri->tahun_masuk) }}"
                                min="1900"
                                max="{{ date('Y') + 1 }}"
                                class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                                placeholder="Tahun masuk santri">
                        </div>

                        <!-- Jenis Kelamin -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin</label>
                            <div class="flex space-x-6">
                                <label class="inline-flex items-center">
                                    <input type="radio"
                                        name="jenis_kelamin"
                                        value="Laki-laki"
                                        {{ old('jenis_kelamin', $santri->jenis_kelamin) == 'Laki-laki' ? 'checked' : '' }}
                                        class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 transition duration-150 ease-in-out">
                                    <span class="ml-2 text-sm text-gray-700">Laki-laki</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio"
                                        name="jenis_kelamin"
                                        value="Perempuan"
                                        {{ old('jenis_kelamin', $santri->jenis_kelamin) == 'Perempuan' ? 'checked' : '' }}
                                        class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 transition duration-150 ease-in-out">
                                    <span class="ml-2 text-sm text-gray-700">Perempuan</span>
                                </label>
                            </div>
                        </div>

                        <!-- Tanggal Lahir -->
                        <div>
                            <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                            <input type="date"
                                name="tanggal_lahir"
                                id="tanggal_lahir"
                                value="{{ old('tanggal_lahir', $santri->tanggal_lahir) }}"
                                class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
                        </div>

                        <!-- Nama Ayah -->
                        <div>
                            <label for="nama_ayah" class="block text-sm font-medium text-gray-700 mb-1">Nama Ayah</label>
                            <input type="text"
                                name="nama_ayah"
                                id="nama_ayah"
                                value="{{ old('nama_ayah', $santri->nama_ayah) }}"
                                class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                                maxlength="100"
                                placeholder="Nama lengkap ayah">
                        </div>

                        <!-- Nama Ibu -->
                        <div>
                            <label for="nama_ibu" class="block text-sm font-medium text-gray-700 mb-1">Nama Ibu</label>
                            <input type="text"
                                name="nama_ibu"
                                id="nama_ibu"
                                value="{{ old('nama_ibu', $santri->nama_ibu) }}"
                                class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                                maxlength="100"
                                placeholder="Nama lengkap ibu">
                        </div>
                    </div>
                </div>

                <!-- Alamat (Full Width) -->
                <div class="mt-4">
                    <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                    <textarea name="alamat"
                        id="alamat"
                        rows="3"
                        class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                        placeholder="Alamat lengkap santri">{{ old('alamat', $santri->alamat) }}</textarea>
                </div>

                <!-- Tombol Aksi -->
                <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('santri.index') }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        <!-- Informasi Sistem -->
        <div class="mt-6 bg-white shadow-sm rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                    <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                    Informasi Sistem
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600">
                    <div class="flex flex-col space-y-2">
                        <span class="font-medium text-gray-700">ID Santri</span>
                        <code class="bg-gray-100 px-2 py-1 rounded text-xs font-mono">{{ $santri->id_santri }}</code>
                    </div>
                    <div class="flex flex-col space-y-2">
                        <span class="font-medium text-gray-700">Dibuat Pada</span>
                        <span>
                            @if($santri->created_at)
                            {{ $santri->created_at->format('d M Y H:i') }}
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </span>
                    </div>
                    <div class="flex flex-col space-y-2">
                        <span class="font-medium text-gray-700">Diupdate Pada</span>
                        <span>
                            @if($santri->updated_at)
                            {{ $santri->updated_at->format('d M Y H:i') }}
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        let formChanged = false;

        // Deteksi perubahan form
        const inputs = form.querySelectorAll('input, textarea, select');
        inputs.forEach(input => {
            const initialValue = input.value;

            input.addEventListener('input', () => {
                formChanged = true;
            });

            input.addEventListener('change', () => {
                formChanged = true;
            });
        });

        // Konfirmasi sebelum meninggalkan halaman
        window.addEventListener('beforeunload', function(e) {
            if (formChanged) {
                e.preventDefault();
                e.returnValue = 'Perubahan yang Anda buat mungkin tidak akan disimpan.';
            }
        });

        // Reset flag ketika form disubmit
        form.addEventListener('submit', function() {
            formChanged = false;
        });

        // Konfirmasi ketika klik tombol batal
        const backButton = document.querySelector("a[href='{{ route('santri.index') }}']");
        if (backButton) {
            backButton.addEventListener('click', function(e) {
                if (formChanged && !confirm('Perubahan yang Anda buat mungkin tidak akan disimpan. Yakin ingin keluar?')) {
                    e.preventDefault();
                }
            });
        }
    });
</script>
@endsection