<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnregisteredTeamInvitation extends Model
{
    //
    protected $table = 'user_team_invitation_unregistered';

    protected $fillable = [
        'team_id',
        'user_email',
        'role_id'
    ];
}
