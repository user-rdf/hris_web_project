@extends('layouts.app')

@section('title', 'Slip Gaji Saya')

@section('content')
<h3>Slip Gaji Saya</h3>

<div class="card shadow-sm">
    <table class="table table-striped mb-0 align-middle">
        <thead><tr><th>Periode</th><th>Gaji Bersih</th><th></th></tr></thead>
        <tbody>
        @forelse ($payrolls as $payroll)
            <tr>
                <td>{{ $payroll->period }}</td>
                <td>Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}</td>
                <td class="text-end">
                    <a href="{{ route('payrolls.show', $payroll) }}" class="btn btn-sm btn-outline-primary">Lihat Slip</a>
                </td>
            </tr>
        @empty
            <tr><td colspan="3" class="text-center">Belum ada slip gaji.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection