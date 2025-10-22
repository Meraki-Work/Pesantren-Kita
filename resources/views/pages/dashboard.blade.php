@extends('index')

@section('title', 'Dashboard')

@section('content')

<div class="flex bg-gray-100">
    <x-sidemenu title="PesantrenKita" class="h-full min-h-screen" />

    <main class="flex-1 p-4 overflow-y-auto">
        <div class="flex items-center space-x-4 mb-4">
            <img src="assets/img/orang.jpg" class="ml-4 w-16 h-16 rounded-full object-cover border-1 border-white" />
            <div>
                <h2 class="text-lg font-semibold text-gray-800">AL-AmalBatam</h2>
            </div>
        </div>

        <div class="grid grid-cols-4 grid-rows-7 gap-4 text-gray-900 p-4 rounded-lg ">
            <div class="box-bg col-span-3 row-span-3 col-start-1 bg-white p-6 rounded-lg relative overflow-hidden" style="background-size: cover; background-position: center;">
                <p class="font-normal text-1 text-gray-800">Pukul</p>
                <p class="text-m font-semibold text-4xl ml-1 mt-1">06 : 42 AM</p>
                <div class="max-w-xs break-words">
                    <h2 class="text-2xl font-normal mt-7 text-gray-800">
                        {{ $user->name ?? 'Selamat Pagi Pak Ustad Arya Agung Setiawan' }}
                    </h2>
                </div>

                <div class="flex space-x-4 mt-9">
                    <button
                        class="px-4 py-2 bg-green-500 text-white rounded shadow-xl hover:bg-green-700">Absen</button>
                    <button class="px-7 py-2 bg-blue-400 text-white rounded shadow-xl hover:bg-blue-600">Izin</button>
                    <button
                        class="px-7 py-2 bg-yellow-500 text-white rounded shadow-xl hover:bg-yellow-600">Cuti</button>
                </div>
                <div class="absolute top-3   right-6 bg-opacity-80 p-4 rounded-lg">
                    <div class="px-4 flex items-center justify-between">
                        <span tabindex="0"
                            class="focus:outline-none  text-base font-bold dark:text-gray-100 text-gray-800">October
                            2020</span>
                        <div class="flex items-center">
                            <button aria-label="calendar backward"
                                class="focus:text-gray-400 hover:text-gray-400 text-gray-800 dark:text-gray-100">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="icon icon-tabler icon-tabler-chevron-left" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <polyline points="15 6 9 12 15 18" />
                                </svg>
                            </button>
                            <button aria-label="calendar forward"
                                class="focus:text-gray-400 hover:text-gray-400 ml-3 text-gray-800 dark:text-gray-100">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="icon icon-tabler  icon-tabler-chevron-right" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <polyline points="9 6 15 12 9 18" />
                                </svg>
                            </button>

                        </div>
                    </div>
                    <div class="flex items-center justify-between pt-4 overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr>
                                    <th>
                                        <div class="w-full flex justify-center">
                                            <p
                                                class="text-base font-medium text-center text-gray-800 dark:text-gray-100">
                                                Mo</p>
                                        </div>
                                    </th>
                                    <th>
                                        <div class="w-full flex justify-center">
                                            <p
                                                class="text-base font-medium text-center text-gray-800 dark:text-gray-100">
                                                Tu</p>
                                        </div>
                                    </th>
                                    <th>
                                        <div class="w-full flex justify-center">
                                            <p
                                                class="text-base font-medium text-center text-gray-800 dark:text-gray-100">
                                                We</p>
                                        </div>
                                    </th>
                                    <th>
                                        <div class="w-full flex justify-center">
                                            <p
                                                class="text-base font-medium text-center text-gray-800 dark:text-gray-100">
                                                Th</p>
                                        </div>
                                    </th>
                                    <th>
                                        <div class="w-full flex justify-center">
                                            <p
                                                class="text-base font-medium text-center text-gray-800 dark:text-gray-100">
                                                Fr</p>
                                        </div>
                                    </th>
                                    <th>
                                        <div class="w-full flex justify-center">
                                            <p
                                                class="text-base font-medium text-center text-gray-800 dark:text-gray-100">
                                                Sa</p>
                                        </div>
                                    </th>
                                    <th>
                                        <div class="w-full flex justify-center">
                                            <p
                                                class="text-base font-medium text-center text-gray-800 dark:text-gray-100">
                                                Su</p>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="pt-6">
                                        <div class="px-2 py-2 cursor-pointer flex w-full justify-center"></div>
                                    </td>
                                    <td class="pt-6">
                                        <div class="px-2 py-2 cursor-pointer flex w-full justify-center"></div>
                                    </td>
                                    <td>
                                        <div class="px-2 py-2 cursor-pointer flex w-full justify-center"></div>
                                    </td>
                                    <td class="pt-6">
                                        <div class="px-2 py-2 cursor-pointer flex w-full justify-center">
                                            <p class="text-base text-gray-500 dark:text-gray-100 font-medium">1
                                            </p>
                                        </div>
                                    </td>
                                    <td class="pt-6">
                                        <div class="px-2 py-2 cursor-pointer flex w-full justify-center">
                                            <p class="text-base text-gray-500 dark:text-gray-100 font-medium">2
                                            </p>
                                        </div>
                                    </td>
                                    <td class="pt-6">
                                        <div class="px-2 py-2 cursor-pointer flex w-full justify-center">
                                            <p class="text-base text-gray-500 dark:text-gray-100">3</p>
                                        </div>
                                    </td>
                                    <td class="pt-6">
                                        <div class="px-2 py-2 cursor-pointer flex w-full justify-center">
                                            <p class="text-base text-gray-500 dark:text-gray-100">4</p>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="px-2 py-2 cursor-pointer flex w-full justify-center">
                                            <p class="text-base text-gray-500 dark:text-gray-100 font-medium">5
                                            </p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="px-2 py-2 cursor-pointer flex w-full justify-center">
                                            <p class="text-base text-gray-500 dark:text-gray-100 font-medium">6
                                            </p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="px-2 py-2 cursor-pointer flex w-full justify-center">
                                            <p class="text-base text-gray-500 dark:text-gray-100 font-medium">7
                                            </p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="w-full h-full">
                                            <div
                                                class="flex items-center justify-center w-full rounded-full cursor-pointer">
                                                <a role="link" tabindex="0"
                                                    class="focus:outline-none  focus:ring-2 focus:ring-offset-2 focus:ring-indigo-700 focus:bg-indigo-500 hover:bg-indigo-500 text-base w-8 h-8 flex items-center justify-center font-medium text-white bg-indigo-700 rounded-full">8</a>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="px-2 py-2 cursor-pointer flex w-full justify-center">
                                            <p class="text-base text-gray-500 dark:text-gray-100 font-medium">9
                                            </p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="px-2 py-2 cursor-pointer flex w-full justify-center">
                                            <p class="text-base text-gray-500 dark:text-gray-100">10</p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="px-2 py-2 cursor-pointer flex w-full justify-center">
                                            <p class="text-base text-gray-500 dark:text-gray-100">11</p>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="px-2 py-2 cursor-pointer flex w-full justify-center">
                                            <p class="text-base text-gray-500 dark:text-gray-100 font-medium">
                                                12</p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="px-2 py-2 cursor-pointer flex w-full justify-center">
                                            <p class="text-base text-gray-500 dark:text-gray-100 font-medium">
                                                13</p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="px-2 py-2 cursor-pointer flex w-full justify-center">
                                            <p class="text-base text-gray-500 dark:text-gray-100 font-medium">
                                                14</p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="px-2 py-2 cursor-pointer flex w-full justify-center">
                                            <p class="text-base text-gray-500 dark:text-gray-100 font-medium">
                                                15</p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="px-2 py-2 cursor-pointer flex w-full justify-center">
                                            <p class="text-base text-gray-500 dark:text-gray-100 font-medium">
                                                16</p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="px-2 py-2 cursor-pointer flex w-full justify-center">
                                            <p class="text-base text-gray-500 dark:text-gray-100">17</p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="px-2 py-2 cursor-pointer flex w-full justify-center">
                                            <p class="text-base text-gray-500 dark:text-gray-100">18</p>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="px-2 py-2 cursor-pointer flex w-full justify-center">
                                            <p class="text-base text-gray-500 dark:text-gray-100 font-medium">
                                                19</p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="px-2 py-2 cursor-pointer flex w-full justify-center">
                                            <p class="text-base text-gray-500 dark:text-gray-100 font-medium">
                                                20</p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="px-2 py-2 cursor-pointer flex w-full justify-center">
                                            <p class="text-base text-gray-500 dark:text-gray-100 font-medium">
                                                21</p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="px-2 py-2 cursor-pointer flex w-full justify-center">
                                            <p class="text-base text-gray-500 dark:text-gray-100 font-medium">
                                                22</p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="px-2 py-2 cursor-pointer flex w-full justify-center">
                                            <p class="text-base text-gray-500 dark:text-gray-100 font-medium">
                                                23</p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="px-2 py-2 cursor-pointer flex w-full justify-center">
                                            <p class="text-base text-gray-500 dark:text-gray-100">24</p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="px-2 py-2 cursor-pointer flex w-full justify-center">
                                            <p class="text-base text-gray-500 dark:text-gray-100">25</p>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="px-2 py-2 cursor-pointer flex w-full justify-center">
                                            <p class="text-base text-gray-500 dark:text-gray-100 font-medium">
                                                26</p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="px-2 py-2 cursor-pointer flex w-full justify-center">
                                            <p class="text-base text-gray-500 dark:text-gray-100 font-medium">
                                                27</p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="px-2 py-2 cursor-pointer flex w-full justify-center">
                                            <p class="text-base text-gray-500 dark:text-gray-100 font-medium">
                                                28</p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="px-2 py-2 cursor-pointer flex w-full justify-center">
                                            <p class="text-base text-gray-500 dark:text-gray-100 font-medium">
                                                29</p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="px-2 py-2 cursor-pointer flex w-full justify-center">
                                            <p class="text-base text-gray-500 dark:text-gray-100 font-medium">
                                                30</p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>




            </div>
            <div class="box-bg row-span-4 col-start-4 bg-white p-4 rounded-lg">
                <p class="text-lg font-semibold text-gray-800 mb-4">Logs Absen</p>

                <div class="flex items-center space-x-4">

                    <img src="{{ asset('assets/img/orang.jpg    ') }}"
                        class="w-12 h-12 rounded-full object-cover border-2 border-green-500" />


                    <div>
                        <p class="text-sm font-medium text-gray-700">Masuk Today</p>
                        <p class="text-xs text-gray-500">Absen at <span class="text-green-600 font-semibold">06:42
                                AM</span></p>
                    </div>
                </div>
            </div>

            <div class="box-bg col-start-1 row-start-4 bg-white p-2 rounded flex items-center">
                <div class="w-2 h-full bg-green-500 rounded-xl"></div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Jumlah Keseluruhan Absen</p>
                    <p class="text-2xl font-bold text-black-600">367 Hari</p>
                </div>
            </div>
            <div class="box-bg col-start-2 row-start-4 bg-white p-2 rounded flex items-center">
                <div class="w-2 h-full bg-green-500 rounded-xl"></div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Jumlah keseluruhan terlambat</p>
                    <p class="text-2xl font-bold text-black-600">289 Hari</p>
                </div>
            </div>
            <div class="box-bg col-start-3 row-start-4 bg-white p-2 rounded flex items-center">
                <div class="w-2 h-full bg-green-500 rounded-xl"></div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Jumlah keseluruhan Cuti</p>
                    <p class="text-2xl font-bold text-black-500">42 Hari</p>
                </div>
            </div>

            <div class="box-bg col-span-2 row-span-15 col-start-1 row-start-5 bg-white p-6 rounded">
                <div class="flex justify-between items-center mb-4">
                    <p class="text-3xl font-semibold text-gray-800">Grafik Prestasi</p>
                    <div>
                        <select
                            class="border border-gray-300 rounded px-3 py-1 text-xl">
                            <option>Kelas 4D</option>
                            <option>Kelas 5A</option>
                            <option>Kelas 6C</option>
                        </select>
                    </div>
                </div>

                <div class="flex-1 flex justify-center items-center">

                    <div id="grafik-prestasi" class="p-8 max-w-[350px] max-h-[350px]"></div>


                    <div>
                        <ul class="space-y-5 text-sm">
                            <li class="flex items-center space-x-2">
                                <span class="w-3 h-3 rounded-full bg-orange-400"></span>
                                <span>Tilawah</span>
                            </li>
                            <li class="flex items-center space-x-2">
                                <span class="w-3 h-3 rounded-full bg-blue-600"></span>
                                <span>Hafalan</span>
                            </li>
                            <li class="flex items-center space-x-2">
                                <span class="w-3 h-3 rounded-full bg-orange-200"></span>
                                <span>Fikih</span>
                            </li>
                            <li class="flex items-center space-x-2">
                                <span class="w-3 h-3 rounded-full bg-teal-200"></span>
                                <span>Belum Bisa</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                const ctx = document.getElementById('grafik-prestasi').appendChild(document.createElement('canvas'));

                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Tilawah', 'Hafalan', 'Fikih', 'Belum Bisa'],
                        datasets: [{
                            data: [8, 12, 11, 2],
                            backgroundColor: ['#fb923c', '#2563eb', '#fed7aa', '#99f6e4'],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        cutout: '70%',
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                enabled: true
                            }
                        }
                    }
                });
            </script>


            <div class="box-bg col-span-2 row-span-15 col-start-3 row-start-5 bg-white p-8 rounded">
                <p class="text-2xl font-bold text-gray-800 mb-4 mt-2 ml-4">Hafalan Terbaik</p>
                <div class="grid grid-cols-2 gap-4 mb-2 ml-4 mt-1">
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
                    </div>
                    <p class="text-2xl font-bold text-gray-800 mb-4 mt-2 ml-1">Tilawah Terbaik</p>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-6 ml-4 mt-1">
                    <div class="bg-gray-100 p-4 rounded-lg shadow break-words">
                        <p class="text-base font-semibold text-stone-600">Casandra Melan Asyifa</p>
                        <p class="text-sm text-gray-500">0093847256</p>
                    </div>
                    <div class="bg-gray-100 p-4 rounded-lg shadow break-words">
                        <p class="text-base font-semibold text-stone-600">Casandra Melan Asyifa</p>
                        <p class="text-sm text-gray-500">0093847256</p>
                    </div>
                    <div class="bg-gray-100 p-4 rounded-lg shadow break-words">
                        <p class="text-base font-semibold text-stone-600">Casandra Melan Asyifa</p>
                        <p class="text-sm text-gray-500">0093847256</p>
                    </div>
                    <div class="bg-gray-100 p-4 rounded-lg shadow break-words">
                        <p class="text-base font-semibold text-stone-600">Casandra Melan Asyifa</p>
                        <p class="text-sm text-gray-500">0093847256</p>
                    </div>
                </div>

                {{ $slot ?? '' }}
    </main>
</div>
@endsection