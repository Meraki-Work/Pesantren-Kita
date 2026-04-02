<!-- Tambahkan Alpine.js di head jika belum ada -->
<script src="//unpkg.com/alpinejs" defer></script>

<!-- Sidebar -->
<aside id="sidebar"
    class="fixed inset-y-0 left-0 z-40 w-56 transform -translate-x-full transition-transform duration-300 ease-in-out 
           bg-gradient-to-b from-emerald-900 to-emerald-700 text-white flex flex-col rounded-e-md 
           md:translate-x-0 md:relative md:rounded-none md:w-56">

    <!-- Header dengan user info -->
    <div class="p-4 border-b border-gray-700">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-full bg-emerald-500 flex items-center justify-center">
                <span class="font-bold text-lg">
                    {{ strtoupper(substr(Auth::user()->username, 0, 1)) }}
                </span>
            </div>
            <div>
                <p class="font-medium truncate">{{ Auth::user()->username }}</p>
                <p class="text-xs text-gray-300 mt-1">
                    @php
                    $roleColors = [
                    'Admin' => 'bg-red-500',
                    'Pengajar' => 'bg-blue-500',
                    'Keuangan' => 'bg-yellow-500',
                    'Super' => 'bg-purple-500'
                    ];
                    $roleColor = $roleColors[Auth::user()->role] ?? 'bg-gray-500';
                    @endphp
                    <span class="px-2 py-1 {{ $roleColor }} rounded-full text-xs">
                        {{ Auth::user()->role }}
                    </span>
                </p>
            </div>
        </div>
    </div>

    <!-- Tombol close di mobile -->
    <div class="p-4 text-xl font-bold flex items-center justify-between border-b border-gray-700">
        Menu Navigasi
        <button id="closeSidebar" class="md:hidden text-white text-2xl focus:outline-none">&times;</button>
    </div>

    <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
        <!-- Menu untuk semua role -->


        <!-- Menu berdasarkan role -->
        @php
        $userRole = Auth::user()->role;
        @endphp

        @if(in_array($userRole, ['Admin', 'Pengajar','Keuangan']))
        <div class="my-4 form-label text-gray-300 uppercase text-xs tracking-wider">Utama</div>

        <a href="{{ route('dashboard') }}"
            class="flex items-center px-3 py-2 rounded transition 
            {{ request()->routeIs('dashboard') 
                ? 'bg-emerald-600 text-white' 
                : 'bg-emerald-800/50 hover:bg-emerald-600 hover:text-white' }} ">
            <i class="fas fa-home w-5 mr-2 text-center"></i>Dashboard
        </a>
        @endif

        <!-- Menu untuk Admin dan Super -->
        @if(in_array($userRole, ['Admin']))
        <div class="my-4 form-label text-gray-300 uppercase text-xs tracking-wider">Administrasi</div>

        <a href="{{ route('kepegawaian.index') }}"
            class="flex items-center px-3 py-2 rounded transition 
            {{ request()->routeIs('kepegawaian.index') 
                ? 'bg-emerald-600 text-white' 
                : 'bg-emerald-800/50 hover:bg-emerald-600 hover:text-white' }}">
            <i class="fas fa-users w-5 mr-2 text-center"></i>Kepegawaian
        </a>

        <a href="{{ route('admin.landing-content.index') }}"
            class="flex items-center px-3 py-2 rounded transition 
            {{ request()->routeIs('admin.landing-content.index') 
                ? 'bg-emerald-600 text-white' 
                : 'bg-emerald-800/50 hover:bg-emerald-600 hover:text-white' }}">
            <i class="fas fa-globe w-5 mr-2 text-center"></i>Publikasi
        </a>
        @endif

        <!-- Menu untuk Admin, Pengajar, dan Keuangan -->
        @if(in_array($userRole, ['Admin', 'Pengajar', 'Keuangan']))
        <a href="{{ route('sanksi.index') }}"
            class="flex items-center px-3 py-2 rounded transition 
            {{ request()->routeIs('sanksi.index') 
                ? 'bg-emerald-600 text-white' 
                : 'bg-emerald-800/50 hover:bg-emerald-600 hover:text-white' }}">
            <i class="fas fa-exclamation-triangle w-5 mr-2 text-center"></i>Sanksi
        </a>
        @endif

        <!-- Menu untuk Admin dan Pengajar -->
        @if(in_array($userRole, ['Admin', 'Pengajar']))
        <a href="{{ route('notulen.index') }}"
            class="flex items-center px-3 py-2 rounded transition 
            {{ request()->routeIs('notulen.index') 
                ? 'bg-emerald-600 text-white' 
                : 'bg-emerald-800/50 hover:bg-emerald-600 hover:text-white' }}">
            <i class="fas fa-clipboard-list w-5 mr-2 text-center"></i>Notulensi
        </a>
        @endif

        <!-- Menu Keuangan: Admin dan Keuangan -->
        @if(in_array($userRole, ['Admin', 'Keuangan']))
        <div class="my-4 form-label text-gray-300 uppercase text-xs tracking-wider">Keuangan</div>

        <a href="{{ route('keuangan.index') }}"
            class="flex items-center px-3 py-2 rounded transition 
            {{ request()->routeIs('keuangan.index') 
                ? 'bg-emerald-600 text-white' 
                : 'bg-emerald-800/50 hover:bg-emerald-600 hover:text-white' }}">
            <i class="fas fa-money-bill-wave w-5 mr-2 text-center"></i>Keuangan
        </a>

        <a href="{{ route('kategori.index') }}"
            class="flex items-center px-3 py-2 rounded transition 
            {{ request()->routeIs('kategori.index') 
                ? 'bg-emerald-600 text-white' 
                : 'bg-emerald-800/50 hover:bg-emerald-600 hover:text-white' }}">
            <i class="fas fa-tags w-5 mr-2 text-center"></i>Kategori
        </a>
        @endif

        <!-- Menu Santri: Admin dan Pengajar -->
        @if(in_array($userRole, ['Admin', 'Pengajar']))
        <div class="my-4 form-label text-gray-300 uppercase text-xs tracking-wider">Santri</div>

        <!-- Dropdown Santri & Kompetensi -->
        <div x-data="{ open: {{ request()->routeIs('santri.*') || request()->routeIs('laundry.*') ? 'true' : 'false' }} }" class="relative">
            <button @click="open = !open"
                class="w-full flex justify-between items-center px-3 py-2 rounded transition 
                {{ request()->routeIs('santri.*') || request()->routeIs('laundry.*') 
                    ? 'bg-emerald-600 text-white' 
                    : 'bg-emerald-800/50 hover:bg-emerald-600 hover:text-white' }}">
                <span class="flex items-center">
                    <i class="fas fa-user-graduate w-5 mr-2 text-center"></i>Santri
                </span>
                <span x-bind:class="{'rotate-180': open}" class="transition-transform">&#9662;</span>
            </button>

            <div x-show="open" x-transition class="mt-1 ml-6 space-y-1">
                <a href="{{ route('santri.index') }}"
                    class="flex items-center px-3 py-2 rounded transition 
                    {{ request()->routeIs('santri.index') 
                        ? 'bg-emerald-600 text-white' 
                        : 'bg-emerald-800/50 hover:bg-emerald-600 hover:text-white' }}">
                    <i class="fas fa-list w-5 mr-2 text-center"></i>Data Santri
                </a>
                <a href="{{ route('santri.kompetensi.index') }}"
                    class="flex items-center px-3 py-2 rounded transition 
                    {{ request()->routeIs('santri.kompetensi.index') 
                        ? 'bg-emerald-600 text-white' 
                        : 'bg-emerald-800/50 hover:bg-emerald-600 hover:text-white' }}">
                    <i class="fas fa-award w-5 mr-2 text-center"></i>Kompetensi
                </a>
                <a href="{{ route('laundry.index') }}"
                    class="flex items-center px-3 py-2 rounded transition 
                    {{ request()->routeIs('laundry.index') 
                        ? 'bg-emerald-600 text-white' 
                        : 'bg-emerald-800/50 hover:bg-emerald-600 hover:text-white' }}">
                    <i class="fas fa-tshirt w-5 mr-2 text-center"></i>Laundry
                </a>
            </div>
        </div>
        @endif

        <!-- Menu Inventaris: Admin, Pengajar, dan Keuangan -->
        @if(in_array($userRole, ['Admin', 'Pengajar', 'Keuangan']))
        <div class="my-4 form-label text-gray-300 uppercase text-xs tracking-wider">Aset</div>

        <a href="{{ route('inventaris.index') }}"
            class="flex items-center px-3 py-2 rounded transition 
            {{ request()->routeIs('inventaris.index') 
                ? 'bg-emerald-600 text-white' 
                : 'bg-emerald-800/50 hover:bg-emerald-600 hover:text-white' }}">
            <i class="fas fa-boxes w-5 mr-2 text-center"></i>Inventaris
        </a>
        @endif

        <!-- Menu khusus Super Admin -->
        @if($userRole == 'Super')
        <div class="my-4 form-label text-gray-300 uppercase text-xs tracking-wider">Super Admin</div>

        <a href="{{ route('super.ponpes.index') }}"
            class="flex items-center px-3 py-2 rounded transition 
            {{ request()->routeIs('super.ponpes.index') 
                ? 'bg-purple-600 text-white' 
                : 'bg-purple-800/50 hover:bg-purple-600 hover:text-white' }}">
            <i class="fas fa-mosque w-5 mr-2 text-center"></i>Pondok Pesantren
        </a>

        <a href="{{ route('super.users.index') }}"
            class="flex items-center px-3 py-2 rounded transition 
            {{ request()->routeIs('super.users.index') 
                ? 'bg-purple-600 text-white' 
                : 'bg-purple-800/50 hover:bg-purple-600 hover:text-white' }}">
            <i class="fas fa-users-cog w-5 mr-2 text-center"></i>Manajemen User
        </a>
        <a href="{{ route('super.langganan.index') }}"
            class="flex items-center px-3 py-2 rounded transition 
            {{ request()->routeIs('super.langganan.*') 
        ? 'bg-purple-600 text-white' 
        : 'bg-purple-800/50 hover:bg-purple-600 hover:text-white' }}">
            <i class="fas fa-users-cog w-5 mr-2 text-center"></i>Manajemen Langganan
        </a>
        <a href="{{ route('super.plan.index') }}"
            class="flex items-center px-3 py-2 rounded transition 
            {{ request()->routeIs('super.plan.*') 
        ? 'bg-purple-600 text-white' 
        : 'bg-purple-800/50 hover:bg-purple-600 hover:text-white' }}">
            <i class="fas fa-users-cog w-5 mr-2 text-center"></i>Manajemen Plan
        </a>
        <a href="{{ route('super.logs.index') }}"
            class="flex items-center px-3 py-2 rounded transition 
            {{ request()->routeIs('super.logs.index') 
                ? 'bg-purple-600 text-white' 
                : 'bg-purple-800/50 hover:bg-purple-600 hover:text-white' }}">
            <i class="fas fa-clipboard-list w-5 mr-2 text-center"></i>Log Sistem
        </a>
        @endif

        <!-- Jika tidak ada menu yang sesuai -->
        @if(!in_array($userRole, ['Admin', 'Pengajar', 'Keuangan', 'Super']))
        <div class="my-4 p-3 bg-yellow-800/30 rounded border border-yellow-600">
            <p class="text-sm text-yellow-200 flex items-center">
                <i class="fas fa-info-circle mr-2"></i>
                Role Anda belum memiliki akses menu khusus.
            </p>
        </div>
        @endif

    </nav>

    <!-- Footer dengan logout -->
    <div class="p-4 border-t border-gray-700">
        <div class="mb-3 text-xs text-gray-400 space-y-1">
            <!-- Perbaikan: Gunakan helper Carbon untuk parsing tanggal -->
            @php
            $createdAt = Auth::user()->created_at;
            // Jika created_at adalah string, convert ke Carbon
            if (is_string($createdAt)) {
            $createdAt = \Carbon\Carbon::parse($createdAt);
            }
            $loginDate = $createdAt ? $createdAt->format('d M Y') : 'N/A';
            @endphp
            <p class="flex items-center">
                <i class="fas fa-calendar-plus w-4 mr-2"></i>
                Bergabung: {{ $loginDate }}
            </p>
            <p class="flex items-center">
                <i class="fas fa-user-tag w-4 mr-2"></i>
                Role: {{ Auth::user()->role }}
            </p>
            <p class="flex items-center">
                <i class="fas fa-envelope w-4 mr-2"></i>
                {{ Auth::user()->email ?? 'No email' }}
            </p>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="w-full flex items-center justify-center px-3 py-2 rounded 
                           bg-red-600 hover:bg-red-700 text-white transition-colors duration-200">
                <i class="fas fa-sign-out-alt mr-2"></i>Logout
            </button>
        </form>
    </div>
</aside>

<!-- Tombol Toggle di Mobile -->
<button id="openSidebar"
    class="fixed top-4 left-4 z-10 md:hidden bg-emerald-800 text-white p-2 rounded-full w-10 h-10 shadow-lg focus:outline-none flex items-center justify-center">
    ☰
</button>

<!-- JS Toggle -->
<script>
    const sidebar = document.getElementById('sidebar');
    const openBtn = document.getElementById('openSidebar');
    const closeBtn = document.getElementById('closeSidebar');

    openBtn.addEventListener('click', () => sidebar.classList.remove('-translate-x-full'));
    closeBtn.addEventListener('click', () => sidebar.classList.add('-translate-x-full'));

    // Close sidebar ketika klik di luar
    document.addEventListener('click', (e) => {
        if (!sidebar.contains(e.target) && !openBtn.contains(e.target) && window.innerWidth < 768) {
            sidebar.classList.add('-translate-x-full');
        }
    });

    // Tambahkan keyboard shortcut (ESC untuk close)
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && window.innerWidth < 768) {
            sidebar.classList.add('-translate-x-full');
        }
    });
</script>