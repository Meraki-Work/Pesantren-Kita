@extends('index')

@section('title', 'Tambah Konten Landing Page')

@section('content')
<div class="flex bg-gray-100 min-h-screen">
    <x-sidemenu title="PesantrenKita" />
    
    <main class="flex-1 p-6 overflow-y-auto">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex justify-between items-center">
                <div>
                    @isset($type)
                    <h2 class="text-2xl font-bold text-gray-900">
                        @if($type == 'carousel')
                            Tambah Carousel
                        @elseif($type == 'about_founder')
                            Tambah Founder/Pendiri
                        @elseif($type == 'about_leader')
                            Tambah Leader/Pengurus
                        @elseif($type == 'about_vision')
                            Tambah Visi
                        @elseif($type == 'about_mision')
                            Tambah Misi
                        @elseif($type == 'footer')
                            Tambah Footer Link
                        @elseif($type == 'program_list')
                            Tambah Program
                        @elseif($type == 'gallery')
                            Tambah Galeri
                        @elseif($type == 'testimony')
                            Tambah Testimoni
                        @elseif($type == 'cta')
                            Tambah Call to Action
                        @else
                            Tambah Konten
                        @endif
                    </h2>
                    @else
                    <h2 class="text-2xl font-bold text-gray-900">Tambah Konten Landing Page</h2>
                    @endisset
                    
                    <p class="mt-1 text-sm text-gray-500">Isi form di bawah ini untuk menambahkan konten baru</p>
                </div>
                <a href="{{ route('admin.landing-content.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-150">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        @isset($type)
        <!-- Form -->
        <div class="bg-white rounded-xl overflow-hidden shadow-md">
            <form action="{{ route('admin.landing-content.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <!-- Hidden Fields -->
                <input type="hidden" name="content_type" value="{{ $type }}">
                
                @if(Auth::user()->role === 'admin' || Auth::user()->role === 'super_admin')
                <div class="p-6 border-b border-gray-200">
                    <label for="ponpes_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Pilih Pesantren <span class="text-red-500">*</span>
                    </label>
                    <select id="ponpes_id" name="ponpes_id" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="">-- Pilih Pesantren --</option>
                        @foreach($ponpesList as $ponpes)
                        <option value="{{ $ponpes->id_ponpes }}" {{ old('ponpes_id') == $ponpes->id_ponpes ? 'selected' : '' }}>
                            {{ $ponpes->nama_ponpes }}
                        </option>
                        @endforeach
                    </select>
                    @error('ponpes_id')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                @else
                <input type="hidden" name="ponpes_id" value="{{ Auth::user()->ponpes_id }}">
                @endif

                <div class="p-6">
                    <!-- Title Field -->
                    <div class="mb-6">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            @if($type == 'carousel')
                                Judul Carousel
                            @elseif($type == 'about_founder' || $type == 'about_leader')
                                Nama
                            @elseif($type == 'about_vision' || $type == 'about_mision')
                                Judul
                            @elseif($type == 'footer')
                                Judul Link (Instagram, WhatsApp, dll)
                            @else
                                Judul
                            @endif
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="title" 
                               id="title" 
                               required
                               value="{{ old('title') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 @enderror"
                               placeholder="@if($type == 'carousel') Masukkan judul carousel... @elseif($type == 'footer') Contoh: Instagram @else Masukkan judul... @endif">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Subtitle Field (for some types) -->
                    @if(in_array($type, ['carousel', 'about_vision', 'about_mision', 'program_list', 'cta']))
                    <div class="mb-6">
                        <label for="subtitle" class="block text-sm font-medium text-gray-700 mb-2">
                            Subjudul
                            @if(in_array($type, ['carousel', 'cta']))
                                <span class="text-red-500">*</span>
                            @endif
                        </label>
                        <input type="text" 
                               name="subtitle" 
                               id="subtitle" 
                               @if(in_array($type, ['carousel', 'cta'])) required @endif
                               value="{{ old('subtitle') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('subtitle') border-red-500 @enderror"
                               placeholder="Masukkan subjudul...">
                        @error('subtitle')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    @endif

                    <!-- Description Field -->
                    <div class="mb-6">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            @if($type == 'carousel')
                                Deskripsi Carousel
                            @elseif($type == 'about_founder' || $type == 'about_leader')
                                Biografi/Deskripsi
                            @elseif($type == 'about_vision')
                                Deskripsi Visi
                            @elseif($type == 'about_mision')
                                Deskripsi Misi
                            @elseif($type == 'footer')
                                URL/Link atau Deskripsi
                            @elseif($type == 'testimony')
                                Testimoni/Ulasan
                            @else
                                Deskripsi
                            @endif
                            @if(!in_array($type, ['footer'])) <span class="text-red-500">*</span> @endif
                        </label>
                        <textarea name="description" 
                                  id="description" 
                                  rows="4"
                                  @if(!in_array($type, ['footer'])) required @endif
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                                  placeholder="@if($type == 'footer') https://instagram.com/username @elseif($type == 'testimony') Masukkan testimoni dari alumni/orang tua santri... @else Masukkan deskripsi... @endif">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        
                        @if($type == 'footer')
                        <p class="mt-1 text-xs text-gray-500">
                            Untuk sosial media: 
                            Instagram: https://instagram.com/username<br>
                            WhatsApp: 6281234567890<br>
                            Email: info@ponpes.com
                        </p>
                        @endif
                    </div>

                    <!-- Image Upload Field -->
                    @if(in_array($type, ['carousel', 'about_founder', 'about_leader', 'about_vision', 'about_mision', 'gallery', 'program_list', 'testimony', 'cta']))
                    <div class="mb-6">
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                            Unggah Gambar
                            @if(in_array($type, ['carousel', 'gallery', 'about_founder', 'about_leader'])) 
                                <span class="text-red-500">*</span>
                            @endif
                        </label>
                        
                        <!-- Image Preview -->
                        <div class="mb-4 hidden" id="imagePreviewContainer">
                            <div class="relative inline-block">
                                <img id="imagePreview" 
                                     class="h-48 w-auto rounded-lg shadow-sm border border-gray-200">
                                <button type="button" 
                                        onclick="removeImage()"
                                        class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 transition-colors duration-150">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <!-- File Input -->
                        <div class="mt-1 flex items-center">
                            <label for="image" class="cursor-pointer w-full">
                                <div class="px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg hover:border-blue-400 hover:bg-blue-50 transition-colors duration-200">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <div class="text-sm text-gray-600">
                                            <span class="font-medium text-blue-600">Klik untuk mengunggah</span> atau drag & drop
                                        </div>
                                        <p class="text-xs text-gray-500">
                                            @if($type == 'carousel')
                                                PNG, JPG, GIF hingga 5MB. Rekomendasi: 1280×720px
                                            @elseif($type == 'about_founder' || $type == 'about_leader')
                                                PNG, JPG hingga 5MB. Rekomendasi: 400×500px
                                            @else
                                                PNG, JPG hingga 5MB
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <input type="file" 
                                       name="image" 
                                       id="image" 
                                       accept="image/*"
                                       @if(in_array($type, ['carousel', 'gallery', 'about_founder', 'about_leader'])) required @endif
                                       class="sr-only"
                                       onchange="previewImage(event)">
                            </label>
                        </div>
                        @error('image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    @endif

                    <!-- Position Field (for founder/leader) -->
                    @if(in_array($type, ['about_founder', 'about_leader']))
                    <div class="mb-6">
                        <label for="position" class="block text-sm font-medium text-gray-700 mb-2">
                            Posisi/Jabatan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="position" 
                               id="position" 
                               required
                               value="{{ old('position') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('position') border-red-500 @enderror"
                               placeholder="@if($type == 'about_founder') Contoh: Pendiri / Founder @elseif($type == 'about_leader') Contoh: Kepala Pesantren @endif">
                        @error('position')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    @endif

                    <!-- URL Field (for cta) -->
                    @if($type == 'cta')
                    <div class="mb-6">
                        <label for="url" class="block text-sm font-medium text-gray-700 mb-2">
                            URL Tujuan <span class="text-red-500">*</span>
                        </label>
                        <input type="url" 
                               name="url" 
                               id="url" 
                               required
                               value="{{ old('url') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('url') border-red-500 @enderror"
                               placeholder="https://example.com atau #section-id">
                        @error('url')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">
                            Gunakan URL lengkap untuk link eksternal atau #section-id untuk link dalam halaman
                        </p>
                    </div>
                    @endif

                    <!-- Display Order Field -->
                    <div class="mb-6">
                        <label for="display_order" class="block text-sm font-medium text-gray-700 mb-2">
                            Urutan Tampil <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               name="display_order" 
                               id="display_order" 
                               min="1"
                               required
                               value="{{ old('display_order', 1) }}"
                               class="w-32 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('display_order') border-red-500 @enderror">
                        @error('display_order')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">
                            Angka kecil akan ditampilkan lebih dulu. Minimal 1.
                        </p>
                    </div>

                    <!-- Active Status -->
                    <div class="mb-6">
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   name="is_active" 
                                   id="is_active" 
                                   value="1" 
                                   {{ old('is_active', true) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="is_active" class="ml-2 block text-sm text-gray-700">
                                Aktifkan konten ini
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('admin.landing-content.index') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-150">
                            Batal
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition-colors duration-150">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                            </svg>
                            @if($type == 'carousel')
                                Simpan Carousel
                            @elseif($type == 'about_founder')
                                Simpan Founder
                            @elseif($type == 'about_leader')
                                Simpan Leader
                            @elseif($type == 'about_vision')
                                Simpan Visi
                            @elseif($type == 'about_mision')
                                Simpan Misi
                            @elseif($type == 'footer')
                                Simpan Footer Link
                            @elseif($type == 'program_list')
                                Simpan Program
                            @elseif($type == 'gallery')
                                Simpan Foto Galeri
                            @elseif($type == 'testimony')
                                Simpan Testimoni
                            @elseif($type == 'cta')
                                Simpan Call to Action
                            @else
                                Simpan Konten
                            @endif
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Tips Section -->
        <div class="mt-8">
            <div class="rounded-lg bg-blue-50 p-6 border border-blue-200">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-blue-800 mb-2">
                            @if($type == 'carousel')
                                Tips Membuat Carousel
                            @elseif($type == 'about_founder')
                                Tips Membuat Profil Founder
                            @elseif($type == 'about_leader')
                                Tips Membuat Profil Leader
                            @elseif($type == 'about_vision')
                                Tips Membuat Visi
                            @elseif($type == 'about_mision')
                                Tips Membuat Misi
                            @elseif($type == 'footer')
                                Tips Membuat Footer Link
                            @elseif($type == 'program_list')
                                Tips Membuat Program
                            @elseif($type == 'gallery')
                                Tips Membuat Galeri
                            @elseif($type == 'testimony')
                                Tips Membuat Testimoni
                            @elseif($type == 'cta')
                                Tips Membuat Call to Action
                            @else
                                Tips Membuat Konten
                            @endif
                        </h3>
                        <ul class="list-disc pl-5 space-y-1 text-sm text-blue-700">
                            @if($type == 'carousel')
                                <li>Gunakan gambar berkualitas tinggi (minimal 1280×720 px)</li>
                                <li>Judul harus menarik dan informatif</li>
                                <li>Deskripsi singkat namun jelas</li>
                                <li>Maksimal 5-7 slide untuk performa optimal</li>
                            @elseif($type == 'about_founder' || $type == 'about_leader')
                                <li>Gunakan foto formal dengan latar belakang netral</li>
                                <li>Tulis biografi yang mencakup latar belakang pendidikan dan pengalaman</li>
                                <li>Sertakan posisi/jabatan yang jelas</li>
                                <li>Ukuran foto ideal: 400×500 px</li>
                            @elseif($type == 'about_vision')
                                <li>Visi harus mencerminkan tujuan jangka panjang pesantren</li>
                                <li>Gunakan kalimat yang inspiratif dan visioner</li>
                                <li>Maksimal 1-2 paragraf</li>
                            @elseif($type == 'about_mision')
                                <li>Misi harus spesifik, terukur, dan dapat dicapai</li>
                                <li>Susun secara berurutan dari yang paling penting</li>
                                <li>Gunakan poin-poin yang jelas</li>
                            @elseif($type == 'footer')
                                <li>Untuk Instagram: gunakan URL lengkap profil</li>
                                <li>Untuk WhatsApp: gunakan format 6281234567890</li>
                                <li>Untuk Email: gunakan format email@ponpes.com</li>
                                <li>Pastikan semua link valid dan aktif</li>
                            @elseif($type == 'gallery')
                                <li>Gunakan foto kegiatan terbaik dari pesantren</li>
                                <li>Berikan judul dan deskripsi yang menjelaskan kegiatan</li>
                                <li>Ukuran foto konsisten untuk tampilan yang rapi</li>
                            @elseif($type == 'testimony')
                                <li>Sertakan nama pemberi testimoni</li>
                                <li>Testimoni harus asli dan relevan</li>
                                <li>Tambahkan foto jika memungkinkan</li>
                            @elseif($type == 'program_list')
                                <li>Jelaskan program dengan detail dan manfaatnya</li>
                                <li>Sertakan gambar yang menarik dan relevan</li>
                                <li>Tambahkan URL untuk informasi lebih lanjut</li>
                            @elseif($type == 'cta')
                                <li>Gunakan kalimat yang persuasif dan jelas</li>
                                <li>Tentukan URL tujuan yang tepat</li>
                                <li>Tambahkan gambar pendukung jika perlu</li>
                            @else
                                <li>Pastikan konten relevan dengan pesantren</li>
                                <li>Gunakan bahasa yang baik dan benar</li>
                                <li>Periksa kembali sebelum menyimpan</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        @else
        <!-- Tampilan pilihan tipe konten (halaman awal create) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Carousel Card -->
            <div class="bg-white overflow-hidden shadow rounded-xl hover:shadow-lg transition-shadow duration-300 border border-gray-200">
                <div class="p-6 text-center">
                    <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-blue-100 mb-4">
                        <svg class="h-10 w-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Carousel</h3>
                    <p class="text-sm text-gray-500 mb-4">
                        Gambar slider utama di halaman depan dengan judul dan deskripsi
                    </p>
                    <div class="mt-4">
                        <a href="{{ route('admin.landing-content.create-type', 'carousel') }}" 
                           class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition-colors duration-150">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Tambah Carousel
                        </a>
                    </div>
                </div>
            </div>

            <!-- About Founder Card -->
            <div class="bg-white overflow-hidden shadow rounded-xl hover:shadow-lg transition-shadow duration-300 border border-gray-200">
                <div class="p-6 text-center">
                    <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-emerald-100 mb-4">
                        <svg class="h-10 w-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Founder/Pendiri</h3>
                    <p class="text-sm text-gray-500 mb-4">
                        Profil pendiri pesantren dengan foto dan biografi
                    </p>
                    <div class="mt-4">
                        <a href="{{ route('admin.landing-content.create-type', 'about_founder') }}" 
                           class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 transition-colors duration-150">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Tambah Founder
                        </a>
                    </div>
                </div>
            </div>

            <!-- About Leader Card -->
            <div class="bg-white overflow-hidden shadow rounded-xl hover:shadow-lg transition-shadow duration-300 border border-gray-200">
                <div class="p-6 text-center">
                    <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-cyan-100 mb-4">
                        <svg class="h-10 w-10 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Leader/Pengurus</h3>
                    <p class="text-sm text-gray-500 mb-4">
                        Profil pengurus pesantren (ketua, sekretaris, bendahara)
                    </p>
                    <div class="mt-4">
                        <a href="{{ route('admin.landing-content.create-type', 'about_leader') }}" 
                           class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-cyan-600 hover:bg-cyan-700 transition-colors duration-150">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Tambah Leader
                        </a>
                    </div>
                </div>
            </div>

            <!-- About Vision Card -->
            <div class="bg-white overflow-hidden shadow rounded-xl hover:shadow-lg transition-shadow duration-300 border border-gray-200">
                <div class="p-6 text-center">
                    <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-purple-100 mb-4">
                        <svg class="h-10 w-10 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Visi</h3>
                    <p class="text-sm text-gray-500 mb-4">
                        Tujuan jangka panjang dan cita-cita pesantren
                    </p>
                    <div class="mt-4">
                        <a href="{{ route('admin.landing-content.create-type', 'about_vision') }}" 
                           class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 transition-colors duration-150">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Tambah Visi
                        </a>
                    </div>
                </div>
            </div>

            <!-- About Mision Card -->
            <div class="bg-white overflow-hidden shadow rounded-xl hover:shadow-lg transition-shadow duration-300 border border-gray-200">
                <div class="p-6 text-center">
                    <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-pink-100 mb-4">
                        <svg class="h-10 w-10 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Misi</h3>
                    <p class="text-sm text-gray-500 mb-4">
                        Tujuan spesifik dan langkah-langkah untuk mencapai visi
                    </p>
                    <div class="mt-4">
                        <a href="{{ route('admin.landing-content.create-type', 'about_mision') }}" 
                           class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-pink-600 hover:bg-pink-700 transition-colors duration-150">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Tambah Misi
                        </a>
                    </div>
                </div>
            </div>

            <!-- Program List Card -->
            <div class="bg-white overflow-hidden shadow rounded-xl hover:shadow-lg transition-shadow duration-300 border border-gray-200">
                <div class="p-6 text-center">
                    <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-yellow-100 mb-4">
                        <svg class="h-10 w-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Program</h3>
                    <p class="text-sm text-gray-500 mb-4">
                        Program dan kegiatan unggulan pesantren
                    </p>
                    <div class="mt-4">
                        <a href="{{ route('admin.landing-content.create-type', 'program_list') }}" 
                           class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-yellow-500 hover:bg-yellow-600 transition-colors duration-150">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Tambah Program
                        </a>
                    </div>
                </div>
            </div>

            <!-- Gallery Card -->
            <div class="bg-white overflow-hidden shadow rounded-xl hover:shadow-lg transition-shadow duration-300 border border-gray-200">
                <div class="p-6 text-center">
                    <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-red-100 mb-4">
                        <svg class="h-10 w-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Galeri</h3>
                    <p class="text-sm text-gray-500 mb-4">
                        Foto kegiatan dan dokumentasi pesantren
                    </p>
                    <div class="mt-4">
                        <a href="{{ route('admin.landing-content.create-type', 'gallery') }}" 
                           class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 transition-colors duration-150">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Tambah Galeri
                        </a>
                    </div>
                </div>
            </div>

            <!-- Testimony Card -->
            <div class="bg-white overflow-hidden shadow rounded-xl hover:shadow-lg transition-shadow duration-300 border border-gray-200">
                <div class="p-6 text-center">
                    <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-indigo-100 mb-4">
                        <svg class="h-10 w-10 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Testimoni</h3>
                    <p class="text-sm text-gray-500 mb-4">
                        Ulasan dari alumni, orang tua santri, dan pihak terkait
                    </p>
                    <div class="mt-4">
                        <a href="{{ route('admin.landing-content.create-type', 'testimony') }}" 
                           class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 transition-colors duration-150">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Tambah Testimoni
                        </a>
                    </div>
                </div>
            </div>

            <!-- CTA Card -->
            <div class="bg-white overflow-hidden shadow rounded-xl hover:shadow-lg transition-shadow duration-300 border border-gray-200">
                <div class="p-6 text-center">
                    <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-teal-100 mb-4">
                        <svg class="h-10 w-10 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Call to Action</h3>
                    <p class="text-sm text-gray-500 mb-4">
                        Aksi yang ingin dilakukan pengunjung (daftar, hubungi, dll)
                    </p>
                    <div class="mt-4">
                        <a href="{{ route('admin.landing-content.create-type', 'cta') }}" 
                           class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-teal-600 hover:bg-teal-700 transition-colors duration-150">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Tambah CTA
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endisset
    </main>
</div>
@endsection

@push('scripts')
<script>
    // Image Preview Function
    function previewImage(event) {
        const input = event.target;
        const preview = document.getElementById('imagePreview');
        const container = document.getElementById('imagePreviewContainer');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                container.classList.remove('hidden');
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Remove Image Function
    function removeImage() {
        const input = document.getElementById('image');
        const preview = document.getElementById('imagePreview');
        const container = document.getElementById('imagePreviewContainer');
        
        input.value = '';
        preview.src = '';
        container.classList.add('hidden');
    }

    // Auto-resize textarea
    const textarea = document.getElementById('description');
    if (textarea) {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    }

    // Dropzone functionality
    const dropZone = document.querySelector('label[for="image"]');
    if (dropZone) {
        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.querySelector('div').classList.add('border-blue-400', 'bg-blue-50');
        });

        dropZone.addEventListener('dragleave', () => {
            dropZone.querySelector('div').classList.remove('border-blue-400', 'bg-blue-50');
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.querySelector('div').classList.remove('border-blue-400', 'bg-blue-50');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                const input = document.getElementById('image');
                input.files = files;
                previewImage({ target: input });
            }
        });
    }
</script>
@endpush