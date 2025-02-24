<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin User',
                'username' => 'admin',
                'email' => 'mohammadrajha2@gmail.com',
                'password' => Hash::make('password'),
                'phone_number' => '123456789',
                'is_admin' => 1,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'User',
                'username' => 'user',
                'email' => 'user@gmail.com',
                'password' => Hash::make('password'),
                'phone_number' => '987654321',
                'is_admin' => 0,
                'email_verified_at' => now(),
            ]
        ]);
    }
}
