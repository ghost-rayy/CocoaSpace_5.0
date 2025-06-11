<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class RegisterUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Register User',
            'email' => 'register@domain.com',
            'staff_id' => '987654321',
            'department' => 'Registration',
            'office_number' => '001',
            'password' => Hash::make('register@123'),
            'is_admin' => false,
            'role' => 'register',
        ]);
    }
}
