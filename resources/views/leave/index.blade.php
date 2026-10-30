@extends('layouts.app')

@section('title', 'Cuti Saya')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="mb-0">Cuti Saya</h3>
        <span class="text-muted">Sisa saldo cuti tahunan: <strong>{{ auth()->user()->leave_balance }} hari</strong></span>
    </div>
    <a href="{{ route('leave-requests.create') }}" class="btn btn-primary">+ Ajukan Cuti</a>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
    <table class="table table-striped mb-0 align-middle">
        <thead>
            <tr><th>Jenis</th><th>Tanggal</th><th>Hari Kerja</th><th>Alasan</th><th>Status</th><th class="text-end">Aksi</th></tr>
        </thead>
        <tbody>
        @forelse ($leaves as $leave)
            <tr>
                <td>{{ $leave->leaveType->name }}</td>
                <td>{{ $leave->start_date->format('d M Y') }} - {{ $leave->end_date->format('d M Y') }}</td>
                <td>{{ $leave->days }}</td>
                <td>{{ $leave->reason }}</td>
                <td>
                    @if ($leave->status === 'pending') <span class="badge bg-warning text-dark">Menunggu</span>
                    @elseif ($leave->status === 'approved') <span class="badge bg-success">Disetujui</span>
                    @else <span class="badge bg-danger">Ditolak</span>
                    @endif
                    @if ($leave->approver)
                        <div class="small text-muted">oleh {{ $leave->approver->name }}</div>
                    @endif
                </td>
                <td class="text-end">
                    @can('cancel', $leave)
                        <form action="{{ route('leave-requests.destroy', $leave) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Batalkan pengajuan ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Batalkan</button>
                        </form>
                    @endcan
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="text-center">Belum ada pengajuan.</td></tr>
        @endforelse
        </tbody>
    </table>
    </div>
</div>
<div class="mt-3">{{ $leaves->links() }}</div>
@endsection