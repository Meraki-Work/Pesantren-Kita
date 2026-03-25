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
                    <p class="text-gray-600 mt-1">Catat kehadiran Anda dengan cepat. Lihat riwayat absensi dan status
                        kehadiran harian.</p>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-4 grid-rows-7 gap-4 text-gray-900 p-4 rounded-lg ">
            <div class="flex justify-between box-bg col-span-3 row-span-3 col-start-1 px-6 py-3 rounded-lg relative overflow-hidden"
                style="background-image: url('{{ asset('assets/img/ji.png') }}'); background-position: center;">
                <div class="flex flex-col lg:flex-row gap-8">
    <!-- Kolom Kiri - Jam, Sapaan, dan Tombol Absensi -->
    <div class="lg:w-1/2">
        <!-- Jam -->
        <div class="mb-6">
            <p class="font-normal text-lg text-gray-600">Jam :</p>
            <p id="jamSekarang" class="text-4xl font-bold text-gray-800 mt-2"></p>
        </div>
        
        <!-- Sapaan -->
        <div class="mb-8">
            <div class="max-w-xs break-words">
                <h2 class="text-2xl md:text-3xl font-semibold text-gray-800">
                    <span id="greetingText"></span>
                    {{ Auth::user()->username }}
                </h2>
                <p class="text-gray-600 mt-2">
                    Selamat {{ Auth::user()->role }}! Semoga hari Anda menyenangkan.
                </p>
            </div>
        </div>
        
        <!-- Tombol Absensi -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Absensi Hari Ini</h3>
            <div class="grid grid-cols-2 gap-4">
                <button
                    class="btn-absen flex h-14 px-4 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition items-center justify-center shadow-md"
                    data-status="Hadir">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    Hadir
                </button>

                <button
                    class="btn-absen flex h-14 px-4 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition items-center justify-center shadow-md"
                    data-status="Izin">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                    Izin
                </button>

                <button
                    class="btn-absen flex h-14 px-4 bg-yellow-500 text-white text-sm font-semibold rounded-lg hover:bg-yellow-600 transition items-center justify-center shadow-md"
                    data-status="Sakit">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 100-2 1 1 0 000 2zm7-1a1 1 0 11-2 0 1 1 0 012 0zm-7.536 5.879a1 1 0 001.415 0 3 3 0 014.242 0 1 1 0 001.415-1.415 5 5 0 00-7.072 0 1 1 0 000 1.415z" clip-rule="evenodd" />
                    </svg>
                    Sakit
                </button>

                <button
                    class="btn-absen flex h-14 px-4 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition items-center justify-center shadow-md"
                    data-status="Alpa">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    Alpa
                </button>
            </div>
        </div>
        
        <!-- Status Absensi Hari Ini -->
        <div id="todayAbsensiStatus" class="mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200 hidden">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div class="absolute -top-1 -right-1 h-4 w-4 bg-white rounded-full flex items-center justify-center">
                            <div class="h-2 w-2 bg-green-500 rounded-full"></div>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="font-medium text-gray-800">Status hari ini:</p>
                        <p id="todayStatus" class="text-lg font-semibold text-gray-900 mt-1"></p>
                        <p id="todayTime" class="text-sm text-gray-500 mt-1"></p>
                    </div>
                </div>
                <button id="undoAbsensi" class="text-red-600 hover:text-red-800 transition hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
            <div class="mt-3 pt-3 border-t border-gray-200">
                <p class="text-sm text-gray-600">Absensi berhasil dicatat. Status Anda akan muncul di kalender.</p>
            </div>
        </div>
    </div>
    
    <!-- =========================================== -->
    <!-- Kolom Kanan - KALENDER ABSENSI -->
    <!-- =========================================== -->
    <div class="lg:w-1/2">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <!-- Header Kalender -->
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
                <div>
                    <h3 class="text-xl font-bold text-gray-800">Kalender Absensi</h3>
                    <p class="text-sm text-gray-600 mt-1">Riwayat kehadiran bulan ini</p>
                </div>
                <div class="flex items-center space-x-2 bg-gray-50 px-3 py-2 rounded-lg">
                    <button id="prevMonth" class="p-2 rounded-full hover:bg-gray-200 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <span id="monthYear" class="font-semibold text-gray-700 min-w-[140px] text-center"></span>
                    <button id="nextMonth" class="p-2 rounded-full hover:bg-gray-200 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Header Hari -->
            <div class="grid grid-cols-7 gap-1 mb-4">
                <div class="text-center text-sm font-semibold text-gray-500 py-3 bg-gray-50 rounded-lg">Sen</div>
                <div class="text-center text-sm font-semibold text-gray-500 py-3 bg-gray-50 rounded-lg">Sel</div>
                <div class="text-center text-sm font-semibold text-gray-500 py-3 bg-gray-50 rounded-lg">Rab</div>
                <div class="text-center text-sm font-semibold text-gray-500 py-3 bg-gray-50 rounded-lg">Kam</div>
                <div class="text-center text-sm font-semibold text-gray-500 py-3 bg-gray-50 rounded-lg">Jum</div>
                <div class="text-center text-sm font-semibold text-gray-500 py-3 bg-gray-50 rounded-lg">Sab</div>
                <div class="text-center text-sm font-semibold text-gray-500 py-3 bg-gray-50 rounded-lg">Min</div>
            </div>

            <!-- Kalender Body -->
            <div id="calendarBody" class="grid grid-cols-7 gap-2 mb-6"></div>

            <!-- Statistik Ringkas -->
            <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-100">
                <h4 class="font-semibold text-gray-800 mb-3">Statistik Bulan Ini</h4>
                <div class="grid grid-cols-4 gap-3">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600" id="statHadir">0</div>
                        <div class="text-xs text-gray-600 mt-1">Hadir</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600" id="statIzin">0</div>
                        <div class="text-xs text-gray-600 mt-1">Izin</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-yellow-600" id="statSakit">0</div>
                        <div class="text-xs text-gray-600 mt-1">Sakit</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-red-600" id="statAlpa">0</div>
                        <div class="text-xs text-gray-600 mt-1">Alpa</div>
                    </div>
                </div>
            </div>

            <!-- Legenda -->
            <div class="mb-6">
                <h4 class="font-semibold text-gray-800 mb-3">Legenda Status</h4>
                <div class="flex flex-wrap gap-4 text-sm">
                    <div class="flex items-center bg-gray-50 px-3 py-2 rounded-lg">
                        <div class="w-4 h-4 rounded-full bg-green-500 mr-2"></div>
                        <span class="text-gray-700">Hadir</span>
                    </div>
                    <div class="flex items-center bg-gray-50 px-3 py-2 rounded-lg">
                        <div class="w-4 h-4 rounded-full bg-blue-500 mr-2"></div>
                        <span class="text-gray-700">Izin</span>
                    </div>
                    <div class="flex items-center bg-gray-50 px-3 py-2 rounded-lg">
                        <div class="w-4 h-4 rounded-full bg-yellow-500 mr-2"></div>
                        <span class="text-gray-700">Sakit</span>
                    </div>
                    <div class="flex items-center bg-gray-50 px-3 py-2 rounded-lg">
                        <div class="w-4 h-4 rounded-full bg-red-500 mr-2"></div>
                        <span class="text-gray-700">Alpa</span>
                    </div>
                    <div class="flex items-center bg-gray-50 px-3 py-2 rounded-lg">
                        <div class="w-4 h-4 rounded-full border-2 border-gray-400 mr-2"></div>
                        <span class="text-gray-700">Belum Absen</span>
                    </div>
                    <div class="flex items-center bg-gray-50 px-3 py-2 rounded-lg">
                        <div class="w-4 h-4 rounded-full bg-gray-300 mr-2"></div>
                        <span class="text-gray-700">Hari Libur</span>
                    </div>
                </div>
            </div>

            <!-- Tombol Selengkapnya -->
            <div class="pt-4 border-t border-gray-200">
                <a href="{{ route('dashboard.absensi.riwayat') }}"
                    class="flex items-center justify-center w-full py-3 bg-emerald-600 text-white font-medium rounded-lg hover:bg-emerald-700 transition shadow-sm">
                    Lihat Riwayat Lengkap
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const buttons = document.querySelectorAll('.btn-absen');
                        const todayStatusDiv = document.getElementById('todayAbsensiStatus');
                        const todayStatusText = document.getElementById('todayStatus');
                        const todayTimeText = document.getElementById('todayTime');
                        const undoButton = document.getElementById('undoAbsensi');
                        const absensiInfoDiv = document.getElementById('absensiInfo');
                        const jamAbsensiInfo = document.getElementById('jamAbsensiInfo');
                        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                        // ===========================================
                        // FUNGSI ABSENSI
                        // ===========================================
                        function updateAbsensiTimeInfo() {
                            const now = new Date();
                            const currentHour = now.getHours();
                            const currentMinute = now.getMinutes();

                            if (currentHour >= 5 && currentHour < 17) {
                                jamAbsensiInfo.textContent = `Absensi dapat dilakukan sampai jam 17:00 WIB.`;
                                jamAbsensiInfo.className = 'text-green-700';
                            } else if (currentHour >= 17) {
                                jamAbsensiInfo.textContent = 'Waktu absensi hari ini sudah berakhir.';
                                jamAbsensiInfo.className = 'text-red-600 font-medium';

                                buttons.forEach(btn => {
                                    btn.disabled = true;
                                    btn.classList.add('opacity-50', 'cursor-not-allowed');
                                });
                            } else {
                                jamAbsensiInfo.textContent = 'Absensi akan dibuka pada jam 05:00 WIB.';
                                jamAbsensiInfo.className = 'text-yellow-600';

                                buttons.forEach(btn => {
                                    btn.disabled = true;
                                    btn.classList.add('opacity-50', 'cursor-not-allowed');
                                });
                            }
                        }

                        async function checkTodayAbsensi() {
                            try {
                                const response = await fetch("{{ route('dashboard.absensi.check') }}");
                                const data = await response.json();

                                if (data.already_absened) {
                                    todayStatusDiv.classList.remove('hidden');

                                    const statusColors = {
                                        'Hadir': 'text-green-600',
                                        'Izin': 'text-blue-600',
                                        'Sakit': 'text-yellow-600',
                                        'Alpa': 'text-red-600'
                                    };

                                    todayStatusText.innerHTML = `<span class="font-bold ${statusColors[data.status] || 'text-gray-600'}">${data.status}</span>`;

                                    if (data.is_auto_alfa) {
                                        todayTimeText.textContent = "Auto: Tidak absen sampai batas waktu";
                                    } else {
                                        todayTimeText.textContent = `Pada ${new Date().toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'})} WIB`;
                                    }

                                    buttons.forEach(btn => {
                                        btn.disabled = true;
                                        btn.classList.add('opacity-50', 'cursor-not-allowed');
                                    });

                                    if (!data.is_auto_alfa) {
                                        undoButton.classList.remove('hidden');
                                    }
                                } else {
                                    todayStatusDiv.classList.add('hidden');
                                    buttons.forEach(btn => {
                                        btn.disabled = false;
                                        btn.classList.remove('opacity-50', 'cursor-not-allowed');
                                    });
                                }

                                updateAbsensiTimeInfo();
                            } catch (error) {
                                console.error('Gagal memeriksa absensi:', error);
                            }
                        }

                        async function kirimAbsensi(status, keterangan = null) {
                            const payload = {
                                status
                            };
                            if (keterangan) {
                                payload.keterangan = keterangan;
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
                                    success: true,
                                    data
                                };
                            } catch (err) {
                                console.error('Absensi error:', err);
                                return {
                                    success: false,
                                    error: err.message
                                };
                            }
                        }

                        // ===========================================
                        // KALENDER ABSENSI
                        // ===========================================
                        const calendarBody = document.getElementById('calendarBody');
                        const monthYear = document.getElementById('monthYear');
                        let currentMonth = new Date().getMonth();
                        let currentYear = new Date().getFullYear();

                        async function renderCalendar(month, year) {
                            try {
                                // Ambil data absensi
                                const response = await fetch("{{ route('dashboard.absensi') }}");
                                const absensiData = await response.json();

                                // Render bulan dan tahun
                                const monthNames = [
                                    'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                                    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                                ];
                                monthYear.textContent = `${monthNames[month]} ${year}`;

                                // Hitung hari pertama dan jumlah hari
                                const firstDay = new Date(year, month, 1).getDay();
                                const daysInMonth = new Date(year, month + 1, 0).getDate();

                                // Konversi ke index Senin (0) - Minggu (6)
                                const startIndex = (firstDay + 6) % 7;

                                calendarBody.innerHTML = '';
                                let date = 1;

                                // Buat 6 baris (maksimal)
                                for (let row = 0; row < 6; row++) {
                                    for (let col = 0; col < 7; col++) {
                                        const dayCell = document.createElement('div');
                                        dayCell.className = 'min-h-12 flex flex-col items-center justify-center p-1';

                                        const dayIndex = row * 7 + col;

                                        if ((row === 0 && col < startIndex) || date > daysInMonth) {
                                            // Sel kosong
                                            dayCell.innerHTML = '<div class="w-8 h-8"></div>';
                                        } else {
                                            // Format tanggal
                                            const formattedDate = `${year}-${String(month + 1).padStart(2, '0')}-${String(date).padStart(2, '0')}`;

                                            // Cek apakah ada absensi di tanggal ini
                                            const absensi = absensiData[formattedDate];
                                            const isToday = formattedDate === new Date().toISOString().split('T')[0];

                                            // Warna berdasarkan status
                                            let bgColor = '';
                                            let borderColor = '';

                                            if (absensi) {
                                                const status = absensi[absensi.length - 1]; // Ambil status terakhir
                                                switch (status) {
                                                    case 'Hadir':
                                                        bgColor = 'bg-green-100';
                                                        borderColor = 'border-green-500';
                                                        break;
                                                    case 'Izin':
                                                        bgColor = 'bg-blue-100';
                                                        borderColor = 'border-blue-500';
                                                        break;
                                                    case 'Sakit':
                                                        bgColor = 'bg-yellow-100';
                                                        borderColor = 'border-yellow-500';
                                                        break;
                                                    case 'Alpa':
                                                        bgColor = 'bg-red-100';
                                                        borderColor = 'border-red-500';
                                                        break;
                                                }
                                            }

                                            // Styling untuk hari ini
                                            if (isToday) {
                                                borderColor = 'border-2 border-blue-500';
                                            }

                                            dayCell.innerHTML = `
                                <div class="w-8 h-8 ${bgColor} ${borderColor} rounded-full flex items-center justify-center text-sm font-medium ${isToday ? 'text-blue-700' : 'text-gray-700'}">
                                    ${date}
                                </div>
                                ${absensi ? `
                                    <div class="mt-1 flex justify-center">
                                        <div class="w-2 h-2 rounded-full ${bgColor.replace('100', '500')}"></div>
                                    </div>
                                ` : ''}
                            `;

                                            // Tooltip untuk detail absensi
                                            if (absensi) {
                                                dayCell.title = `${formattedDate}: ${absensi.join(', ')}`;
                                            }

                                            date++;
                                        }

                                        calendarBody.appendChild(dayCell);
                                    }

                                    if (date > daysInMonth) break;
                                }
                            } catch (error) {
                                console.error('Gagal memuat kalender:', error);
                                calendarBody.innerHTML = `
                    <div class="col-span-7 text-center py-4 text-gray-500">
                        Gagal memuat data kalender
                    </div>
                `;
                            }
                        }

                        // Navigasi bulan
                        document.getElementById('prevMonth').addEventListener('click', () => {
                            currentMonth--;
                            if (currentMonth < 0) {
                                currentMonth = 11;
                                currentYear--;
                            }
                            renderCalendar(currentMonth, currentYear);
                        });

                        document.getElementById('nextMonth').addEventListener('click', () => {
                            currentMonth++;
                            if (currentMonth > 11) {
                                currentMonth = 0;
                                currentYear++;
                            }
                            renderCalendar(currentMonth, currentYear);
                        });

                        // ===========================================
                        // EVENT HANDLERS
                        // ===========================================
                        buttons.forEach(btn => {
                            btn.addEventListener('click', async function() {
                                const status = this.dataset.status;
                                const waktuSekarang = new Date();
                                const jam = waktuSekarang.toLocaleTimeString('id-ID', {
                                    hour: '2-digit',
                                    minute: '2-digit'
                                });

                                // Validasi jam absensi
                                if (waktuSekarang.getHours() < 5 || waktuSekarang.getHours() >= 17) {
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'Waktu Absensi',
                                        text: 'Absensi hanya dapat dilakukan dari jam 05:00 sampai 17:00 WIB.',
                                        confirmButtonText: 'Mengerti'
                                    });
                                    return;
                                }

                                // Jika butuh keterangan
                                if (status === 'Izin' || status === 'Sakit' || status === 'Alpa') {
                                    const {
                                        value: keterangan
                                    } = await Swal.fire({
                                        title: `${status}`,
                                        html: `
                            <div style="text-align: center;">
                                <textarea 
                                    id="swal-input" 
                                    class="swal2-textarea" 
                                    placeholder="Tuliskan alasan ${status.toLowerCase()}..."
                                    rows="4"
                                    style="
                                        resize: none; 
                                        padding: 15px; 
                                        border-radius: 8px; 
                                        border: 1px solid #d1d5db;
                                        width: 90%;
                                        margin: 10px auto;
                                        text-align: left;
                                        font-size: 14px;
                                    "></textarea>
                            </div>
                        `,
                                        focusConfirm: false,
                                        showCancelButton: true,
                                        confirmButtonText: 'Simpan',
                                        cancelButtonText: 'Batal',
                                        width: '450px',
                                        preConfirm: () => {
                                            const v = Swal.getPopup().querySelector('#swal-input').value.trim();
                                            if (!v) {
                                                Swal.showValidationMessage('Keterangan harus diisi!');
                                                return null;
                                            }
                                            if (v.length < 10) {
                                                Swal.showValidationMessage('Keterangan minimal 10 karakter!');
                                                return null;
                                            }
                                            return v;
                                        },
                                        didOpen: () => {
                                            setTimeout(() => {
                                                const textarea = Swal.getPopup().querySelector('#swal-input');
                                                textarea.focus();
                                            }, 100);
                                        }
                                    });

                                    if (!keterangan) return;

                                    const confirm = await Swal.fire({
                                        title: `Konfirmasi ${status}`,
                                        html: `
                            <div style="text-align: center; padding: 10px 0;">
                                <p style="margin-bottom: 15px; color: #374151;">Keterangan:</p>
                                <div style="
                                    background-color: #f9fafb; 
                                    padding: 15px; 
                                    border-radius: 8px; 
                                    margin: 0 auto;
                                    width: 90%;
                                    max-height: 150px;
                                    overflow-y: auto;
                                    text-align: left;
                                ">
                                    <p style="margin: 0; color: #4b5563; font-size: 14px; line-height: 1.5;">${keterangan}</p>
                                </div>
                            </div>
                        `,
                                        icon: 'question',
                                        showCancelButton: true,
                                        confirmButtonText: 'Ya, Simpan',
                                        cancelButtonText: 'Ubah',
                                        confirmButtonColor: '#10b981',
                                        cancelButtonColor: '#6b7280',
                                        reverseButtons: true,
                                        width: '450px'
                                    });

                                    if (!confirm.isConfirmed) {
                                        this.click();
                                        return;
                                    }

                                    const result = await kirimAbsensi(status, keterangan);

                                    if (result.success) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Berhasil!',
                                            text: `${status} berhasil disimpan`,
                                            showConfirmButton: false,
                                            timer: 1500
                                        });
                                        await checkTodayAbsensi();
                                        await loadAllLogs();
                                        await renderCalendar(currentMonth, currentYear);
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Gagal!',
                                            text: result.error || 'Gagal menyimpan absensi'
                                        });
                                    }
                                    return;
                                }

                                // Untuk Hadir
                                if (status === 'Hadir') {
                                    const confirm = await Swal.fire({
                                        title: `Absen Hadir`,
                                        html: `Yakin ingin absen <b>Hadir</b> sekarang?<br><small class="text-gray-500">${jam} WIB</small>`,
                                        icon: 'question',
                                        showCancelButton: true,
                                        confirmButtonText: 'Ya, Absen!',
                                        cancelButtonText: 'Batal'
                                    });

                                    if (!confirm.isConfirmed) return;

                                    const result = await kirimAbsensi(status);

                                    if (result.success) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Berhasil!',
                                            text: `Absen Hadir tersimpan pada ${jam}`,
                                            showConfirmButton: false,
                                            timer: 1500
                                        });
                                        await checkTodayAbsensi();
                                        await loadAllLogs();
                                        await renderCalendar(currentMonth, currentYear);
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Gagal!',
                                            text: result.error || 'Gagal menyimpan absensi'
                                        });
                                    }
                                }
                            });
                        });

                        // Tombol undo absensi
                        undoButton.addEventListener('click', async function() {
                            const confirm = await Swal.fire({
                                title: 'Batalkan Absensi?',
                                text: 'Apakah Anda yakin ingin membatalkan absensi hari ini?',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Ya, Batalkan',
                                cancelButtonText: 'Tidak',
                                confirmButtonColor: '#ef4444'
                            });

                            if (confirm.isConfirmed) {
                                try {
                                    const response = await fetch("{{ route('dashboard.absensi.store') }}?_method=DELETE", {
                                        method: 'POST',
                                        headers: {
                                            'X-CSRF-TOKEN': CSRF_TOKEN,
                                            'Accept': 'application/json'
                                        }
                                    });

                                    const data = await response.json();

                                    if (data.success) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Dibatalkan!',
                                            text: 'Absensi hari ini telah dibatalkan',
                                            showConfirmButton: false,
                                            timer: 1500
                                        });
                                        await checkTodayAbsensi();
                                        await loadAllLogs();
                                        await renderCalendar(currentMonth, currentYear);
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Gagal!',
                                            text: data.message || 'Gagal membatalkan absensi'
                                        });
                                    }
                                } catch (error) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        text: 'Terjadi kesalahan saat membatalkan absensi'
                                    });
                                }
                            }
                        });

                        // Fungsi untuk memuat log absensi
                        async function loadAllLogs() {
                            const logContainer = document.getElementById('logAbsen');
                            if (!logContainer) return;

                            try {
                                const res = await fetch("{{ route('dashboard.absensi.all') }}");
                                const data = await res.json();

                                logContainer.innerHTML = '';

                                if (data.success && data.data.length > 0) {
                                    data.data.forEach(item => {
                                        const tanggalFormatted = new Date(item.tanggal).toLocaleDateString('id-ID', {
                                            day: '2-digit',
                                            month: 'long',
                                            year: 'numeric'
                                        });

                                        const jamFormatted = item.jam ?
                                            new Date(`2000-01-01T${item.jam}`).toLocaleTimeString('id-ID', {
                                                hour: '2-digit',
                                                minute: '2-digit',
                                                second: '2-digit'
                                            }) :
                                            '-';

                                        const statusColors = {
                                            'Hadir': 'border-green-500 bg-green-50',
                                            'Izin': 'border-blue-500 bg-blue-50',
                                            'Sakit': 'border-yellow-500 bg-yellow-50',
                                            'Alpa': 'border-red-500 bg-red-50'
                                        };

                                        const statusTextColors = {
                                            'Hadir': 'text-green-700',
                                            'Izin': 'text-blue-700',
                                            'Sakit': 'text-yellow-700',
                                            'Alpa': 'text-red-700'
                                        };

                                        const borderColor = statusColors[item.status] || 'border-gray-300 bg-gray-50';
                                        const textColor = statusTextColors[item.status] || 'text-gray-700';

                                        logContainer.insertAdjacentHTML('beforeend', `
                            <div class="flex items-center space-x-3 p-3 rounded-lg border ${borderColor} mb-2">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-full ${item.status === 'Alpa' && item.keterangan?.includes('Auto:') ? 'bg-red-100 border-2 border-red-300' : 'bg-gray-100'} flex items-center justify-center">
                                        <span class="text-sm font-bold ${item.status === 'Alpa' && item.keterangan?.includes('Auto:') ? 'text-red-600' : 'text-gray-600'}">
                                            ${item.status.charAt(0)}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <div class="flex justify-between items-start">
                                        <p class="text-sm font-semibold ${textColor}">${item.status}</p>
                                        <span class="text-xs text-gray-500">${tanggalFormatted}</span>
                                    </div>
                                    <p class="text-xs text-gray-600 mt-1">
                                        ${item.keterangan || '-'} 
                                        ${item.jam ? `pukul ${jamFormatted}` : ''}
                                        ${item.keterangan?.includes('Auto:') ? '<span class="ml-1 text-xs bg-red-100 text-red-800 px-2 py-1 rounded-full">Auto</span>' : ''}
                                    </p>
                                </div>
                            </div>
                        `);
                                    });
                                } else {
                                    logContainer.innerHTML = `
                        <div class="text-center py-6 text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="mt-2">Belum ada riwayat absensi</p>
                        </div>
                    `;
                                }
                            } catch (error) {
                                console.error('Gagal memuat log absen:', error);
                                logContainer.innerHTML = `
                    <div class="text-center py-6 text-red-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="mt-2">Gagal memuat data absensi</p>
                    </div>
                `;
                            }
                        }

                        // ===========================================
                        // INISIALISASI
                        // ===========================================
                        setInterval(updateAbsensiTimeInfo, 60000);

                        // Jalankan semua fungsi
                        checkTodayAbsensi();
                        loadAllLogs();
                        updateAbsensiTimeInfo();
                        renderCalendar(currentMonth, currentYear);
                    });
                </script>

            </div>

            <div class="box-bg row-span-4 col-start-4 bg-white p-4 rounded-lg">
                <p class="text-lg font-semibold text-gray-800 mb-4">Logs Absen</p>
                <div id="logAbsen" class="space-y-3 mt-4 flex flex-col">
                </div>
                <!-- Pesan Informasi -->
                <div id="absensiInfo" class="mt-3 p-2 bg-blue-50 border border-blue-200 rounded-lg text-sm text-blue-800">
                    <div class="flex items-start">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 mt-0.5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                        <div>
                            <p class="font-medium">Informasi Absensi:</p>
                            <p id="jamAbsensiInfo">Absensi dapat dilakukan dari jam 05:00 sampai 17:00 WIB.</p>
                            <p id="autoAlfaInfo" class="mt-1 text-xs">Sistem akan otomatis menandai <span class="font-semibold text-red-600">Alpa</span> jika tidak absen sampai jam 17:00.</p>
                        </div>
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
                            <div class="box-bg p-4 transition-all duration-200 hover:shadow-md">
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

                            document.getElementById('greetingText').textContent = greeting + ", ";
                        }

                        setInterval(updateDashboardTime, 1000);
                        updateDashboardTime();
                    </script>

@endsection