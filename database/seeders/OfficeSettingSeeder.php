<?php

namespace Database\Seeders;

use App\Models\OfficeSetting;
use Illuminate\Database\Seeder;

class OfficeSettingSeeder extends Seeder
{
    public function run(): void
    {
        // Koordinat dummy (Jakarta). Ganti lewat menu Pengaturan Kantor atau di sini
        // dengan koordinat tempatmu agar bisa mencoba absen saat demo.
        OfficeSetting::create([
            'name'            => 'Kantor Pusat',
            'latitude'        => -6.1754000,
            'longitude'       => 106.8272000,
            'radius_m'        => 100,
            'work_start_time' => '08:00:00',
        ]);
    }
}
