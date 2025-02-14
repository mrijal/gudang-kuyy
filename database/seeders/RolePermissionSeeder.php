<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Define roles
        $roles = ['Super Admin', 'Gudang', 'Admin', 'Kasir'];

        // Define permission categories
        $categories = ['barang_masuk', 'barang_keluar', 'user', 'product', 'opname', 'supplier', 'role'];

        // Define permission types
        $actions = ['add', 'edit', 'delete', 'view'];

        // Create permissions
        $permissions = [];
        foreach ($categories as $category) {
            foreach ($actions as $action) {
                $permissionName = "{$action}-{$category}";
                $permissions[] = Permission::firstOrCreate(['name' => $permissionName]);
            }
        }

        // Create roles and assign all permissions to Super Admin
        foreach ($roles as $roleName) {
            $role = Role::firstOrCreate(['name' => $roleName]);

            if ($roleName === 'Super Admin') {
                $role->syncPermissions($permissions);
            }
        }

        // Assign roles super admin to user with id 1
        $user = User::find(1);
        $user->assignRole('Super Admin');

        echo "Roles & Permissions Seeded Successfully! 🚀\n";
    }
}
