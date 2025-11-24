<header class="fixed top-0 left-0 w-full bg-[#2e4f45] text-white shadow-md z-50">
  <div class="max-w-7xl mx-auto flex items-center justify-between px-8 py-2">
    <!-- Logo -->
    <div class="flex items-center">
      <img src="{{ asset('asset/logo_PesantrenKita_2.png') }}" 
           alt="Logo PesantrenKita" 
           class="w-48 h-auto" />
    </div>

    <!-- Menu -->
    <nav class="absolute left-1/2 transform -translate-x-1/2 flex gap-12 text-sm md:text-base font-medium tracking-wide">
      <a href="{{ route('landing_utama') }}" class="hover:text-[#a0f0c5] transition">Home</a>
      <a href="{{ route('landing_about') }}" class="hover:text-[#a0f0c5] transition">About</a>
      <a href="{{ route('contact') }}" class="hover:text-[#a0f0c5] transition">Contact</a>
    </nav>

    <!-- Tombol Login -->
    <div>
      <a href="{{ route('login') }}" 
         class="bg-[#a0f0c5] text-[#2e4f45] px-4 py-2 rounded-xl font-semibold hover:bg-white transition">
        Login
      </a>
    </div>
  </div>
</header>
