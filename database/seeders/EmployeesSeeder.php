<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Employee;

class EmployeesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 6; $i++) {
            Employee::create([
                'names' => "Employee $i",
                'email' => "employee$i@gmail.com",
                'telephone' => "25078848484$i",
                'code' => "EMP-12121$i"
            ]);
        }
    }
}
