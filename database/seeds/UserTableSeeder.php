<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_admin = Role::where('name' , 'admin')->first();
        $role_user = Role::where('name' , 'user')->first();

        $admin = new User;
        $admin->name = 'Admin';
        $admin->email = 'admin@admin.com';
        $admin->password = bcrypt(123456);
        $admin->phone =  '01234567890';
        $admin->active = 1;
        $admin->save();
        $admin->roles()->attach($role_admin);

        $user = new User;
        $user->name = 'User';
        $user->email = 'user@admin.com';
        $user->password = bcrypt(123456);
        $user->phone =  '01234567891';
        $user->active = 1;
        $user->save();
        $user->roles()->attach($role_user);

    }
}
