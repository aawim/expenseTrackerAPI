<?php
namespace Database\Seeders;

// use App\Models\Permission;
// use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create permissions
        $permissions = [
            'create_user',
            'edit_user',
            'delete_user',
            'view_user',

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

            'view_expense',
            'create_expense',
            'edit_expense',
            'delete_expense',

            'dashboard',

        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'api']);
        }

        // 2. Create admin role and assign all permissions

        $admin   = Role::create(['name' => 'Admin', 'guard_name' => 'api']);
        $manager = Role::create(['name' => 'Manager', 'guard_name' => 'api']);
        $userRole    = Role::create(['name' => 'User', 'guard_name' => 'api']);

        $admin->syncPermissions(Permission::where('guard_name', 'api')->get());

        // 3. Create admin user
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@mail.com'],
            [
                'name'     => 'Admin User',
                'password' => Hash::make('12345678a'), // Change this!
            ]
        );

        // 4. Assign Admin role to the user
        $adminUser->assignRole($admin);

        $manager->syncPermissions([
            'view_user',
        ]);

        // 3. Create admin user
        $user = User::firstOrCreate(
            ['email' => 'user@mail.com'],
            [
                'name'     => 'User25',
                'password' => Hash::make('12345678a'), // Change this!
            ]
        );

        // 4. Assign Admin role to the user
        $user->assignRole($userRole);

        $user->syncPermissions([
            'view_user',
        ]);

    }
}
