<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegularUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $userRole = Role::where('name', 'user')->first();


        $user = [
         'sample',
        ];

        foreach($user as $newUser) {
            $user = User::create([
                'name' => $newUser,
                'email' => $newUser . '@sampleuser.com',
                'password' => Hash::make($newUser),
            ]);
            $user->attachRole($userRole);
        }

        $usersFactroy = factory(User::class, 10)->create();
    }
}
