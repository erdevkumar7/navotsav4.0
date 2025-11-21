<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'Events' => [
                'view events',
                'create events',
                'edit events',
                'delete events',
                'publish events'
            ],
            'Categories' => [
                'view categories',
                'add category',
                'delete category'
            ],
            'Tickets' => [
                'view tickets',
                'refund tickets',
            ],
            'Finance' => [
                'view payouts',
                'process payouts',
            ],
            'Users' => [
                'view organizers',
                'suspend organizer',
                'verify organizer',
                'view buyers',

            ],
        ];

        $allPermissions = collect($permissions)->flatten()->toArray();

        // 2. Delete permissions that are not in the array
        Permission::whereNotIn('name', $allPermissions)->delete();

        foreach ($permissions as $module => $perms) {
            foreach ($perms as $perm) {
                Permission::firstOrCreate(
                    ['name' => $perm, 'guard_name' => 'web'],
                    ['module' => $module]
                );
            }
        }

        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin']);

        // Get all permissions
        $allPermissions = Permission::all();

        // Assign all permissions
        $superAdminRole->syncPermissions($allPermissions);
    }
}
