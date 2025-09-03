<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Tạo 10 tài khoản gia sư
        for ($i = 1; $i <= 10; $i++) {
            User::create([
                'name' => "Gia sư $i",
                'email' => "tutor$i@example.com",
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_admin' => false
            ]);
        }

        // Tạo 20 tài khoản học sinh
        for ($i = 1; $i <= 20; $i++) {
            User::create([
                'name' => "Học sinh $i",
                'email' => "student$i@example.com",
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_admin' => false
            ]);
        }
    }
} 