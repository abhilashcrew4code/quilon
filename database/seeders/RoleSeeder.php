<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $exist_chk  = Role::where('name', 'super-admin')->count();
        if ($exist_chk > 0) {
            echo "\n already exists";
        } else {
            // create roles and assign created permissions
            // this can be done as separate statements
            $role1 = Role::create(['name' => 'super-admin']);
            $role1->givePermissionTo(Permission::all());
            $role2 = Role::create(['name' => 'admin']);
            $role3 = Role::create(['name' => 'user']);

            //Assign Roles to Users
            $super_admin = User::where('username', 'superadmin')->first();
            $super_admin->assignRole('super-admin');

            $admin = User::where('username', 'admin')->first();
            $admin->assignRole('admin');
        }
    }
}