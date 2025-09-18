<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\PermissionGroup;
use App\Models\User;
use DB;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $perm_group = PermissionGroup::where('name', 'ACL')->first();
        if (!$perm_group) {
            $perm_group = PermissionGroup::create(['name' => 'ACL']);
        }

        // create permissions
        DB::table('permissions')->insertOrIgnore(['name' => 'acl.roles.manage', 'guard_name' => 'web', 'display_name' => 'Role Management', 'group_id' => $perm_group->id]);
        DB::table('permissions')->insertOrIgnore(['name' => 'acl.permissions.manage', 'guard_name' => 'web', 'display_name' => 'Permission Management', 'group_id' => $perm_group->id]);
        DB::table('permissions')->insertOrIgnore(['name' => 'acl.users.manage', 'guard_name' => 'web', 'display_name' => 'User Permission Management', 'group_id' => $perm_group->id]);


        $perm_group = PermissionGroup::where('name', 'Administration')->first();
        if (!$perm_group) {
            $perm_group = PermissionGroup::create(['name' => 'Administration']);
        }

        DB::table('permissions')->insertOrIgnore(['name' => 'users.list', 'guard_name' => 'web', 'display_name' => 'List Users', 'group_id' => $perm_group->id]);
        DB::table('permissions')->insertOrIgnore(['name' => 'password.change', 'guard_name' => 'web', 'display_name' => 'Change User Password', 'group_id' => $perm_group->id]);
        DB::table('permissions')->insertOrIgnore(['name' => 'users.profile', 'guard_name' => 'web', 'display_name' => 'User Profile', 'group_id' => $perm_group->id]);
        DB::table('permissions')->insertOrIgnore(['name' => 'announcements', 'guard_name' => 'web', 'display_name' => 'Announcements', 'group_id' => $perm_group->id]);


        $perm_group = PermissionGroup::where('name', 'Manage')->first();
        if (!$perm_group) {
            $perm_group = PermissionGroup::create(['name' => 'Manage']);
        }

        DB::table('permissions')->insertOrIgnore(['name' => 'products', 'guard_name' => 'web', 'display_name' => 'Manage Products', 'group_id' => $perm_group->id]);
        DB::table('permissions')->insertOrIgnore(['name' => 'orders', 'guard_name' => 'web', 'display_name' => 'Manage Orders', 'group_id' => $perm_group->id]);
        DB::table('permissions')->insertOrIgnore(['name' => 'enquiry', 'guard_name' => 'web', 'display_name' => 'Manage Enquiry', 'group_id' => $perm_group->id]);

        $perm_group = PermissionGroup::where('name', 'Expenses')->first();
        if (!$perm_group) {
            $perm_group = PermissionGroup::create(['name' => 'Expenses']);
        }

        DB::table('permissions')->insertOrIgnore(['name' => 'expenses', 'guard_name' => 'web', 'display_name' => 'Expenses', 'group_id' => $perm_group->id]);


        $perm_group = PermissionGroup::where('name', 'Reports And Logs')->first();
        if (!$perm_group) {
            $perm_group = PermissionGroup::create(['name' => 'Reports And Logs']);
        }

        DB::table('permissions')->insertOrIgnore(['name' => 'reports.login.logs.list', 'guard_name' => 'web', 'display_name' => 'List Login Logs', 'group_id' => $perm_group->id]);
        DB::table('permissions')->insertOrIgnore(['name' => 'reports.login.logs.download', 'guard_name' => 'web', 'display_name' => 'Download Login Logs', 'group_id' => $perm_group->id]);


        $perm_group = PermissionGroup::where('name', 'Global Configurations')->first();
        if (!$perm_group) {
            $perm_group = PermissionGroup::create(['name' => 'Global Configurations']);
        }

        DB::table('permissions')->insertOrIgnore(['name' => 'settings', 'guard_name' => 'web', 'display_name' => 'Global Configurations', 'group_id' => $perm_group->id]);

        $perm_group = PermissionGroup::where('name', 'Dashboard')->first();
        if (!$perm_group) {
            $perm_group = PermissionGroup::create(['name' => 'Dashboard']);
        }

        DB::table('permissions')->insertOrIgnore(['name' => 'dashboard', 'guard_name' => 'web', 'display_name' => 'Dashboard', 'group_id' => $perm_group->id]);



        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
