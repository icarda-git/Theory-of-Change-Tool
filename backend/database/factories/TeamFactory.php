<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\User;
use App\Teams;
use Faker\Generator as Faker;
use Illuminate\Support\Carbon;

$factory->define(Teams::class, function (Faker $faker) {
    $title = $faker->title;
    $time = Carbon::now()->format('Y-m-d H:i:s');

    return [
        //
        'authorizer_id' => null,
        'user_leader_id' => User::all()->random()->id,
        'clarisa_project_id' => 0,
        'active' => $faker->boolean,
        'name' => $title,
        'display_name' => $title,
        'description' => $faker->text(100),
        'created_at' => $time,
        'updated_at' => $time,
    ];
});
