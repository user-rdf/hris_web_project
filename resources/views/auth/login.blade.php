@extends('layouts.guest')

@section('title', 'Masuk')

@section('content')
<h3 class="mb-1">Selamat datang</h3>
<p class="text-muted mb-4">Masuk untuk melanjutkan ke KelolaKita.</p>

<form method="POST" action="{{ route('login') }}">
    @csrf

    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" value="{{ old('email') }}"
               class="form-control @error('email') is-invalid @enderror" placeholder="nama@perusahaan.com" autofocus>
        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password"
               class="form-control @error('password') is-invalid @enderror" placeholder="Password Anda">
        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="form-check mb-4">
        <input type="checkbox" name="remember" id="remember" class="form-check-input">
        <label for="remember" class="form-check-label">Ingat saya</label>
    </div>

    <button class="btn btn-primary w-100 py-2">Masuk</button>
</form>

<p class="mt-4 mb-0 text-center text-muted">
    Belum punya akun? <a href="{{ route('register') }}" class="fw-semibold">Daftar</a>
</p>

<div class="border rounded-3 p-3 mt-4 small bg-body-tertiary">
    <div class="fw-semibold mb-2">Akun demo <span class="text-muted fw-normal">(password: <code>password</code>)</span></div>
    <div class="d-flex flex-wrap gap-2">
        <button type="button" class="btn btn-sm btn-outline-secondary" data-demo="admin@hris.test">Admin HR</button>
        <button type="button" class="btn btn-sm btn-outline-secondary" data-demo="manager.it@hris.test">Manager</button>
        <button type="button" class="btn btn-sm btn-outline-secondary" data-demo="andi@hris.test">Karyawan</button>
    </div>
</div>

<script>
    document.querySelectorAll('[data-demo]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.querySelector('[name=email]').value = btn.dataset.demo;
            document.querySelector('[name=password]').value = 'password';
        });
    });
</script>
@endsection