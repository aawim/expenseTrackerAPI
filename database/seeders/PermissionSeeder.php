<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

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
            'delete_admin'
            ,
            'view_expense',
            'create_expense',
            'edit_expense',
            'delete_expense',
            
            'dashboard',
 
     
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}