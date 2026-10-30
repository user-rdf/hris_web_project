@extends('layouts.app')

@section('title', 'Absensi')

@section('content')
<h3>Absensi</h3>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <p class="mb-1"><strong>{{ now()->translatedFormat('l, d F Y') }}</strong></p>
        @if ($office)
            <p class="text-muted">
                Lokasi: {{ $office->name }} &middot; radius {{ $office->radius_m }} m &middot;
                jam masuk {{ substr($office->work_start_time, 0, 5) }}
            </p>
        @else
            <p class="text-danger">Lokasi kantor belum diatur.</p>
        @endif

        @if (! $today)
            {{-- Belum check-in: ambil lokasi dulu lewat JavaScript, lalu kirim form --}}
            <form id="checkin-form" method="POST" action="{{ route('attendance.checkin') }}">
                @csrf
                <input type="hidden" name="latitude" id="lat">
                <input type="hidden" name="longitude" id="lng">
                <button type="button" id="btn-checkin" class="btn btn-success btn-lg">Check-in</button>
            </form>
        @elseif (! $today->check_out)
            <p>Check-in: <strong>{{ substr($today->check_in, 0, 5) }}</strong>
                <span class="badge {{ $today->status === 'late' ? 'bg-warning text-dark' : 'bg-success' }}">
                    {{ $today->status === 'late' ? 'Terlambat' : 'Tepat waktu' }}
                </span>
            </p>
            <form method="POST" action="{{ route('attendance.checkout') }}">
                @csrf
                <button class="btn btn-danger btn-lg">Check-out</button>
            </form>
        @else
            <p class="mb-0 text-success">
                Absensi hari ini selesai. Masuk {{ substr($today->check_in, 0, 5) }},
                pulang {{ substr($today->check_out, 0, 5) }}.
            </p>
        @endif
    </div>
</div>

<h5>Riwayat</h5>
<div class="card shadow-sm">
    <table class="table table-striped mb-0">
        <thead>
            <tr><th>Tanggal</th><th>Masuk</th><th>Pulang</th><th>Status</th></tr>
        </thead>
        <tbody>
        @forelse ($history as $row)
            <tr>
                <td>{{ $row->date->format('d M Y') }}</td>
                <td>{{ $row->check_in ? substr($row->check_in, 0, 5) : '-' }}</td>
                <td>{{ $row->check_out ? substr($row->check_out, 0, 5) : '-' }}</td>
                <td>
                    @if ($row->status === 'present') <span class="badge bg-success">Hadir</span>
                    @elseif ($row->status === 'late') <span class="badge bg-warning text-dark">Terlambat</span>
                    @else <span class="badge bg-danger">Alpa</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr><td colspan="4" class="text-center">Belum ada riwayat.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="mt-3">{{ $history->links() }}</div>

<script>
    const btn = document.getElementById('btn-checkin');

    if (btn) {
        btn.addEventListener('click', function () {
            if (!navigator.geolocation) {
                alert('Browser Anda tidak mendukung GPS.');
                return;
            }

            btn.disabled = true;
            btn.textContent = 'Mengambil lokasi...';

            navigator.geolocation.getCurrentPosition(
                function (pos) {
                    document.getElementById('lat').value = pos.coords.latitude;
                    document.getElementById('lng').value = pos.coords.longitude;
                    document.getElementById('checkin-form').submit();
                },
                function (err) {
                    alert('Gagal mengambil lokasi: ' + err.message);
                    btn.disabled = false;
                    btn.textContent = 'Check-in';
                },
                { enableHighAccuracy: true, timeout: 15000 }
            );
        });
    }
</script>
@endsection