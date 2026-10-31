@extends('layouts.app')

@section('title', 'Evaluasi Kinerja')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Evaluasi Kinerja</h3>
        @if (!auth()->user()->isEmployee())
            <a href="{{ route('performance-reviews.create') }}" class="btn btn-primary">+ Tambah Penilaian</a>
        @endif
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-striped mb-0 align-middle">
                <thead>
                    <tr>
                        <th>Periode</th>
                        <th>Karyawan</th>
                        <th>Penilai</th>
                        <th>Skor</th>
                        <th>Catatan</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reviews as $review)
                        <tr>
                            <td>{{ $review->period }}</td>
                            <td>{{ $review->employee->name }}</td>
                            <td>{{ $review->reviewer->name }}</td>
                            <td><span class="badge bg-primary">{{ $review->score }} / 5</span></td>
                            <td>{{ $review->notes ?? '-' }}</td>
                            <td class="text-end text-nowrap">
                                @can('manage', $review)
                                    <a href="{{ route('performance-reviews.edit', $review) }}"
                                        class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('performance-reviews.destroy', $review) }}" method="POST"
                                        class="d-inline" data-confirm="Hapus penilaian ini?">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada penilaian.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $reviews->links() }}</div>
@endsection