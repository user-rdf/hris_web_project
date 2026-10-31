@extends('layouts.app')

@section('title', 'Persetujuan Cuti')

@section('content')
    <h3>Persetujuan Cuti</h3>

    <h5 class="mt-4">Menunggu keputusan</h5>
    <div class="card shadow-sm mb-4">
        <div class="table-responsive">
            <table class="table table-striped mb-0 align-middle">
                <thead>
                    <tr>
                        <th>Karyawan</th>
                        <th>Jenis</th>
                        <th>Tanggal</th>
                        <th>Hari Kerja</th>
                        <th>Alasan</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pending as $leave)
                        <tr>
                            <td>{{ $leave->user->name }}
                                <div class="small text-muted">Saldo: {{ $leave->user->leave_balance }} hari</div>
                            </td>
                            <td>{{ $leave->leaveType->name }}</td>
                            <td>{{ $leave->start_date->translatedFormat('d M Y') }} - {{ $leave->end_date->translatedFormat('d M Y') }}</td>
                            <td>{{ $leave->days }}</td>
                            <td>{{ $leave->reason }}</td>
                            <td class="text-end text-nowrap">
                                <form action="{{ route('leave-requests.approve', $leave) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn btn-sm btn-success">Setujui</button>
                                </form>
                                <form action="{{ route('leave-requests.reject', $leave) }}" method="POST" class="d-inline"
                                    data-confirm="Tolak pengajuan ini?">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn btn-sm btn-danger">Tolak</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada pengajuan yang menunggu.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <h5>Keputusan terakhir Anda</h5>
    <div class="card shadow-sm">
        <table class="table table-striped mb-0">
            <thead>
                <tr>
                    <th>Karyawan</th>
                    <th>Jenis</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($decided as $leave)
                    <tr>
                        <td>{{ $leave->user->name }}</td>
                        <td>{{ $leave->leaveType->name }}</td>
                        <td>{{ $leave->start_date->translatedFormat('d M Y') }} - {{ $leave->end_date->translatedFormat('d M Y') }}</td>
                        <td>
                            @if ($leave->status === 'approved') <span class="badge bg-success">Disetujui</span>
                            @else <span class="badge bg-danger">Ditolak</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Belum ada keputusan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection