@extends('layouts.app')

@section('title', 'Tambah Penilaian')

@section('content')
<h3>Tambah Penilaian</h3>
<div class="card shadow-sm"><div class="card-body">
    <form method="POST" action="{{ route('performance-reviews.store') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">Karyawan</label>
            <select name="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
                <option value="">-- Pilih --</option>
                @foreach ($employees as $employee)
                    <option value="{{ $employee->id }}" {{ old('user_id') == $employee->id ? 'selected' : '' }}>
                        {{ $employee->name }}
                    </option>
                @endforeach
            </select>
            @error('user_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Periode (bulan)</label>
                <input type="month" name="period" max="{{ now()->format('Y-m') }}"
                       value="{{ old('period', now()->subMonthNoOverflow()->format('Y-m')) }}"
                       class="form-control @error('period') is-invalid @enderror" required>
                @error('period') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Skor</label>
                <select name="score" class="form-select @error('score') is-invalid @enderror" required>
                    @foreach ([5 => '5 - Sangat baik', 4 => '4 - Baik', 3 => '3 - Cukup', 2 => '2 - Kurang', 1 => '1 - Sangat kurang'] as $value => $label)
                        <option value="{{ $value }}" {{ old('score', 3) == $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('score') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Catatan</label>
            <textarea name="notes" rows="3" class="form-control @error('notes') is-invalid @enderror">{{ old('notes') }}</textarea>
            @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <button class="btn btn-primary">Simpan</button>
        <a href="{{ route('performance-reviews.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div></div>
@endsection