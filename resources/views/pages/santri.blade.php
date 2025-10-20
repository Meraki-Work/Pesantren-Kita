@extends('index')

@section('title', 'santri')

@section('content')


<div class="flex bg-gray-100 min-h-screen">
<body class="flex bg-gray-100 min-h-screen">
    <x-sidemenu title="PesantrenKita" />

    <main class="flex-1 p-4 h-full">
        <div class="w-full h-full grid grid-cols-8 grid-rows-6 gap-2">


            <div class="box-bg col-span-6 row-span-2 w-full h-55">
                <p class="text-m font-semibold text-2 ml-3 mt-3 text-2xl text-gray-800">Aksi Cepat</p>
                <div class="grid grid-cols-3 gap-4">
                    <div
                        class="bg-[#A8E6CF] rounded-2xl mt-4 ml-4 h-36 w-full p-5 flex justify-between items-start cursor-pointer hover:bg-green-300 transition">

                        <div>

                            <div>
                                <img src="{{ asset('assets/img/Frame.png') }}" alt="Laundry Icon"
                                    class="w-13 h-13 mb-2 mt-0.5">
                            </div>

                            <h2 class="text-lg font-bold text-gray-900">Kelas 4 D</h2>
                            <p class="text-sm text-gray-700">14 Santri | 4 Kompetensi</p>
                        </div>


                        <div class="flex items-end">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" class="w-6 h-6 text-gray-700 mt-10">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>

                    <div
                        class="bg-[#2ECC71] rounded-2xl mt-4 ml-4 h-36 w-full p-5 flex flex-col justify-center hover:bg-green-600 transition cursor-pointer">

                        <div>
                            <img src="{{ asset('assets/img/laundry.png') }}" alt="Laundry Icon"
                                class="w-16 h-16 mb-2 mt-0.5">
                        </div>


                        <h2 class="text-lg font-semibold text-gray-900">Laundry Santri</h2>
                    </div>
                    <div
                        class="bg-[#344E41] rounded-3xl mt-4 ml-4 h-36 w-70 flex flex-col p-4 hover:bg-[#2f423c] transition cursor-pointer">

                        <img src="{{ asset('assets/img/kompetensi.png') }}" alt="Kompetensi Icon"
                            class="w-14 h-14 mb-2 mt-3">


                        <h2 class="text-lg font-semibold text-white">Kompetensi Santri</h2>
                    </div>

                </div>

            </div>
            <div class="box-bg col-span-2 col-start-1 row-start-3 w-full h-25 -mt-4 flex items-center">
                <div class="flex items-center justify-between w-[400px]">

                    <div class="flex flex-col ml-5">
                        <p class="text-base font-medium text-gray-700 leading-tight">Jumlah Keseluruhan</p>
                        <p class="text-base font-medium text-gray-700 leading-tight">Rapat 2024</p>
                    </div>


                    <div class="flex items-center mr-10 space-x-2">
                        <div class="mr-11 w-3 h-16 bg-[#2ECC71] rounded-full"></div>
                        <span class="text-4xl font-semibold text-gray-800">4</span>
                    </div>
                </div>
            </div>
            <div class="box-bg col-span-2 col-start-3 row-start-3 w-full h-25 -mt-4 flex items-center">
                <div class="flex items-center justify-between w-[400px]">

                    <div class="flex flex-col ml-5">
                        <p class="text-base font-medium text-gray-700 leading-tight">Jumlah Keseluruhan</p>
                        <p class="text-base font-medium text-gray-700 leading-tight">Rapat 2024</p>
                    </div>


                    <div class="flex items-center mr-10 space-x-2">
                        <div class="mr-11 w-3 h-16 bg-[#2ECC71] rounded-full"></div>
                        <span class="text-4xl font-semibold text-gray-800">4</span>
                    </div>
                </div>
            </div>
            <div class="box-bg col-span-2 col-start-5 row-start-3 w-full h-25 -mt-4 flex items-center">

                <button
                    class="flex items-center justify-center w-16 h-16 bg-[#2ECC71] rounded-lg hover:bg-green-700 transition ml-4">
                    <span class="text-white text-3xl font-bold">+</span>
                </button>


                <span class="ml-4 text-gray-800 font-medium text-base">Tambahkan Santri</span>
            </div>

            <div
                class="box-bg col-span-2 row-span-5 col-start-7 row-start-1 w-full h-full p-4 bg-white rounded-xl flex flex-col justify-start">

                <div class="mb-4">
                    <div class="flex items-center space-x-3 mb-2">
                        <img src="{{ asset('assets/img/Frame.png') }}" alt="Group Icon" class="w-13 h-13  ">

                    </div>
                    <h2 class="text-lg font-bold text-black">Kelas 4 D</h2>
                    <p class="text-sm text-gray-600">14 Santri | 4 Kompetensi</p>
                </div>


                <div class="mb-4">
                    <h3 class="text-sm font-semibold text-black mb-2">Kompetensi</h3>
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="px-3 py-1 bg-[#A8E6CF] text-[#344E41] rounded-full text-xs">Tilawah</span>
                        <span class="px-3 py-1 bg-[#A8E6CF] text-[#344E41] rounded-full text-xs">Hafalan</span>


                        <div class="flex gap-2">
                            <span class="px-3 py-1 bg-[#A8E6CF] text-[#344E41] rounded-full text-xs">Physic</span>
                            <span class="px-3 py-1 bg-[#A8E6CF] text-[#344E41] rounded-full text-xs">Chemical</span>
                        </div>
                    </div>




                    <button
                        class="w-full h-12 py-2 bg-[#2ECC71] text-white text-sm font-semibold rounded-lg hover:bg-green-600 transition">
                        Tambah Kompetensi Santri
                    </button>
                </div>


                <div class="mt-6">
                    <h3 class="text-sm font-semibold text-black mb-3">History</h3>
                    <ul class="space-y-4 text-sm text-gray-700">

                        <li class="flex items-start space-x-2">
                            <div class="w-4 h-3 bg-green-500 rounded-full mt-1"></div>
                            <div>
                                <p class="font-semibold text-[#1E3932]">Today</p>
                                <p><span class="font-bold text-gray-800">Marshela Nauli Umaiyah</span> — Hafalan Juz 4 -
                                    Juz 6</p>
                            </div>
                        </li>


                        <li class="flex items-start space-x-2">
                            <div class="w-3 h-3 bg-green-500 rounded-full mt-1"></div>
                            <div>
                                <p class="font-semibold text-[#1E3932]">Yesterday</p>
                                <p><span class="font-bold text-gray-800">Melan Aisyah</span> — Tilawah</p>
                            </div>
                        </li>


                        <li class="flex items-start space-x-2">
                            <div class="w-3 h-3 bg-green-500 rounded-full mt-1"></div>
                            <div>
                                <p class="font-semibold text-[#1E3932]  ">Yesterday</p>
                                <p><span class="font-bold text-gray-800">Joko Pranowo</span> — Chemical (O2NNCH2)₃</p>
                            </div>
                        </li>
                    </ul>
                </div>

            </div>

            <div class="box-bg col-span-6 row-span-3 row-start-4 w-full h-full -mt-7">
                <div class="w-full h-full bg-white rounded-xl border-grey p-4 overflow-auto">





                    <table id="tableKeuangan"
                        class="w-full h-full text-sm text-left text-gray-600 border border-gray-300">

                        <thead class="bg-mid text-white text-xs uppercase">
                            <tr>
                                <th class="px-4 py-3 bg-mid">Nama</th>
                                <th class="px-4 py-3 bg-mid">NISN</th>
                                <th class="px-4 py-3 bg-mid">Tanggal</th>
                                <th class="px-4 py-3 bg-mid">Dibayarkan</th>
                                <th class="px-4 py-3 bg-mid">Status</th>
                                <th class="px-4 py-3 text-center bg-mid">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyData">
                            <tr class="border-grey hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    Arya Agung
                                </td>
                                <td class="px-4 py-3">
                                    4342311048
                                </td>
                                <td class="px-4 py-3">
                                    25 Desember 2025
                                </td>
                                <td class="px-4 py-3">
                                    30.000
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-3 py-1 bg-green-500 text-white text-sm font-medium rounded-full">
                                        Lunas
                                    </span>
                                </td>


                                <td class="px-4 py-3 text-center text-gray-400">
                                    <button class="ml-9 circle-more flex justify-center items-center">
                                        <a href="#">
                                            <img src="{{ asset('assets/img/titik.png') }}" class="w-5 h-5">
                                        </a>
                                    </button>
                                </td>
                            </tr>
                            <tr class="border-grey hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    Arya Agung
                                </td>
                                <td class="px-4 py-3">
                                    4342311048
                                </td>
                                <td class="px-4 py-3">
                                    25 Desember 2025
                                </td>
                                <td class="px-4 py-3">
                                    30.000
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-3 py-1 bg-green-500 text-white text-sm font-medium rounded-full">
                                        Lunas
                                    </span>
                                </td>


                                <td class="px-4 py-3 text-center text-gray-400">
                                    <button class="ml-9 circle-more flex justify-center items-center">
                                        <a href="#">
                                            <img src="{{ asset('assets/img/titik.png') }}" class="w-5 h-5">
                                        </a>
                                    </button>
                                </td>
                            </tr>
                            <tr class="border-grey hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    Arya Agung
                                </td>
                                <td class="px-4 py-3">
                                    4342311048
                                </td>
                                <td class="px-4 py-3">
                                    25 Desember 2025
                                </td>
                                <td class="px-4 py-3">
                                    -
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-3 py-1 bg-red-600 text-white text-sm font-medium rounded-full">
                                        Belum
                                    </span>
                                </td>

                                <td class="px-4 py-3 text-center text-gray-400">
                                    <button class="ml-9 circle-more flex justify-center items-center">
                                        <a href="#">
                                            <img src="{{ asset('assets/img/titik.png') }}" class="w-5 h-5">
                                        </a>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection

    <main class="flex-1 p-4 h-full">
        <div class="w-full h-full grid grid-cols-8 grid-rows-6 gap-2">


            <div class="box-bg col-span-6 row-span-2 w-full h-55">
                <p class="text-m font-semibold text-2 ml-3 mt-3 text-2xl text-gray-800">Aksi Cepat</p>
                <div class="grid grid-cols-3 gap-4">
                    <div
                        class="bg-[#A8E6CF] rounded-2xl mt-4 ml-4 h-36 w-full p-5 flex justify-between items-start cursor-pointer hover:bg-green-300 transition">

                        <div>

                            <div>
                                <img src="{{ asset('assets/img/Frame.png') }}" alt="Laundry Icon"
                                    class="w-13 h-13 mb-2 mt-0.5">
                            </div>

                            <h2 class="text-lg font-bold text-gray-900">Kelas 4 D</h2>
                            <p class="text-sm text-gray-700">14 Santri | 4 Kompetensi</p>
                        </div>


                        <div class="flex items-end">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" class="w-6 h-6 text-gray-700 mt-10">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>

                    <div
                        class="bg-[#2ECC71] rounded-2xl mt-4 ml-4 h-36 w-full p-5 flex flex-col justify-center hover:bg-green-600 transition cursor-pointer">

                        <div>
                            <img src="{{ asset('assets/img/laundry.png') }}" alt="Laundry Icon"
                                class="w-16 h-16 mb-2 mt-0.5">
                        </div>


                        <h2 class="text-lg font-semibold text-gray-900">Laundry Santri</h2>
                    </div>
                    <div
                        class="bg-[#344E41] rounded-3xl mt-4 ml-4 h-36 w-70 flex flex-col p-4 hover:bg-[#2f423c] transition cursor-pointer">

                        <img src="{{ asset('assets/img/kompetensi.png') }}" alt="Kompetensi Icon"
                            class="w-14 h-14 mb-2 mt-3">


                        <h2 class="text-lg font-semibold text-white">Kompetensi Santri</h2>
                    </div>

                </div>

            </div>
            <div class="box-bg col-span-2 col-start-1 row-start-3 w-full h-25 -mt-4 flex items-center">
                <div class="flex items-center justify-between w-[400px]">

                    <div class="flex flex-col ml-5">
                        <p class="text-base font-medium text-gray-700 leading-tight">Jumlah Keseluruhan</p>
                        <p class="text-base font-medium text-gray-700 leading-tight">Rapat 2024</p>
                    </div>


                    <div class="flex items-center mr-10 space-x-2">
                        <div class="mr-11 w-3 h-16 bg-[#2ECC71] rounded-full"></div>
                        <span class="text-4xl font-semibold text-gray-800">4</span>
                    </div>
                </div>
            </div>
            <div class="box-bg col-span-2 col-start-3 row-start-3 w-full h-25 -mt-4 flex items-center">
                <div class="flex items-center justify-between w-[400px]">

                    <div class="flex flex-col ml-5">
                        <p class="text-base font-medium text-gray-700 leading-tight">Jumlah Keseluruhan</p>
                        <p class="text-base font-medium text-gray-700 leading-tight">Rapat 2024</p>
                    </div>


                    <div class="flex items-center mr-10 space-x-2">
                        <div class="mr-11 w-3 h-16 bg-[#2ECC71] rounded-full"></div>
                        <span class="text-4xl font-semibold text-gray-800">4</span>
                    </div>
                </div>
            </div>
            <div class="box-bg col-span-2 col-start-5 row-start-3 w-full h-25 -mt-4 flex items-center">

                <button
                    class="flex items-center justify-center w-16 h-16 bg-[#2ECC71] rounded-lg hover:bg-green-700 transition ml-4">
                    <span class="text-white text-3xl font-bold">+</span>
                </button>


                <span class="ml-4 text-gray-800 font-medium text-base">Tambahkan Santri</span>
            </div>

            <div
                class="box-bg col-span-2 row-span-5 col-start-7 row-start-1 w-full h-full p-4 bg-white rounded-xl flex flex-col justify-start">

                <div class="mb-4">
                    <div class="flex items-center space-x-3 mb-2">
                        <img src="{{ asset('assets/img/Frame.png') }}" alt="Group Icon" class="w-13 h-13  ">

                    </div>
                    <h2 class="text-lg font-bold text-black">Kelas 4 D</h2>
                    <p class="text-sm text-gray-600">14 Santri | 4 Kompetensi</p>
                </div>


                <div class="mb-4">
                    <h3 class="text-sm font-semibold text-black mb-2">Kompetensi</h3>
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="px-3 py-1 bg-[#A8E6CF] text-[#344E41] rounded-full text-xs">Tilawah</span>
                        <span class="px-3 py-1 bg-[#A8E6CF] text-[#344E41] rounded-full text-xs">Hafalan</span>


                        <div class="flex gap-2">
                            <span class="px-3 py-1 bg-[#A8E6CF] text-[#344E41] rounded-full text-xs">Physic</span>
                            <span class="px-3 py-1 bg-[#A8E6CF] text-[#344E41] rounded-full text-xs">Chemical</span>
                        </div>
                    </div>




                    <button
                        class="w-full h-12 py-2 bg-[#2ECC71] text-white text-sm font-semibold rounded-lg hover:bg-green-600 transition">
                        Tambah Kompetensi Santri
                    </button>
                </div>


                <div class="mt-6">
                    <h3 class="text-sm font-semibold text-black mb-3">History</h3>
                    <ul class="space-y-4 text-sm text-gray-700">

                        <li class="flex items-start space-x-2">
                            <div class="w-4 h-3 bg-green-500 rounded-full mt-1"></div>
                            <div>
                                <p class="font-semibold text-[#1E3932]">Today</p>
                                <p><span class="font-bold text-gray-800">Marshela Nauli Umaiyah</span> — Hafalan Juz 4 -
                                    Juz 6</p>
                            </div>
                        </li>


                        <li class="flex items-start space-x-2">
                            <div class="w-3 h-3 bg-green-500 rounded-full mt-1"></div>
                            <div>
                                <p class="font-semibold text-[#1E3932]">Yesterday</p>
                                <p><span class="font-bold text-gray-800">Melan Aisyah</span> — Tilawah</p>
                            </div>
                        </li>


                        <li class="flex items-start space-x-2">
                            <div class="w-3 h-3 bg-green-500 rounded-full mt-1"></div>
                            <div>
                                <p class="font-semibold text-[#1E3932]  ">Yesterday</p>
                                <p><span class="font-bold text-gray-800">Joko Pranowo</span> — Chemical (O2NNCH2)₃</p>
                            </div>
                        </li>
                    </ul>
                </div>

            </div>

            <div class="box-bg col-span-6 row-span-3 row-start-4 w-full h-full -mt-7">
                <div class="w-full h-full bg-white rounded-xl border-grey p-4 overflow-auto">





                    <table id="tableKeuangan"
                        class="w-full h-full text-sm text-left text-gray-600 border border-gray-300">

                        <thead class="bg-mid text-white text-xs uppercase">
                            <tr>
                                <th class="px-4 py-3 bg-mid">Nama</th>
                                <th class="px-4 py-3 bg-mid">NISN</th>
                                <th class="px-4 py-3 bg-mid">Tanggal</th>
                                <th class="px-4 py-3 bg-mid">Dibayarkan</th>
                                <th class="px-4 py-3 bg-mid">Status</th>
                                <th class="px-4 py-3 text-center bg-mid">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyData">
                            <tr class="border-grey hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    Arya Agung
                                </td>
                                <td class="px-4 py-3">
                                    4342311048
                                </td>
                                <td class="px-4 py-3">
                                    25 Desember 2025
                                </td>
                                <td class="px-4 py-3">
                                    30.000
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-3 py-1 bg-green-500 text-white text-sm font-medium rounded-full">
                                        Lunas
                                    </span>
                                </td>


                                <td class="px-4 py-3 text-center text-gray-400">
                                    <button class="ml-9 circle-more flex justify-center items-center">
                                        <a href="#">
                                            <img src="{{ asset('assets/img/titik.png') }}" class="w-5 h-5">
                                        </a>
                                    </button>
                                </td>
                            </tr>
                            <tr class="border-grey hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    Arya Agung
                                </td>
                                <td class="px-4 py-3">
                                    4342311048
                                </td>
                                <td class="px-4 py-3">
                                    25 Desember 2025
                                </td>
                                <td class="px-4 py-3">
                                    30.000
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-3 py-1 bg-green-500 text-white text-sm font-medium rounded-full">
                                        Lunas
                                    </span>
                                </td>


                                <td class="px-4 py-3 text-center text-gray-400">
                                    <button class="ml-9 circle-more flex justify-center items-center">
                                        <a href="#">
                                            <img src="{{ asset('assets/img/titik.png') }}" class="w-5 h-5">
                                        </a>
                                    </button>
                                </td>
                            </tr>
                            <tr class="border-grey hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    Arya Agung
                                </td>
                                <td class="px-4 py-3">
                                    4342311048
                                </td>
                                <td class="px-4 py-3">
                                    25 Desember 2025
                                </td>
                                <td class="px-4 py-3">
                                    -
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-3 py-1 bg-red-600 text-white text-sm font-medium rounded-full">
                                        Belum
                                    </span>
                                </td>

                                <td class="px-4 py-3 text-center text-gray-400">
                                    <button class="ml-9 circle-more flex justify-center items-center">
                                        <a href="#">
                                            <img src="{{ asset('assets/img/titik.png') }}" class="w-5 h-5">
                                        </a>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</body>
