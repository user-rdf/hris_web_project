<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\OfficeSetting;
use App\Models\User;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $office = OfficeSetting::current();
        $users  = User::whereIn('role', ['manager', 'employee'])->get();

        // Dari awal bulan lalu sampai kemarin, supaya payroll bulan lalu punya data lengkap
        $start = now()->subMonthNoOverflow()->startOfMonth();
        $end   = now()->subDay()->startOfDay();

        mt_srand(42); // seed tetap -> data dummy selalu sama setiap kali di-seed

        $rows = [];
        foreach ($users as $user) {
            for ($day = $start->copy(); $day->lte($end); $day->addDay()) {
                if ($day->isWeekend()) {
                    continue;
                }

                $roll = mt_rand(1, 100);
                if ($roll <= 5) {            // ~5% alpa
                    $status = 'absent';
                    $in = $out = $lat = $lng = null;
                } else {
                    $status = $roll <= 20 ? 'late' : 'present'; // ~15% telat
                    $in  = $status === 'late'
                        ? sprintf('08:%02d:00', mt_rand(10, 59))
                        : sprintf('07:%02d:00', mt_rand(40, 59));
                    $out = sprintf('17:%02d:00', mt_rand(0, 30));
                    // sebaran +- 55 meter dari kantor (masih dalam radius)
                    $lat = $office->latitude  + mt_rand(-50, 50) / 100000;
                    $lng = $office->longitude + mt_rand(-50, 50) / 100000;
                }

                $rows[] = [
                    'user_id' => $user->id, 'date' => $day->toDateString(),
                    'check_in' => $in, 'check_out' => $out,
                    'check_in_lat' => $lat, 'check_in_lng' => $lng,
                    'status' => $status, 'created_at' => now(), 'updated_at' => now(),
                ];
            }
        }

        // insert massal (satu query per chunk) lebih cepat daripada create() satu per satu
        foreach (array_chunk($rows, 200) as $chunk) {
            Attendance::insert($chunk);
        }
    }
}
