<div x-data="{
    openModal: false,
    nisnValue: '',
    nikValue: '',
    nisnError: '',
    nikError: '',
    isChecking: false,
    
    async checkNisn(value) {
        if (!value || value.length < 3) {
            this.nisnError = '';
            return;
        }
        
        this.isChecking = true;
        this.nisnError = '';
        
        try {
            const response = await fetch('{{ route('santri.check-unique') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    field: 'nisn',
                    value: value
                })
            });
            
            const data = await response.json();
            
            if (!data.available) {
                this.nisnError = data.message;
            } else {
                this.nisnError = '';
            }
        } catch (error) {
            console.error('Error checking NISN:', error);
            this.nisnError = 'Terjadi kesalahan saat memeriksa NISN';
        } finally {
            this.isChecking = false;
        }
    },
    
    async checkNik(value) {
        if (!value || value.length < 3) {
            this.nikError = '';
            return;
        }
        
        this.isChecking = true;
        this.nikError = '';
        
        try {
            const response = await fetch('{{ route('santri.check-unique') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    field: 'nik',
                    value: value
                })
            });
            
            const data = await response.json();
            
            if (!data.available) {
                this.nikError = data.message;
            } else {
                this.nikError = '';
            }
        } catch (error) {
            console.error('Error checking NIK:', error);
            this.nikError = 'Terjadi kesalahan saat memeriksa NIK';
        } finally {
            this.isChecking = false;
        }
    },
    
    canSubmit() {
        return !this.nisnError && !this.nikError && this.nisnValue && this.nikValue;
    },
    
    resetForm() {
        this.nisnValue = '';
        this.nikValue = '';
        this.nisnError = '';
        this.nikError = '';
        this.isChecking = false;
        this.openModal = false;
    },
    
    handleSubmit() {
        if (this.canSubmit()) {
            // Submit form secara normal
            document.getElementById('santriForm').submit();
        }
        // Jika tidak bisa submit, biarkan form tidak melakukan apa-apa
        // Validation errors akan ditampilkan oleh Alpine
    }
}" x-cloak>
    <!-- Tombol Buka Modal -->
    <button 
        @click="openModal = true" 
        class="w-full bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition duration-200">
        Tambah Santri
    </button>

    <!-- Modal -->
    <div 
        x-show="openModal"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 flex items-center justify-center backdrop-blur-sm bg-black/40 z-50"
    >
        <div 
            class="bg-white rounded-xl shadow-2xl w-full max-w-3xl p-6 transform transition-all max-h-[90vh] overflow-y-auto"
        >
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Tambah Santri Baru</h3>
                <button 
                    @click="resetForm()"
                    class="text-gray-400 hover:text-gray-600 transition duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Notifikasi -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    {!! session('error') !!}
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('santri.store') }}" method="POST" class="space-y-4" id="santriForm">
                @csrf

                <div>
                    <label class="block font-medium text-gray-700 mb-2">Nama Santri <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200"
                        placeholder="Masukkan nama lengkap santri"
                        value="{{ old('nama') }}">
                    @error('nama')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-medium text-gray-700 mb-2">NISN <span class="text-red-500">*</span></label>
                        <input 
                            type="text" 
                            name="nisn" 
                            required
                            x-model="nisnValue"
                            @input.debounce.500ms="checkNisn($event.target.value)"
                            :class="nisnError ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-green-500'"
                            class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:border-transparent transition duration-200"
                            placeholder="Masukkan NISN"
                            value="{{ old('nisn') }}">
                        
                        <!-- Loading Indicator -->
                        <div x-show="isChecking && nisnValue" class="mt-1 flex items-center text-blue-600 text-sm">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Memeriksa NISN...
                        </div>
                        
                        <!-- Error Message -->
                        <div x-show="nisnError" class="mt-1 flex items-center text-red-600 text-sm">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span x-text="nisnError"></span>
                        </div>
                        
                        <!-- Success Message -->
                        <div x-show="!nisnError && nisnValue && !isChecking" class="mt-1 flex items-center text-green-600 text-sm">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            NISN tersedia
                        </div>
                        
                        @error('nisn')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block font-medium text-gray-700 mb-2">NIK <span class="text-red-500">*</span></label>
                        <input 
                            type="text" 
                            name="nik" 
                            required
                            x-model="nikValue"
                            @input.debounce.500ms="checkNik($event.target.value)"
                            :class="nikError ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-green-500'"
                            class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:border-transparent transition duration-200"
                            placeholder="Masukkan NIK"
                            value="{{ old('nik') }}">
                        
                        <!-- Loading Indicator -->
                        <div x-show="isChecking && nikValue" class="mt-1 flex items-center text-blue-600 text-sm">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Memeriksa NIK...
                        </div>
                        
                        <!-- Error Message -->
                        <div x-show="nikError" class="mt-1 flex items-center text-red-600 text-sm">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span x-text="nikError"></span>
                        </div>
                        
                        <!-- Success Message -->
                        <div x-show="!nikError && nikValue && !isChecking" class="mt-1 flex items-center text-green-600 text-sm">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            NIK tersedia
                        </div>
                        
                        @error('nik')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Fields lainnya... -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-medium text-gray-700 mb-2">Kelas</label>
                        <select name="id_kelas" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200">
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k->id_kelas }}" {{ old('id_kelas') == $k->id_kelas ? 'selected' : '' }}>
                                    {{ $k->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_kelas')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block font-medium text-gray-700 mb-2">Tahun Masuk <span class="text-red-500">*</span></label>
                        <select name="tahun_masuk" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200">
                            <option value="">-- Pilih Tahun --</option>
                            @for ($i = 2010; $i <= now()->year; $i++)
                                <option value="{{ $i }}" {{ old('tahun_masuk') == $i ? 'selected' : '' }}>
                                    {{ $i }}
                                </option>
                            @endfor
                        </select>
                        @error('tahun_masuk')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-medium text-gray-700 mb-2">Jenis Kelamin <span class="text-red-500">*</span></label>
                        <select name="jenis_kelamin" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200">
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block font-medium text-gray-700 mb-2">Tanggal Lahir <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_lahir" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200"
                            value="{{ old('tanggal_lahir') }}">
                        @error('tanggal_lahir')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-medium text-gray-700 mb-2">Nama Ayah</label>
                        <input type="text" name="nama_ayah"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200"
                            placeholder="Nama ayah santri"
                            value="{{ old('nama_ayah') }}">
                        @error('nama_ayah')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block font-medium text-gray-700 mb-2">Nama Ibu</label>
                        <input type="text" name="nama_ibu"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200"
                            placeholder="Nama ibu santri"
                            value="{{ old('nama_ibu') }}">
                        @error('nama_ibu')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Submit Button Status -->
                <div x-show="nisnError || nikError" class="bg-red-50 border border-red-200 rounded-lg p-3">
                    <div class="flex items-center text-red-700 text-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Perbaiki data di atas sebelum menyimpan</span>
                    </div>
                </div>

                <div class="flex justify-end gap-2 mt-6 pt-4 border-t border-gray-200">
                    <button 
                        type="button" 
                        @click="resetForm()" 
                        class="px-6 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition duration-200 font-medium">
                        Batal
                    </button>
                    <button 
                        type="submit"
                        :disabled="!canSubmit()"
                        :class="canSubmit() ? 'bg-green-600 hover:bg-green-700 cursor-pointer' : 'bg-gray-400 cursor-not-allowed'"
                        class="px-6 py-2 text-white rounded-lg transition duration-200 font-medium flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>