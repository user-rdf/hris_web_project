@extends('layouts.app')

@section('title', 'Departemen')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Departemen</h3>
        <a href="{{ route('departments.create') }}" class="btn btn-primary">+ Tambah</a>
    </div>

    <div class="card shadow-sm">
        <table class="table table-striped mb-0">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Jumlah Karyawan</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($departments as $department)
                    <tr>
                        <td>{{ $department->name }}</td>
                        <td>{{ $department->users_count }}</td>
                        <td class="text-end">
                            <a href="{{ route('departments.edit', $department) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('departments.destroy', $department) }}" method="POST" class="d-inline"
                                data-confirm="Hapus departemen ini?">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">Belum ada data.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection