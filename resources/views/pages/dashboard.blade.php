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
                <div class="box-bg col-span-3 row-span-3 col-start-1 p-6 rounded-lg relative overflow-hidden"
                    style="background-image: url('{{ asset('assets/img/ji.png') }}'); background-position: center;">
                    <p class="font-normal text-1 text-gray-800">Pukul</p>
                    <p id="jamSekarang" class="text-m font-semibold text-4xl ml-1 mt-1"></p>
                    <div class="max-w-xs break-words">
                        <h2 class="text-2xl font-normal mt-7 text-gray-800">
                            {{ $user->name ?? 'Selamat Pagi Pak Ustad Arya Agung Setiawan' }}
                        </h2>
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
                            }

                            // Jalankan pertama kali dan perbarui tiap 60 detik
                            updateClock();
                            setInterval(updateClock, 60000);
                        });
                    </script>

                    <div class="flex space-x-4 mt-9">
                        <button class="btn-absen px-4 py-2 bg-green-500 text-white rounded shadow-xl hover:bg-green-700"
                            data-status="Hadir">Absen</button>
                        <button class="btn-absen px-7 py-2 bg-blue-400 text-white rounded shadow-xl hover:bg-blue-600"
                            data-status="Izin">Izin</button>
                        <button class="btn-absen px-7 py-2 bg-yellow-500 text-white rounded shadow-xl hover:bg-yellow-600"
                            data-status="Cuti">Cuti</button>
                    </div>
                    <!-- pastikan SweetAlert2 sudah dimuat di layout (atau tambahkan <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>) -->

                    <script>
                        document.addEventListener('DOMContentLoaded', () => {
                            const buttons = document.querySelectorAll('.btn-absen');
                            const logContainer = document.getElementById('logAbsen');

                            // Ambil CSRF token dari meta
                            const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                            // === Fungsi: Ambil Data Absensi dari Server ===
                            async function getAbsensi() {
                                try {
                                    const response = await fetch("{{ url('/get-absensi') }}");
                                    const data = await response.json();

                                    data.forEach(absen => {
                                        const el = document.querySelector(`[data-date="${absen.tanggal}"]`);
                                        if (el) {
                                            el.classList.remove('hadir', 'izin', 'cuti');
                                            el.classList.add(absen.status.toLowerCase());
                                        }
                                    });
                                } catch (err) {
                                    console.error('Gagal memuat absensi:', err);
                                }
                            }

                            // === Fungsi: Ambil Log Absen dari Server ===
                            async function loadAllLogs() {
                                try {
                                    const res = await fetch("{{ url('/get-log-absen') }}");
                                    const data = await res.json();

                                    logContainer.innerHTML = ''; // kosongkan log sebelum render ulang

                                    // hanya ambil 1 data terbaru per tanggal
                                    const uniqueByDate = {};
                                    data.forEach(item => {
                                        if (!uniqueByDate[item.tanggal]) {
                                            uniqueByDate[item.tanggal] = item;
                                        }
                                    });

                                    Object.values(uniqueByDate).forEach(item => {
                                        const newLog = document.createElement('div');
                                        newLog.classList.add('log-item');
                                        newLog.innerHTML = `
                    <img src="{{ asset('assets/img/orang.jpg') }}" 
                        class="w-12 h-12 rounded-full object-cover border-2 ${
                            item.status === 'Hadir' ? 'border-green-500' :
                            item.status === 'Izin' ? 'border-blue-500' :
                            'border-yellow-500'
                        }" />
                    <div>
                        <p class="text-sm font-medium text-gray-700">${item.status} (${item.tanggal})</p>
                        <p class="text-xs text-gray-500">${item.keterangan ?? '-'} 
                            <span class="text-green-600 font-semibold">${item.created_at ? item.created_at.slice(11, 16) : ''}</span>
                        </p>
                    </div>
                `;
                                        logContainer.appendChild(newLog);
                                    });
                                } catch (error) {
                                    console.error('Gagal memuat log absen:', error);
                                }
                            }

                            // === Fungsi: Kirim Data Absen ke Server ===
                            async function kirimDataToServer(payload, btn) {
                                if (btn) {
                                    btn.disabled = true;
                                    btn.classList.add('opacity-60', 'cursor-not-allowed');
                                }

                                try {
                                    const response = await fetch("{{ url('/dashboard') }}", {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': CSRF_TOKEN
                                        },
                                        body: JSON.stringify(payload)
                                    });

                                    if (!response.ok) throw new Error(`Server error: ${response.status}`);

                                    const data = await response.json();
                                    return {
                                        ok: true,
                                        json: data
                                    };
                                } catch (err) {
                                    console.error('Fetch error:', err);
                                    Swal.fire('Terjadi Kesalahan!', 'Tidak dapat menghubungi server.', 'error');
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

                            // === Fungsi Utama: Klik Tombol Absen ===
                            buttons.forEach(btn => {
                                btn.addEventListener('click', async () => {
                                    const status = btn.dataset.status;
                                    const waktuSekarang = new Date();
                                    const jam = waktuSekarang.toLocaleTimeString('id-ID', {
                                        hour: '2-digit',
                                        minute: '2-digit'
                                    });

                                    // --- Jika Izin / Cuti ---
                                    if (status === 'Izin' || status === 'Cuti') {
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

                                        if (res.ok && res.json.success) {
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Berhasil!',
                                                text: `${status} tersimpan pada ${jam}`,
                                                showConfirmButton: false,
                                                timer: 1500
                                            });

                                            // 🔁 perbarui log & kalender tanpa refresh halaman
                                            await loadAllLogs();
                                            await getAbsensi();
                                        }
                                        return;
                                    }

                                    // --- Jika Hadir ---
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

                                    if (res.ok && res.json.success) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Berhasil!',
                                            text: `${status} tersimpan pada ${jam}`,
                                            showConfirmButton: false,
                                            timer: 1500
                                        });

                                        await loadAllLogs();
                                        await getAbsensi();
                                    }
                                });
                            });

                            // === Jalankan di awal saat halaman dibuka ===
                            loadAllLogs();
                            getAbsensi();
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
                                            const waktu = new Date(item.tanggal).toLocaleDateString('id-ID', {
                                                day: '2-digit',
                                                month: 'long',
                                                year: 'numeric'
                                            });

                                            const statusColor =
                                                item.status === 'Hadir' ? 'border-green-500' :
                                                item.status === 'Cuti' ? 'border-yellow-400' :
                                                'border-blue-400'

                                            const statusTextColor =
                                                item.status === 'Hadir' ? 'text-green-500' :
                                                item.status === 'Cuti' ? 'text-yellow-600' :
                                                'text-blue-400'

                                            const logItem = `
                        <div class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-50 transition">
                            <img src="{{ asset('assets/img/orang.jpg') }}"
                                class="w-12 h-12 rounded-full object-cover border-2 ${statusColor}" />
                            <div>
                                <p class="text-sm font-semibold ${statusTextColor}">${item.status}</p>
                                <p class="text-xs text-gray-500">${item.keterangan ?? '-'} pada 
                                    <span class="font-medium text-green-600">${waktu}</span>
                                </p>
                            </div>
                        </div>
                    `;
                                            logContainer.innerHTML += logItem;
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



                    <div class="absolute top-3 right-6 bg-opacity-90 p-4 z-14 w-77">

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
                        <img src="{{ asset('assets/img/orang.jpg') }}"
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

                        <div id="grafik-prestasi" class="p-8 max-w-[350px] max-h-[350px]"></div>


                        <div>
                            <ul class="space-y-5 text-sm">
                                <li class="flex items-center space-x-2">
                                    <span class="w-3 h-3 rounded-full bg-orange-400"></span>
                                    <span>Akademik</span>
                                </li>
                                <li class="flex items-center space-x-2">
                                    <span class="w-3 h-3 rounded-full bg-blue-600"></span>
                                    <span>Non Akademik</span>
                                </li>
                                <li class="flex items-center space-x-2">
                                    <span class="w-3 h-3 rounded-full bg-orange-200"></span>
                                    <span>Tahfids</span>
                                </li>
                                <li class="flex items-center space-x-2">
                                    <span class="w-3 h-3 rounded-full bg-teal-200"></span>
                                    <span>Hafalan</span>
                                </li>
                                <li class="flex items-center space-x-2">
                                    <span class="w-3 h-3 rounded-full bg-amber-900"></span>
                                    <span>Lainnya</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
                    <p class="text-2xl font-bold text-gray-800 mb-4 mt-2 ml-4">Akademik Terbaik</p>
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
                        <p class="text-2xl font-bold text-gray-800 mb-4 mt-2 ml-1">Tahfidz Terbaik</p>
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
