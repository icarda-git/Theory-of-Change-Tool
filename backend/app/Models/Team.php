<?php

namespace App\Models;

use Illuminate\Support\Facades\Config;
use Laratrust\Models\LaratrustTeam;

class Team extends LaratrustTeam
{
    public $guarded = [];

    public function TeamInvitation(){
        return $this->hasMany('App\TeamInvitation');
    }

    public function leader(){
        return $this->belongsTo('App\Models\User', 'user_leader_id');
    }
}


