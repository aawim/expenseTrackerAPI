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
            'create user',
            'edit user',
            'delete user',
            'view user',

            'view roles',
            'create roles',
            'edit roles',
            'delete roles',

            'view permissions',
            'create permissions',
            'edit permissions',
            'delete permissions',

            'view admin',
            'create admin',
            'edit admin',
            'delete admin',

            'view event',
            'create event',
            'edit event',
            'delete event',

            'view booking',
            'create booking',
            'edit booking',
            'delete booking',

        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'api']);
        }

        // 2. Create admin role and assign all permissions

        $admin   = Role::create(['name' => 'Admin', 'guard_name' => 'api']);
        $manager = Role::create(['name' => 'Manager', 'guard_name' => 'api']);
        $user    = Role::create(['name' => 'User', 'guard_name' => 'api']);

        $admin->syncPermissions(Permission::where('guard_name', 'api')->get());

        // 3. Create admin user
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@mail.com'],
            [
                'name'     => 'Admin User',
                'password' => Hash::make('12345678'), // Change this!
            ]
        );

        // 4. Assign Admin role to the user
        $adminUser->assignRole($admin);

        $manager->syncPermissions([
            'view user',
        ]);

    }
}
