@props(['footer' => null])

@php
$footer = $footer ?? (object) [
    'logo' => 'default-logo.png',
    'instagram' => '#',
    'whatsapp' => '-',
    'alamat' => '-',
    'copyright' => '© ' . date('Y')
];
@endphp

<h1 class="text-white text-4xl">FOOTER MASUK</h1>
<footer id="contact" class="bg-[#244b3f] text-white py-10 px-6 text-sm">
    <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-10 items-start">

        <div class="flex justify-center md:justify-start">
            <img src="{{ asset('uploads/footer/' . $footer->logo) }}" class="w-44 h-auto">
        </div>

        <div>
            <h4 class="font-semibold mb-2">Informasi Kontak</h4>
            <p>Instagram : {{ $footer->instagram }}</p>
            <p>WhatsApp : {{ $footer->whatsapp }}</p>
            <p>{{ $footer->alamat }}</p>
        </div>

        <div>
            <h4 class="font-semibold mb-2">Media Sosial</h4>
            <div class="flex gap-4 mt-1">
                <a href="{{ $footer->instagram }}">
                    <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/instagram.svg"
                         class="w-6 h-6 invert brightness-200">
                </a>
            </div>
        </div>

    </div>

    <div class="text-center text-gray-300 mt-8 border-t border-gray-600 pt-4 text-xs">
        {{ $footer->copyright }}
    </div>
</footer>
