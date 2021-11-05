<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Config;
use Laratrust\Traits\LaratrustUserTrait;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use LaratrustUserTrait;
    use HasApiTokens;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roles()
    {
        $roles = $this->morphToMany(
            Config::get('laratrust.models.role'),
            'user',
            Config::get('laratrust.tables.role_user'),
            Config::get('laratrust.foreign_keys.user'),
            Config::get('laratrust.foreign_keys.role')
        );
        if (Config::get('laratrust.use_teams')) {
            $roles->withPivot(Config::get('laratrust.foreign_keys.team'));
        }
        return $roles;
    }

    public function teams(){

        $teams = $this->morphToMany(
            Config::get('laratrust.models.team'),
            'user',
            Config::get('laratrust.tables.role_user'),
            Config::get('laratrust.foreign_keys.user'),
            Config::get('laratrust.foreign_keys.team')

            );
        if (Config::get('laratrust.use_teams')) {
            $teams->withPivot(Config::get('laratrust.foreign_keys.team'));
        }
        return $teams;
    }

    public function userRolesInTeams(){
        $roles = $this->morphToMany(
            Config::get('laratrust.models.role'),
            Config::get('laratrust.models.role_user'),
            Config::get('laratrust.tables.teams')
        );
        return $roles;
    }


}
