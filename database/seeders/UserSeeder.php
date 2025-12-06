<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin Perpustakaan',
            'email' => 'admin@perpustakaan.com',
            'password' => Hash::make('password'), // Password: password
            'role' => 'admin',
            'member_type' => 'staff',
            'status' => 'active',
            'phone' => '081234567890',
            'address' => 'Jl. Perpustakaan No. 1, Jakarta',
            'max_loan' => 10,
            'loan_period_days' => 30,
            'member_since' => now(),
        ]);

        // Create Sample Member Users
        $members = [
            [
                'name' => 'Ahmad Rifai',
                'email' => 'ahmad@member.com',
                'password' => Hash::make('password'),
                'role' => 'member',
                'member_type' => 'student',
                'status' => 'active',
                'phone' => '081111111111',
                'address' => 'Jl. Merdeka No. 10, Jakarta',
                'institution' => 'SMA Negeri 1 Jakarta',
                'student_id' => '2021001',
                'max_loan' => 3,
                'loan_period_days' => 14,
                'member_since' => now()->subMonths(6),
                'member_expired_at' => now()->addYear(),
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@member.com',
                'password' => Hash::make('password'),
                'role' => 'member',
                'member_type' => 'teacher',
                'status' => 'active',
                'phone' => '082222222222',
                'address' => 'Jl. Sudirman No. 20, Jakarta',
                'institution' => 'SMA Negeri 1 Jakarta',
                'occupation' => 'Teacher',
                'max_loan' => 5,
                'loan_period_days' => 21,
                'member_since' => now()->subYear(),
                'member_expired_at' => now()->addYears(2),
            ],
            [
                'name' => 'Citra Dewi',
                'email' => 'citra@member.com',
                'password' => Hash::make('password'),
                'role' => 'member',
                'member_type' => 'public',
                'status' => 'active',
                'phone' => '083333333333',
                'address' => 'Jl. Thamrin No. 30, Jakarta',
                'max_loan' => 2,
                'loan_period_days' => 7,
                'member_since' => now()->subMonths(3),
                'member_expired_at' => now()->addMonths(6),
            ],
            [
                'name' => 'Dedi Prasetyo',
                'email' => 'dedi@member.com',
                'password' => Hash::make('password'),
                'role' => 'member',
                'member_type' => 'public',
                'status' => 'pending',
                'phone' => '084444444444',
                'address' => 'Jl. Gatot Subroto No. 40, Jakarta',
                'max_loan' => 2,
                'loan_period_days' => 7,
            ],
        ];

        foreach ($members as $member) {
            User::create($member);
        }

        // Create Additional Members using Factory (optional)
        // Uncomment below if you want to create more sample members
        /*
        \App\Models\User::factory(10)->create([
            'role' => 'member',
            'status' => 'active',
        ]);
        */
    }
}