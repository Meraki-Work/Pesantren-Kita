@extends('index')

@section('title', 'Dashboard')

@section('content')

    <div class="flex bg-gray-100">
        <x-sidemenu title="PesantrenKita" class="h-full min-h-screen" />

        <main class="flex-1 p-4 overflow-y-auto">
            <div class="">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 px-4 py-2 pt-4">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Halaman Dashboard</h1>
                        <p class="text-gray-600 mt-1">Catat kehadiran Anda dengan cepat. Lihat riwayat absensi dan status kehadiran harian.</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-4 grid-rows-7 gap-4 text-gray-900 p-4 rounded-lg ">
                <div class="flex justify-between box-bg col-span-3 row-span-3 col-start-1 px-6 py-3 rounded-lg relative overflow-hidden"
                    style="background-image: url('{{ asset('assets/img/ji.png') }}'); background-position: center;">
                    <div>
                        <div>
                            <p class="font-normal text-xl text-gray-800">Jam :</p>
                            <p id="jamSekarang" class="text-m font-semibold text-2xl ml-1 mt-1"></p>
                            <div class="max-w-xs break-words">
                                <h2 class="text-2xl font-normal mt-7 text-gray-800">
                                    <span id="greetingText"></span>
                                    {{ Auth::user()->username }}
                                </h2>
                            </div>
                        </div>
                        <script>
                            function updateDashboardTime() {
                                const now = new Date();

                                // Jam realtime
                                let hours = now.getHours().toString().padStart(2, '0');
                                let minutes = now.getMinutes().toString().padStart(2, '0');
                                let seconds = now.getSeconds().toString().padStart(2, '0');

                                document.getElementById('jamSekarang').textContent = `${hours}:${minutes}:${seconds}`;

                                // Ucapan berdasarkan waktu
                                let greeting = "";
                                if (now.getHours() >= 4 && now.getHours() < 11) {
                                    greeting = "Selamat Pagi";
                                } else if (now.getHours() >= 11 && now.getHours() < 15) {
                                    greeting = "Selamat Siang";
                                } else if (now.getHours() >= 15 && now.getHours() < 18) {
                                    greeting = "Selamat Sore";
                                } else {
                                    greeting = "Selamat Malam";
                                }

                                document.getElementById('greetingText').textContent = greeting;
                            }

                            setInterval(updateDashboardTime, 1000);
                            updateDashboardTime();
                        </script>

                        <div class="flex space-x-4 mt-9">
                            <button class="btn-absen px-4 py-2 bg-green-500 text-white rounded shadow-xl hover:bg-green-700"
                                data-status="Hadir">Absen</button>
                            <button class="btn-absen px-7 py-2 bg-blue-400 text-white rounded shadow-xl hover:bg-blue-600"
                                data-status="Izin">Izin</button>
                            <button
                                class="btn-absen px-7 py-2 bg-yellow-500 text-white rounded shadow-xl hover:bg-yellow-600"
                                data-status="Cuti">Cuti</button>
                        </div>
                    </div>
                    <!-- pastikan SweetAlert2 sudah dimuat di layout (atau tambahkan <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>) -->

                    <script>
                        document.addEventListener('DOMContentLoaded', () => {
                            const buttons = document.querySelectorAll('.btn-absen');
                            const logContainer = document.getElementById('logAbsen');
                            const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                            // Cek status absensi hari ini
                            async function checkTodayAbsensi() {
                                try {
                                    const response = await fetch("{{ route('dashboard.absensi.check') }}");
                                    const data = await response.json();

                                    if (data.already_absened) {
                                        buttons.forEach(btn => {
                                            btn.disabled = true;
                                            btn.classList.add('opacity-50', 'cursor-not-allowed');
                                            // Hanya ubah teks untuk tombol yang sesuai dengan status
                                            if (btn.dataset.status === data.status) {
                                                btn.innerHTML = `Sudah ${data.status}`;
                                            }
                                        });
                                    }
                                } catch (error) {
                                    console.error('Gagal memeriksa absensi:', error);
                                }
                            }

                            // Fungsi: Kirim Data Absen ke Server - DIPERBAIKI
                            async function kirimDataToServer(payload, btn) {
                                if (btn) {
                                    btn.disabled = true;
                                    btn.classList.add('opacity-60', 'cursor-not-allowed');
                                }

                                try {
                                    const response = await fetch("{{ route('dashboard.absensi.store') }}", {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': CSRF_TOKEN,
                                            'Accept': 'application/json'
                                        },
                                        body: JSON.stringify(payload)
                                    });

                                    const data = await response.json();

                                    if (!response.ok) {
                                        throw new Error(data.message || `Server error: ${response.status}`);
                                    }

                                    return {
                                        ok: true,
                                        json: data
                                    };
                                } catch (err) {
                                    console.error('Fetch error:', err);
                                    Swal.fire('Terjadi Kesalahan!', err.message, 'error');
                                    return {
                                        ok: false
                                    };
                                } finally {
                                    if (btn) {
                                        btn.disabled = false;
                                        btn.classList.remove('opacity-60', 'cursor-not-allowed');
                                    }
                                }
                            }

                            // Fungsi: Ambil Log Absen dari Server
                            async function loadAllLogs() {
                                const logContainer = document.getElementById('logAbsen');

                                try {
                                    const res = await fetch("{{ route('dashboard.absensi.all') }}");
                                    const data = await res.json();

                                    logContainer.innerHTML = '';

                                    if (data.success && data.data.length > 0) {
                                        data.data.forEach(item => {

                                            // Format tanggal
                                            const tanggalFormatted = new Date(item.tanggal).toLocaleDateString(
                                            'id-ID', {
                                                day: '2-digit',
                                                month: 'long',
                                                year: 'numeric'
                                            });

                                            // Format jam
                                            const jamFormatted = item.jam ?
                                                new Date(`2000-01-01T${item.jam}`).toLocaleTimeString('id-ID', {
                                                    hour: '2-digit',
                                                    minute: '2-digit',
                                                    second: '2-digit'
                                                }) :
                                                '-';

                                            const statusColor =
                                                item.status === 'Hadir' ? 'border-green-500' :
                                                item.status === 'Izin' ? 'border-blue-500' :
                                                item.status === 'Sakit' ? 'border-yellow-500' :
                                                'border-red-500';

                                            const statusTextColor =
                                                item.status === 'Hadir' ? 'text-green-600' :
                                                item.status === 'Izin' ? 'text-blue-600' :
                                                item.status === 'Sakit' ? 'text-yellow-600' :
                                                'text-red-600';

                                            logContainer.insertAdjacentHTML('beforeend', `
                    <div class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-50 transition">
                        <div>
                            <p class="text-sm font-semibold ${statusTextColor}">${item.status}</p>
                            <p class="text-xs text-gray-500">${item.keterangan} pada
                                <span class="font-medium text-green-600">${tanggalFormatted}</span>
                                pukul <span class="font-semibold">${jamFormatted}</span>
                            </p>
                        </div>
                    </div>
                `);
                                        });
                                    } else {
                                        logContainer.innerHTML = `
                <p class="text-sm text-gray-500 text-center py-4">Belum ada data absensi.</p>
            `;
                                    }

                                } catch (error) {
                                    console.error('Gagal memuat log absen:', error);
                                    logContainer.innerHTML = `
            <p class="text-sm text-red-500 text-center py-4">Gagal memuat data absensi.</p>
        `;
                                }
                            }


                            // Event handler untuk tombol absen
                            buttons.forEach(btn => {
                                btn.addEventListener('click', async () => {
                                    const status = btn.dataset.status;
                                    const waktuSekarang = new Date();
                                    const jam = waktuSekarang.toLocaleTimeString('id-ID', {
                                        hour: '2-digit',
                                        minute: '2-digit'
                                    });

                                    // Jika Izin / Sakit butuh keterangan
                                    if (status === 'Izin' || status === 'Sakit') {
                                        const {
                                            value: keterangan
                                        } = await Swal.fire({
                                            title: `${status} - Masukkan keterangan`,
                                            html: `<input id="swal-input" class="swal2-input" placeholder="Tuliskan keterangan...">`,
                                            focusConfirm: false,
                                            showCancelButton: true,
                                            confirmButtonText: 'Simpan',
                                            cancelButtonText: 'Batal',
                                            preConfirm: () => {
                                                const v = Swal.getPopup().querySelector(
                                                    '#swal-input').value;
                                                if (!v || v.trim().length === 0) {
                                                    Swal.showValidationMessage(
                                                        'Keterangan harus diisi!');
                                                }
                                                return v.trim();
                                            }
                                        });

                                        if (!keterangan) return;

                                        const confirm = await Swal.fire({
                                            title: `Yakin ingin ${status.toLowerCase()}?`,
                                            text: keterangan,
                                            icon: 'question',
                                            showCancelButton: true,
                                            confirmButtonText: 'Ya, simpan!',
                                            cancelButtonText: 'Batal'
                                        });

                                        if (!confirm.isConfirmed) return;

                                        const res = await kirimDataToServer({
                                            status,
                                            keterangan
                                        }, btn);
                                        if (res.ok) {
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Berhasil!',
                                                text: `${status} tersimpan pada ${jam}`,
                                                showConfirmButton: false,
                                                timer: 1500
                                            });
                                            await loadAllLogs();
                                            await checkTodayAbsensi();
                                        }
                                        return;
                                    }

                                    // Untuk Hadir dan Alpa
                                    const result = await Swal.fire({
                                        title: `Yakin ingin ${status.toLowerCase()} sekarang?`,
                                        icon: 'question',
                                        showCancelButton: true,
                                        confirmButtonText: 'Ya, simpan!',
                                        cancelButtonText: 'Batal'
                                    });

                                    if (!result.isConfirmed) return;

                                    const res = await kirimDataToServer({
                                        status
                                    }, btn);
                                    if (res.ok) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Berhasil!',
                                            text: `${status} tersimpan pada ${jam}`,
                                            showConfirmButton: false,
                                            timer: 1500
                                        });
                                        await loadAllLogs();
                                        await checkTodayAbsensi();
                                    }
                                });
                            });

                            // Jalankan saat halaman dimuat
                            checkTodayAbsensi();
                            loadAllLogs();
                        });
                    </script>
                    <script>
                        document.addEventListener('DOMContentLoaded', async () => {
                            const logContainer = document.getElementById('logAbsen');

                            async function loadAllLogs() {
                                try {
                                    const res = await fetch("{{ route('dashboard.absensi.all') }}");
                                    const data = await res.json();

                                    if (data.success && data.data.length > 0) {
                                        logContainer.innerHTML = ''; // Kosongkan isi lama

                                        data.data.forEach(item => {
                                            // Format tanggal ke Indonesia
                                            const tanggalFormatted = new Date(item.tanggal).toLocaleDateString(
                                                'id-ID', {
                                                    day: '2-digit',
                                                    month: 'long',
                                                    year: 'numeric'
                                                });

                                            // Format jam (ambil dari DB)
                                            const jamFormatted = item.jam ?
                                                new Date(`2000-01-01T${item.jam}`).toLocaleTimeString('id-ID', {
                                                    hour: '2-digit',
                                                    minute: '2-digit',
                                                    second: '2-digit'
                                                }) :
                                                "-";

                                            // Warna status
                                            const statusColor =
                                                item.status === 'Hadir' ? 'border-green-500' :
                                                item.status === 'Cuti' ? 'border-yellow-400' :
                                                'border-blue-400';

                                            const statusTextColor =
                                                item.status === 'Hadir' ? 'text-green-600' :
                                                item.status === 'Cuti' ? 'text-yellow-600' :
                                                'text-blue-400';

                                            // Elemen log
                                            const logItem = `
                        <div class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-50 transition">
                            <div>
                                <p class="text-sm font-semibold ${statusTextColor}">${item.status}</p>
                                <p class="text-xs text-gray-500">${item.keterangan ?? '-'} pada 
                                    <span class="font-medium text-green-600">${tanggalFormatted}</span> 
                                    pukul <span class="font-semibold">${jamFormatted}</span>
                                </p>
                            </div>
                        </div>
                    `;

                                            // Masukkan ke container
                                            logContainer.insertAdjacentHTML('beforeend', logItem);
                                        });

                                    } else {
                                        logContainer.innerHTML = `
                    <p class="text-sm text-gray-500">Belum ada data absensi.</p>
                `;
                                    }

                                } catch (err) {
                                    console.error("Gagal memuat data absensi:", err);
                                    logContainer.innerHTML = `
                <p class="text-sm text-red-500">Gagal memuat data absensi.</p>
            `;
                                }
                            }

                            // Jalankan saat halaman dimuat
                            await loadAllLogs();
                        });
                    </script>


                    <div class="bg-opacity-90 p-2">
                        <div class="px-4 flex items-center justify-between">
                            <span id="monthYear" class="text-base font-bold text-black"></span>
                            <div class="flex items-center space-x-2">
                                <button id="prevMonth" class="text-black hover:text-gray-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" class="icon icon-tabler icon-tabler-chevron-left">
                                        <polyline points="15 6 9 12 15 18" />
                                    </svg>
                                </button>
                                <button id="nextMonth" class="text-black hover:text-gray-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" class="icon icon-tabler icon-tabler-chevron-right">
                                        <polyline points="9 6 15 12 9 18" />
                                    </svg>
                                </button>
                            </div>
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
                                <script>
                                    document.addEventListener("DOMContentLoaded", async function() {
                                        const calendarBody = document.getElementById("calendarBody");
                                        const monthYear = document.getElementById("monthYear");
                                        const today = new Date();
                                        let currentMonth = today.getMonth();
                                        let currentYear = today.getFullYear();

                                        // 🗓️ Render kalender
                                        function renderCalendar(month, year, absensiData = []) {
                                            calendarBody.innerHTML = "";

                                            const firstDay = new Date(year, month).getDay(); // hari pertama bulan
                                            const daysInMonth = new Date(year, month + 1, 0).getDate();

                                            monthYear.textContent = new Date(year, month).toLocaleString("id-ID", {
                                                month: "long",
                                                year: "numeric",
                                            });

                                            let date = 1;
                                            for (let i = 0; i < 6; i++) {
                                                const row = document.createElement("tr");
                                                for (let j = 0; j < 7; j++) {
                                                    const cell = document.createElement("td");
                                                    cell.classList.add("py-2");

                                                    if (i === 0 && j < (firstDay === 0 ? 6 : firstDay - 1)) {
                                                        cell.textContent = "";
                                                    } else if (date <= daysInMonth) {
                                                        cell.textContent = date;
                                                        const fullDate =
                                                            `${year}-${String(month + 1).padStart(2, "0")}-${String(date).padStart(2, "0")}`;
                                                        cell.dataset.date = fullDate;

                                                        // 🟢 Warnai tanggal sesuai data absensi dari server
                                                        const absensi = absensiData.find(a => a.tanggal === fullDate);
                                                        if (absensi) {
                                                            if (absensi.status === "Hadir") cell.classList.add("status-hadir");
                                                            if (absensi.status === "Izin") cell.classList.add("status-izin");
                                                            if (absensi.status === "Cuti") cell.classList.add("status-cuti");
                                                        }

                                                        // 🟡 Warnai semua tanggal dari awal bulan hingga hari ini (jika bulan & tahun sekarang)
                                                        if (year === today.getFullYear() && month === today.getMonth() && date <= today
                                                            .getDate()) {
                                                            cell.classList.add("status-rentang");
                                                        }

                                                        date++;
                                                    }
                                                    row.appendChild(cell);
                                                }
                                                calendarBody.appendChild(row);
                                            }
                                        }

                                        // 🔄 Ambil data absensi dari backend
                                        async function getAbsensi() {
                                            try {
                                                const res = await fetch("{{ route('dashboard.absensi') }}");
                                                const data = await res.json();
                                                renderCalendar(currentMonth, currentYear, data);
                                            } catch (error) {
                                                console.error("Gagal memuat data absensi:", error);
                                                renderCalendar(currentMonth, currentYear, []);
                                            }
                                        }

                                        // ⏩ Navigasi bulan
                                        document.getElementById("prevMonth").addEventListener("click", () => {
                                            currentMonth--;
                                            if (currentMonth < 0) {
                                                currentMonth = 11;
                                                currentYear--;
                                            }
                                            getAbsensi();
                                        });

                                        document.getElementById("nextMonth").addEventListener("click", () => {
                                            currentMonth++;
                                            if (currentMonth > 11) {
                                                currentMonth = 0;
                                                currentYear++;
                                            }
                                            getAbsensi();
                                        });

                                        // 🚀 Jalankan saat halaman dibuka
                                        getAbsensi();
                                    });
                                </script>
                            </table>
                            <div class="flex justify-end -mt-2">
                                <a href="{{ route('dashboard.absensi.riwayat') }}"
                                    class="px-4 py-1.5 text-sm bg-blue-500 text-white rounded-full shadow-md hover:shadow-xl hover:scale-105 transition-all duration-300 flex items-center gap-1">
                                    Selengkapnya
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>

                        </div>
                    </div>

                    <script>
                        document.addEventListener("DOMContentLoaded", async function() {
                            const calendarBody = document.getElementById("calendarBody");
                            const monthYear = document.getElementById("monthYear");
                            let currentMonth = new Date().getMonth();
                            let currentYear = new Date().getFullYear();

                            // Warna status
                            const statusColors = {
                                "Hadir": "bg-green-500 text-white",
                                "Izin": "bg-blue-500 text-white",
                                "Cuti": "bg-yellow-400 text-black"
                            };

                            // Ambil data absensi dari backend
                            async function getAbsensi() {
                                try {
                                    const res = await fetch("{{ route('dashboard.absensi') }}");
                                    return await res.json();
                                } catch (error) {
                                    console.error("Gagal ambil data absensi:", error);
                                    return {};
                                }
                            }

                            // Render kalender
                            async function renderCalendar(month, year) {
                                const absensiData = await getAbsensi();
                                calendarBody.innerHTML = "";

                                const firstDay = new Date(year, month).getDay();
                                const daysInMonth = new Date(year, month + 1, 0).getDate();
                                monthYear.textContent = new Date(year, month).toLocaleString("id-ID", {
                                    month: "long",
                                    year: "numeric"
                                });

                                let date = 1;
                                for (let i = 0; i < 6; i++) {
                                    const row = document.createElement("tr");

                                    for (let j = 0; j < 7; j++) {
                                        const cell = document.createElement("td");
                                        cell.classList.add("py-2", "text-center");

                                        if (i === 0 && j < (firstDay === 0 ? 6 : firstDay - 1)) {
                                            cell.textContent = "";
                                        } else if (date <= daysInMonth) {
                                            const fullDate =
                                                `${year}-${String(month + 1).padStart(2, "0")}-${String(date).padStart(2, "0")}`;
                                            const dayDiv = document.createElement("div");
                                            dayDiv.textContent = date;
                                            dayDiv.classList.add("w-8", "h-8", "flex", "items-center", "justify-center",
                                                "mx-auto", "rounded-full", "cursor-pointer", "transition");

                                            // Jika ada data absensi
                                            if (absensiData[fullDate]) {
                                                const statuses = absensiData[fullDate];
                                                const status = statuses[statuses.length - 1]; // ambil status terakhir
                                                const colorClass = statusColors[status] || "bg-gray-200";
                                                dayDiv.className =
                                                    `w-8 h-8 flex items-center justify-center mx-auto rounded-full font-semibold ${colorClass}`;
                                            } else {
                                                // default
                                                dayDiv.classList.add("hover:bg-gray-200", "text-gray-800");
                                            }

                                            cell.appendChild(dayDiv);
                                            date++;
                                        }
                                        row.appendChild(cell);
                                    }
                                    calendarBody.appendChild(row);
                                }
                            }

                            // Navigasi bulan
                            document.getElementById("prevMonth").addEventListener("click", async () => {
                                currentMonth--;
                                if (currentMonth < 0) {
                                    currentMonth = 11;
                                    currentYear--;
                                }
                                await renderCalendar(currentMonth, currentYear);
                            });

                            document.getElementById("nextMonth").addEventListener("click", async () => {
                                currentMonth++;
                                if (currentMonth > 11) {
                                    currentMonth = 0;
                                    currentYear++;
                                }
                                await renderCalendar(currentMonth, currentYear);
                            });

                            await renderCalendar(currentMonth, currentYear);
                        });
                    </script>


                    <style>
                        .status-hadir {
                            background-color: #3b82f6;
                            color: white;
                            border-radius: 50%;
                            padding: 4px 8px;
                        }

                        .status-izin {
                            background-color: #1e90ff;
                            color: white;
                            border-radius: 50%;
                            padding: 4px 8px;
                        }

                        .status-cuti {
                            background-color: #facc15;
                            color: black;
                            border-radius: 50%;
                            padding: 4px 8px;
                        }
                    </style>



                </div>
                <div class="box-bg row-span-4 col-start-4 bg-white p-4 rounded-lg">
                    <p class="text-lg font-semibold text-gray-800 mb-4">Logs Absen</p>

                    <div id="logAbsen" class="space-y-3 mt-4 flex flex-col">
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
                        <p class="text-2xl font-bold text-black-600">{{ $jumlahHadir }} Hari</p>
                    </div>
                </div>
                <div class="box-bg col-start-2 row-start-4 bg-white p-2 rounded flex items-center">
                    <div class="w-2 h-full bg-green-500 rounded-xl"></div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Jumlah keseluruhan izin</p>
                        <p class="text-2xl font-bold text-black-600">{{ $jumlahIzin }} Hari</p>
                    </div>
                </div>
                <div class="box-bg col-start-3 row-start-4 bg-white p-2 rounded flex items-center">
                    <div class="w-2 h-full bg-green-500 rounded-xl"></div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Jumlah keseluruhan Cuti</p>
                        <p class="text-2xl font-bold text-black-500">{{ $jumlahCuti ?? 0 }} Hari</p>
                    </div>
                </div>

                <div class="box-bg col-span-2 row-span-15 col-start-1 row-start-5 bg-white p-6 rounded">
                    <div class="flex justify-between items-center mb-4">
                        <p class="text-3xl font-semibold text-gray-800">Grafik Prestasi</p>
                        <div>
                            <select id="selectKelas" class="border border-gray-300 rounded px-3 py-1 text-xl">
                                @foreach ($kelas as $k)
                                    <option value="{{ $k->nama_kelas }}">{{ $k->nama_kelas }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="flex-1 flex justify-center items-center">
                        @if ($akademikTerbaik->isEmpty() && $tahfidzTerbaik->isEmpty())
                            <div class="text-center">
                                <p class="mb-4 text-gray-500 text-lg">Belum ada data prestasi</p>
                                <a href="{{ url('/santri') }}"
                                    class="px-6 py-3 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                                    Tambah Data Santri
                                </a>
                            </div>
                        @else
                            <div id="grafik-prestasi" class="p-8 max-w-[350px] max-h-[350px]"></div>
                        @endif
                    </div>
                </div>

                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        const selectKelas = document.getElementById('selectKelas');
                        const container = document.getElementById('grafik-prestasi');
                        const canvas = document.createElement('canvas');
                        container.appendChild(canvas);
                        let chart;

                        async function loadGrafik(kelas) {
                            try {
                                const res = await fetch(
                                    `{{ route('dashboard.prestasi') }}?kelas=${encodeURIComponent(kelas)}`);
                                const data = await res.json();

                                if (!data || Object.keys(data).length === 0) {
                                    if (chart) chart.destroy();
                                    return;
                                }

                                const labels = ['Akademik', 'Non Akademik', 'Tahfidz', 'Hafalan', 'Lainnya'];
                                const values = labels.map(l => data[l] || 0);

                                if (chart) chart.destroy();

                                chart = new Chart(canvas, {
                                    type: 'doughnut',
                                    data: {
                                        labels,
                                        datasets: [{
                                            data: values,
                                            backgroundColor: ['#fb923c', '#2563eb', '#fed7aa', '#99f6e4',
                                                '#694e43'
                                            ],
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
                            } catch (error) {
                                console.error("Gagal memuat grafik prestasi:", error);
                            }
                        }

                        selectKelas.addEventListener('change', e => loadGrafik(e.target.value));
                        loadGrafik(selectKelas.value);
                    });
                </script>

                <div class="box-bg col-span-2 row-span-15 col-start-3 row-start-5 bg-white p-8 rounded">
                    <!-- Top Students Section -->

                    <!-- Header Section -->
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-semibold text-gray-800">Santri Berprestasi</h3>
                        <div class="flex items-center space-x-2 text-sm text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                            <span>Top Performers</span>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <!-- Akademik Terbaik -->
                        <div>
                            <div class="flex items-center space-x-2 mb-3">
                                <div class="w-1 h-6 bg-blue-500 rounded-full"></div>
                                <h4 class="text-lg font-semibold text-gray-800">Akademik Terbaik</h4>
                            </div>

                            @if ($akademikTerbaik && count($akademikTerbaik) > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    @foreach ($akademikTerbaik as $index => $santri)
                                        <div
                                            class="bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 rounded-xl p-4 transition-all duration-200 hover:shadow-md">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center space-x-3">
                                                    <!-- Ranking Badge -->
                                                    <div
                                                        class="flex-shrink-0 w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center text-sm font-bold">
                                                        {{ $index + 1 }}
                                                    </div>
                                                    <div>
                                                        <p class="font-semibold text-gray-800">{{ $santri->nama }}</p>
                                                        <p class="text-xs text-gray-600">{{ $santri->nisn }}</p>
                                                    </div>
                                                </div>
                                                <!-- Achievement Icon -->
                                                <div class="text-blue-500">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4 bg-gray-50 rounded-lg border border-gray-200">
                                    <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p class="text-gray-500 text-sm">Belum ada data akademik</p>
                                </div>
                            @endif
                        </div>

                        <!-- Tahfidz Terbaik -->
                        <div>
                            <div class="flex items-center space-x-2 mb-3">
                                <div class="w-1 h-6 bg-green-500 rounded-full"></div>
                                <h4 class="text-lg font-semibold text-gray-800">Tahfidz Terbaik</h4>
                            </div>

                            @if ($tahfidzTerbaik && count($tahfidzTerbaik) > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    @foreach ($tahfidzTerbaik as $index => $santri)
                                        <div
                                            class="bg-gradient-to-r from-green-50 to-green-100 border border-green-200 rounded-xl p-4 transition-all duration-200 hover:shadow-md">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center space-x-3">
                                                    <!-- Ranking Badge -->
                                                    <div
                                                        class="flex-shrink-0 w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center text-sm font-bold">
                                                        {{ $index + 1 }}
                                                    </div>
                                                    <div>
                                                        <p class="font-semibold text-gray-800">{{ $santri->nama }}</p>
                                                        <p class="text-xs text-gray-600">{{ $santri->nisn }}</p>
                                                    </div>
                                                </div>
                                                <!-- Achievement Icon -->
                                                <div class="text-green-500">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4 bg-gray-50 rounded-lg border border-gray-200">
                                    <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p class="text-gray-500 text-sm">Belum ada data tahfidz</p>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>

                {{ $slot ?? '' }}
        </main>
    </div>
@endsection
