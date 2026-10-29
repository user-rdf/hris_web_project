<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $dept = Department::pluck('id', 'name'); // ['IT' => 2, ...]

        // Semua akun dummy memakai password: "password" (di-hash otomatis oleh cast 'hashed')
        // forceCreate dipakai karena 'role' tidak ada di $fillable.
        User::forceCreate([
            'name' => 'Admin HR', 'email' => 'admin@hris.test', 'password' => 'password',
            'role' => 'admin', 'department_id' => $dept['Human Resources'],
            'basic_salary' => 12000000, 'allowance' => 1500000,
        ]);

        $managerIt = User::forceCreate([
            'name' => 'Budi Santoso', 'email' => 'manager.it@hris.test', 'password' => 'password',
            'role' => 'manager', 'department_id' => $dept['IT'],
            'basic_salary' => 15000000, 'allowance' => 2000000,
        ]);

        $managerMkt = User::forceCreate([
            'name' => 'Sari Wulandari', 'email' => 'manager.mkt@hris.test', 'password' => 'password',
            'role' => 'manager', 'department_id' => $dept['Marketing'],
            'basic_salary' => 14000000, 'allowance' => 2000000,
        ]);

        $employees = [
            ['Andi Pratama',    'andi@hris.test',   'IT',        $managerIt,  8000000, 1000000],
            ['Dewi Lestari',    'dewi@hris.test',   'IT',        $managerIt,  8500000, 1000000],
            ['Rizky Ramadhan',  'rizky@hris.test',  'IT',        $managerIt,  7500000, 1000000],
            ['Putri Anggraini', 'putri@hris.test',  'IT',        $managerIt,  9000000, 1200000],
            ['Fajar Nugroho',   'fajar@hris.test',  'IT',        $managerIt,  7000000,  800000],
            ['Maya Sari',       'maya@hris.test',   'Marketing', $managerMkt, 7000000,  800000],
            ['Doni Setiawan',   'doni@hris.test',   'Marketing', $managerMkt, 7500000,  800000],
            ['Lina Marlina',    'lina@hris.test',   'Marketing', $managerMkt, 6500000,  700000],
            ['Hendra Gunawan',  'hendra@hris.test', 'Marketing', $managerMkt, 8000000, 1000000],
            ['Nadia Putri',     'nadia@hris.test',  'Marketing', $managerMkt, 6800000,  700000],
        ];

        foreach ($employees as [$name, $email, $deptName, $manager, $salary, $allowance]) {
            User::forceCreate([
                'name' => $name, 'email' => $email, 'password' => 'password',
                'role' => 'employee', 'department_id' => $dept[$deptName],
                'manager_id' => $manager->id,
                'basic_salary' => $salary, 'allowance' => $allowance,
            ]);
        }
    }
}
