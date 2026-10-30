<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'HRIS')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

@auth
<nav class="navbar navbar-expand navbar-dark bg-primary mb-4">
    <div class="container">
        <a class="navbar-brand" href="{{ route('dashboard') }}">HRIS</a>

        <ul class="navbar-nav me-auto">
            <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('attendance.index') }}">Absensi</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('leave-requests.index') }}">Cuti</a></li>
            @if (auth()->user()->isManager() || auth()->user()->isAdmin())
                <li class="nav-item"><a class="nav-link" href="{{ route('leave-approvals.index') }}">Persetujuan Cuti</a></li>
            @endif
            @if (auth()->user()->isAdmin())
                <li class="nav-item"><a class="nav-link" href="{{ route('employees.index') }}">Karyawan</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('departments.index') }}">Departemen</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('office-settings.edit') }}">Pengaturan Kantor</a></li>
            @endif
        </ul>

        <div class="d-flex align-items-center gap-3 text-white">
            <span>{{ auth()->user()->name }}
                <span class="badge bg-light text-primary">{{ auth()->user()->role }}</span>
            </span>
            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button class="btn btn-sm btn-outline-light">Logout</button>
            </form>
        </div>
    </div>
</nav>
@endauth

<div class="container pb-5">
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @yield('content')
</div>

</body>
</html>