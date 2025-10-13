<section class="bg-[#fdfdfd] py-16 px-4">
    <div class="max-w-5xl mx-auto bg-[#d9f7e2] rounded-2xl shadow-inner py-10 px-6 md:px-12">
        {{-- Fitur Utama --}}
        <h2 class="text-2xl md:text-3xl font-semibold text-[#244b3f] mb-4 text-center">
            Fitur-Fitur Utama PesantrenKita
        </h2>
        <p class="text-gray-700 max-w-3xl mx-auto mb-10 leading-relaxed text-center">
            PesantrenKita dilengkapi dengan berbagai fitur manajemen pondok pesantren yang dirancang
            untuk mempermudah aktivitas administrasi, akademik, dan operasional sehari-hari.
        </p>

        {{-- Card Fitur --}}
        <div class="flex flex-wrap justify-center gap-6">
            @php
                $fitur = [
                    ['icon' => 'person', 'title' => 'Manajemen Data Santri', 'desc' => 'Kelola biodata dan perkembangan kompetensi santri'],
                    ['icon' => 'schedule', 'title' => 'Absensi', 'desc' => 'Pencatatan kehadiran pegawai lengkap dengan rekap bulanan'],
                    ['icon' => 'badge', 'title' => 'Kepegawaian', 'desc' => 'Pengelolaan data pegawai dan jabatan dengan mudah'],
                    ['icon' => 'payments', 'title' => 'Manajemen Keuangan', 'desc' => 'Kelola keuangan dan laporan keuangan pondok pesantren'],
                    ['icon' => 'description', 'title' => 'Laporan', 'desc' => 'Menyediakan laporan kehadiran, keuangan, dan data santri'],
                    ['icon' => 'inventory_2', 'title' => 'Manajemen Inventaris', 'desc' => 'Mengelola aset dan perlengkapan pondok pesantren'],
                    ['icon' => 'note_alt', 'title' => 'Notulensi', 'desc' => 'Mencatat dan merekap hasil rapat secara terorganisir'],
                    ['icon' => 'manage_accounts', 'title' => 'User Role', 'desc' => 'Pengaturan akses untuk admin dan pengajar'],
                ];
            @endphp

            @foreach ($fitur as $item)
                <div class="bg-white rounded-lg shadow-md p-6 w-[220px] h-[180px] hover:shadow-lg transition flex flex-col items-center justify-center text-center">
                    <span class="material-symbols-outlined text-[#244b3f] text-5xl mb-3">
                        {{ $item['icon'] }}
                    </span>
                    <h3 class="font-semibold text-[#244b3f] mb-1">{{ $item['title'] }}</h3>
                    <p class="text-sm text-gray-600">{{ $item['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Kelebihan --}}
<div class="max-w-5xl mx-auto bg-[#D9F7E2] rounded-2xl shadow-md py-10 px-6 md:px-12 mt-16">
    <h2 class="text-2xl md:text-3xl font-semibold text-[#244b3f] mb-10 text-center">
        Mengapa Harus PesantrenKita?
    </h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 justify-items-center">
        <div class="bg-[#A8E6CF] w-[180px] h-[150px] rounded-lg shadow-md flex flex-col items-center justify-center hover:scale-105 transition-transform duration-200">
            <span class="material-symbols-outlined text-black text-5xl mb-2">desktop_windows</span>
            <h3 class="text-sm font-medium text-black text-center">Mudah & Terpusat</h3>
        </div>

        <div class="bg-[#A8E6CF] w-[180px] h-[150px] rounded-lg shadow-md flex flex-col items-center justify-center hover:scale-105 transition-transform duration-200">
            <span class="material-symbols-outlined text-black text-5xl mb-2">bolt</span>
            <h3 class="text-sm font-medium text-black text-center">Efisien & Cepat</h3>
        </div>

        <div class="bg-[#A8E6CF] w-[180px] h-[150px] rounded-lg shadow-md flex flex-col items-center justify-center hover:scale-105 transition-transform duration-200">
            <span class="material-symbols-outlined text-black text-5xl mb-2">diversity_3</span>
            <h3 class="text-sm font-medium text-black text-center">Transparan & Terhubung</h3>
        </div>
    </div>
</div>
</section>
