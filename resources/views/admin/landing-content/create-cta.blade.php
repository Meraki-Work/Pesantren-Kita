@extends('index')

@section('title', 'Tambah Call to Action')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Tambah Call to Action (CTA)</h2>
                        <p class="mt-1 text-sm text-gray-500">Buat aksi yang ingin dilakukan pengunjung landing page</p>
                    </div>
                    <a href="{{ route('admin.landing-content.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>

            <!-- Form -->
            <div class="bg-white shadow rounded-lg">
                <form action="{{ route('admin.landing-content.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Hidden fields -->
                    <input type="hidden" name="content_type" value="cta">
                    
                    @if(optional(Auth::user())->role === 'admin' || optional(Auth::user())->role === 'super_admin')
                    <div class="p-6 border-b border-gray-200">
                        <label for="ponpes_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Pilih Pesantren <span class="text-red-500">*</span>
                        </label>
                        <select id="ponpes_id" name="ponpes_id" required
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
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
                    <input type="hidden" name="ponpes_id" value="{{ optional(Auth::user())->ponpes_id }}">
                    @endif

                    <div class="p-6 space-y-6">
                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                Judul CTA <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="title" name="title" required
                                   value="{{ old('title') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   placeholder="Contoh: Daftar Sekarang, Hubungi Kami, Lihat Program">
                            @error('title')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Subtitle -->
                        <div>
                            <label for="subtitle" class="block text-sm font-medium text-gray-700 mb-2">
                                Subjudul/Tagline (Opsional)
                            </label>
                            <input type="text" id="subtitle" name="subtitle"
                                   value="{{ old('subtitle') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   placeholder="Contoh: Jadilah bagian dari keluarga besar kami">
                            @error('subtitle')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Deskripsi Tambahan (Opsional)
                            </label>
                            <textarea id="description" name="description" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                      placeholder="Deskripsi singkat untuk mendorong pengunjung melakukan aksi...">{{ old('description') }}</textarea>
                            @error('description')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- URL -->
                        <div>
                            <label for="url" class="block text-sm font-medium text-gray-700 mb-2">
                                URL Tujuan <span class="text-red-500">*</span>
                            </label>
                            <input type="url" id="url" name="url" required
                                   value="{{ old('url') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   placeholder="https://example.com/pendaftaran atau #contact">
                            <p class="mt-1 text-sm text-gray-500">
                                Link yang akan dituju saat tombol diklik
                            </p>
                            @error('url')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Button Text -->
                        <div>
                            <label for="position" class="block text-sm font-medium text-gray-700 mb-2">
                                Teks Tombol (Opsional)
                            </label>
                            <input type="text" id="position" name="position"
                                   value="{{ old('position') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   placeholder="Contoh: Klik di sini, Daftar Sekarang, WhatsApp Kami">
                            <p class="mt-1 text-sm text-gray-500">
                                Teks yang muncul di tombol. Kosongkan untuk menggunakan default.
                            </p>
                            @error('position')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Image -->
                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                                Gambar Latar Belakang (Opsional)
                            </label>
                            <div class="mt-1 flex items-center">
                                <input type="file" id="image" name="image"
                                       accept="image/*"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>
                            <p class="mt-1 text-sm text-gray-500">
                                Gambar latar untuk CTA section. Ukuran rekomendasi: 1200×400 px
                            </p>
                            @error('image')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Display Order -->
                        <div>
                            <label for="display_order" class="block text-sm font-medium text-gray-700 mb-2">
                                Urutan Tampil <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="display_order" name="display_order" required min="1"
                                   value="{{ old('display_order', 1) }}"
                                   class="w-32 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <p class="mt-1 text-sm text-gray-500">
                                Angka lebih kecil akan ditampilkan lebih dahulu
                            </p>
                            @error('display_order')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Active Status -->
                        <div class="flex items-center">
                            <input type="checkbox" id="is_active" name="is_active" value="1"
                                   {{ old('is_active', true) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                Tampilkan CTA ini
                            </label>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between">
                        <div class="text-sm text-gray-500">
                            <p>CTA akan ditampilkan di bagian akhir sebelum footer untuk mendorong aksi pengunjung</p>
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('admin.landing-content.index') }}" 
                               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Batal
                            </a>
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Simpan CTA
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Tips Section -->
            <div class="mt-6">
                <div class="rounded-lg bg-teal-50 p-6 border border-teal-200">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-teal-800 mb-2">Tips Membuat Call to Action yang Efektif</h3>
                            <ul class="list-disc pl-5 space-y-1 text-sm text-teal-700">
                                <li><strong>Gunakan kata kerja aksi:</strong> "Daftar", "Hubungi", "Download", "Mulai"</li>
                                <li><strong>Buat jelas manfaatnya:</strong> "Dapatkan ebook gratis", "Konsultasi gratis"</li>
                                <li><strong>Gunakan sense of urgency:</strong> "Terbatas untuk 50 pendaftar pertama"</li>
                                <li><strong>Warna tombol kontras:</strong> Gunakan warna yang mencolok dari background</li>
                                <li><strong>Letak strategis:</strong> Biasanya di akhir section atau sebelum footer</li>
                                <li><strong>Test berbagai versi:</strong> Coba teks dan desain yang berbeda untuk conversion terbaik</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CTA Examples -->
            <div class="mt-6">
                <div class="rounded-lg bg-blue-50 p-6 border border-blue-200">
                    <h3 class="text-lg font-medium text-blue-800 mb-4">Contoh CTA yang Efektif</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-white p-4 rounded-lg border border-blue-100">
                            <h4 class="font-medium text-blue-700 mb-2">Untuk Pendaftaran</h4>
                            <p class="text-sm text-gray-600 mb-2">"Bergabunglah dengan kami untuk pendidikan terbaik"</p>
                            <button class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700 transition-colors">
                                Daftar Sekarang
                            </button>
                        </div>
                        <div class="bg-white p-4 rounded-lg border border-green-100">
                            <h4 class="font-medium text-green-700 mb-2">Untuk Kontak</h4>
                            <p class="text-sm text-gray-600 mb-2">"Butuh informasi lebih lanjut? Tim kami siap membantu"</p>
                            <button class="bg-green-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-green-700 transition-colors">
                                Hubungi Kami
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div id="previewModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Preview CTA</h3>
                <button onclick="closePreview()" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div id="ctaPreview" class="bg-gradient-to-r from-blue-500 to-teal-500 text-white p-8 rounded-lg text-center">
                <!-- Preview akan diisi oleh JavaScript -->
            </div>
            
            <div class="mt-4 text-sm text-gray-500">
                <p>Preview di atas adalah contoh bagaimana CTA akan ditampilkan di landing page</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Live Preview
    function updatePreview() {
        const title = document.getElementById('title').value || 'Judul CTA';
        const subtitle = document.getElementById('subtitle').value || 'Subjudul yang menarik perhatian';
        const description = document.getElementById('description').value || 'Deskripsi singkat tentang aksi yang diinginkan';
        const buttonText = document.getElementById('position').value || 'Klik di sini';
        
        const previewHTML = `
            <h3 class="text-2xl font-bold mb-2">${title}</h3>
            ${subtitle ? `<p class="text-lg mb-4">${subtitle}</p>` : ''}
            ${description ? `<p class="mb-6">${description}</p>` : ''}
            <a href="#" class="inline-flex items-center px-6 py-3 bg-white text-blue-600 font-semibold rounded-lg hover:bg-gray-100 transition-colors duration-200">
                ${buttonText}
                <svg class="ml-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                </svg>
            </a>
        `;
        
        document.getElementById('ctaPreview').innerHTML = previewHTML;
    }

    // Attach event listeners for live preview
    document.addEventListener('DOMContentLoaded', function() {
        const previewFields = ['title', 'subtitle', 'description', 'position'];
        
        previewFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                field.addEventListener('input', updatePreview);
            }
        });
        
        // Initial preview
        updatePreview();
    });

    function showPreview() {
        document.getElementById('previewModal').classList.remove('hidden');
        updatePreview();
    }

    function closePreview() {
        document.getElementById('previewModal').classList.add('hidden');
    }

    // Image Preview
    const imageInput = document.getElementById('image');
    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewDiv = document.getElementById('ctaPreview');
                    previewDiv.style.backgroundImage = `linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url(${e.target.result})`;
                    previewDiv.style.backgroundSize = 'cover';
                    previewDiv.style.backgroundPosition = 'center';
                }
                reader.readAsDataURL(file);
            }
        });
    }
</script>
@endpush

@endsection