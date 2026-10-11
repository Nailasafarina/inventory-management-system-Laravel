<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Akun Administrator
        User::firstOrCreate(
            ['email' => 'admin@inventpro.com'],
            [
                'name' => 'Manager Snack Corner (Admin)',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // 2. Akun Staff
        User::firstOrCreate(
            ['email' => 'staff@inventpro.com'],
            [
                'name' => 'Staff Kasir & Gudang (Staff)',
                'password' => Hash::make('staff123'),
                'role' => 'staff',
                'email_verified_at' => now(),
            ]
        );
    }
}
