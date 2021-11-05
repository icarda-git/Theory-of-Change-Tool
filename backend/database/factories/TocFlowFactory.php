<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\TocFlow;
use Faker\Generator as Faker;

$factory->define(TocFlow::class, function (Faker $faker) {
    return [
        //
        'data' => [
            'team_id' => Team::all()->random()->id,
            'programme' => [
                'programme_id' => $requestData->programme->programme_id,
                'title' => $requestData->programme->title,
                'description' => $requestData->programme->description,
                'type' => $requestData->programme->type,
                'action_areas' =>
                    $requestData->programme->action_areas
                ,//action_areas
                'donors' =>
                    $requestData->programme->donors
                ,//donors
                'partners' =>
                    $requestData->programme->partners
                ,//partners
                'sdgs' =>
                    $requestData->programme->sdgs,//sdgs
            ],//programme
            'pdb' => [
                'status' => $requestData->pdb->status,
                'pdb_link' => $requestData->pdb->pdb_link
            ],//pdb
            'team_members' => $allTeamMembers,
        ],
    ];
});
