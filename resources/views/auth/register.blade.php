@extends('layouts.guest')

@section('title', 'Registrasi')

@section('content')
<h3 class="mb-1">Buat akun</h3>
<p class="text-muted mb-4">Daftar sebagai karyawan baru.</p>

<form method="POST" action="{{ route('register') }}">
    @csrf

    <div class="mb-3">
        <label class="form-label">Nama</label>
        <input type="text" name="name" value="{{ old('name') }}"
               class="form-control @error('name') is-invalid @enderror" autofocus>
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" value="{{ old('email') }}"
               class="form-control @error('email') is-invalid @enderror">
        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Password <span class="text-muted fw-normal">(minimal 8 karakter)</span></label>
        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-4">
        <label class="form-label">Ulangi Password</label>
        <input type="password" name="password_confirmation" class="form-control">
    </div>

    <button class="btn btn-primary w-100 py-2">Daftar</button>
</form>

<p class="mt-4 mb-0 text-center text-muted">
    Sudah punya akun? <a href="{{ route('login') }}" class="fw-semibold">Masuk</a>
</p>
@endsection