@extends('index')

@section('title', 'Dashboard')

@section('content')

<div class="flex bg-gray-100">
    <x-sidemenu title="PesantrenKita" class="h-full min-h-screen" />

    <main class="flex-1 p-4 overflow-y-auto">
        <div class="p-6 bg-gray-50 rounded-xl shadow-sm">
    <h2 class="text-2xl font-semibold mb-6 text-gray-800">Notulensi Rapat</h2>

    <form action="#" method="POST" class="grid grid-cols-2 gap-6">
        {{-- Kolom Kiri --}}
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Agenda Rapat</label>
                <input type="text" name="agenda" class="w-full border border-green-300 rounded-md p-2 focus:outline-none focus:ring-1 focus:ring-green-400" placeholder="Rapat Paripurna Internal Pondok Pesantren">
            </div>

            <div class="flex space-x-4">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Hari/Tanggal</label>
                    <input type="date" name="tanggal" class="w-full border border-green-300 rounded-md p-2 focus:outline-none focus:ring-1 focus:ring-green-400">
                </div>
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Waktu</label>
                    <input type="text" name="waktu" class="w-full border border-green-300 rounded-md p-2 focus:outline-none focus:ring-1 focus:ring-green-400" placeholder="00:00 - 00:00">
                </div>
            </div>

            <div class="flex space-x-4">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tempat</label>
                    <input type="text" name="tempat" class="w-full border border-green-300 rounded-md p-2 focus:outline-none focus:ring-1 focus:ring-green-400" placeholder="Tambahkan Tempat">
                </div>
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pimpinan</label>
                    <input type="text" name="pimpinan" class="w-full border border-green-300 rounded-md p-2 focus:outline-none focus:ring-1 focus:ring-green-400" placeholder="Tambahkan Pimpinan">
                </div>
            </div>

            {{-- Peserta --}}
            <div id="peserta-container" class="space-y-2">
                <div class="flex space-x-2 items-center">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Peserta</label>
                        <input type="text" name="peserta[]" class="w-full border border-green-300 rounded-md p-2 focus:outline-none focus:ring-1 focus:ring-green-400" placeholder="Tambahkan Peserta">
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sebagai</label>
                        <input type="text" name="sebagai[]" class="w-full border border-green-300 rounded-md p-2 focus:outline-none focus:ring-1 focus:ring-green-400" placeholder="Tambahkan Tempat">
                    </div>
                    <button type="button" onclick="addPeserta()" class="bg-green-500 text-white rounded-full w-8 h-8 flex items-center justify-center mt-5">+</button>
                </div>

                <div class="flex space-x-2 items-center">
                    <div class="flex-1">
                        <input type="text" name="peserta[]" class="w-full border border-green-300 rounded-md p-2 focus:outline-none focus:ring-1 focus:ring-green-400" placeholder="Tambahkan Peserta">
                    </div>
                    <div class="flex-1">
                        <input type="text" name="sebagai[]" class="w-full border border-green-300 rounded-md p-2 focus:outline-none focus:ring-1 focus:ring-green-400" placeholder="Tambahkan Tempat">
                    </div>
                    <button type="button" onclick="removeElement(this)" class="bg-green-500 text-white rounded-full w-8 h-8 flex items-center justify-center">−</button>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                <textarea name="keterangan" rows="4" class="w-full border border-green-300 rounded-md p-2 focus:outline-none focus:ring-1 focus:ring-green-400" placeholder="Tuliskan keterangan rapat..."></textarea>
            </div>
        </div>

        {{-- Kolom Kanan --}}
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jalannya Rapat</label>
                <textarea name="jalannya_rapat" rows="6" class="w-full border border-green-300 rounded-md p-2 focus:outline-none focus:ring-1 focus:ring-green-400" placeholder="Tuliskan jalannya rapat..."></textarea>
            </div>

            {{-- Poin-Poin Rapat --}}
            <div id="poin-container" class="space-y-2">
                <div class="flex space-x-2 items-center">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Poin Poin Rapat</label>
                        <input type="text" name="poin[]" class="w-full border border-green-300 rounded-md p-2 focus:outline-none focus:ring-1 focus:ring-green-400" placeholder="Tambah Poin">
                    </div>
                    <button type="button" onclick="addPoin()" class="bg-green-500 text-white rounded-full w-8 h-8 flex items-center justify-center mt-5">+</button>
                </div>

                <div class="flex space-x-2 items-center">
                    <input type="text" name="poin[]" class="flex-1 border border-green-300 rounded-md p-2 focus:outline-none focus:ring-1 focus:ring-green-400" placeholder="Tambah Poin">
                    <button type="button" onclick="removeElement(this)" class="bg-green-500 text-white rounded-full w-8 h-8 flex items-center justify-center">−</button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    function addPeserta() {
        const container = document.getElementById('peserta-container');
        const div = document.createElement('div');
        div.classList.add('flex', 'space-x-2', 'items-center');
        div.innerHTML = `
            <div class="flex-1">
                <input type="text" name="peserta[]" class="w-full border border-green-300 rounded-md p-2 focus:outline-none focus:ring-1 focus:ring-green-400" placeholder="Tambahkan Peserta">
            </div>
            <div class="flex-1">
                <input type="text" name="sebagai[]" class="w-full border border-green-300 rounded-md p-2 focus:outline-none focus:ring-1 focus:ring-green-400" placeholder="Tambahkan Tempat">
            </div>
            <button type="button" onclick="removeElement(this)" class="bg-green-500 text-white rounded-full w-8 h-8 flex items-center justify-center">−</button>
        `;
        container.appendChild(div);
    }

    function addPoin() {
        const container = document.getElementById('poin-container');
        const div = document.createElement('div');
        div.classList.add('flex', 'space-x-2', 'items-center');
        div.innerHTML = `
            <input type="text" name="poin[]" class="flex-1 border border-green-300 rounded-md p-2 focus:outline-none focus:ring-1 focus:ring-green-400" placeholder="Tambah Poin">
            <button type="button" onclick="removeElement(this)" class="bg-green-500 text-white rounded-full w-8 h-8 flex items-center justify-center">−</button>
        `;
        container.appendChild(div);
    }

    function removeElement(btn) {
        btn.parentElement.remove();
    }
</script>
    </main>
            </div>
@endsection