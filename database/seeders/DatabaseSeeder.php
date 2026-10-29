<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Urutan penting: tabel induk harus terisi sebelum tabel yang bergantung padanya
        $this->call([
            DepartmentSeeder::class,
            OfficeSettingSeeder::class,
            LeaveTypeSeeder::class,
            UserSeeder::class,
            AttendanceSeeder::class,
            LeaveReviewSeeder::class,
        ]);
    }
}
