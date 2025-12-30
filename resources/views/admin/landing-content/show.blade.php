@extends('index')

@section('title', 'Detail Konten Landing Page')

@section('content')
<div class="min-h-screen bg-gray-50 p-4 md:p-6">
    <div class="max-w-6xl mx-auto">
        <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Detail Konten Landing Page</h1>
                <p class="text-gray-600 mt-1">Informasi lengkap konten landing page</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.landing-content.edit', $landingContent->id_content) }}" class="inline-flex items-center px-4 py-2 border border-amber-300 rounded-md shadow-sm text-sm font-medium text-amber-700 bg-amber-50 hover:bg-amber-100">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Edit
                </a>
                <a href="{{ route('admin.landing-content.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali
                </a>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 space-y-6">
                        <div class="border border-gray-100 rounded-lg p-4 bg-gray-50">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Konten</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500">Judul</p>
                                    <p class="text-gray-900 font-medium">{{ $landingContent->title ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Tipe Konten</p>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-sm font-medium {{ $landingContent->content_type == 'carousel' ? 'bg-blue-100 text-blue-800' : ($landingContent->content_type == 'about_founder' ? 'bg-green-100 text-green-800' : ($landingContent->content_type == 'about_leader' ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-800')) }}">{{ $landingContent->content_type }}</span>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-500">Pesantren</p>
                                    <p class="text-gray-900">{{ $landingContent->ponpes->nama_ponpes ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Posisi/Jabatan</p>
                                    <p class="text-gray-900">{{ $landingContent->position ?? '-' }}</p>
                                </div>

                                <div class="md:col-span-2">
                                    <p class="text-sm text-gray-500">Subjudul</p>
                                    <p class="text-gray-900">{{ $landingContent->subtitle ?? '-' }}</p>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-500">URL/Link</p>
                                    <p class="text-gray-900">@if($landingContent->url)<a href="{{ $landingContent->url }}" target="_blank" class="text-blue-600 hover:underline">{{ $landingContent->url }}</a>@else - @endif</p>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-500">Urutan Tampilan</p>
                                    <p class="text-gray-900">{{ $landingContent->display_order }}</p>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-500">Status</p>
                                    <p class="text-gray-900">{!! $landingContent->is_active ? '<span class="text-green-600 font-medium">Aktif</span>' : '<span class="text-red-600 font-medium">Nonaktif</span>' !!}</p>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-500">Dibuat</p>
                                    <p class="text-gray-900">{{ $landingContent->created_at->translatedFormat('d F Y H:i') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Diperbarui</p>
                                    <p class="text-gray-900">{{ $landingContent->updated_at->translatedFormat('d F Y H:i') }}</p>
                                </div>
                            </div>
                        </div>

                        @if($landingContent->description)
                        <div class="border border-gray-100 rounded-lg p-4">
                            <h3 class="text-md font-semibold text-gray-900 mb-3">Deskripsi Lengkap</h3>
                            <div class="prose max-w-none text-gray-800">{!! nl2br(e($landingContent->description)) !!}</div>
                        </div>
                        @endif
                    </div>

                    <div class="space-y-6">
                        <div class="border border-gray-100 rounded-lg overflow-hidden">
                            <div class="p-4 bg-gray-50">
                                <h3 class="font-semibold text-gray-900">Preview Gambar</h3>
                            </div>
                            <div class="p-4 text-center">
                                @if($landingContent->image)
                                <img src="{{ Storage::url($landingContent->image) }}" alt="{{ $landingContent->title }}" class="w-full h-auto rounded-lg object-cover" style="max-height:300px;">
                                <div class="mt-3">
                                    <a href="{{ Storage::url($landingContent->image) }}" target="_blank" class="inline-flex items-center px-3 py-2 border border-gray-300 rounded text-sm text-gray-700 hover:bg-gray-50">
                                        Lihat Full Size
                                    </a>
                                </div>
                                @else
                                <div class="py-8 text-center text-gray-400">
                                    <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <p class="mt-3">Tidak ada gambar</p>
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="border border-gray-100 rounded-lg p-4 bg-gray-50">
                            <h3 class="font-semibold text-gray-900 mb-3">Preview di Frontend</h3>
                            <p class="text-sm text-gray-600 mb-3">Konten ini akan muncul di:</p>
                            <ul class="list-disc pl-5 text-gray-700 mb-3">
                                @if($landingContent->content_type == 'carousel')
                                <li>Bagian carousel utama</li>
                                @elseif($landingContent->content_type == 'about_founder')
                                <li>Bagian tentang pendiri</li>
                                @elseif($landingContent->content_type == 'about_leader')
                                <li>Bagian tentang pimpinan</li>
                                @elseif($landingContent->content_type == 'footer')
                                <li>Bagian footer</li>
                                @elseif($landingContent->content_type == 'section_title')
                                <li>Judul section</li>
                                @endif
                            </ul>
                            @if($landingContent->ponpes)
                            <a href="{{ route('landing.show', $landingContent->ponpes_id) }}" target="_blank" class="inline-flex items-center px-3 py-2 border border-blue-300 rounded text-sm text-blue-700 hover:bg-blue-50 w-full justify-center">
                                Lihat di Landing Page
                            </a>
                            @endif
                        </div>

                        <div class="border border-gray-100 rounded-lg p-4">
                            <h3 class="font-semibold text-gray-900 mb-3">Aksi Cepat</h3>
                            <div class="space-y-3">
                                <form action="{{ route('admin.landing-content.destroy', $landingContent->id_content) }}" method="POST" onsubmit="return confirm('Hapus konten ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">Hapus Konten</button>
                                </form>

                                <form action="{{ route('admin.landing-content.toggle-status', $landingContent->id_content) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="is_active" value="{{ $landingContent->is_active ? 0 : 1 }}">
                                    <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 {{ $landingContent->is_active ? 'bg-yellow-500 text-white hover:bg-yellow-600' : 'bg-green-600 text-white hover:bg-green-700' }} rounded-md">{{ $landingContent->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection