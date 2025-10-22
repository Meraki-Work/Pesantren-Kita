@extends('index')

@section('content')
<div x-data="{ openModal: false }" x-cloak class="p-6">

    <!-- Tombol Tambah Kompetensi -->
    <button @click="openModal = true" 
        class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
        + Tambah Pencapaian
    </button>

    <!-- Modal Form -->
    <div x-show="openModal"
         class="fixed inset-0 flex items-center justify-center backdrop-blur-sm bg-black/40 z-50"
         x-transition>
        <div @click.away="openModal = false"
             class="bg-white rounded-xl shadow-2xl w-full max-w-lg p-6 transform transition-all scale-95 hover:scale-100 duration-200">
            
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Tambah Pencapaian</h3>

            <!-- Notifikasi -->
            @if(session('success'))
                <div class="bg-green-100 text-green-700 p-3 rounded mb-3">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 text-red-700 p-3 rounded mb-3">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('kompetensi.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="block font-medium">Santri</label>
                    <select name="id_santri" class="border w-full rounded-md p-2" required>
                        <option value="">-- Pilih Santri --</option>
                        @foreach($santri as $s)
                            <option value="{{ $s->id_santri }}">{{ $s->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="block font-medium">Judul</label>
                    <input type="text" name="judul" class="border w-full rounded-md p-2" placeholder="Judul pencapaian" required>
                </div>

                <div class="mb-3">
                    <label class="block font-medium">Deskripsi</label>
                    <textarea name="deskripsi" class="border w-full rounded-md p-2" placeholder="Deskripsi pencapaian"></textarea>
                </div>

                <div class="mb-3">
                    <label class="block font-medium">Tipe</label>
                    <select name="tipe" class="border w-full rounded-md p-2" required>
                        <option value="">-- Pilih Tipe --</option>
                        <option value="Akademik">Akademik</option>
                        <option value="Non-Akademik">Non-Akademik</option>
                        <option value="Tahfidz">Tahfidz</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="block font-medium">Skor</label>
                    <input type="number" name="skor" class="border w-full rounded-md p-2" placeholder="Skor (opsional)">
                </div>

                <div class="mb-3">
                    <label class="block font-medium">Tanggal</label>
                    <input type="date" name="tanggal" class="border w-full rounded-md p-2" required>
                </div>

                <div class="flex justify-end gap-2 mt-4">
                    <button type="button" @click="openModal = false" class="px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
