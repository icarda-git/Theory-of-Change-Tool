<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class Toc extends Model
{
    //
    protected $connection = 'mongodb';
    protected $collection = 'tocs';

    protected $fillable = [
        '_id',
        'toc_id',
        'published',
        'published_data',
        'deleted',
        'version',
        'number',
        'tocFlow_id',
        'toc_type',
        'toc_type_id',
        'toc_title',
        'toc',
    ];
}
