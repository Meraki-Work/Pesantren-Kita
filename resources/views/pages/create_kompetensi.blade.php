@extends('index')

@section('content')

<div class="flex bg-gray-50 min-h-screen">
    <x-sidemenu title="PesantrenKita" />
    <main class="flex-1 p-6 overflow-auto">
        <!-- Header Section -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Manajemen Kompetensi</h1>
            <p class="text-gray-600">Tambah dan kelala kompetensi santri dengan mudah</p>
        </div>

        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Form Section -->
            <div class="box-bg w-full lg:max-w-md p-6 transform transition-all duration-300 hover:shadow-lg rounded-xl">
                <div class="flex items-center mb-6">
                    <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-trophy text-white text-sm"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800">Tambah Kompetensi Baru</h3>
                </div>

                <!-- Alert Messages -->
                @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 p-4 rounded-lg mb-4 flex items-center">
                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                    {{ session('success') }}
                </div>
                @endif
                @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-lg mb-4 flex items-center">
                    <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
                    {{ session('error') }}
                </div>
                @endif

                <form action="{{ route('kompetensi.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <!-- Nama Santri -->
                    <div class="form-group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-user-graduate text-blue-500 mr-2 text-sm"></i>
                            Nama Santri
                        </label>
                        <!-- Di bagian select santri - PERBAIKI DATA ATTRIBUTES -->
                        <select name="id_santri" id="santriSelect"
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white" required>
                            <option value="">-- Pilih Santri --</option>
                            @foreach($santri as $s)
                            <option value="{{ $s->id_santri }}"
                                data-kelas="{{ $s->kelas ? $s->kelas->id_kelas : '' }}" {{-- ✅ PERBAIKAN: kelas bukan kela --}}
                                data-kelas-nama="{{ $s->kelas ? $s->kelas->nama_kelas : 'Tidak ada kelas' }}"
                                data-kelas-tingkat="{{ $s->kelas ? $s->kelas->tingkat : '' }}">
                                {{ $s->nama }}
                                @if($s->kelas) {{-- ✅ PERBAIKAN: kelas bukan kela --}}
                                - {{ $s->kelas->nama_kelas }} ({{ $s->kelas->tingkat }})
                                @else
                                - Belum ada kelas
                                @endif
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Kelas (otomatis) -->
                    <div class="form-group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-chalkboard-teacher text-green-500 mr-2 text-sm"></i>
                            Kelas
                        </label>
                        <select name="id_kelas" id="kelasSelect"
                            class="w-full p-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-600">
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelas as $k)
                            <option value="{{ $k->id_kelas }}">{{ $k->nama_kelas }} ({{ $k->tingkat }})</option>
                            @endforeach
                        </select>
                        <div id="kelasInfo" class="hidden mt-2 p-2 bg-blue-50 border border-blue-200 rounded-lg">
                            <p class="text-xs text-blue-700 flex items-center">
                                <i class="fas fa-info-circle text-blue-500 mr-1"></i>
                                <span id="kelasText">Kelas akan terisi otomatis berdasarkan santri yang dipilih</span>
                            </p>
                        </div>
                    </div>

                    <!-- Judul Kompetensi -->
                    <div class="form-group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-heading text-purple-500 mr-2 text-sm"></i>
                            Judul Kompetensi
                        </label>
                        <input type="text" name="judul"
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200"
                            placeholder="Masukkan judul kompetensi" required>
                    </div>

                    <!-- Deskripsi -->
                    <div class="form-group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-align-left text-orange-500 mr-2 text-sm"></i>
                            Deskripsi
                        </label>
                        <textarea name="deskripsi"
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200"
                            rows="3" placeholder="Deskripsi detail tentang kompetensi..."></textarea>
                    </div>

                    <!-- Tipe Kompetensi -->
                    <div class="form-group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-tags text-red-500 mr-2 text-sm"></i>
                            Tipe Kompetensi
                        </label>
                        <select name="tipe"
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200" required>
                            <option value="">-- Pilih Tipe Kompetensi --</option>
                            <option value="Akademik" class="py-2">🎓 Akademik</option>
                            <option value="Non-Akademik" class="py-2">⚽ Non-Akademik</option>
                            <option value="Tahfidz" class="py-2">📖 Tahfidz</option>
                            <option value="Lainnya" class="py-2">🔧 Lainnya</option>
                        </select>
                    </div>

                    <!-- Skor -->
                    <div class="form-group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-star text-yellow-500 mr-2 text-sm"></i>
                            Skor/Nilai
                        </label>
                        <input type="number" name="skor"
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-all duration-200"
                            placeholder="Masukkan skor (opsional)" min="0" max="100" step="0.1">
                    </div>

                    <!-- Tanggal -->
                    <div class="form-group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-calendar-alt text-teal-500 mr-2 text-sm"></i>
                            Tanggal Kompetensi
                        </label>
                        <input type="date" name="tanggal"
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-200" required>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-200">
                        <a href="{{ route('kompetensi.index') }}"
                            class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all duration-200 font-medium flex items-center">
                            <i class="fas fa-times mr-2"></i>
                            Batal
                        </a>
                        <button type="submit"
                            class="px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center">
                            <i class="fas fa-save mr-2"></i>
                            Simpan Kompetensi
                        </button>
                    </div>
                </form>
            </div>

            <!-- Info Panel -->
            <div class="flex-1">
                <div class="box-bg p-6 rounded-xl h-full">
                    <div class="flex items-center mb-6">
                        <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-chart-bar text-white text-sm"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">Informasi Kompetensi</h3>
                    </div>

                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                        <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-trophy text-white text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-blue-600 font-medium">Total Kompetensi</p>
                                    <p class="text-xl font-bold text-blue-700">-</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-green-50 p-4 rounded-lg border border-green-100">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-user-graduate text-white text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-green-600 font-medium">Santri Aktif</p>
                                    <p class="text-xl font-bold text-green-700">{{ $santri->count() }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-orange-50 p-4 rounded-lg border border-orange-100">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-orange-500 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-chalkboard text-white text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-orange-600 font-medium">Total Kelas</p>
                                    <p class="text-xl font-bold text-orange-700">{{ $kelas->count() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Tips -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                        <div class="flex items-start">
                            <i class="fas fa-lightbulb text-yellow-500 text-lg mr-3 mt-1"></i>
                            <div>
                                <h4 class="font-semibold text-yellow-800 mb-2">Tips Pengisian:</h4>
                                <ul class="text-yellow-700 text-sm space-y-1">
                                    <li>• Pilih santri terlebih dahulu untuk mengisi kelas otomatis</li>
                                    <li>• Isi judul yang jelas dan deskriptif</li>
                                    <li>• Skor bersifat opsional, dapat diisi nanti</li>
                                    <li>• Pastikan tanggal sesuai dengan pelaksanaan kompetensi</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity Placeholder -->
                    <!-- GANTI bagian Recent Activity Placeholder dengan ini: -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-700 mb-3 flex items-center">
                            <i class="fas fa-history text-gray-500 mr-2"></i>
                            Aktivitas Terbaru
                            <span class="ml-2 bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">
                                {{ $aktivitasTerbaru->count() }}
                            </span>
                        </h4>

                        @if($aktivitasTerbaru->count() > 0)
                        <div class="space-y-3">
                            @foreach($aktivitasTerbaru as $aktivitas)
                            @php
                            // Tentukan warna berdasarkan tipe
                            $tipeColor = match($aktivitas->tipe) {
                            'Akademik' => 'blue',
                            'Non-Akademik' => 'green',
                            'Tahfidz' => 'purple',
                            default => 'gray'
                            };

                            // Tentukan icon berdasarkan tipe
                            $tipeIcon = match($aktivitas->tipe) {
                            'Akademik' => 'graduation-cap',
                            'Non-Akademik' => 'football-ball',
                            'Tahfidz' => 'book-quran',
                            default => 'trophy'
                            };
                            @endphp

                            <div class="flex items-start space-x-3 p-2 hover:bg-gray-50 rounded-lg transition duration-200">
                                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-{{ $tipeColor }}-100 flex items-center justify-center">
                                    <i class="fas fa-{{ $tipeIcon }} text-{{ $tipeColor }}-600 text-sm"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">
                                        {{ $aktivitas->santri->nama ?? 'Santri tidak ditemukan' }}
                                    </p>
                                    <p class="text-xs text-gray-600 truncate">
                                        {{ $aktivitas->judul }}
                                    </p>
                                    <div class="flex items-center mt-1">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-{{ $tipeColor }}-100 text-{{ $tipeColor }}-800">
                                            {{ $aktivitas->tipe }}
                                        </span>
                                        @if($aktivitas->skor)
                                        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-star mr-1 text-yellow-500"></i>
                                            {{ $aktivitas->skor }}
                                        </span>
                                        @endif
                                        <span class="ml-2 text-xs text-gray-500">
                                            {{ \Carbon\Carbon::parse($aktivitas->tanggal)->format('d M Y') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-inbox text-3xl mb-2 opacity-50"></i>
                            <p>Belum ada aktivitas kompetensi</p>
                            <p class="text-sm mt-1">Data akan muncul setelah Anda menambahkan kompetensi</p>
                        </div>
                        @endif

                        @if($aktivitasTerbaru->count() > 0)
                        <div class="mt-4 pt-3 border-t border-gray-200">
                            <a href="{{ route('santri.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center justify-center">
                                <i class="fas fa-list mr-2"></i>
                                Lihat Semua Kompetensi
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const santriSelect = document.getElementById('santriSelect');
                const kelasSelect = document.getElementById('kelasSelect');
                const kelasInfo = document.getElementById('kelasInfo');
                const kelasText = document.getElementById('kelasText');

                console.log('JavaScript loaded - Auto fill kelas system ready');

                santriSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const kelasId = selectedOption.getAttribute('data-kelas');
                    const kelasNama = selectedOption.getAttribute('data-kelas-nama');
                    const kelasTingkat = selectedOption.getAttribute('data-kelas-tingkat');

                    console.log('Santri changed:', {
                        santri: selectedOption.text,
                        kelasId: kelasId,
                        kelasNama: kelasNama,
                        kelasTingkat: kelasTingkat
                    });

                    if (kelasId && kelasId !== '') {
                        // Set nilai kelas select
                        kelasSelect.value = kelasId;

                        // Update info text
                        kelasText.textContent = `Kelas otomatis terisi: ${kelasNama} (${kelasTingkat})`;

                        // Tampilkan info dan beri styling feedback
                        kelasInfo.classList.remove('hidden');
                        kelasSelect.classList.remove('bg-gray-50', 'text-gray-600');
                        kelasSelect.classList.add('bg-green-50', 'text-green-700', 'border-green-300');

                        console.log('Kelas berhasil di-set:', kelasId);

                        // Kembalikan ke normal setelah 3 detik, tapi tetap tampilkan info
                        setTimeout(() => {
                            kelasSelect.classList.remove('bg-green-50', 'text-green-700', 'border-green-300');
                            kelasSelect.classList.add('bg-gray-50', 'text-gray-600');
                        }, 3000);
                    } else {
                        // Reset jika santri tidak punya kelas
                        kelasSelect.value = '';
                        kelasText.textContent = 'Santri ini belum memiliki kelas. Silakan pilih kelas secara manual.';
                        kelasInfo.classList.remove('hidden');
                        kelasSelect.classList.remove('bg-gray-50', 'text-gray-600');
                        kelasSelect.classList.add('bg-yellow-50', 'text-yellow-700', 'border-yellow-300');

                        console.log('Santri tidak memiliki kelas');
                    }
                });

                // Juga trigger change event jika ada nilai yang sudah dipilih (untuk edit mode)
                if (santriSelect.value) {
                    santriSelect.dispatchEvent(new Event('change'));
                }

                // Debug: Log semua data santri dan kelasnya
                console.log('Data santri available:');
                const santriOptions = santriSelect.querySelectorAll('option');
                santriOptions.forEach(option => {
                    if (option.value) {
                        console.log({
                            santri: option.text,
                            kelasId: option.getAttribute('data-kelas'),
                            kelasNama: option.getAttribute('data-kelas-nama')
                        });
                    }
                });

                // Add some interactive effects untuk form fields
                const inputs = document.querySelectorAll('input, select, textarea');
                inputs.forEach(input => {
                    input.addEventListener('focus', function() {
                        this.parentElement.classList.add('ring-2', 'ring-blue-200', 'rounded-lg');
                    });

                    input.addEventListener('blur', function() {
                        this.parentElement.classList.remove('ring-2', 'ring-blue-200', 'rounded-lg');
                    });
                });
            });
        </script>

        <style>
            .form-group {
                transition: all 0.3s ease;
            }

            .box-bg {
                background: white;
                border-radius: 12px;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            }

            input,
            select,
            textarea {
                transition: all 0.3s ease;
            }

            input:focus,
            select:focus,
            textarea:focus {
                transform: translateY(-1px);
            }

            .gradient-bg {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            }
        </style>
    </main>
</div>
@endsection