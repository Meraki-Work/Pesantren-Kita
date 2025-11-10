{{-- resources/views/pages/kelas/create.blade.php --}}
<div x-data="{ openModal: false }" x-cloak>
    <!-- Tombol Buka Modal -->
    <button 
        @click="openModal = true" 
        class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
        Tambah Kelas
    </button>

    <!-- Modal -->
    <div 
        x-show="openModal"
        class="fixed inset-0 flex items-center justify-center backdrop-blur-sm bg-black/40 z-50"
        x-transition
    >
        <div 
            @click.away="openModal = false"
            class="bg-white rounded-xl shadow-2xl w-full max-w-md p-6 transform transition-all scale-95 hover:scale-100 duration-200"
        >
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Tambah Kelas Baru</h3>

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
            <form action="{{ route('kelas.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="block font-medium">Nama Kelas</label>
                    <input 
                        type="text" 
                        name="nama_kelas" 
                        class="border border-gray-300 w-full rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" 
                        placeholder="Contoh: Kelas Wustha A" 
                        required>
                </div>

                <div class="mb-3">
                    <label class="block font-medium">Tingkat</label>
                    <select 
                        name="tingkat" 
                        class="border border-gray-300 w-full rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" 
                        required>
                        <option value="">-- Pilih Tingkat --</option>
                        <option value="Ula">Ula</option>
                        <option value="Wustha">Wustha</option>
                        <option value="Ulya">Ulya</option>
                    </select>
                </div>

                <div class="flex justify-end gap-2 mt-4">
                    <button 
                        type="button" 
                        @click="openModal = false" 
                        class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition">
                        Batal
                    </button>
                    <button 
                        type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


