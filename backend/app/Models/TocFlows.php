<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class TocFlows extends Model
{
    //
    protected $connection = 'mongodb';
    protected $collection = 'tocFlows';

    protected $fillable = [
        'data',
        'data.team_id',
        'data.initiative_level',
        'data.workpackage_level',
        'data.programme',
        'data.pdb',
        'data.team_members',
        'data.work_packages'
    ];

}
