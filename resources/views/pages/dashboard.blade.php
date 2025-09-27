@extends('index')

@section('title', 'Dashboard')

@section('content')
    <h1>Dashboard Page</h1>
@endsection

<body class="flex bg-gray-100">
    <x-sidemenu title="PesantrenKita" />

    <main class="flex-1 p-4">
        <div class="flex items-center space-x-4 mb-4">
            <img src="assets/img/orang.png" class="ml-4 w-16 h-16 rounded-full object-cover border-1 border-white" />
            <div>
                <h2 class="text-lg font-semibold text-gray-800">AL-AmalBatam</h2>
            </div>
        </div>

        <div class="grid grid-cols-4 grid-rows-7 gap-4 text-gray-900 p-4 rounded-lg h-full">
            <div class="box-bg col-span-3 row-span-3 col-start-1 bg-white p-6 rounded-lg relative overflow-hidden"
                style="background-image: url('{{ asset('assets/img/ji.png') }}'); background-size: cover; background-position: center;">
                <p class="font-normal text-1 text-gray-800">Pukul</p>
                <p class="text-m font-semibold text-lg ml-1 mt-1">06 : 42 AM</p>
                <div class="max-w-xs break-words">
                    <h2 class="text-lg font-normal mt-6 text-gray-800">
                        {{ $user->name ?? 'Selamat Pagi Pak Ustad Arya Agung Setiawan' }}
                    </h2>
                </div>

                <div class="flex space-x-4 mt-8">
                    <button
                        class="px-4 py-2 bg-green-500 text-white rounded shadow-xl hover:bg-green-700">Absen</button>
                    <button class="px-7 py-2 bg-blue-400 text-white rounded shadow-xl hover:bg-blue-600">Izin</button>
                    <button
                        class="px-7 py-2 bg-yellow-500 text-white rounded shadow-xl hover:bg-yellow-600">Cuti</button>
                </div>


            </div>
            <div class="border border-green-500 row-span-4 col-start-4 bg-white p-4 rounded-lg shadow-xl">
                <p class="text-lg font-semibold text-gray-800 mb-4">Logs Absen</p>

                <div class="flex items-center space-x-4">
                    <!-- Foto Profil -->
                    <img src="{{ asset('assets/img/orang.png') }}"
                        class="w-12 h-12 rounded-full object-cover border-2 border-green-500" />

                    <!-- Nama dan Waktu -->
                    <div>
                        <p class="text-sm font-medium text-gray-700">Masuk Today</p>
                        <p class="text-xs text-gray-500">Absen at <span class="text-green-600 font-semibold">06:42
                                AM</span></p>
                    </div>
                </div>
            </div>

            <div
                class="border border-green-500 col-start-1 row-start-4 bg-white p-2 rounded flex items-center shadow-xl">
                <!-- Garis hijau -->
                <div class="w-4 h-full bg-green-500 rounded-xl"></div>

                <!-- Konten teks -->
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Jumlah Keseluruhan Absen</p>
                    <p class="text-2xl font-bold text-black-600">367 Hari</p>
                </div>
            </div>
            <div
                class="border border-green-500 col-start-2 row-start-4 bg-white p-2 rounded flex items-center shadow-xl">
                <!-- Garis hijau -->
                <div class="w-4 h-full bg-green-500 rounded-xl"></div>

                <!-- Konten teks -->
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Jumlah keseluruhan terlambat</p>
                    <p class="text-2xl font-bold text-black-600">289 Hari</p>
                </div>
            </div>

            <div
                class="border border-green-500 col-start-3 row-start-4 bg-white p-2 rounded flex items-center shadow-xl">
                <!-- Garis hijau -->
                <div class="w-4 h-full bg-green-500 rounded-xl"></div>

                <!-- Konten teks -->
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Jumlah keseluruhan Cuti</p>
                    <p class="text-2xl font-bold text-black-500">42 Hari</p>
                </div>

            </div>
            <div
                class="border border-green-500 col-span-2 row-span-3 col-start-1 row-start-5 bg-white p-2 rounded shadow-xl">
                6
            </div>
            <div
                class="border border-green-500 col-span-2 row-span-3 col-start-3 row-start-5 bg-white p-2 rounded shadow-xl">
                <p class="text-2xl font-bold text-gray-800 mb-4 mt-2 ml-4">Hafalan Terbaik</p>
                <div class="grid grid-cols-2 gap-4 mb-6 ml-4 mt-1">
                    <div class="bg-gray-100 p-4 rounded-lg shadow break-words">
                        <p class="text-base font-semibold text-stone-600">Casandra Melan Asyifa</p>
                        <p class="text-sm text-gray-500">0093847256</p>
                    </div>
                    <div class="bg-gray-100 p-4 rounded-lg shadow break-words">
                        <p class="text-base font-semibold text-stone-600">Sodiqin Al Falah</p>
                        <p class="text-sm text-gray-500">0093132256</p>
                    </div>
                    <div class="bg-gray-100 p-4 rounded-lg shadow break-words">
                        <p class="text-base font-semibold text-stone-600">Ibrahim Ibnu Aristoteles</p>
                        <p class="text-sm text-gray-500">0134447256</p>
                    </div>
                    <div class="bg-gray-100 p-4 rounded-lg shadow break-words">

                        <p class="text-base font-semibold text-stone-600">Abdul Mail Prasetyo</p>
                        <p class="text-sm text-gray-500">0014247751</p>
                    </div><p class="text-2xl font-bold text-gray-800 mb-4 mt-2 ml-1">Tilawah Terbaik</p>
                </div>
            </div>

            {{ $slot ?? '' }}
    </main>
</body>
