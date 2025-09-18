<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $exist_chk  = User::where('username', 'superadmin')->count();
        if ($exist_chk > 0) {
            echo "\n superadmin already exists";
        } else {
            $super_admin = User::factory()->create([
                'name' => 'Super Admin',
                'username' => 'superadmin',
                'email' => NULL,
                'user_type' => 'superadmin',
                'password' => Hash::make('user@pass'), 'status' => 1,
            ]);
            //dd($super_admin);
        }
        $exist_chk  = User::where('username', 'admin')->count();
        if ($exist_chk > 0) {
            echo "\n admin already exists";
        } else {
            $admin = User::factory()->create([
                'name' => 'Administrator',
                'username' => 'admin',
                'email' => NULL,
                'user_type' => 'admin',
                'password' => Hash::make('user@pass'), 'status' => 1,
            ]);
        }
    }
}
