<!DOCTYPE html>
<html lang="id">
<head>
    @include('layouts._head')
    <title>@yield('title', 'Beranda') - HRIS</title>
</head>
<body>
@php($me = auth()->user())

<div class="sidebar-backdrop" id="sidebar-backdrop"></div>

<aside class="sidebar" id="sidebar">
    <a href="{{ route('dashboard') }}" class="brand"><i class="bi bi-people-fill"></i> HRIS</a>

    <nav class="nav flex-column pb-4">
        <div class="nav-label">Menu</div>
        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <a class="nav-link {{ request()->routeIs('attendance.*') ? 'active' : '' }}" href="{{ route('attendance.index') }}">
            <i class="bi bi-geo-alt"></i> Absensi
        </a>
        <a class="nav-link {{ request()->routeIs('leave-requests.*') ? 'active' : '' }}" href="{{ route('leave-requests.index') }}">
            <i class="bi bi-calendar2-check"></i> Cuti
        </a>
        <a class="nav-link {{ request()->routeIs('performance-reviews.*') ? 'active' : '' }}" href="{{ route('performance-reviews.index') }}">
            <i class="bi bi-star"></i> Evaluasi
        </a>
        <a class="nav-link {{ request()->routeIs('payrolls.*') ? 'active' : '' }}" href="{{ route('payrolls.index') }}">
            <i class="bi bi-cash-coin"></i> {{ $me->isAdmin() ? 'Payroll' : 'Slip Gaji' }}
        </a>

        @if ($me->isManager() || $me->isAdmin())
            <div class="nav-label">Persetujuan</div>
            <a class="nav-link {{ request()->routeIs('leave-approvals.*') ? 'active' : '' }}" href="{{ route('leave-approvals.index') }}">
                <i class="bi bi-check2-square"></i> Persetujuan Cuti
            </a>
        @endif

        @if ($me->isAdmin())
            <div class="nav-label">Administrasi</div>
            <a class="nav-link {{ request()->routeIs('employees.*') ? 'active' : '' }}" href="{{ route('employees.index') }}">
                <i class="bi bi-people"></i> Karyawan
            </a>
            <a class="nav-link {{ request()->routeIs('departments.*') ? 'active' : '' }}" href="{{ route('departments.index') }}">
                <i class="bi bi-diagram-3"></i> Departemen
            </a>
            <a class="nav-link {{ request()->routeIs('office-settings.*') ? 'active' : '' }}" href="{{ route('office-settings.edit') }}">
                <i class="bi bi-gear"></i> Pengaturan Kantor
            </a>
        @endif
    </nav>
</aside>

<div class="main">
    <header class="topbar d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-3">
            <button type="button" class="btn btn-light d-lg-none" id="sidebar-toggle" aria-label="Menu">
                <i class="bi bi-list fs-5"></i>
            </button>
            <div class="text-muted d-none d-sm-block">
                <i class="bi bi-calendar3 me-1"></i>{{ now()->translatedFormat('l, d F Y') }}
            </div>
        </div>

        <div class="dropdown">
            <button class="btn btn-light d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="avatar">{{ mb_strtoupper(mb_substr($me->name, 0, 1)) }}</span>
                <span class="d-none d-sm-block text-start lh-sm">
                    <span class="d-block fw-semibold small">{{ $me->name }}</span>
                    <span class="d-block text-muted" style="font-size: .75rem;">{{ $me->role_label }}</span>
                </span>
                <i class="bi bi-chevron-down small"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Keluar</button>
                    </form>
                </li>
            </ul>
        </div>
    </header>

    <main class="content">
        @yield('content')
    </main>
</div>

@include('layouts._scripts')
</body>
</html>