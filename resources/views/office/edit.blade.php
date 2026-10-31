@extends('layouts.app')

@section('title', 'Pengaturan Kantor')

@section('content')
    <h3>Pengaturan Kantor</h3>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('office-settings.update') }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Nama Kantor</label>
                    <input type="text" name="name" value="{{ old('name', $office->name) }}"
                        class="form-control @error('name') is-invalid @enderror" required>
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Latitude</label>
                        <input type="number" step="any" name="latitude" id="latitude"
                            value="{{ old('latitude', $office->latitude) }}"
                            class="form-control @error('latitude') is-invalid @enderror" required>
                        @error('latitude')
                        <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Longitude</label>
                        <input type="number" step="any" name="longitude" id="longitude"
                            value="{{ old('longitude', $office->longitude) }}"
                            class="form-control @error('longitude') is-invalid @enderror" required>
                        @error('longitude')
                        <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <button type="button" id="btn-here" class="btn btn-outline-primary mb-3">
                    Gunakan lokasi saya sekarang
                </button>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Radius absen (meter)</label>
                        <input type="number" name="radius_m" value="{{ old('radius_m', $office->radius_m ?? 100) }}"
                            class="form-control @error('radius_m') is-invalid @enderror" required>
                        @error('radius_m')
                        <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Jam masuk</label>
                        <input type="time" name="work_start_time"
                            value="{{ old('work_start_time', substr($office->work_start_time ?? '08:00:00', 0, 5)) }}"
                            class="form-control @error('work_start_time') is-invalid @enderror" required>
                        @error('work_start_time')
                        <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <button class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('btn-here').addEventListener('click', function () {
            if (!navigator.geolocation) { notify('error', 'Browser tidak mendukung GPS.'); return; }

            navigator.geolocation.getCurrentPosition(
                function (pos) {
                    document.getElementById('latitude').value = pos.coords.latitude.toFixed(7);
                    document.getElementById('longitude').value = pos.coords.longitude.toFixed(7);
                },
                function (err) { notify('error', 'Gagal mengambil lokasi: ' + err.message); },
                { enableHighAccuracy: true, timeout: 15000 }
            );
        });
    </script>
@endsection