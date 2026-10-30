<?php

namespace App\Http\Controllers;

use App\Models\OfficeSetting;
use App\Services\GeoService;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    // Halaman absensi: status hari ini + riwayat milik sendiri
    public function index(Request $request)
    {
        $user    = $request->user();
        $today   = $user->attendances()->where('date', today()->toDateString())->first();
        $history = $user->attendances()->orderByDesc('date')->paginate(10);
        $office  = OfficeSetting::current();

        return view('attendance.index', compact('today', 'history', 'office'));
    }

    public function checkIn(Request $request, GeoService $geo)
    {
        // Koordinat dari browser tetap divalidasi di server, tidak dipercaya begitu saja
        $data = $request->validate([
            'latitude'  => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
        ]);

        $office = OfficeSetting::current();
        if (! $office) {
            return back()->with('error', 'Lokasi kantor belum diatur oleh admin.');
        }

        $user = $request->user();

        if ($user->attendances()->where('date', today()->toDateString())->exists()) {
            return back()->with('error', 'Anda sudah check-in hari ini.');
        }

        $distance = $geo->distanceInMeters(
            $office->latitude, $office->longitude,
            (float) $data['latitude'], (float) $data['longitude']
        );

        if ($distance > $office->radius_m) {
            return back()->with('error',
                'Anda berada ' . round($distance) . ' meter dari kantor. Maksimal ' . $office->radius_m . ' meter.');
        }

        $now    = now(); // memakai APP_TIMEZONE (Asia/Jakarta)
        $status = $now->format('H:i:s') > $office->work_start_time ? 'late' : 'present';

        try {
            $user->attendances()->create([
                'date'         => today()->toDateString(),
                'check_in'     => $now->format('H:i:s'),
                'check_in_lat' => $data['latitude'],
                'check_in_lng' => $data['longitude'],
                'status'       => $status,
            ]);
        } catch (QueryException $e) {
            // Misalnya tombol ditekan dua kali cepat dan terkena unique(user_id, date)
            return back()->with('error', 'Gagal menyimpan absensi, silakan coba lagi.');
        }

        return back()->with('success',
            'Check-in berhasil pukul ' . $now->format('H:i') . ($status === 'late' ? ' (terlambat).' : '.'));
    }

    public function checkOut(Request $request)
    {
        $record = $request->user()->attendances()->where('date', today()->toDateString())->first();

        if (! $record || ! $record->check_in) {
            return back()->with('error', 'Anda belum check-in hari ini.');
        }

        if ($record->check_out) {
            return back()->with('error', 'Anda sudah check-out hari ini.');
        }

        $record->update(['check_out' => now()->format('H:i:s')]);

        return back()->with('success', 'Check-out berhasil pukul ' . now()->format('H:i') . '.');
    }
}