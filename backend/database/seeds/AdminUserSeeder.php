<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Role::where('name', 'superadministrator')->first();

        $user = new User();
        $user->name = 'george';
        $user->email = 'george@sampleuser.com';
        $user->password = Hash::make('george');
        $user->save();

        $user->attachRole($admin);


    }
}
