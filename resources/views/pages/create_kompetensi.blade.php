@extends('index')

@section('content')

<div class="flex bg-gray-100">
    <x-sidemenu title="PesantrenKita" />
    <main class="flex-1 p-6 overflow-hidden">
        <div class="flex gap-2">
            <div class="box-bg w-full max-w-lg p-6 transform transition-all">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Tambah Kompetensi</h3>

                <!-- alert -->
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

                <form action="{{ route('kompetensi.store') }}" method="POST">
                    @csrf

                    <!-- Pilih Santri -->
                    <div class="mb-3">
                        <label class="block font-medium">Nama Santri</label>
                        <select name="id_santri" id="santriSelect" class="input input-bordered w-full rounded-md p-2" required>
                            <option value="">-- Pilih Santri --</option>
                            @foreach($santri as $s)
                            <option value="{{ $s->id_santri }}" data-kelas="{{ $s->kela ? $s->kela->id_kelas : '' }}">
                                {{ $s->nama }} @if($s->kela) @endif
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Kelas (otomatis terisi) -->
                    <div class="mb-3">
                        <label class="block font-medium">Kelas</label>
                        <select name="id_kelas" id="kelasSelect" class="input input-bordered w-full rounded-md p-2" disabled>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelas as $k)
                            <option value="{{ $k->id_kelas }}">{{ $k->nama_kelas }} ({{ $k->tingkat }})</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Judul -->
                    <div class="mb-3">
                        <label class="block font-medium">Judul</label>
                        <input type="text" name="judul" class="input input-bordered w-full rounded-md p-2" required>
                    </div>

                    <!-- Deskripsi -->
                    <div class="mb-3">
                        <label class="block font-medium">Deskripsi</label>
                        <textarea name="deskripsi" class="input input-bordered w-full rounded-md p-2" rows="3"></textarea>
                    </div>

                    <!-- Tipe -->
                    <div class="mb-3">
                        <label class="block font-medium">Tipe</label>
                        <select name="tipe" class="input input-bordered w-full rounded-md p-2" required>
                            <option value="">-- Pilih Tipe --</option>
                            <option value="Akademik">Akademik</option>
                            <option value="Non-Akademik">Non-Akademik</option>
                            <option value="Tahfidz">Tahfidz</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>

                    <!-- Skor -->
                    <div class="mb-3">
                        <label class="block font-medium">Skor</label>
                        <input type="number" name="skor" class="input input-bordered w-full rounded-md p-2">
                    </div>

                    <!-- Tanggal -->
                    <div class="mb-3">
                        <label class="block font-medium">Tanggal</label>
                        <input type="date" name="tanggal" class="input input-bordered w-full rounded-md p-2" required>
                    </div>

                    <div class="flex justify-end gap-2 mt-4">
                        <button type="button" @click="openModal = false" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                            Simpan
                        </button>
                    </div>
                </form>

            </div>
            <div class="p-4 box-bg">
                <h3>Kompetensi</h3>
            </div>
        </div>
        <script>
            const santriSelect = document.getElementById('santriSelect');
            const kelasSelect = document.getElementById('kelasSelect');

            santriSelect.addEventListener('change', function() {
                const kelasId = this.options[this.selectedIndex].getAttribute('data-kelas');
                kelasSelect.value = kelasId || '';
            });
        </script>
    </main>
</div>
@endsection