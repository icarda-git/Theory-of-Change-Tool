<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamInvitation extends Model
{
    //
    protected $table = 'user_team_invitation';

    protected $fillable = [
        'team_id',
        'user_id',
        'role_id'
    ];

    public function team(){
        return $this->belongsTo('App\Models\Team');
    }

    public function role(){
        return $this->belongsTo('App\Models\Role');
    }

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

}
