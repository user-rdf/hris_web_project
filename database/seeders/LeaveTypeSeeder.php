<?php

namespace Database\Seeders;

use App\Models\LeaveType;
use Illuminate\Database\Seeder;

class LeaveTypeSeeder extends Seeder
{
    public function run(): void
    {
        LeaveType::create(['name' => 'Cuti Tahunan',    'is_paid' => true]);
        LeaveType::create(['name' => 'Sakit',           'is_paid' => true]);
        LeaveType::create(['name' => 'Izin Tanpa Gaji', 'is_paid' => false]);
    }
}
