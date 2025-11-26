@extends('index')

@section('title', 'Registrasi Pengguna')

@section('content')

<body class="min-h-screen flex items-center justify-center relative">
  <!-- Background gambar -->
  <div class="absolute inset-0 bg-cover bg-center"
    style="background-image: url('{{ asset('asset/masjid-hd-pc.jpg') }}');">
  </div>

  <!-- Overlay gelap -->
  <div class="absolute inset-0 bg-black/30"></div>

  <!-- Konten form -->
  <div class="relative z-10 bg-[#F8FFF8]/95 w-full max-w-4xl rounded-xl shadow-lg p-10 mx-4">
    <h2 class="text-2xl font-bold text-center mb-8">Registrasi Pengguna</h2>

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

    <form method="POST" action="{{ route('register.store') }}" class="grid md:grid-cols-2 gap-6" id="registrationForm">
      @csrf

      {{-- Kolom kiri --}}
      <div class="space-y-4">
        <div class="relative">
          <label class="block text-sm font-medium mb-2">Nama Pengguna</label>
          <input type="text" name="username" placeholder="Masukkan Nama Pengguna"
            value="{{ old('username') }}" required class="{{ $inputClass }}">
          <i class="fa-solid fa-user absolute right-3 top-10 text-gray-500"></i>
        </div>

        <div class="relative">
          <label class="block text-sm font-medium mb-2">Email</label>
          <input type="email" name="email" placeholder="Masukkan Email"
            value="{{ old('email') }}" required class="{{ $inputClass }}">
          <i class="fa-solid fa-envelope absolute right-3 top-10 text-gray-500"></i>
        </div>

        <div class="relative">
          <label class="block text-sm font-medium mb-2">Role</label>
          <select name="role" required class="{{ $inputClass }}">
            <option value="">Pilih Role</option>
            <option value="Admin" {{ old('role') == 'Admin' ? 'selected' : '' }}>Admin</option>
            <option value="Pengajar" {{ old('role') == 'Pengajar' ? 'selected' : '' }}>Pengajar</option>
            <option value="Keuangan" {{ old('role') == 'Keuangan' ? 'selected' : '' }}>Keuangan</option>
          </select>
          <i class="fa-solid fa-user-tag absolute right-3 top-10 text-gray-500"></i>
        </div>
      </div>

      {{-- Kolom kanan --}}
      <div class="space-y-4">
        {{-- Pilihan Pondok Pesantren --}}
        <div class="relative">
          <label class="block text-sm font-medium mb-2">Pondok Pesantren</label>

          {{-- Debug Info --}}
          <div class="mb-2">
            @if(isset($ponpesList) && $ponpesList->count() > 0)
            <div class="text-xs text-green-600 bg-green-50 p-2 rounded">
              ✅ Ditemukan <strong>{{ $ponpesList->count() }}</strong> pondok pesantren tersedia
            </div>
            @else
            <div class="text-xs text-yellow-600 bg-yellow-50 p-2 rounded">
              ⚠️ Sistem akan membuat pondok pesantren default otomatis
            </div>
            @endif
          </div>

          {{-- Radio options --}}
          <div class="flex space-x-4 mb-3">
            <label class="flex items-center">
              <input type="radio" name="ponpes_option" value="existing"
                {{ old('ponpes_option', 'existing') == 'existing' ? 'checked' : '' }}
                class="mr-2" onchange="togglePonpesOptions()">
              <span class="text-sm">Gabung Pondok yang Ada</span>
            </label>
            <label class="flex items-center">
              <input type="radio" name="ponpes_option" value="new"
                {{ old('ponpes_option') == 'new' ? 'checked' : '' }}
                class="mr-2" onchange="togglePonpesOptions()">
              <span class="text-sm">Buat Pondok Baru</span>
            </label>
          </div>

          {{-- Option 1: Input Manual ID Ponpes --}}
          <div id="existing-ponpes" class="ponpes-option {{ old('ponpes_option', 'existing') == 'existing' ? 'active' : '' }}">
            <div class="mb-2">
              <label class="block text-xs text-gray-600 mb-1">
                Masukkan ID Pondok Pesantren yang valid
              </label>
              <input type="text"
                name="manual_ponpes_id"
                id="manual_ponpes_id"
                placeholder="Contoh: a1b2c3d4e5f6g7h8i9j0"
                value="{{ old('manual_ponpes_id') }}"
                class="{{ $inputClass }}"
                onkeyup="checkPonpesId(this.value)">
              <div id="ponpes-validation" class="text-xs mt-1"></div>
            </div>
            <i class="fa-solid fa-key absolute right-3 top-20 text-gray-500"></i>
          </div>

          {{-- Option 2: Buat baru --}}
          <div id="new-ponpes" class="ponpes-option {{ old('ponpes_option') == 'new' ? 'active' : '' }}">
            <input type="text"
              name="new_ponpes_name" placeholder="Masukkan Nama Pondok Pesantren Baru" value="{{ old('new_ponpes_name') }}" class="{{ $inputClass }}">
            <i class="fa-solid fa-plus absolute right-3 top-10 text-gray-500"></i>
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
        </div>

        {{-- Tombol --}}
        <div class="md:col-span-2 flex flex-col items-center gap-4 mt-6">
          <button
            type="submit"
            id="submitButton"
            class="relative w-full md:w-[300px] bg-green-500 hover:bg-green-600 text-white py-3 rounded-lg transition duration-300 font-semibold disabled:bg-gray-400 disabled:cursor-not-allowed">
            <span class="button-text">Registrasi</span>

            <!-- Spinner default hidden -->
            <div class="loading-spinner hidden absolute inset-0 flex items-center justify-center">
              <svg class="animate-spin h-6 w-6 text-white" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                  d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z">
                </path>
              </svg>
              <span class="ml-2">Memproses...</span>
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
    </form>
  </div>

  <script>
    // =====================
    // STATE
    // =====================
    let isSubmitting = false;

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
    // TOGGLE PONPES OPTION
    // =====================
    function togglePonpesOptions() {
      const existingOption = document.getElementById('existing-ponpes');
      const newOption = document.getElementById('new-ponpes');
      const existingRadio = document.querySelector('input[name="ponpes_option"][value="existing"]');

      if (existingRadio.checked) {
        existingOption.classList.add('active');
        newOption.classList.remove('active');

        document.getElementById('manual_ponpes_id').disabled = false;
        document.querySelector('input[name="new_ponpes_name"]').disabled = true;

        document.querySelector('input[name="new_ponpes_name"]').value = '';
      } else {
        existingOption.classList.remove('active');
        newOption.classList.add('active');

        document.getElementById('manual_ponpes_id').disabled = true;
        document.querySelector('input[name="new_ponpes_name"]').disabled = false;

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
              validationDiv.innerHTML = `<span class="text-green-600">${data.message} - ${data.nama_ponpes}</span>`;
            } else {
              validationDiv.innerHTML = `<span class="text-red-600">${data.message}</span>`;
            }
          })
          .catch(() => {
            validationDiv.innerHTML = '<span class="text-yellow-600">⚠️ Gagal memverifikasi ID</span>';
          });
      }, 500);
    }

    // =====================
    // VALIDASI FORM
    // =====================
    function validateForm() {
      const password = document.getElementById('password').value;
      const confirmPassword = document.getElementById('password_confirmation').value;

      if (password !== confirmPassword) {
        alert('Konfirmasi kata sandi tidak sesuai!');
        document.getElementById('password_confirmation').focus();
        return false;
      }

      const ponpesOption = document.querySelector('input[name="ponpes_option"]:checked');
      if (!ponpesOption) {
        alert('Silakan pilih opsi Pondok Pesantren!');
        return false;
      }

      if (ponpesOption.value === 'existing') {
        const manualId = document.getElementById('manual_ponpes_id').value.trim();
        if (!manualId) {
          alert('Silakan masukkan ID Pondok Pesantren yang valid.');
          return false;
        }
      } else {
        const newName = document.querySelector('input[name="new_ponpes_name"]').value.trim();
        if (!newName) {
          alert('Silakan masukkan nama Pondok Pesantren baru.');
          return false;
        }
      }

      return true;
    }

    // =====================
    // HANDLE FORM SUBMIT
    // =====================
    document.getElementById('registrationForm').addEventListener('submit', function(e) {
      const submitButton = document.getElementById('submitButton');

      // Cegah double submit
      if (isSubmitting) {
        e.preventDefault();
        return;
      }

      isSubmitting = true;

      // Disable tombol langsung
      submitButton.disabled = true;
      submitButton.classList.add('opacity-50', 'cursor-not-allowed', 'pointer-events-none');

      // Tampilkan loading
      showLoading();

      // Stop submit sementara → lakukan validasi dulu
      e.preventDefault();

      if (!validateForm()) {
        hideLoading();
        submitButton.disabled = false;
        submitButton.classList.remove('opacity-50', 'cursor-not-allowed', 'pointer-events-none');
        isSubmitting = false;
        return;
      }

      // Hapus input sesuai pilihan ponpes
      const option = document.querySelector('input[name="ponpes_option"]:checked');
      if (option.value === 'existing') {
        document.querySelector('input[name="new_ponpes_name"]').disabled = true;
      } else {
        document.getElementById('manual_ponpes_id').disabled = true;
      }

      // Submit form secara manual (tidak akan masuk submit event lagi)
      this.submit();

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