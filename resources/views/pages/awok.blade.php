@extends('index') @section('title', 'Dashboard') @section('content') <div class="flex bg-gray-100"> <x-sidemenu
        title="PesantrenKita" class="h-full min-h-screen" />
    <main class="flex-1 p-4 overflow-y-auto">
        <div class="flex items-center space-x-4 mb-4"> <img src="assets/img/orang.jpg"
                class="ml-4 w-16 h-16 rounded-full object-cover border-1 border-white" />
            <div>
                <h2 class="text-lg font-semibold text-gray-800">AL-AmalBatam</h2>
            </div>
        </div>
        <div class="grid grid-cols-4 grid-rows-7 gap-4 text-gray-900 p-4 rounded-lg ">
            <div class="box-bg col-span-3 row-span-3 col-start-1 p-6 rounded-lg relative overflow-hidden"
                style="background-image: url('{{ asset('assets/img/ji.png') }}'); background-position: center;">
                <p class="font-normal text-1 text-gray-800">Pukul</p>
                <p id="jamSekarang" class="text-m font-semibold text-4xl ml-1 mt-1"></p>
                <div class="max-w-xs break-words">
                    <h2 class="text-2xl font-normal mt-7 text-gray-800">
                        {{ $user->name ?? 'Selamat Pagi Pak Ustad Arya Agung Setiawan' }} </h2>
                </div>
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                                const jamSekarang = document.getElementById('jamSekarang');

                                function updateClock() {
                                    const waktu = new Date();
                                    const jam = waktu.toLocaleTimeString('id-ID', {
                                        hour: '2-digit',
                                        minute: '2-digit'
                                    });
                                    jamSekarang.textContent = jam;
                                } // Jalankan pertama kali dan perbarui tiap 60 detik updateClock(); setInterval(updateClock, 60000); }); 
                </script>
                <div class="flex space-x-4 mt-9"> <button
                        class="btn-absen px-4 py-2 bg-green-500 text-white rounded shadow-xl hover:bg-green-700"
                        data-status="Hadir">Absen</button> <button
                        class="btn-absen px-7 py-2 bg-blue-400 text-white rounded shadow-xl hover:bg-blue-600"
                        data-status="Izin">Izin</button> <button
                        class="btn-absen px-7 py-2 bg-yellow-500 text-white rounded shadow-xl hover:bg-yellow-600"
                        data-status="Cuti">Cuti</button> </div>
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                                const buttons = document.querySelectorAll('.btn-absen');
                                const logContainer = document.getElementById('logAbsen');
                                buttons.forEach(btn => {
                                            btn.addEventListener('click', async () => {
                                                            const status = btn.dataset
                                                            .status; // "Hadir", "Izin", atau "Cuti" const waktuSekarang = new Date(); const jam = waktuSekarang.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }); Swal.fire({ title: Yakin ingin ${status.toLowerCase()} sekarang?, icon: 'question', showCancelButton: true, confirmButtonText: 'Ya, simpan!', cancelButtonText: 'Batal', }).then(async (result) => { if (result.isConfirmed) { try { const response = await fetch("{{ route('dashboard.store') }}", { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: JSON.stringify({ status }) }); const data = await response.json(); if (data.success) { // ✅ SweetAlert sukses Swal.fire({ icon: 'success', title: 'Berhasil!', text: ${status} berhasil disimpan pada ${jam}, showConfirmButton: false, timer: 2000 }); // Ganti tampilan log logContainer.innerHTML = <img src="{{ asset('assets/img/orang.jpg') }}" class="w-12 h-12 rounded-full object-cover border-2 border-green-500" /> <div> <p class="text-sm font-medium text-gray-700">${status} Today</p> <p class="text-xs text-gray-500">${status} at <span class="text-green-600 font-semibold">${jam}</span> </p> </div> ; } else { Swal.fire({ icon: 'error', title: 'Gagal!', text: 'Data tidak berhasil disimpan!', confirmButtonColor: '#d33' }); } } catch (error) { Swal.fire({ icon: 'error', title: 'Terjadi Kesalahan!', text: 'Tidak dapat menghubungi server.', confirmButtonColor: '#d33' }); console.error(error); } } }); }); }); }); 
                </script>
                <div class="absolute top-3 right-6 bg-opacity-90 p-4 z-14 w-77">
                    <div class="px-4 flex items-center justify-between"> <span id="monthYear"
                            class="text-base font-bold text-black"></span>
                        <div class="flex items-center space-x-2"> <button id="prevMonth"
                                class="text-black hover:text-gray-700"> <svg xmlns="http://www.w3.org/2000/svg"
                                    width="20" height="20" fill="none" stroke="currentColor"
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                                    class="icon icon-tabler icon-tabler-chevron-left">
                                    <polyline points="15 6 9 12 15 18" />
                                </svg> </button> <button id="nextMonth" class="text-black hover:text-gray-700"> <svg
                                    xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" class="icon icon-tabler icon-tabler-chevron-right">
                                    <polyline points="9 6 15 12 9 18" />
                                </svg> </button> </div>
                    </div>
                    <div class="pt-4">
                        <table class="w-full text-center text-black">
                            <thead>
                                <tr>
                                    <th>Mo</th>
                                    <th>Tu</th>
                                    <th>We</th>
                                    <th>Th</th>
                                    <th>Fr</th>
                                    <th>Sa</th>
                                    <th>Su</th>
                                </tr>
                            </thead>
                            <tbody id="calendarBody"></tbody>
                        </table>
                    </div>
                </div>
                <script>
                    const monthYear = document.getElementById("monthYear");
                    const calendarBody = document.getElementById("calendarBody");
                    const prevMonthBtn = document.getElementById("prevMonth");
                    const nextMonthBtn = document.getElementById("nextMonth");
                    let date = new Date();
                    let currentMonth = date.getMonth();
                    let currentYear = date.getFullYear();
                    const months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October",
                        "November", "December"
                    ];

                    function generateCalendar(month, year) {
                        calendarBody.innerHTML = "";
                        monthYear.textContent = $ {
                            months[month]
                        }
                        $ {
                            year
                        };
                        const firstDay = new Date(year, month)
                    .getDay(); // 0=Sun const daysInMonth = 32 - new Date(year, month, 32).getDate(); let dateNum = 1; // Adjust for Monday start const startDay = (firstDay === 0) ? 6 : firstDay - 1; for (let i = 0; i < 6; i++) { const row = document.createElement("tr"); for (let j = 0; j < 7; j++) { const cell = document.createElement("td"); if (i === 0 && j < startDay) { cell.textContent = ""; } else if (dateNum > daysInMonth) { break; } else { const cellText = document.createElement("div"); cellText.textContent = dateNum; cellText.classList.add("py-1", "rounded-full", "w-8", "h-8", "mx-auto", "cursor-pointer"); // Highlight today if ( dateNum === new Date().getDate() && year === new Date().getFullYear() && month === new Date().getMonth() ) { cellText.classList.add("bg-indigo-600", "text-white", "font-semibold"); } else { cellText.classList.add("hover:bg-gray-200", "text-black"); } cell.appendChild(cellText); dateNum++; } row.appendChild(cell); } calendarBody.appendChild(row); } } prevMonthBtn.addEventListener("click", () => { currentMonth--; if (currentMonth < 0) { currentMonth = 11; currentYear--; } generateCalendar(currentMonth, currentYear); }); nextMonthBtn.addEventListener("click", () => { currentMonth++; if (currentMonth > 11) { currentMonth = 0; currentYear++; } generateCalendar(currentMonth, currentYear); }); generateCalendar(currentMonth, currentYear); 
                </script>
            </div>
            <div class="box-bg row-span-4 col-start-4 bg-white p-4 rounded-lg">
                <p class="text-lg font-semibold text-gray-800 mb-4">Logs Absen</p>
                <div id="logAbsen" class="flex items-center space-x-4"> <img src="{{ asset('assets/img/orang.jpg') }}"
                        class="w-12 h-12 rounded-full object-cover border-2 border-green-500" />
                    <div>
                        <p class="text-sm font-medium text-gray-700">Today</p>
                        <p class="text-xs text-gray-500"><span class="text-green-600 font-semibold">06:42 AM</span></p>
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
                    <div> <select class="border border-gray-300 rounded px-3 py-1 text-xl">
                            <option>Kelas 4D</option>
                            <option>Kelas 5A</option>
                            <option>Kelas 6C</option>
                        </select> </div>
                </div>
                <div class="flex-1 flex justify-center items-center">
                    <div id="grafik-prestasi" class="p-8 max-w-[350px] max-h-[350px]"></div>
                    <div>
                        <ul class="space-y-5 text-sm">
                            <li class="flex items-center space-x-2"> <span
                                    class="w-3 h-3 rounded-full bg-orange-400"></span> <span>Tilawah</span> </li>
                            <li class="flex items-center space-x-2"> <span
                                    class="w-3 h-3 rounded-full bg-blue-600"></span> <span>Hafalan</span> </li>
                            <li class="flex items-center space-x-2"> <span
                                    class="w-3 h-3 rounded-full bg-orange-200"></span> <span>Fikih</span> </li>
                            <li class="flex items-center space-x-2"> <span
                                    class="w-3 h-3 rounded-full bg-teal-200"></span> <span>Belum Bisa</span> </li>
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
                </div> {{ $slot ?? '' }}
    </main>
</div> @endsection
