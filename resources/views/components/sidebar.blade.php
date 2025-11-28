<!-- 
 Nama file   : sidebar.blade.php
 Deskripsi   : File ini berfungsi untuk menampilkan navigasi utama (sidebar) pada bagian kiri pada halaman kepegawaian
 Dibuat oleh : Anastasya Floresha Dominiq Ginting - NIM: 3312401068
 Tanggal     : 14 November 2025
--> 

<script src="//unpkg.com/alpinejs" defer></script>

<aside id="sidebar"
    class="fixed inset-y-0 left-0 z-40 w-56 transform -translate-x-full transition-transform duration-300 ease-in-out 
           bg-[#344E41] text-white flex flex-col rounded-e-md 
           md:translate-x-0 md:relative md:rounded-none md:w-56">

    <div class="p-4 text-2xl font-bold border-b border-gray-700 flex items-center justify-between">
        PesantrenKita
        <button id="closeSidebar" class="md:hidden text-white text-2xl focus:outline-none">&times;</button>
    </div>

    <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
        <div class="my-3 form-label text-gray-300 uppercase text-[10px] tracking-wider">Pengurus</div>

        <!-- Dashboard -->
        <a href="#"
            class="block px-3 py-2 rounded transition 
            bg-mint hover:bg-active hover:text-white">
            Dashboard
        </a>

        <!-- Kepegawaian -->
        <a href="{{ route('admin.kepegawaian') }}"
            class="block px-3 py-2 rounded transition
            {{ request()->routeIs('admin.kepegawaian') 
                ? 'bg-[#A8E6CF] text-[#344E41] font-semibold' 
                : 'hover:bg-[#A8E6CF]/40' }}">
            Kepegawaian
        </a>

        <!-- Sanksi (#) -->
        <a href="#"
            class="block px-3 py-2 rounded transition 
            bg-mint hover:bg-active hover:text-white">
            Sanksi
        </a>

        <!-- Notulensi (#) -->
        <a href="#"
            class="block px-3 py-2 rounded transition 
            bg-mint hover:bg-active hover:text-white">
            Notulensi
        </a>

        <!-- Kelola Landing Page -->
        <a href="{{ route('admin.landing.index') }}"
            class="block px-3 py-2 rounded transition
            {{ request()->routeIs('admin.landing.*') 
                ? 'bg-[#A8E6CF] text-[#344E41] font-semibold' 
                : 'hover:bg-[#A8E6CF]/40' }}">
            Kelola Landing Page
        </a>

        <div class="my-3 form-label text-gray-300 uppercase text-[10px] tracking-wider">Manajemen</div>

        <!-- Keuangan (#) -->
        <a href="#"
            class="block px-3 py-2 rounded transition 
            bg-mint hover:bg-active hover:text-white">
            Keuangan
        </a>

        <!-- Dropdown Santri (# semua) -->
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open"
                class="w-full flex justify-between items-center px-3 py-2 rounded transition bg-mint hover:bg-active hover:text-white">
                Santri
                <span x-bind:class="{'rotate-180': open}" class="transition-transform">&#9662;</span>
            </button>

            <div x-show="open" x-transition class="mt-1 ml-2 space-y-1">
                <a href="#" class="block px-3 py-2 rounded bg-mint hover:bg-active hover:text-white">Santri</a>
                <a href="#" class="block px-3 py-2 rounded bg-mint hover:bg-active hover:text-white">Laundry</a>
            </div>
        </div>

        <!-- Inventaris (#) -->
        <a href="#"
            class="block px-3 py-2 rounded transition 
            bg-mint hover:bg-active hover:text-white">
            Inventaris
        </a>
    </nav>

    <div class="p-4 border-t border-gray-700">
        <button class="w-full text-left px-3 py-2 rounded hover:bg-red-600">
            Logout
        </button>
    </div>
</aside>

<button id="openSidebar"
    class="fixed top-4 left-4 z-10 md:hidden bg-emerald-800 text-white p-2 rounded-full w-10 h-10 shadow-lg">
    ☰
</button>

<script>
    const sidebar = document.getElementById('sidebar');
    const openBtn = document.getElementById('openSidebar');
    const closeBtn = document.getElementById('closeSidebar');

    openBtn.addEventListener('click', () => sidebar.classList.remove('-translate-x-full'));
    closeBtn.addEventListener('click', () => sidebar.classList.add('-translate-x-full'));
</script>

