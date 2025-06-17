<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
    {
        $permissions = [
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',
            'view_roles',
            'create_roles',
            'edit_roles',
            'delete_roles',
            'view_permissions',
            'create_permissions',
            'edit_permissions',
            'delete_permissions',
            'view_admin',
            'create_admin',
            'edit_admin',
            'delete_admin',
            'view_event',
            'create_event',
            'edit_event',
            'delete_event',
            'view_booking',
            'create_booking',
            'edit_booking',
            'delete_booking',
     
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
