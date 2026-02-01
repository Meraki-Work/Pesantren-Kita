@extends('index')
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP - PesantrenKita</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white p-8 rounded-lg shadow-md w-96">
        <h2 class="text-2xl font-bold text-center mb-6">Verifikasi OTP</h2>

        {{-- DEBUG INFO --}}
        @if(app()->environment('local'))
        <div class="bg-gray-100 p-2 mb-4 text-xs rounded">
            <p><strong>Debug:</strong> {{ session('email') ?? 'No session email' }}</p>
            <p><strong>User ID:</strong> {{ session('user_id') ?? 'No user_id' }}</p>
        </div>
        @endif

        {{-- ✅ TAMPILKAN OTP JIKA EMAIL GAGAL --}}
        @if(session('otp_display'))
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-800 px-4 py-3 rounded mb-4">
                <div class="font-bold mb-2">⚠️ Email tidak terkirim!</div>
                <p class="mb-2">Gunakan kode OTP berikut untuk verifikasi:</p>
                <div class="text-center">
                    <span class="text-2xl font-bold text-red-600">{{ session('otp_display') }}</span>
                </div>
                <p class="text-sm mt-2">Kode berlaku 10 menit</p>
            </div>
        @endif

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('warning'))
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">
                {{ session('warning') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        {{-- PERIKSA APAKAH EMAIL ADA DI SESSION --}}
        @php
            $userEmail = session('email');
            $userId = session('user_id');
        @endphp

        @if(empty($userEmail))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <p>⚠️ Sesi telah berakhir. Silakan registrasi ulang.</p>
                <a href="{{ route('registrasi.index') }}" class="text-red-600 hover:underline font-semibold">
                    Kembali ke Registrasi
                </a>
            </div>
        @else
            {{-- ✅ PERBAIKI: Gunakan route 'verify.otp' (bukan 'verify-otp.post') --}}
            <form action="{{ route('verify.otp') }}" method="POST" id="verifyForm">
                @csrf
                <input type="hidden" name="email" value="{{ $userEmail }}">
                <input type="hidden" name="user_id" value="{{ $userId }}">
                
                <p class="text-center text-gray-600 mb-4">
                    Kode OTP telah dikirim ke:<br>
                    <strong class="text-blue-600">{{ $userEmail }}</strong>
                </p>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Kode OTP (6 digit)</label>
                    <input type="text" 
                           name="otp" 
                           id="otpInput"
                           maxlength="6" 
                           required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 text-center text-2xl font-mono tracking-widest"
                           placeholder="123456"
                           autocomplete="off"
                           pattern="\d{6}">
                    <p class="text-xs text-gray-500 mt-1">Masukkan 6 digit kode OTP yang diterima via email</p>
                </div>

                <div class="flex space-x-2 mb-3">
                    <button type="submit" 
                            class="flex-1 bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded-md font-semibold">
                        Verifikasi
                    </button>
                    <button type="button" 
                            onclick="clearOtp()"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 py-2 px-4 rounded-md">
                        Clear
                    </button>
                </div>
            </form>

            {{-- ✅ PERBAIKI: Gunakan route 'resend.otp' sesuai dengan web.php --}}
            <form action="{{ route('resend.otp') }}" method="POST" class="mb-3">
                @csrf
                <input type="hidden" name="email" value="{{ $userEmail }}">
                <button type="submit" 
                        class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-md">
                    Kirim Ulang OTP
                </button>
            </form>
        @endif

        <div class="text-center">
            <a href="{{ route('registrasi.index') }}" class="text-gray-600 hover:text-green-600 hover:underline text-sm">
                Kembali ke Halaman Registrasi
            </a>
        </div>
    </div>

    {{-- Session Timer --}}
    @if(!empty($userEmail))
    <div class="fixed bottom-4 right-4 bg-gray-800 text-white px-4 py-2 rounded-lg shadow-lg">
        <p class="text-sm">Sesi berakhir dalam: <span id="sessionTimer" class="font-bold">15:00</span></p>
    </div>
    @endif

    {{-- Auto focus dan input validation --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto focus ke input OTP
            const otpInput = document.getElementById('otpInput');
            if (otpInput) {
                otpInput.focus();
                
                // Auto numeric input dan auto submit jika 6 digit
                otpInput.addEventListener('input', function(e) {
                    // Hanya angka
                    this.value = this.value.replace(/[^0-9]/g, '');
                    
                    // Auto submit jika sudah 6 digit
                    if (this.value.length === 6) {
                        document.getElementById('verifyForm').submit();
                    }
                });
            }

            // Session Timer
            @if(!empty($userEmail))
            let sessionTime = 15 * 60; // 15 menit dalam detik
            const timerElement = document.getElementById('sessionTimer');

            function updateTimer() {
                const minutes = Math.floor(sessionTime / 60);
                const seconds = sessionTime % 60;
                
                timerElement.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                
                if (sessionTime <= 0) {
                    // Redirect ke halaman registrasi dengan pesan error
                    alert('Sesi registrasi telah berakhir. Silakan registrasi ulang.');
                    window.location.href = "{{ route('registrasi.index') }}";
                } else {
                    sessionTime--;
                    setTimeout(updateTimer, 1000);
                }
            }

            // Mulai timer
            updateTimer();

            // Cek session status setiap 30 detik
            setInterval(() => {
                fetch('{{ route("verify.session-check") }}')
                    .then(response => response.json())
                    .then(data => {
                        if (!data.valid) {
                            alert('Sesi telah berakhir. Silakan registrasi ulang.');
                            window.location.href = "{{ route('registrasi.index') }}";
                        }
                    })
                    .catch(error => {
                        console.error('Session check error:', error);
                    });
            }, 30000);
            @endif
        });

        function clearOtp() {
            document.getElementById('otpInput').value = '';
            document.getElementById('otpInput').focus();
        }

        // Blok reload halaman dengan peringatan
        window.addEventListener('beforeunload', function (e) {
            // Cek apakah user belum verifikasi
            if (window.location.pathname.includes('/verify')) {
                @if(!empty($userEmail))
                const confirmationMessage = 'Anda yakin ingin meninggalkan halaman ini? Sesi verifikasi akan berakhir.';
                e.returnValue = confirmationMessage;
                return confirmationMessage;
                @endif
            }
        });
    </script>
</body>
</html>