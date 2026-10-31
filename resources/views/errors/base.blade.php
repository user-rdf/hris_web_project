<!DOCTYPE html>
<html lang="id">
<head>
    @include('layouts._head')
    <title>@yield('code') - HRIS</title>
</head>
<body class="d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    <div class="text-center px-3">
        <div class="display-1 fw-bold text-primary">@yield('code')</div>
        <h4 class="mb-2">@yield('heading')</h4>
        <p class="text-muted mb-4">@yield('message')</p>
        <a href="{{ url('/') }}" class="btn btn-primary">Kembali ke Beranda</a>
    </div>
</body>
</html>