<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Nama</label>
        <input type="text" name="name" value="{{ old('name', $employee?->name) }}"
               class="form-control @error('name') is-invalid @enderror" required>
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" value="{{ old('email', $employee?->email) }}"
               class="form-control @error('email') is-invalid @enderror" required>
        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Password {{ $employee ? '(kosongkan jika tidak diganti)' : '' }}</label>
        <input type="password" name="password"
               class="form-control @error('password') is-invalid @enderror" {{ $employee ? '' : 'required' }}>
        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Departemen</label>
        <select name="department_id" class="form-select @error('department_id') is-invalid @enderror" required>
            <option value="">-- Pilih --</option>
            @foreach ($departments as $department)
                <option value="{{ $department->id }}"
                    {{ old('department_id', $employee?->department_id) == $department->id ? 'selected' : '' }}>
                    {{ $department->name }}
                </option>
            @endforeach
        </select>
        @error('department_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Role</label>
        <select name="role" class="form-select @error('role') is-invalid @enderror" required>
            @foreach (['employee' => 'Karyawan', 'manager' => 'Manager', 'admin' => 'Admin HR'] as $value => $label)
                <option value="{{ $value }}" {{ old('role', $employee?->role ?? 'employee') === $value ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Atasan (Manager)</label>
        <select name="manager_id" class="form-select @error('manager_id') is-invalid @enderror">
            <option value="">-- Tidak ada --</option>
            @foreach ($managers as $manager)
                <option value="{{ $manager->id }}"
                    {{ old('manager_id', $employee?->manager_id) == $manager->id ? 'selected' : '' }}>
                    {{ $manager->name }}
                </option>
            @endforeach
        </select>
        @error('manager_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Gaji Pokok (Rp)</label>
        <input type="number" step="1" name="basic_salary" value="{{ old('basic_salary', $employee?->basic_salary) }}"
               class="form-control @error('basic_salary') is-invalid @enderror" required>
        @error('basic_salary') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Tunjangan (Rp)</label>
        <input type="number" step="1" name="allowance" value="{{ old('allowance', $employee?->allowance) }}"
               class="form-control @error('allowance') is-invalid @enderror" required>
        @error('allowance') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Jatah Cuti (hari)</label>
        <input type="number" name="leave_balance" value="{{ old('leave_balance', $employee?->leave_balance ?? 12) }}"
               class="form-control @error('leave_balance') is-invalid @enderror" required>
        @error('leave_balance') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<button class="btn btn-primary">Simpan</button>
<a href="{{ route('employees.index') }}" class="btn btn-secondary">Batal</a>