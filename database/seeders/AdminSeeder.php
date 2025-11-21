<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $admin = User::firstOrCreate(
            ['email' => 'fifty.play@yopmail.com'], // change this email
            [
                'user_type' => 1,
                'name' => 'Super Admin',
                'password' => "123456", // change password
                'email_verified_at' => now(),
            ]
        );

        if (!$admin->HasRole('super-admin')) {
            $admin->assignRole('super-admin');
        }

        $this->command->info('✅ Super Admin created with email: admin@yopmail.com and password: 123456');
    }
}
