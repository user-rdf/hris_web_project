<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\Payroll;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PayrollService
{
    public const LATE_DEDUCTION_PER_DAY = 50000;
    public const WORKDAYS_PER_MONTH     = 22;

    /** Buat atau hitung ulang payroll semua karyawan untuk satu periode (YYYY-MM). */
    public function generate(string $period): int
    {
        // Transaksi: semua baris berhasil, atau tidak ada yang tersimpan
        return DB::transaction(function () use ($period) {
            $users = User::all();

            foreach ($users as $user) {
                // updateOrCreate: periode yang sama dihitung ulang, tidak menjadi baris ganda
                Payroll::updateOrCreate(
                    ['user_id' => $user->id, 'period' => $period],
                    $this->calculate($user, $period)
                );
            }

            return $users->count();
        });
    }

    /** Hitung komponen gaji satu karyawan. */
    public function calculate(User $user, string $period): array
    {
        $start = Carbon::createFromFormat('Y-m-d', $period . '-01')->startOfDay();
        $end   = $start->copy()->endOfMonth()->startOfDay();

        // Hari yang tercatat hadir atau telat
        $attendances = Attendance::where('user_id', $user->id)
            ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
            ->whereIn('status', ['present', 'late'])
            ->get();

        $attendedDates = $attendances->map(fn ($a) => $a->date->toDateString())->flip();
        $lateDays      = $attendances->where('status', 'late')->count();

        // Tanggal cuti yang disetujui pada bulan ini: tanggal => apakah berbayar
        $leaveDates = [];
        $leaves = LeaveRequest::with('leaveType')
            ->where('user_id', $user->id)
            ->where('status', 'approved')
            ->where('start_date', '<=', $end->toDateString())
            ->where('end_date', '>=', $start->toDateString())
            ->get();

        foreach ($leaves as $leave) {
            $from = $leave->start_date->copy()->max($start);
            $to   = $leave->end_date->copy()->min($end);

            for ($d = $from->copy(); $d->lte($to); $d->addDay()) {
                if (! $d->isWeekend()) {
                    $leaveDates[$d->toDateString()] = (bool) $leave->leaveType->is_paid;
                }
            }
        }

        // Hitung hari alpa: hari kerja tanpa kehadiran dan tanpa cuti berbayar
        $absentDays = 0;
        for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
            if ($d->isWeekend()) {
                continue;
            }

            $key = $d->toDateString();

            if ($attendedDates->has($key)) {
                continue;
            }

            if (($leaveDates[$key] ?? false) === true) {
                continue; // cuti berbayar tidak dipotong
            }

            $absentDays++; // alpa atau izin tanpa gaji
        }

        $basic     = (float) $user->basic_salary;
        $allowance = (float) $user->allowance;

        $lateDeduction   = $lateDays * self::LATE_DEDUCTION_PER_DAY;
        $absentDeduction = round($absentDays * ($basic / self::WORKDAYS_PER_MONTH), 2);
        $net             = max(0, $basic + $allowance - $lateDeduction - $absentDeduction);

        return [
            'basic_salary'     => $basic,
            'allowance'        => $allowance,
            'late_days'        => $lateDays,
            'absent_days'      => $absentDays,
            'late_deduction'   => $lateDeduction,
            'absent_deduction' => $absentDeduction,
            'net_salary'       => $net,
        ];
    }
}