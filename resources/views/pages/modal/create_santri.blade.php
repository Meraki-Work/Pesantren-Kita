<div x-data="{ openModal: false }" x-cloak>
    <!-- Tombol Buka Modal -->
    <button 
        @click="openModal = true" 
        class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
        + Tambah Santri
    </button>

    <!-- Modal -->
    <div 
        x-show="openModal"
        class="fixed inset-0 flex items-center justify-center backdrop-blur-sm bg-black/40 z-50"
        x-transition
    >
        <div 
            @click.away="openModal = false"
            class="bg-white rounded-xl shadow-2xl w-full max-w-3xl p-6 transform transition-all scale-95 hover:scale-100 duration-200"
        >
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Tambah Santri Baru</h3>

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
            <form action="{{ route('santri.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block font-medium">Nama Santri</label>
                    <input type="text" name="nama" required
                        class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-green-400 focus:outline-none">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-medium">NISN</label>
                        <input type="text" name="nisn"
                            class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-green-400 focus:outline-none">
                    </div>
                    <div>
                        <label class="block font-medium">NIK</label>
                        <input type="text" name="nik"
                            class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-green-400 focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-medium">Kelas</label>
                        <select name="id_kelas" class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-green-400 focus:outline-none">
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k->id_kelas }}">{{ $k->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-medium">Tahun Masuk</label>
                        <select name="tahun_masuk" class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-green-400 focus:outline-none">
                            <option value="">-- Pilih Tahun --</option>
                            @for ($i = 2010; $i <= now()->year; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-medium">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-green-400 focus:outline-none">
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-medium">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir"
                            class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-green-400 focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-medium">Nama Ayah</label>
                        <input type="text" name="nama_ayah"
                            class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-green-400 focus:outline-none">
                    </div>
                    <div>
                        <label class="block font-medium">Nama Ibu</label>
                        <input type="text" name="nama_ibu"
                            class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-green-400 focus:outline-none">
                    </div>
                </div>

                <div class="flex justify-end gap-2 mt-6">
                    <button 
                        type="button" 
                        @click="openModal = false" 
                        class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 transition">
                        Batal
                    </button>
                    <button 
                        type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
    
</div>
