<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::updateOrCreate(
            ['email' => 'admin@sungokong.com'],
            [
                'name' => 'Admin Sungokong',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        // Staff
        User::updateOrCreate(
            ['email' => 'staff@example.com'],
            [
                'name' => 'Staff',
                'password' => Hash::make('password'),
                'role' => 'staff',
            ]
        );

        // User biasa (random 5)
        User::factory(5)->create([
            'role' => 'user',
        ]);

        // User spesifik dengan email user@gmail.com dan password user123
        User::updateOrCreate(
            ['email' => 'user@gmail.com'],
            [
                'name' => 'User Biasa',
                'password' => Hash::make('user123'),
                'role' => 'user',
            ]
        );
    }
}
