<?php

return [
    /**
     * Control if the seeder should create a user per role while seeding the data.
     */
    'create_users' => false,

    /**
     * Control if all the laratrust tables should be truncated before running the seeder.
     */
    'truncate_tables' => true,

    'roles_structure' => [

        /*monster user. Not to be used*/
        'superadministrator' => [
            'users' => 'c,r,u,d',
            'tokflow' => 'c,r,u,d','l',
            'profile' => 'c,r,u,d',
            'comment' => 'c,r,u,d',
            'mention' => 'c,r,u,d',
            'invite' => 'c,r,u,d',
        ],

        'administrator' => [
            'users' => 'r,u,d',
            'tokflow' => 'c,r,u,d',
            'profile' => 'r,u',
            'comment' => 'c,r,u,d',
            'mention' => 'c,r,u,d',
        ],

        'user' => [
            'tokflow' => 'c,r',
            'profile' => 'r,u',
        ],

        /*team roles*/
        'leader' => [
            'tokflow' => 'c,r,u,l',
            'profile' => 'r,u',
            'comment' => 'c,r,u,d',
            'mention' => 'c,r,u,d',
            'invite' => 'c,r,u,d',
        ],

        'coleader' => [
            'tokflow' => 'r,u,l',
            'profile' => 'r,u',
            'comment' => 'c,r,u,d',
            'mention' => 'c,r,u',
            'invite' => 'c,r,u,d',
        ],

        'member' => [
            'profile' => 'r,u',
            'comment' => 'c,r,u,d',
            'mention' => 'c,r,u',
        ],

        'reviewer' => [
            'profile' => 'r,u',
            'comment' => 'c,r,u,d',
            'mention' => 'c,r,u',
        ],

        'stakeholder' => [
            'profile' => 'r,u',
            'mention' => 'c,r,u',
        ],
        /*end of team roles*/

    ],

    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete',
//        for flow(s)
        'l' => 'lock',
//        for user(s)
        'com' => 'comment,'
    ]
];
