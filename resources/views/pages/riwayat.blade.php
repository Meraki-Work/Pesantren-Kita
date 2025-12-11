@extends('index')

@section('content')
    <div class="flex bg-gray-100 min-h-screen">
        <x-sidemenu title="PesantrenKita" class="h-full" />

        <main class="flex-1 p-6 overflow-y-auto">

            <div class="grid grid-cols-5 grid-rows-5 gap-4 h-[720px]">

                {{-- Profil User --}}

                <div class="bg-white border border-green-500 col-span-2 row-span-5 rounded-2xl p-6 overflow-hidden">
                    <div
                        class="bg-gradient-to-br from-green-50 to-white border border-green-300 rounded-xl p-4 shadow hover:shadow-lg transition-all duration-300 h-[150px]">
                        @if (auth()->user()->role === 'admin' && $userProfile === null)
                            <p><strong>Nama :</strong> -</p>
                            <p><strong>Ponpes :</strong> -</p>
                            <p><strong>Role :</strong> -</p>
                            <p><strong>Email :</strong> -</p>
                        @else
                            <p class="text-lg font-bold text-gray-800">
                                {{ $selectedUser->username ?? '-' }}
                            </p>
                            <p class="text-sm text-gray-600 mt-1">Ponpes:
                                <span class="font-semibold">{{ $selectedUser->nama_ponpes ?? '-' }}</span>
                            </p>
                            <p class="text-sm text-gray-600">Role:
                                <span class="font-semibold">{{ $selectedUser->role ?? '-' }}</span>
                            </p>
                            <p class="text-sm text-gray-500 mt-1">Email: {{ $selectedUser->email ?? '-' }}</p>
                        @endif
                    </div>


                    {{-- ABSENSI DATA --}}
                    <script>
                        window.absensiData = @json($absensiPerTanggal);
                    </script>

                    {{-- KALENDER --}}
                    <div x-data="calendar()" class="w-full h-full p-4">

                        <div class="flex items-center justify-between">
                            <button @click="prevMonth" class="px-3 py-1 bg-gray-200 rounded-lg">&lt;</button>
                            <h2 class="text-2xl font-bold text-gray-800" x-text="monthNames[month] + ' ' + year"></h2>
                            <button @click="nextMonth" class="px-3 py-1 bg-gray-200 rounded-lg">&gt;</button>
                        </div>

                        <div class="grid grid-cols-7 text-center mt-4 font-semibold text-gray-600">
                            <template x-for="day in days">
                                <div class="py-2 border-b" x-text="day"></div>
                            </template>
                        </div>

                        <div
                            class="grid grid-cols-7 gap-px mt-1 bg-gray-200 rounded-lg overflow-hidden auto-rows-fr h-[450px]">
                            <template x-for="date in dates">
                                <div class="bg-white min-h-14 relative p-1 hover:bg-gray-50 cursor-pointer flex flex-col">
                                    <span x-text="date.number" :class="date.current ? 'text-black' : 'text-gray-300'"
                                        class="text-sm font-semibold"></span>

                                    <template x-if="date.event">
                                        <span class="text-[10px] font-bold px-1 rounded mt-1"
                                            :class="{
                                                'bg-green-500 text-white': date.event === 'Hadir',
                                                'bg-yellow-500 text-white': date.event === 'Izin',
                                                'bg-blue-500 text-white': date.event === 'Sakit',
                                                'bg-red-500 text-white': date.event === 'Alpa'
                                            }"
                                            x-text="date.event">
                                        </span>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- Alpine Calendar Script --}}
                    <script>
                        function calendar() {
                            return {
                                month: new Date().getMonth(),
                                year: new Date().getFullYear(),
                                days: ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"],
                                monthNames: [
                                    "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                                    "Juli", "Agustus", "September", "Oktober", "November", "Desember"
                                ],
                                dates: [],

                                loadDates() {
                                    const firstDay = new Date(this.year, this.month, 1).getDay();
                                    const totalDays = new Date(this.year, this.month + 1, 0).getDate();
                                    const prevLastDay = new Date(this.year, this.month, 0).getDate();

                                    this.dates = [];

                                    // tanggal bulan sebelumnya
                                    for (let i = firstDay - 1; i >= 0; i--) {
                                        this.dates.push({
                                            number: prevLastDay - i,
                                            current: false,
                                            event: null
                                        });
                                    }

                                    // tanggal bulan ini
                                    for (let i = 1; i <= totalDays; i++) {
                                        let formattedDate =
                                            `${this.year}-${String(this.month + 1).padStart(2,'0')}-${String(i).padStart(2,'0')}`;

                                        let event = window.absensiData?.[formattedDate] ?? null;

                                        this.dates.push({
                                            number: i,
                                            current: true,
                                            event: event
                                        });
                                    }
                                },

                                nextMonth() {
                                    this.month++;
                                    if (this.month > 11) {
                                        this.month = 0;
                                        this.year++;
                                    }
                                    this.loadDates();
                                },

                                prevMonth() {
                                    this.month--;
                                    if (this.month < 0) {
                                        this.month = 11;
                                        this.year--;
                                    }
                                    this.loadDates();
                                },

                                init() {
                                    this.loadDates();
                                }
                            };
                        }
                    </script>

                </div>

                {{-- Statistik Kartu --}}
                <div class="bg-gradient-to-br from-green-400 to-green-600 rounded-2xl h-[180px] flex flex-col shadow-lg">
                    <p class="text-gray-600 text-lg font-bold mt-6 ml-5">Hadir</p>
                    <p class="text-5xl font-extrabold text-white mt-6 ml-5">{{ $jumlahHadir }}</p>
                </div>

                <div class="bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-2xl h-[180px] flex flex-col shadow-lg">
                    <p class="text-gray-600 text-lg font-bold mt-6 ml-5">Izin</p>
                    <p class="text-5xl font-extrabold text-white mt-6 ml-5">{{ $jumlahIzin }}</p>
                </div>

                <div class="bg-gradient-to-br from-red-400 to-red-600 rounded-2xl h-[180px] flex flex-col shadow-lg">
                    <p class="text-gray-600 text-lg font-bold mt-6 ml-5">Sakit</p>
                    <p class="text-5xl font-extrabold text-white mt-6 ml-5">{{ $jumlahSakit }}</p>
                </div>

                {{-- LIST USER (Admin only) --}}
                @if ($currentUser->role === 'Admin')
                    <div
                        class="bg-white border border-green-500 col-span-3 row-span-3 col-start-3 row-start-3 rounded-2xl p-6 overflow-y-auto -mt-23">
                        <h2 class="text-2xl font-bold text-gray-800 mb-3">Daftar Pengajar & Keuangan</h2>

                        <div class="grid grid-cols-2 gap-4">
                            @foreach ($users as $usr)
                                <div
                                    class="bg-gradient-to-br from-green-50 to-white border border-green-300 rounded-xl p-4 shadow hover:shadow-lg transition-all duration-300">
                                    <p class="text-lg font-bold text-gray-800">{{ $usr->username }}</p>
                                    <p class="text-sm text-gray-600 mt-1">Ponpes: <span
                                            class="font-semibold">{{ $usr->nama_ponpes }}</span></p>
                                    <p class="text-sm text-gray-600">Role: <span
                                            class="font-semibold">{{ $usr->role }}</span></p>
                                    <p class="text-sm text-gray-500 mt-1">Email: {{ $usr->email }}</p>

                                    <a href="{{ route('dashboard.absensi.riwayat', ['user_id' => $usr->id_user]) }}"
                                        class="mt-3 inline-block bg-green-600 text-white px-3 py-1 rounded-lg text-sm font-bold hover:bg-green-700">
                                        Lihat Detail
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif  
            </div>
        </main>
    </div>
@endsection
