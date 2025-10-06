<aside class="w-56 h- page-bg text-white flex flex-col rounded-e-md">
    <div class="p-4 text-2xl font-bold border-b border-gray-700">
        {{ $title ?? 'Menu' }}
    </div>

    <nav class="flex-1 p-4 space-y-2">
        <div class="my-6 form-label">Pengurus</div>
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
            Sangksi
        </a>

        <a href="{{ route('notulensi') }}"
            class="block px-3 py-2 rounded transition 
          {{ request()->routeIs('notulensi') 
              ? 'bg-active text-white' 
              : 'bg-mint hover:bg-active hover:text-white' }}">
            Notulensi
        </a>

        <div class="my-4 form-label">Management</div>

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