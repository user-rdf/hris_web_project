<div class="mb-3">
    <label class="form-label">Nama Departemen</label>
    <input type="text" name="name" value="{{ old('name', $department?->name) }}"
           class="form-control @error('name') is-invalid @enderror" required>
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<button class="btn btn-primary">Simpan</button>
<a href="{{ route('departments.index') }}" class="btn btn-secondary">Batal</a>