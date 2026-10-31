@extends('layouts.app')

@section('title', 'Payroll')

@section('content')
    <h3>Payroll</h3>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex flex-wrap gap-4 align-items-end">
                <form method="GET" action="{{ route('payrolls.index') }}" class="d-flex gap-2 align-items-end">
                    <div>
                        <label class="form-label">Lihat periode</label>
                        <input type="month" name="period" value="{{ $period }}" class="form-control" required>
                    </div>
                    <button class="btn btn-outline-primary">Lihat</button>
                </form>

                <form method="POST" action="{{ route('payrolls.generate') }}" class="d-flex gap-2 align-items-end"
                    data-confirm="Hitung payroll untuk periode ini? Data periode yang sama akan dihitung ulang.">
                    @csrf
                    <div>
                        <label class="form-label">Generate periode</label>
                        <input type="month" name="period" value="{{ $period }}" class="form-control" required>
                    </div>
                    <button class="btn btn-primary">Generate Payroll</button>
                </form>
            </div>
            <div class="small text-muted mt-3">
                Potongan telat Rp 50.000/hari. Potongan alpa dan izin tanpa gaji = gaji pokok &divide; 22 per hari.
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-striped mb-0 align-middle">
                <thead>
                    <tr>
                        <th>Karyawan</th>
                        <th>Gaji Pokok</th>
                        <th>Tunjangan</th>
                        <th>Telat</th>
                        <th>Alpa</th>
                        <th>Potongan</th>
                        <th>Gaji Bersih</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($payrolls as $payroll)
                        <tr>
                            <td>{{ $payroll->user->name }}
                                <div class="small text-muted">{{ $payroll->user->department?->name }}</div>
                            </td>
                            <td>Rp {{ number_format($payroll->basic_salary, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($payroll->allowance, 0, ',', '.') }}</td>
                            <td>{{ $payroll->late_days }} hari</td>
                            <td>{{ $payroll->absent_days }} hari</td>
                            <td>Rp {{ number_format($payroll->late_deduction + $payroll->absent_deduction, 0, ',', '.') }}</td>
                            <td><strong>Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}</strong></td>
                            <td class="text-end">
                                <a href="{{ route('payrolls.show', $payroll) }}" class="btn btn-sm btn-outline-primary">Slip</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Belum ada payroll untuk periode {{ $period }}. Klik Generate
                                Payroll.</td>
                        </tr>
                    @endforelse
                </tbody>
                @if ($payrolls->isNotEmpty())
                    <tfoot>
                        <tr class="table-light">
                            <td colspan="6" class="text-end"><strong>Total gaji bersih</strong></td>
                            <td colspan="2"><strong>Rp {{ number_format($payrolls->sum('net_salary'), 0, ',', '.') }}</strong>
                            </td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>
@endsection