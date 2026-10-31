@extends('layouts.app')

@section('title', 'Edit Penilaian')

@section('content')
<h3>Edit Penilaian</h3>
<p class="text-muted">{{ $review->employee->name }} &middot; periode {{ $review->period }}</p>

<div class="card shadow-sm"><div class="card-body">
    <form method="POST" action="{{ route('performance-reviews.update', $review) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Skor</label>
            <select name="score" class="form-select @error('score') is-invalid @enderror" required>
                @foreach ([5 => '5 - Sangat baik', 4 => '4 - Baik', 3 => '3 - Cukup', 2 => '2 - Kurang', 1 => '1 - Sangat kurang'] as $value => $label)
                    <option value="{{ $value }}" {{ old('score', $review->score) == $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            @error('score') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Catatan</label>
            <textarea name="notes" rows="3" class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $review->notes) }}</textarea>
            @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <button class="btn btn-primary">Simpan</button>
        <a href="{{ route('performance-reviews.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div></div>
@endsection