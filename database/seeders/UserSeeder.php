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
            'status' => 'active',
            'phone' => '081234567890',
            'address' => 'Jl. Perpustakaan No. 1, Jakarta',
        ]);

        // Create Sample Member Users
        $members = [
            [
                'name' => 'Ahmad Rifai',
                'email' => 'ahmad@member.com',
                'password' => Hash::make('password'),
                'role' => 'member',
                'status' => 'active',
                'phone' => '081111111111',
                'address' => 'Jl. Merdeka No. 10, Jakarta',
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@member.com',
                'password' => Hash::make('password'),
                'role' => 'member',
                'status' => 'pending',
                'phone' => '082222222222',
                'address' => 'Jl. Sudirman No. 20, Jakarta',
            ],
            [
                'name' => 'Citra Dewi',
                'email' => 'citra@member.com',
                'password' => Hash::make('password'),
                'role' => 'member',
                'status' => 'active',
                'phone' => '083333333333',
                'address' => 'Jl. Thamrin No. 30, Jakarta',
            ],
            [
                'name' => 'Dedi Prasetyo',
                'email' => 'dedi@member.com',
                'password' => Hash::make('password'),
                'role' => 'member',
                'status' => 'rejected',
                'phone' => '084444444444',
                'address' => 'Jl. Gatot Subroto No. 40, Jakarta',
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