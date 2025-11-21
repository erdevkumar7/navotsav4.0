<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class OrganizerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $organizers = [
            [
                'name' => 'John Organizer',
                'user_type' => EVENT_ORGANIZER,
                'email' => 'john.organizer@yopmail.com.com',
                'password' => Hash::make('123456'),
                'status' => 'active',
                'is_verified' => true,
            ],
            [
                'name' => 'Jane Organizer',
                'user_type' => EVENT_ORGANIZER,
                'email' => 'jane.organizer@yopmail.com',
                'password' => Hash::make('123456'),
                'status' => 'active',
                'is_verified' => false, // pending verification
            ],
        ];

        foreach ($organizers as $org) {
            $user = User::firstOrCreate(
                ['email' => $org['email']], // avoid duplicate
                $org
            );
        }
    }
}
