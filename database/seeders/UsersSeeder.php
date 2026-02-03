<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // DEFAULT ADMIN
        User::create([
            'name' => 'System Admin',
            'email' => 'admin@gmail.com',
            'role' => User::ADMIN,
            'password' => Hash::make('admin12345')
        ]);
    }
}
