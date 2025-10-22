@extends('index')

@section('content')
<div class="flex bg-gray-100 min-h-screen">
    <x-sidemenu title="PesantrenKita" />
    
    <main class="flex-1 p-6 overflow-hidden">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <!-- Header -->
                <div class="flex items-center mb-6">
                    <a href="{{ route('laundry.index') }}" class="text-blue-600 hover:text-blue-800 mr-3 transition duration-150" title="Kembali ke daftar transaksi">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </a>
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">Edit Transaksi</h3>
                        <p class="text-sm text-gray-600">Memperbarui data transaksi keuangan</p>
                    </div>
                </div>

                <!-- Alert Messages -->
                @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('success') }}
                </div>
                @endif

                @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('error') }}
                </div>
                @endif

                <form action="{{ route('laundry.update', $keuangan->id_keuangan) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <!-- Santri -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Santri</label>
                        <select name="id_santri" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150">
                            <option value="">-- Pilih Santri --</option>
                            @foreach($santri as $s)
                            <option value="{{ $s->id_santri }}" {{ $keuangan->id_santri == $s->id_santri ? 'selected' : '' }}>
                                {{ $s->nama }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Kategori -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kategori <span class="text-red-500">*</span></label>
                        <select name="id_kategori" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategori as $k)
                            <option value="{{ $k->id_kategori }}" {{ $keuangan->id_kategori == $k->id_kategori ? 'selected' : '' }}>
                                {{ $k->nama_kategori }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Sumber Dana -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sumber Dana <span class="text-red-500">*</span></label>
                        <input type="text" name="sumber_dana" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150" 
                               value="{{ old('sumber_dana', $keuangan->sumber_dana) }}" required>
                    </div>

                    <!-- Keterangan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                        <textarea name="keterangan" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150">{{ old('keterangan', $keuangan->keterangan) }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Jumlah -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah <span class="text-red-500">*</span></label>
                            <input type="number" name="jumlah" step="0.01" min="0" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150" 
                                   value="{{ old('jumlah', $keuangan->jumlah) }}" required>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                            <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150" required>
                                <option value="Masuk" {{ $keuangan->status == 'Masuk' ? 'selected' : '' }}>Pemasukan</option>
                                <option value="Keluar" {{ $keuangan->status == 'Keluar' ? 'selected' : '' }}>Pengeluaran</option>
                            </select>
                        </div>
                    </div>

                    <!-- Tanggal -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150" 
                               value="{{ old('tanggal', $keuangan->tanggal) }}" required>
                    </div>

                    <div class="flex justify-end gap-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('laundry.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-150 font-medium">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-150 font-medium flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Update Transaksi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>
@endsection