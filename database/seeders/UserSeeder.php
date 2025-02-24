<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'username' => 'admin_user',
                'first_name' => 'admin',
                'middle_name' => 'Super',
                'last_name' => 'User',
                'college' => 'Admin College',
                'department' => 'Management',
                'role' => 'Admin',
                'email' => 'admin@example.com',
                'user_id' => 'ADM001',
                'profile_picture' => null,
                'password' => Hash::make('password123'), // Hashing the password for security
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'employee_user',
                'first_name' => 'John',
                'middle_name' => 'Doe',
                'last_name' => 'Smith',
                'college' => 'Science',
                'department' => 'Physics',
                'role' => 'employee',
                'email' => 'employee@example.com',
                'user_id' => 'EMP001',
                'profile_picture' => null,
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'staff_user',
                'first_name' => 'Jane',
                'middle_name' => 'Marie',
                'last_name' => 'Doe',
                'college' => 'Arts',
                'department' => 'History',
                'role' => 'staff',
                'email' => 'staff@example.com',
                'user_id' => 'STF001',
                'profile_picture' => null,
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
