<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        {
            User::create([
                'name' => 'Administrator',
                'email' => 'admin@admin.com',
                'staff_id' => '123456789',
                'department' => 'Administration',
                'office_number' => '000',
                'password' => Hash::make('admin@123'),
                'is_admin' => true,
                'role' =>'admin',
            ]);

            User::create([
                'name' => 'Register Attendee',
                'email' => 'register@gmail.com',
                'staff_id' => '987654321',
                'department' => 'Registration',
                'office_number' => '001',
                'password' => Hash::make('register@123'),
                'is_admin' => false,
                'role' => 'register',
            ]);
        }
    }
}
