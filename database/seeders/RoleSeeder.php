<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'super-admin', 'user_type' => 1]);
        Role::firstOrCreate(['name' => 'admin', 'user_type' => 2]);
        Role::firstOrCreate(['name' => 'event-organizer', 'user_type' => 3]);
        Role::firstOrCreate(['name' => 'finance-manager', 'user_type' => 4]);
    }
}
