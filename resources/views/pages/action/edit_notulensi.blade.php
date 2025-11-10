@extends('index')

@section('title', 'Edit Notulen - PesantrenKita')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden mb-6">
            <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">Edit Notulen Rapat</h2>
                        <p class="text-sm text-gray-600 mt-1">Perbarui informasi notulen rapat</p>
                    </div>
                    <a href="{{ route('notulen.show', $notulen->id_notulen) }}" 
                       class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        ← Kembali ke Detail
                    </a>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <form action="{{ route('notulen.update', $notulen->id_notulen) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="p-6 space-y-6">
                    <!-- Agenda -->
                    <div>
                        <label for="agenda" class="block text-sm font-medium text-gray-700 mb-2">
                            Agenda Rapat <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="agenda" 
                               id="agenda"
                               value="{{ old('agenda', $notulen->agenda) }}"
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                               placeholder="Contoh: Rapat Koordinasi Bulanan, Evaluasi Program Tahunan, dll">
                        @error('agenda')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Pimpinan Rapat -->
                        <div>
                            <label for="pimpinan" class="block text-sm font-medium text-gray-700 mb-2">
                                Pimpinan Rapat <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="pimpinan" 
                                   id="pimpinan"
                                   value="{{ old('pimpinan', $notulen->pimpinan) }}"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                   placeholder="Nama pimpinan rapat">
                            @error('pimpinan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tempat -->
                        <div>
                            <label for="tempat" class="block text-sm font-medium text-gray-700 mb-2">
                                Tempat Rapat <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="tempat" 
                                   id="tempat"
                                   value="{{ old('tempat', $notulen->tempat) }}"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                   placeholder="Contoh: Aula Utama, Ruang Rapat, dll">
                            @error('tempat')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Tanggal -->
                        <div>
                            <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">
                                Tanggal Rapat <span class="text-red-500">*</span>
                            </label>
                            <input type="date" 
                                   name="tanggal" 
                                   id="tanggal"
                                   value="{{ old('tanggal', $notulen->tanggal->format('Y-m-d')) }}"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                            @error('tanggal')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Waktu -->
                        <div>
                            <label for="waktu" class="block text-sm font-medium text-gray-700 mb-2">
                                Waktu Rapat <span class="text-red-500">*</span>
                            </label>
                            <input type="time" 
                                   name="waktu" 
                                   id="waktu"
                                   value="{{ old('waktu', $notulen->waktu_formatted) }}"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                            @error('waktu')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Peserta -->
                    <div>
                        <label for="peserta" class="block text-sm font-medium text-gray-700 mb-2">
                            Daftar Peserta <span class="text-red-500">*</span>
                        </label>
                        <textarea name="peserta" 
                                  id="peserta"
                                  rows="3"
                                  required
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                  placeholder="Tulis nama-nama peserta rapat, pisahkan dengan koma atau enter">{{ old('peserta', $notulen->peserta) }}</textarea>
                        @error('peserta')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Alur Rapat -->
                    <div>
                        <label for="alur_rapat" class="block text-sm font-medium text-gray-700 mb-2">
                            Alur / Proses Rapat <span class="text-red-500">*</span>
                        </label>
                        <textarea name="alur_rapat" 
                                  id="alur_rapat"
                                  rows="4"
                                  required
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                  placeholder="Jelaskan urutan acara dan proses rapat yang dilakukan">{{ old('alur_rapat', $notulen->alur_rapat) }}</textarea>
                        @error('alur_rapat')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Hasil Rapat -->
                    <div>
                        <label for="hasil" class="block text-sm font-medium text-gray-700 mb-2">
                            Hasil & Keputusan Rapat <span class="text-red-500">*</span>
                        </label>
                        <textarea name="hasil" 
                                  id="hasil"
                                  rows="4"
                                  required
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                  placeholder="Tuliskan hasil pembahasan, keputusan, dan action items yang disepakati">{{ old('hasil', $notulen->hasil) }}</textarea>
                        @error('hasil')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Keterangan -->
                    <div>
                        <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                            Keterangan Tambahan
                        </label>
                        <textarea name="keterangan" 
                                  id="keterangan"
                                  rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                  placeholder="Catatan tambahan, lampiran, atau informasi pendukung">{{ old('keterangan', $notulen->keterangan) }}</textarea>
                        @error('keterangan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <div>
                            <button type="button" 
                                    onclick="confirmDelete()"
                                    class="px-4 py-2 text-red-600 border border-red-300 rounded-lg hover:bg-red-50 transition duration-200 font-medium">
                                Hapus Notulen
                            </button>
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('notulen.show', $notulen->id_notulen) }}" 
                               class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200 font-medium">
                                Batal
                            </a>
                            <button type="submit" 
                                    class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 font-medium">
                                Update Notulen
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Delete Form (Hidden) -->
            <form id="deleteForm" 
                  action="{{ route('notulen.destroy', $notulen->id_notulen) }}" 
                  method="POST" 
                  class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
</div>

<script>
    function confirmDelete() {
        if (confirm('Apakah Anda yakin ingin menghapus notulen ini? Tindakan ini tidak dapat dibatalkan.')) {
            document.getElementById('deleteForm').submit();
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Focus ke input agenda
        document.getElementById('agenda').focus();
    });
</script>

<!-- Include SweetAlert2 for better confirmation -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Enhanced delete confirmation dengan SweetAlert2
    function confirmDelete() {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Notulen rapat akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteForm').submit();
            }
        });
    }
</script>
@endsection