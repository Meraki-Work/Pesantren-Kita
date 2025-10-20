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
                : 'bg-mint hover:bg-active hover:text-white' }}">
            Dashboard
        </a>

        <a href="{{ route('kepegawaian') }}"
            class="block px-3 py-2 rounded transition 
            {{ request()->routeIs('users.index') 
                ? 'bg-active text-white' 
                : 'bg-mint hover:bg-active hover:text-white' }}">
            Kepegawaian
        </a>

        <a href="{{ route('settings') }}"
            class="block px-3 py-2 rounded transition 
            {{ request()->routeIs('settings') 
                ? 'bg-active text-white' 
                : 'bg-mint hover:bg-active hover:text-white' }}">
            Sanksi
        </a>

        <a href="{{ route('notulensi') }}"
            class="block px-3 py-2 rounded transition 
            {{ request()->routeIs('notulensi') 
                ? 'bg-active text-white' 
                : 'bg-mint hover:bg-active hover:text-white' }}">
            Notulensi
        </a>

        <div class="my-4 form-label text-gray-300 uppercase tracking-wider">Manajemen</div>

        <a href="{{ route('keuangan') }}"
            class="block px-3 py-2 rounded transition 
            {{ request()->routeIs('keuangan') 
                ? 'bg-active text-white' 
                : 'bg-mint hover:bg-active hover:text-white' }}">
            Keuangan
        </a>

        <a href="{{ route('santri') }}"
            class="block px-3 py-2 rounded transition 
            {{ request()->routeIs('santri.index') 
                ? 'bg-active text-white' 
                : 'bg-mint hover:bg-active hover:text-white' }}">
            Santri
        </a>

        <a href="{{ route('inventaris') }}"
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
