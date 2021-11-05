<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class Comment extends Model
{
    //
    protected $connection = 'mongodb';
    protected $collection = 'tok_comments';

    protected $fillable = [
        'tokFlow_details',
        'comments',
        ];
}
