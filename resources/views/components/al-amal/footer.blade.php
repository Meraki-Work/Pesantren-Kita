<!-- 
 Nama file   : footer.blade.php
 Deskripsi   : File ini berfungsi untuk menampilkan bagian footer (bagian bawah halaman) dari website 
               Pondok Pesantren Al Amal Batam.
 Dibuat oleh : Anastasya Floresha Dominiq Ginting - NIM: 3312401068
 Tanggal     : 14 Oktober 2025
--> 

<footer id="contact" class="bg-[#244b3f] text-white py-10 px-6 text-sm">
    <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-10 items-start">

        <!-- Logo -->
        <div class="flex justify-center md:justify-start">
            <img src="{{ asset('asset/logo_al-amal.png') }}" 
                 alt="Logo PesantrenKita" 
                 class="w-44 h-auto" />
        </div>

        <!-- Informasi Kontak -->
        <div class="flex flex-col items-center md:items-start">
            <h4 class="font-semibold mb-2">Informasi Kontak</h4>
            <p>Instagram : @PesantrenKita</p>
            <p>WhatsApp : +6282385223438</p>
            <p>Politeknik Negeri Batam</p>
        </div>

        <!-- Media Sosial -->
        <div class="flex flex-col items-center md:items-start">
            <h4 class="font-semibold mb-2">Media Sosial</h4>
            <div class="flex gap-4 mt-1">
                <a href="#" class="hover:opacity-80 transition">
                    <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/instagram.svg" 
                         alt="Instagram" 
                         class="w-6 h-6 invert brightness-200" />
                </a>
                <a href="#" class="hover:opacity-80 transition">
                    <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/facebook.svg" 
                         alt="Facebook" 
                         class="w-6 h-6 invert brightness-200" />
                </a>
                <a href="#" class="hover:opacity-80 transition">
                    <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/tiktok.svg" 
                         alt="TikTok" 
                         class="w-6 h-6 invert brightness-200" />
                </a>
            </div>
        </div>
    </div>

    <div class="text-center text-gray-300 mt-8 border-t border-gray-600 pt-4 text-xs">
        &copy; 2025 PesantrenKita. All Rights Reserved.
    </div>
</footer>
