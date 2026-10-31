<!DOCTYPE html>
<html lang="id">
<head>
    @include('layouts._head')
    <title>@yield('title', 'Masuk') - KelolaKita</title>
</head>
<body>
<div class="auth-wrap">
    <div class="auth-hero d-none d-lg-flex">
        <div class="d-flex align-items-center gap-2 fs-3 fw-bold mb-4">
            <i class="bi bi-people-fill"></i> KelolaKita
        </div>
        <h1>KelolaKita membantu Anda mengelola absensi, cuti, kinerja, dan gaji dalam satu tempat.</h1>
        <ul class="list-unstyled mt-4">
            <li><i class="bi bi-geo-alt"></i> Absensi berbasis lokasi GPS</li>
            <li><i class="bi bi-calendar2-check"></i> Pengajuan dan persetujuan cuti</li>
            <li><i class="bi bi-star"></i> Evaluasi kinerja per periode</li>
            <li><i class="bi bi-cash-coin"></i> Payroll otomatis dan slip gaji</li>
        </ul>
    </div>

    <div class="auth-form">
        <div class="auth-card">
            @yield('content')
        </div>
    </div>
</div>

@include('layouts._scripts')
</body>
</html>