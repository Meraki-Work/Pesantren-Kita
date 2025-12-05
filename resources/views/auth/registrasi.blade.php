@extends('index')

@section('title', 'Registrasi Pengguna')

@section('content')

<body class="min-h-screen flex items-center justify-center relative overflow-y-auto">
  <!-- Background gambar -->
  <div class="fixed inset-0 bg-cover bg-center"
    style="background-image: url('{{ asset('asset/masjid-hd-pc.jpg') }}');">
  </div>

  <!-- Overlay gelap -->
  <div class="fixed inset-0 bg-black/30"></div>

  <!-- Konten form -->
  <div class="relative z-10 bg-[#F8FFF8]/95 w-full max-w-4xl rounded-xl shadow-lg p-6 mx-4 my-8">
    <h2 class="text-2xl font-bold text-center mb-6">Registrasi Pengguna</h2>

    @php
    $inputClass = 'w-full h-[45px] pl-3 pr-10 text-sm placeholder:text-sm bg-gray-100
    border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500';
    @endphp

    {{-- Tampilkan pesan sukses --}}
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-center">
      {{ session('success') }}
    </div>
    @endif

    {{-- Tampilkan pesan error --}}
    @if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 p-3 rounded mb-4">
      <ul class="list-disc list-inside text-sm">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('register.store') }}" class="grid md:grid-cols-2 gap-4" id="registrationForm">
      @csrf

      {{-- Kolom kiri --}}
      <div class="space-y-4">
        <div class="relative">
          <label class="block text-sm font-medium mb-2">Nama Pengguna</label>
          <input type="text" name="username" placeholder="Masukkan Nama Pengguna"
            value="{{ old('username') }}" required class="{{ $inputClass }}">
          <i class="fa-solid fa-user absolute right-3 top-10 text-gray-500"></i>
        </div>

        <!-- Ganti bagian select role -->
        <div class="relative">
          <label class="block text-sm font-medium mb-2">Role</label>
          <select id="roleSelect" name="role" required class="{{ $inputClass }}">
            <option value="">Pilih Role</option>
            <option value="Admin" {{ old('role') == 'Admin' ? 'selected' : '' }}>Admin</option>
            <option value="Pengajar" {{ old('role') == 'Pengajar' ? 'selected' : '' }}>Pengajar</option>
            <option value="Keuangan" {{ old('role') == 'Keuangan' ? 'selected' : '' }}>Keuangan</option>
          </select>
          <i class="fa-solid fa-user-tag absolute right-3 top-10 text-gray-500"></i>

          <!-- Notifikasi role otomatis -->
          <div id="roleAutoAdmin" class="hidden mt-2 p-2 bg-blue-50 border border-blue-200 rounded-lg">
            <div class="flex items-center text-xs text-blue-700">
              <svg class="w-3 h-3 mr-2 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
              </svg>
              <span>Role otomatis diatur ke <strong>Admin</strong> karena Anda membuat pondok baru</span>
            </div>
          </div>
        </div>
        <div class="relative">
          <label class="block text-sm font-medium mb-2 flex items-center">
            Pondok Pesantren
            <!-- Tooltip Icon -->
            <div class="relative group ml-2">
              <svg class="w-4 h-4 text-gray-400 cursor-help" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              <!-- Tooltip Content -->
              <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 bg-gray-800 text-white text-xs rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none w-64 text-center z-50">
                Pilih untuk bergabung dengan pondok yang sudah ada atau buat pondok baru
                <div class="absolute top-full left-1/2 transform -translate-x-1/2 border-4 border-transparent border-t-gray-800"></div>
              </div>
            </div>
          </label>

          {{-- Info Status Pondok --}}
          <div class="mb-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
            <div class="flex items-start">
              <svg class="w-4 h-4 text-blue-600 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              <div class="text-xs text-blue-700">
                <strong>Ketentuan:</strong><br>
                • Untuk membuat pondok baru, pilih role <strong>Admin</strong><br>
                • Untuk bergabung dengan pondok yang ada, pilih role sesuai kebutuhan
              </div>
            </div>
          </div>
        </div>

        <div class="grid grid-cols-1 gap-3 mb-4">
          <!-- Option 1: Gabung Pondok yang Ada -->
          <div class="relative">
            <input type="radio" name="ponpes_option" value="existing" id="option-existing"
              {{ old('ponpes_option', 'existing') == 'existing' ? 'checked' : '' }}
              class="hidden peer" onchange="togglePonpesOptions()">
            <label for="option-existing" class="block p-3 border-2 border-gray-200 rounded-lg cursor-pointer transition-all duration-200 peer-checked:border-green-500 peer-checked:bg-green-50 hover:border-green-300">
              <div class="flex items-center">
                <div class="w-5 h-5 border-2 border-gray-300 rounded-full peer-checked:border-green-500 peer-checked:bg-green-500 flex items-center justify-center mr-3 flex-shrink-0">
                  <svg class="w-3 h-3 text-white hidden peer-checked:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                  </svg>
                </div>
                <div class="min-w-0">
                  <div class="font-medium text-gray-900 text-sm">Gabung Pondok yang Ada</div>
                  <div class="text-xs text-gray-600 mt-1">Bergabung dengan pondok pesantren yang sudah terdaftar</div>
                </div>
              </div>
            </label>
          </div>

          <!-- Option 2: Buat Pondok Baru -->
          <div class="relative">
            <input type="radio" name="ponpes_option" value="new" id="option-new"
              {{ old('ponpes_option') == 'new' ? 'checked' : '' }}
              class="hidden peer" onchange="togglePonpesOptions()">
            <label for="option-new" class="block p-3 border-2 border-gray-200 rounded-lg cursor-pointer transition-all duration-200 peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:border-blue-300">
              <div class="flex items-center">
                <div class="w-5 h-5 border-2 border-gray-300 rounded-full peer-checked:border-blue-500 peer-checked:bg-blue-500 flex items-center justify-center mr-3 flex-shrink-0">
                  <svg class="w-3 h-3 text-white hidden peer-checked:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                  </svg>
                </div>
                <div class="min-w-0">
                  <div class="font-medium text-gray-900 text-sm">Buat Pondok Baru</div>
                  <div class="text-xs text-gray-600 mt-1">Membuat pondok pesantren baru (Hanya untuk Admin)</div>
                </div>
              </div>
            </label>
          </div>
        </div>
      </div>

      {{-- Kanan --}}
      <div class="space-y-4">
        <div class="relative">
          <label class="block text-sm font-medium mb-2">Email</label>
          <input type="email" name="email" placeholder="Masukkan Email"
            value="{{ old('email') }}" required class="{{ $inputClass }}">
          <i class="fa-solid fa-envelope absolute right-3 top-10 text-gray-500"></i>
        </div>

        {{-- Pilihan Pondok Pesantren --}}
        <div class="relative">
          <div id="new-ponpes" class="ponpes-option transition-all duration-300 overflow-hidden {{ old('ponpes_option') == 'new' ? 'max-h-[200px] opacity-100' : 'max-h-0 opacity-0' }}">
            <div class="pt-3">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Nama Pondok Pesantren Baru
                  <span class="text-xs text-gray-500 ml-1">(wajib)</span>
                </label>
                <input type="text"
                  name="new_ponpes_name"
                  placeholder="Masukkan nama pondok pesantren baru"
                  value="{{ old('new_ponpes_name') }}"
                  class="{{ $inputClass }}">
                <div class="text-xs text-gray-500 mt-1 flex items-center">
                  <svg class="w-3 h-3 mr-1 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                  </svg>
                  Sistem akan membuat ID pondok secara otomatis
                </div>
              </div>
            </div>
          </div>

          {{-- Option 1: Dropdown Pilih Pondok yang Ada --}}
          <div id="existing-ponpes" class="ponpes-option transition-all duration-300 overflow-hidden {{ old('ponpes_option', 'existing') == 'existing' ? 'max-h-[500px] opacity-100' : 'max-h-0 opacity-0' }}">
            <div class="pt-3 space-y-3">
              {{-- Manual Input ID (Alternatif) --}}
              <div class="">
                <label class="block text-xs font-medium text-gray-600 mb-2 flex items-center">
                  <span>Masukkan ID Pondok secara manual</span>
                  <div class="relative group ml-1">
                    <svg class="w-3 h-3 text-gray-400 cursor-help" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-1 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none w-48 text-center z-50">
                      Gunakan jika Anda sudah memiliki ID Pondok
                      <div class="absolute top-full left-1/2 transform -translate-x-1/2 border-4 border-transparent border-t-gray-800"></div>
                    </div>
                  </div>
                </label>
                <input type="text"
                  name="manual_ponpes_id"
                  id="manual_ponpes_id"
                  placeholder="Contoh: ponpes_a1b2c3d4e5"
                  value="{{ old('manual_ponpes_id') }}"
                  class="{{ $inputClass }} text-xs"
                  onkeyup="checkPonpesId(this.value)">
                <div id="ponpes-validation" class="text-xs mt-1 min-h-[20px]"></div>
              </div>
            </div>
          </div>
        </div>

        <div class="relative">
          <label class="block text-sm font-medium mb-2">Kata Sandi</label>
          <input type="password" name="password" id="password"
            placeholder="Masukkan Kata Sandi (minimal 6 karakter)"
            required minlength="6" class="{{ $inputClass }}">
          <i class="fa-solid fa-eye absolute right-3 top-10 text-gray-500 cursor-pointer"
            onclick="togglePassword('password', this)"></i>
        </div>

        <div class="relative">
          <label class="block text-sm font-medium mb-2">Konfirmasi Kata Sandi</label>
          <input type="password" name="password_confirmation" id="password_confirmation"
            placeholder="Ulangi Kata Sandi" required minlength="6" class="{{ $inputClass }}">
          <i class="fa-solid fa-eye absolute right-3 top-10 text-gray-500 cursor-pointer"
            onclick="togglePassword('password_confirmation', this)"></i>
        </div>

        <div class="relative">
          <div class="md:col-span-2 flex flex-col items-center gap-4 mt-6">
            <button
              type="submit"
              id="submitButton"
              class="relative w-full md:w-[300px] bg-green-500 hover:bg-green-600 text-white py-3 rounded-lg transition duration-300 font-semibold disabled:bg-gray-400 disabled:cursor-not-allowed">
              <span class="button-text">Registrasi</span>

              <!-- Spinner default hidden -->
              <div class="loading-spinner hidden absolute inset-0 flex items-center justify-center">
                <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z">
                  </path>
                </svg>
                <span class="ml-2 text-sm">Memproses...</span>
              </div>
            </button>

            {{-- Link ke halaman login --}}
            <p class="text-center text-sm text-gray-600">
              Sudah punya akun?
              <a href="{{ route('login') }}" class="text-green-600 hover:underline font-semibold">
                Masuk di sini
              </a>
            </p>
          </div>
        </div>
      </div>
    </form>
  </div>

  <style>
    .ponpes-option {
      transition: all 0.3s ease-in-out;
    }

    .min-h-\[20px\] {
      min-height: 20px;
    }

    #ponpes-validation {
      min-height: 20px;
    }

    html,
    body {
      height: 100%;
      overflow-y: auto;
    }

    .min-h-screen {
      min-height: 100vh;
    }

    select:disabled {
      background-color: #f3f4f6;
      color: #6b7280;
      cursor: not-allowed;
    }
  </style>

  <script>
    // =====================
    // STATE
    // =====================
    let isSubmitting = false;

    document.getElementById('registrationForm').addEventListener('submit', function(e) {
      const role = document.getElementById('roleSelect').value;

      if (role === "" || role === null) {
        e.preventDefault();
        alert("Role wajib dipilih!");
        return false;
      }
    });


    // =====================
    // SHOW / HIDE LOADING
    // =====================
    function showLoading() {
      const submitButton = document.getElementById('submitButton');
      const buttonText = submitButton.querySelector('.button-text');
      const loadingSpinner = submitButton.querySelector('.loading-spinner');

      buttonText.style.display = 'none';
      loadingSpinner.classList.remove('hidden');
      submitButton.disabled = true;
    }

    function hideLoading() {
      const submitButton = document.getElementById('submitButton');
      const buttonText = submitButton.querySelector('.button-text');
      const loadingSpinner = submitButton.querySelector('.loading-spinner');

      buttonText.style.display = 'inline';
      loadingSpinner.classList.add('hidden');
      submitButton.disabled = false;
    }

    // =====================
    // PASSWORD TOGGLE
    // =====================
    function togglePassword(id, el) {
      const input = document.getElementById(id);
      if (input.type === "password") {
        input.type = "text";
        el.classList.replace("fa-eye", "fa-eye-slash");
      } else {
        input.type = "password";
        el.classList.replace("fa-eye-slash", "fa-eye");
      }
    }

    // =====================
    // TOGGLE PONPES OPTION & ROLE
    // =====================
    function togglePonpesOptions() {
      const existingOption = document.getElementById('existing-ponpes');
      const newOption = document.getElementById('new-ponpes');
      const existingRadio = document.getElementById('option-existing');
      const roleSelect = document.getElementById('roleSelect');
      const roleAutoAdmin = document.getElementById('roleAutoAdmin');

      if (existingRadio.checked) {
        // Show existing option, hide new option
        existingOption.classList.remove('max-h-0', 'opacity-0');
        existingOption.classList.add('max-h-[500px]', 'opacity-100');

        newOption.classList.remove('max-h-[200px]', 'opacity-100');
        newOption.classList.add('max-h-0', 'opacity-0');

        // Enable role selection untuk gabung pondok
        roleSelect.disabled = false;
        roleAutoAdmin.classList.add('hidden');

        // Enable/disable fields
        document.getElementById('manual_ponpes_id').disabled = false;
        document.querySelector('input[name="new_ponpes_name"]').disabled = true;

        // Clear new ponpes name
        document.querySelector('input[name="new_ponpes_name"]').value = '';
      } else {
        // Show new option, hide existing option
        newOption.classList.remove('max-h-0', 'opacity-0');
        newOption.classList.add('max-h-[200px]', 'opacity-100');

        existingOption.classList.remove('max-h-[500px]', 'opacity-100');
        existingOption.classList.add('max-h-0', 'opacity-0');

        // Enable/disable fields
        document.getElementById('manual_ponpes_id').disabled = true;
        document.querySelector('input[name="new_ponpes_name"]').disabled = false;

        // Set role otomatis ke Admin
        roleSelect.value = 'Admin';

        // Kunci secara visual tapi tetap terkirim ke server
        roleSelect.classList.add('pointer-events-none', 'bg-gray-100', 'text-gray-500');

        // Tampilkan info role otomatis
        roleAutoAdmin.classList.remove('hidden');


        // Clear existing fields
        document.getElementById('manual_ponpes_id').value = '';
        document.getElementById('ponpes-validation').innerHTML = '';
      }
    }

    // =====================
    // VALIDASI PONPES ID REALTIME
    // =====================
    function checkPonpesId(ponpesId) {
      const validationDiv = document.getElementById('ponpes-validation');

      if (ponpesId.length < 5) {
        validationDiv.innerHTML = '';
        return;
      }

      clearTimeout(window.ponpesCheckTimeout);
      window.ponpesCheckTimeout = setTimeout(() => {
        fetch('/check-ponpes-id', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
              ponpes_id: ponpesId
            })
          })
          .then(response => response.json())
          .then(data => {
            if (data.valid) {
              validationDiv.innerHTML = `<span class="text-green-600 flex items-center">
                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                ${data.message} - ${data.nama_ponpes}
              </span>`;
            } else {
              validationDiv.innerHTML = `<span class="text-red-600 flex items-center">
                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                ${data.message}
              </span>`;
            }
          })
          .catch(() => {
            validationDiv.innerHTML = '<span class="text-yellow-600">⚠️ Gagal memverifikasi ID</span>';
          });
      }, 500);
    }

    // =====================
    // VALIDASI FORM YANG DIPERBAIKI
    // =====================
    function validateForm() {
      const password = document.getElementById('password').value;
      const confirmPassword = document.getElementById('password_confirmation').value;
      const role = document.getElementById('roleSelect').value;
      const ponpesOption = document.querySelector('input[name="ponpes_option"]:checked');

      // Validasi password
      if (password !== confirmPassword) {
        alert('Konfirmasi kata sandi tidak sesuai!');
        document.getElementById('password_confirmation').focus();
        return false;
      }

      // Validasi pilihan pondok
      if (!ponpesOption) {
        alert('Silakan pilih opsi Pondok Pesantren!');
        return false;
      }

      // Validasi untuk gabung pondok yang ada
      if (ponpesOption.value === 'existing') {
        const manualId = document.getElementById('manual_ponpes_id').value.trim();

        if (!manualId) {
          alert('Silakan masukkan ID Pondok Pesantren secara manual.');
          document.getElementById('manual_ponpes_id').focus();
          return false;
        }
      }
      // Validasi untuk buat pondok baru
      else {
        const newName = document.querySelector('input[name="new_ponpes_name"]').value.trim();

        // Validasi nama pondok
        if (!newName) {
          alert('Silakan masukkan nama Pondok Pesantren baru.');
          document.querySelector('input[name="new_ponpes_name"]').focus();
          return false;
        }

        // Validasi role harus Admin untuk buat pondok baru
        if (role !== 'Admin') {
          alert('Hanya pengguna dengan role Admin yang dapat membuat pondok pesantren baru.');
          return false;
        }
      }

      return true;
    }

    // =====================
    // HANDLE FORM SUBMIT YANG DIPERBAIKI
    // =====================
    document.getElementById('registrationForm').addEventListener('submit', function(e) {
      const submitButton = document.getElementById('submitButton');

      // Cegah double submit
      if (isSubmitting) {
        e.preventDefault();
        return;
      }

      // Validasi form sebelum submit
      if (!validateForm()) {
        e.preventDefault();
        return;
      }

      isSubmitting = true;

      // Disable tombol langsung
      submitButton.disabled = true;
      submitButton.classList.add('opacity-50', 'cursor-not-allowed', 'pointer-events-none');

      // Tampilkan loading
      showLoading();

      // Hapus input yang tidak digunakan sebelum submit
      const option = document.querySelector('input[name="ponpes_option"]:checked');
      if (option.value === 'existing') {
        document.querySelector('input[name="new_ponpes_name"]').disabled = true;
      } else {
        document.getElementById('manual_ponpes_id').disabled = true;
      }

      // Biarkan form submit secara normal
      // Tidak perlu e.preventDefault() di sini karena kita mau form benar-benar submit

      // Fallback jika server lama tidak respon
      setTimeout(() => {
        if (isSubmitting) {
          hideLoading();
          alert('Terjadi masalah. Silakan coba lagi.');
          submitButton.disabled = false;
          submitButton.classList.remove('opacity-50', 'cursor-not-allowed', 'pointer-events-none');
          isSubmitting = false;
        }
      }, 10000);
    });

    // =====================
    // ON PAGE LOAD
    // =====================
    document.addEventListener('DOMContentLoaded', () => {
      togglePonpesOptions();
      hideLoading();
    });

    // Reset state jika user keluar halaman
    window.addEventListener('beforeunload', hideLoading);
  </script>

</body>

</html>