@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
@php($me = auth()->user())

<div class="mb-4">
    <h3 class="mb-1">Halo, {{ strtok($me->name, ' ') }}</h3>
    <div class="text-muted">
        Anda masuk sebagai <span class="badge bg-primary-subtle text-primary">{{ $me->role_label }}</span>
    </div>
</div>

<div class="row g-3 mb-4">
    @if ($me->isAdmin())
        @include('partials.stat', ['label' => 'Total karyawan', 'value' => $stats['total_employees'], 'icon' => 'bi-people', 'color' => 'primary', 'url' => route('employees.index')])
        @include('partials.stat', ['label' => 'Hadir hari ini', 'value' => $stats['present_today'], 'icon' => 'bi-person-check', 'color' => 'success'])
        @include('partials.stat', ['label' => 'Cuti menunggu', 'value' => $stats['pending_leaves'], 'icon' => 'bi-hourglass-split', 'color' => 'warning', 'url' => route('leave-approvals.index')])
        @include('partials.stat', ['label' => 'Gaji bersih bulan lalu', 'value' => 'Rp ' . number_format($stats['last_payroll'], 0, ',', '.'), 'icon' => 'bi-cash-stack', 'color' => 'info', 'url' => route('payrolls.index')])
    @elseif ($me->isManager())
        @include('partials.stat', ['label' => 'Anggota tim', 'value' => $stats['team_size'], 'icon' => 'bi-people', 'color' => 'primary'])
        @include('partials.stat', ['label' => 'Tim hadir hari ini', 'value' => $stats['present_today'], 'icon' => 'bi-person-check', 'color' => 'success'])
        @include('partials.stat', ['label' => 'Cuti menunggu', 'value' => $stats['pending_leaves'], 'icon' => 'bi-hourglass-split', 'color' => 'warning', 'url' => route('leave-approvals.index')])
        @include('partials.stat', ['label' => 'Sisa cuti saya', 'value' => $stats['leave_balance'] . ' hari', 'icon' => 'bi-calendar2-check', 'color' => 'info'])
    @else
        @include('partials.stat', [
            'label' => 'Absensi hari ini',
            'value' => $stats['attendance_today'] ? 'Masuk ' . substr($stats['attendance_today']->check_in, 0, 5) : 'Belum absen',
            'icon' => 'bi-geo-alt', 'color' => $stats['attendance_today'] ? 'success' : 'danger', 'url' => route('attendance.index'),
        ])
        @include('partials.stat', ['label' => 'Sisa cuti', 'value' => $stats['leave_balance'] . ' hari', 'icon' => 'bi-calendar2-check', 'color' => 'primary', 'url' => route('leave-requests.index')])
        @include('partials.stat', ['label' => 'Cuti menunggu', 'value' => $stats['pending_leaves'], 'icon' => 'bi-hourglass-split', 'color' => 'warning'])
        @include('partials.stat', [
            'label' => 'Gaji bersih bulan lalu',
            'value' => $stats['last_payroll'] ? 'Rp ' . number_format($stats['last_payroll'], 0, ',', '.') : '-',
            'icon' => 'bi-cash-stack', 'color' => 'info', 'url' => route('payrolls.index'),
        ])
    @endif
</div>

<div class="row g-3">
    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="fw-bold mb-0">Rekap kehadiran bulan ini</h6>
                <div class="text-muted small mb-4">{{ $me->isAdmin() ? 'Seluruh karyawan' : 'Milik Anda' }}</div>

                <div class="row text-center g-3">
                    <div class="col-4">
                        <div class="fs-2 fw-bold text-success">{{ $summary['present'] ?? 0 }}</div>
                        <div class="text-muted small">Tepat waktu</div>
                    </div>
                    <div class="col-4">
                        <div class="fs-2 fw-bold text-warning">{{ $summary['late'] ?? 0 }}</div>
                        <div class="text-muted small">Terlambat</div>
                    </div>
                    <div class="col-4">
                        <div class="fs-2 fw-bold text-danger">{{ $summary['absent'] ?? 0 }}</div>
                        <div class="text-muted small">Alpa</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Akses cepat</h6>
                <div class="d-grid gap-2">
                    <a href="{{ route('attendance.index') }}" class="btn btn-primary"><i class="bi bi-geo-alt me-2"></i>Absen sekarang</a>
                    <a href="{{ route('leave-requests.create') }}" class="btn btn-outline-primary"><i class="bi bi-calendar2-plus me-2"></i>Ajukan cuti</a>
                    @if ($me->isAdmin())
                        <a href="{{ route('payrolls.index') }}" class="btn btn-outline-primary"><i class="bi bi-cash-coin me-2"></i>Kelola payroll</a>
                        <a href="{{ route('employees.create') }}" class="btn btn-outline-primary"><i class="bi bi-person-plus me-2"></i>Tambah karyawan</a>
                    @else
                        <a href="{{ route('payrolls.index') }}" class="btn btn-outline-primary"><i class="bi bi-cash-coin me-2"></i>Lihat slip gaji</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection