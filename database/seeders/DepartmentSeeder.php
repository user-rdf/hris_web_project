<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Human Resources', 'IT', 'Marketing'] as $name) {
            Department::create(['name' => $name]);
        }
    }
}
