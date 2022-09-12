<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeederTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $allPermissions = [
            [
                'name' => 'Edit Profile',
                'guard_name' => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Change Password',
                'guard_name' => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Manage User',
                'guard_name' => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Create User',
                'guard_name' => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Edit User',
                'guard_name' => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Delete User',
                'guard_name' => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Create Role',
                'guard_name' => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Manage Role',
                'guard_name' => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Edit Role',
                'guard_name' => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                "name" => 'Manage Customer',
                'guard_name' => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                "name" => 'Edit Customer',
                'guard_name' => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                "name" => 'Delete Customer',
                'guard_name' => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                "name" => 'Manage Bank Account',
                'guard_name' => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                "name" => 'Manage Bank Statement',
                'guard_name' => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                "name" => 'Manage Global Setting',
                'guard_name' => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                "name" => 'Manage Email',
                'guard_name' => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                "name" => 'Create Email',
                'guard_name' => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                "name" => 'Manage Notification',
                'guard_name' => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                "name" => 'Create Notification',
                'guard_name' => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                "name" => 'Manage Sms',
                'guard_name' => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                "name" => 'Create Sms',
                'guard_name' => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                "name" => 'Manage Savings Goal Management',
                'guard_name' => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                "name" => 'Edit Savings Goal Management',
                'guard_name' => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                "name" => 'Delete Savings Goal Management',
                'guard_name' => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                "name" => 'Manage Smart Rule Management',
                'guard_name' => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                "name" => 'Edit Smart Rule Management',
                'guard_name' => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                "name" => 'Delete Smart Rule Management',
                'guard_name' => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                "name" => 'Manage Salary Advance Management',
                'guard_name' => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                "name" => 'Edit Salary Advance Management',
                'guard_name' => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                "name" => 'Delete Salary Advance Management',
                'guard_name' => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                "name" => 'Manage Saving Transaction',
                'guard_name' => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                "name" => 'Manage Bill Payment',
                'guard_name' => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                "name" => 'Manage Bill Transaction',
                'guard_name' => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];
        Permission::insert($allPermissions);

        $super_admin_permissions = [
            ['name' => 'Create Role'],
            ['name' => 'Manage Role'],
            ['name' => 'Edit Role'],
            ['name' => 'Manage Customer'],
            ['name' => 'Edit Customer'],
            ['name' => 'Delete Customer'],
            ["name" => 'Manage Bank Account'],
            ["name" => 'Manage Bank Statement'],
            ["name" => 'Manage Global Setting'],
            ["name" => 'Manage Email'],
            ["name" => 'Create Email'],
            ["name" => 'Manage Notification'],
            ["name" => 'Create Notification'],
            ["name" => 'Manage Sms'],
            ["name" => 'Create Sms'],
            ["name" => 'Manage Savings Goal Management'],
            ["name" => 'Edit Savings Goal Management'],
            ["name" => 'Delete Savings Goal Management'],
            ["name" => 'Manage Smart Rule Management'],
            ["name" => 'Edit Smart Rule Management',],
            ["name" => 'Manage Salary Advance Management'],
            ["name" => 'Edit Salary Advance Management'],
            ["name" => "Manage User"],
            ["name" => "Edit User"],
            ["name" => "Create User"],
            ["name" => "Delete User"],
            ["name" => "Manage Saving Transaction"],
            ["name" => "Manage Bill Payment"],
            ["name" => "Manage Bill Transaction"]
        ];

        $super_admin_role             = new Role();
        $super_admin_role->name       = 'Super Admin';
        $super_admin_role->guard_name = 'admin';
        $super_admin_role->save();
        $super_admin_role ->givePermissionTo($super_admin_permissions);

        $super_admin = Admin::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt(1234)
        ]);
        $super_admin->assignRole($super_admin_role);
    }
}
