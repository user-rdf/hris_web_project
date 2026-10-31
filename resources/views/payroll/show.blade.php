@extends('layouts.app')

@section('title', 'Slip Gaji')

@section('content')
<div class="row justify-content-center">
<div class="col-md-8">
    <div class="card shadow-sm">
        <div class="card-body p-4">
            <h4 class="mb-1">Slip Gaji</h4>
            <p class="text-muted">Periode {{ $payroll->period }}</p>

            <table class="table table-borderless mb-4">
                <tr><td width="30%">Nama</td><td>: {{ $payroll->user->name }}</td></tr>
                <tr><td>Departemen</td><td>: {{ $payroll->user->department?->name ?? '-' }}</td></tr>
                <tr><td>Email</td><td>: {{ $payroll->user->email }}</td></tr>
            </table>

            <table class="table">
                <tr><th colspan="2">Pendapatan</th></tr>
                <tr><td>Gaji pokok</td><td class="text-end">Rp {{ number_format($payroll->basic_salary, 0, ',', '.') }}</td></tr>
                <tr><td>Tunjangan</td><td class="text-end">Rp {{ number_format($payroll->allowance, 0, ',', '.') }}</td></tr>

                <tr><th colspan="2">Potongan</th></tr>
                <tr>
                    <td>Keterlambatan ({{ $payroll->late_days }} hari)</td>
                    <td class="text-end">- Rp {{ number_format($payroll->late_deduction, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Alpa / izin tanpa gaji ({{ $payroll->absent_days }} hari)</td>
                    <td class="text-end">- Rp {{ number_format($payroll->absent_deduction, 0, ',', '.') }}</td>
                </tr>

                <tr class="table-primary">
                    <th>Gaji bersih</th>
                    <th class="text-end">Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}</th>
                </tr>
            </table>

            <div class="d-print-none">
                <button onclick="window.print()" class="btn btn-primary">Cetak</button>
                <a href="{{ route('payrolls.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </div>
</div>
</div>
@endsection