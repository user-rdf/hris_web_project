<?php

namespace Database\Seeders;

use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\PerformanceReview;
use App\Models\User;
use Illuminate\Database\Seeder;

class LeaveReviewSeeder extends Seeder
{
    public function run(): void
    {
        $employees = User::where('role', 'employee')->get();
        $annual    = LeaveType::where('name', 'Cuti Tahunan')->first();
        $sick      = LeaveType::where('name', 'Sakit')->first();

        // Tiga contoh pengajuan cuti: disetujui, menunggu, ditolak
        // (status & approved_by tidak fillable, jadi di-set manual)
        $samples = [
            [$employees[0], $annual, now()->subDays(20), now()->subDays(18), 'Acara keluarga',  'approved'],
            [$employees[1], $annual, now()->addDays(5),  now()->addDays(7),  'Liburan singkat', 'pending'],
            [$employees[5], $sick,   now()->subDays(10), now()->subDays(9),  'Demam dan flu',   'rejected'],
        ];

        foreach ($samples as [$user, $type, $from, $to, $reason, $status]) {
            $leave = new LeaveRequest([
                'user_id' => $user->id, 'leave_type_id' => $type->id,
                'start_date' => $from->toDateString(), 'end_date' => $to->toDateString(),
                'reason' => $reason,
            ]);
            $leave->status = $status;
            $leave->approved_by = $status === 'pending' ? null : $user->manager_id;
            $leave->save();
        }

        // Evaluasi bulan lalu untuk setiap karyawan, dinilai atasan langsungnya
        mt_srand(7);
        $period = now()->subMonthNoOverflow()->format('Y-m');
        $notes  = ['Kinerja sangat baik', 'Perlu meningkatkan ketepatan waktu', 'Konsisten dan bisa diandalkan'];

        foreach ($employees as $employee) {
            $review = new PerformanceReview([
                'user_id' => $employee->id, 'period' => $period,
                'score'   => mt_rand(3, 5), 'notes' => $notes[array_rand($notes)],
            ]);
            $review->reviewer_id = $employee->manager_id;
            $review->save();
        }
    }
}
