@extends('index')

@section('title', 'Tambah Sanksi')

@section('content')
<div class="flex bg-gray-100 min-h-screen">

    <x-sidemenu title="PesantrenKita" />

    <main class="flex-1 p-4 h-full">
        <div class="bg-white rounded-lg shadow-md p-6 max-w-4xl mx-auto">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Tambah Data Sanksi</h1>
                <a href="{{ route('sanksi') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>

            <!-- Session Messages -->
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('sanksi.store') }}" method="POST" id="sanksiForm">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- User Selection -->
<div class="md:col-span-2">
    <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">Pilih User *</label>
    <select name="user_id" id="user_id" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        <option value="">-- Pilih User --</option>
        @foreach($users as $user)
            @php
                // Cek kedua kemungkinan ID
                $userId = $user->id_user ?? $user->id ?? null;
            @endphp
            @if($userId)
                <option value="{{ $userId }}" {{ old('user_id') == $userId ? 'selected' : '' }}>
                    {{ $user->username }} 
                    @if($user->email) - {{ $user->email }} @endif
                    @if($user->name) - {{ $user->name }} @endif
                    (ID: {{ $userId }})
                </option>
            @endif
        @endforeach
    </select>
    @error('user_id')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>
                    <!-- Jenis Sanksi -->
                    <div>
                        <label for="jenis" class="block text-sm font-medium text-gray-700 mb-2">Jenis Sanksi *</label>
                        <select name="jenis" id="jenis" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">-- Pilih Jenis Sanksi --</option>
                            <option value="Ringan" {{ old('jenis') == 'Ringan' ? 'selected' : '' }}>Ringan</option>
                            <option value="Sedang" {{ old('jenis') == 'Sedang' ? 'selected' : '' }}>Sedang</option>
                            <option value="Berat" {{ old('jenis') == 'Berat' ? 'selected' : '' }}>Berat</option>
                            <option value="Lainnya" {{ old('jenis') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('jenis')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal -->
                    <div>
                        <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Sanksi *</label>
                        <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('tanggal')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Hukuman -->
                    <div class="md:col-span-2">
                        <label for="hukuman" class="block text-sm font-medium text-gray-700 mb-2">Hukuman *</label>
                        <input type="text" name="hukuman" id="hukuman" value="{{ old('hukuman') }}" required placeholder="Contoh: Membersihkan kamar mandi, Tugas tambahan, dll." class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('hukuman')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Deskripsi -->
                    <div class="md:col-span-2">
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Pelanggaran *</label>
                        <textarea name="deskripsi" id="deskripsi" rows="4" required placeholder="Jelaskan detail pelanggaran yang dilakukan..." class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                        <div class="flex space-x-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="status" value="Aktif" {{ old('status', 'Aktif') == 'Aktif' ? 'checked' : '' }} required class="text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-gray-700">Aktif</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="status" value="Selesai" {{ old('status') == 'Selesai' ? 'checked' : '' }} class="text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-gray-700">Selesai</span>
                            </label>
                        </div>
                        @error('status')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('sanksi') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition duration-200">
                        Batal
                    </a>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg flex items-center transition duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>

<script>
    // Set max date to today
    document.getElementById('tanggal').max = new Date().toISOString().split('T')[0];

    // Client-side validation
    document.getElementById('sanksiForm').addEventListener('submit', function(e) {
        const userId = document.getElementById('user_id');
        const jenis = document.getElementById('jenis');
        const tanggal = document.getElementById('tanggal');
        const hukuman = document.getElementById('hukuman');
        const deskripsi = document.getElementById('deskripsi');
        
        let isValid = true;

        // Reset previous error styles
        [userId, jenis, tanggal, hukuman, deskripsi].forEach(field => {
            field.classList.remove('border-red-500');
        });

        // Validate each field
        if (!userId.value) {
            userId.classList.add('border-red-500');
            isValid = false;
        }

        if (!jenis.value) {
            jenis.classList.add('border-red-500');
            isValid = false;
        }

        if (!tanggal.value) {
            tanggal.classList.add('border-red-500');
            isValid = false;
        }

        if (!hukuman.value.trim()) {
            hukuman.classList.add('border-red-500');
            isValid = false;
        }

        if (!deskripsi.value.trim()) {
            deskripsi.classList.add('border-red-500');
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
            alert('Harap lengkapi semua field yang wajib diisi.');
        }
    });
</script>
@endsection