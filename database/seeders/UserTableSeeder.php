<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $password = 'test123';

        /** Create Super Admin */
        $superAdminRole = Role::firstWhere('name', 'super');
        $super = User::create([
            'username' => 'super',
            'name' => 'Super Admin',
            'email' => 'super@laraplate.com',
            'email_verified_at' => time(),
            'password' => bcrypt($password),
        ]);
        $super->attachRole($superAdminRole);

        /** Create Admins */
        $adminRole = Role::firstWhere('name', 'admin');
        $admins = User::factory(2)->create([
            'password' => bcrypt($password),
        ]);
        foreach ($admins as $admin) {
            $admin->attachRole($adminRole);
        }

        /** Create Users */
        $userRole = Role::firstWhere('name', 'user');
        $users = User::factory(200)->create([
            'password' => bcrypt($password),
        ]);
        foreach ($users as $user) {
            $user->attachRole($userRole);
        }
    }
}
