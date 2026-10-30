@extends('layouts.app')

@section('title', 'Ajukan Cuti')

@section('content')
<h3>Ajukan Cuti</h3>
<div class="card shadow-sm"><div class="card-body">
    <form method="POST" action="{{ route('leave-requests.store') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">Jenis Cuti</label>
            <select name="leave_type_id" class="form-select @error('leave_type_id') is-invalid @enderror" required>
                <option value="">-- Pilih --</option>
                @foreach ($types as $type)
                    <option value="{{ $type->id }}" {{ old('leave_type_id') == $type->id ? 'selected' : '' }}>
                        {{ $type->name }}{{ $type->is_paid ? '' : ' (tanpa gaji)' }}
                    </option>
                @endforeach
            </select>
            @error('leave_type_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ old('start_date') }}"
                       class="form-control @error('start_date') is-invalid @enderror" required>
                @error('start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Tanggal Selesai</label>
                <input type="date" name="end_date" value="{{ old('end_date') }}"
                       class="form-control @error('end_date') is-invalid @enderror" required>
                @error('end_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Alasan</label>
            <textarea name="reason" rows="3" class="form-control @error('reason') is-invalid @enderror" required>{{ old('reason') }}</textarea>
            @error('reason') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <button class="btn btn-primary">Kirim Pengajuan</button>
        <a href="{{ route('leave-requests.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div></div>
@endsection