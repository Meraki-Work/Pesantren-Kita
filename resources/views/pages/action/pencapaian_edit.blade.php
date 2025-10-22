@extends('index')

@section('title', 'Edit Pencapaian')

@section('content')
<div class="flex bg-gray-100 min-h-screen">
    <x-sidemenu title="PesantrenKita" />
    
    <main class="flex-1 p-6">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Edit Pencapaian</h1>
                <p class="text-gray-600">Edit data pencapaian untuk {{ $pencapaian->nama_santri }}</p>
            </div>

            <!-- Info Santri -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex items-center space-x-3">
                    <div class="flex-1">
                        <h3 class="font-semibold text-blue-800">{{ $pencapaian->nama_santri }}</h3>
                        <p class="text-sm text-blue-600">{{ $pencapaian->nama_kelas }}</p>
                    </div>
                    <a href="{{ route('santri.index') }}" 
                       class="text-sm text-blue-600 hover:text-blue-800 underline">
                        Kembali ke Daftar
                    </a>
                </div>
            </div>

            <!-- Form -->
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <form action="{{ route('pencapaian.update', $pencapaian->id_pencapaian) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-4">
                        <!-- Judul -->
                        <div>
                            <label for="judul" class="block text-sm font-medium text-gray-700 mb-1">Judul Pencapaian *</label>
                            <input type="text" 
                                   name="judul" 
                                   id="judul"
                                   value="{{ old('judul', $pencapaian->judul) }}"
                                   required
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('judul')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Deskripsi -->
                        <div>
                            <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                            <textarea name="deskripsi" 
                                      id="deskripsi" 
                                      rows="3"
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('deskripsi', $pencapaian->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Tipe -->
                            <div>
                                <label for="tipe" class="block text-sm font-medium text-gray-700 mb-1">Tipe *</label>
                                <select name="tipe" 
                                        id="tipe"
                                        required
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Pilih Tipe</option>
                                    <option value="akademik" {{ old('tipe', $pencapaian->tipe) == 'akademik' ? 'selected' : '' }}>Akademik</option>
                                    <option value="non-akademik" {{ old('tipe', $pencapaian->tipe) == 'non-akademik' ? 'selected' : '' }}>Non-Akademik</option>
                                    <option value="hafalan" {{ old('tipe', $pencapaian->tipe) == 'hafalan' ? 'selected' : '' }}>Hafalan</option>
                                    <option value="olahraga" {{ old('tipe', $pencapaian->tipe) == 'olahraga' ? 'selected' : '' }}>Olahraga</option>
                                    <option value="seni" {{ old('tipe', $pencapaian->tipe) == 'seni' ? 'selected' : '' }}>Seni</option>
                                </select>
                                @error('tipe')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Skor -->
                            <div>
                                <label for="skor" class="block text-sm font-medium text-gray-700 mb-1">Skor (0-100) *</label>
                                <input type="number" 
                                       name="skor" 
                                       id="skor"
                                       min="0"
                                       max="100"
                                       step="0.1"
                                       value="{{ old('skor', $pencapaian->skor) }}"
                                       required
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @error('skor')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Tanggal -->
                        <div>
                            <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-1">Tanggal *</label>
                            <input type="date" 
                                   name="tanggal" 
                                   id="tanggal"
                                   value="{{ old('tanggal', \Carbon\Carbon::parse($pencapaian->tanggal)->format('Y-m-d')) }}"
                                   required
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('tanggal')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-6 flex justify-end space-x-3">
                        <a href="{{ route('santri.index') }}" 
                           class="px-4 py-2 text-sm text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                            Batal
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 text-sm text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
                            Update Pencapaian
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>
@endsection