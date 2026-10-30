@extends('layouts.app')

@section('title', 'Karyawan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Karyawan</h3>
    <a href="{{ route('employees.create') }}" class="btn btn-primary">+ Tambah</a>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
    <table class="table table-striped mb-0 align-middle">
        <thead>
            <tr>
                <th>Nama</th><th>Email</th><th>Role</th><th>Departemen</th>
                <th>Atasan</th><th>Gaji Pokok</th><th class="text-end">Aksi</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($employees as $employee)
            <tr>
                <td>{{ $employee->name }}</td>
                <td>{{ $employee->email }}</td>
                <td><span class="badge bg-secondary">{{ $employee->role }}</span></td>
                <td>{{ $employee->department?->name ?? '-' }}</td>
                <td>{{ $employee->manager?->name ?? '-' }}</td>
                <td>Rp {{ number_format($employee->basic_salary, 0, ',', '.') }}</td>
                <td class="text-end text-nowrap">
                    <a href="{{ route('employees.edit', $employee) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('employees.destroy', $employee) }}" method="POST" class="d-inline"
                          onsubmit="return confirm('Hapus karyawan ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="7" class="text-center">Belum ada data.</td></tr>
        @endforelse
        </tbody>
    </table>
    </div>
</div>

<div class="mt-3">{{ $employees->links() }}</div>
@endsection