@if(session('success'))
    <div style="color: green;">{{ session('success') }}</div>
@endif

@if($errors->any())
    <div style="color: red;">{{ $errors->first() }}</div>
@endif

<form action="{{ route('verify.otp') }}" method="POST">
    @csrf
    <input type="hidden" name="email" value="{{ session('email') ?? old('email') }}">
    <label>Kode OTP:</label>
    <input type="text" name="otp" placeholder="Masukkan 6 digit OTP" required>
    <button type="submit">Verifikasi</button>
</form>
