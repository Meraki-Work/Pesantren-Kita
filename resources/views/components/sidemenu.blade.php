<!-- 
 Nama file   : sidebar.blade.php
 Deskripsi   : File ini berfungsi untuk menampilkan navigasi utama (sidebar) pada bagian kiri pada halaman kepegawaian
 Dibuat oleh : Anastasya Floresha Dominiq Ginting - NIM: 3312401068
 Tanggal     : 14 November 2025
--> 

<!-- Tambahkan Alpine.js di head jika belum ada -->
<script src="//unpkg.com/alpinejs" defer></script>

<!-- Sidebar -->
<aside id="sidebar"
    class="fixed inset-y-0 left-0 z-40 w-56 transform -translate-x-full transition-transform duration-300 ease-in-out 
           bg-gradient-to-b from-emerald-900 to-emerald-700 text-white flex flex-col rounded-e-md 
           md:translate-x-0 md:relative md:rounded-none md:w-56">
    <div class="p-4 text-2xl font-bold border-b border-gray-700 flex items-center justify-between">
        {{ $title ?? 'Menu' }}
        <!-- Tombol close di mobile -->
        <button id="closeSidebar" class="md:hidden text-white text-2xl focus:outline-none">&times;</button>
    </div>

    <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
        <div class="my-6 form-label text-gray-300 uppercase text-xs tracking-wider">Pengurus</div>

        <a href="{{ route('dashboard') }}"
            class="block px-3 py-2 rounded transition 
            {{ request()->routeIs('dashboard') 
                ? 'bg-active text-white' 
                : 'bg-mint hover:bg-active hover:text-white' }} ">
            Dashboard
        </a>
        <!-- Kepegawaian -->
        <a href="{{ route('kepegawaian.index') }}"
            class="block px-3 py-2 rounded transition 
            {{ request()->routeIs('users.index') 
                ? 'bg-active text-white' 
                : 'bg-mint hover:bg-active hover:text-white' }}">
            Kepegawaian
        </a>
        <!-- Kelola Landing Page -->
        <a href="{{ route('admin.landing-content.index') }}"
            class="block px-3 py-2 rounded transition 
            {{ request()->routeIs('admin.landing-content') 
                ? 'bg-active text-white' 
                : 'bg-mint hover:bg-active hover:text-white' }}">
            Kelola Landing Page
        </a>
        <a 
            class="block px-3 py-2 rounded transition 
            {{ request()->routeIs('settings') 
                ? 'bg-active text-white' 
                : 'bg-mint hover:bg-active hover:text-white' }}">
            Sanksi
        </a>

        <a 
            class="block px-3 py-2 rounded transition 
    {{ request()->routeIs('notulen.index') 
        ? 'bg-active text-white' 
        : 'bg-mint hover:bg-active hover:text-white' }}">
            Notulensi
        </a>

        <div class="my-4 form-label text-gray-300 uppercase tracking-wider">Manajemen</div>

        <a 
            class="block px-3 py-2 rounded transition 
            {{ request()->routeIs('keuangan') 
                ? 'bg-active text-white' 
                : 'bg-mint hover:bg-active hover:text-white' }}">
            Keuangan
        </a>

        <!-- Dropdown Santri & Kompetensi -->
        <div x-data="{ open: {{ request()->routeIs('santri.*') || request()->routeIs('laundry.*') ? 'true' : 'false' }} }" class="relative">
            <button @click="open = !open"
                class="w-full flex justify-between items-center px-3 py-2 rounded transition 
                {{ request()->routeIs('santri.*') || request()->routeIs('laundry.*') 
                    ? 'bg-active text-white' 
                    : 'bg-mint hover:bg-active hover:text-white' }}">
                Santri
                <span x-bind:class="{'rotate-180': open}" class="transition-transform">&#9662;</span>
            </button>

            <div x-show="open" x-transition class="mt-1 ml-2 space-y-1">
                <a 
                    class="block px-3 py-2 rounded transition 
                    {{ request()->routeIs('santri.index') 
                        ? 'bg-active text-white' 
                        : 'bg-mint hover:bg-active hover:text-white' }}">
                    Santri
                </a>
                <a 
                    class="block px-3 py-2 rounded transition 
                    {{ request()->routeIs('laundry.index') 
                        ? 'bg-active text-white' 
                        : 'bg-mint hover:bg-active hover:text-white' }}">
                    Laundry
                </a>
            </div>
        </div>

        <a 
            class="block px-3 py-2 rounded transition 
            {{ request()->routeIs('inventaris.index') 
                ? 'bg-active text-white' 
                : 'bg-mint hover:bg-active hover:text-white' }}">
            Inventaris
        </a>
    </nav>

    <div class="p-4 border-t border-gray-700">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-left px-3 py-2 rounded hover:bg-red-600">
                Logout
            </button>
        </form>
    </div>
</aside>

<!-- Tombol Toggle di Mobile -->
<button id="openSidebar"
    class="fixed top-4 left-4 z-10 md:hidden bg-emerald-800 text-white p-2 rounded-full w-10 h-10 shadow-lg focus:outline-none">
    ☰
</button>

<!-- JS Toggle -->
<script>
    const sidebar = document.getElementById('sidebar');
    const openBtn = document.getElementById('openSidebar');
    const closeBtn = document.getElementById('closeSidebar');

    openBtn.addEventListener('click', () => sidebar.classList.remove('-translate-x-full'));
    closeBtn.addEventListener('click', () => sidebar.classList.add('-translate-x-full'));
</script>