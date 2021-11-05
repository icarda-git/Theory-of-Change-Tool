<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model;

class Mention extends Model
{
    //
    protected $connection = 'mongodb';
    protected $collection = 'tok_mentions';

    protected $fillable = [
        'tokFlow_comments',
        'tokFlow_comments.tokFlow_id',
        'tokFlow_comments.data',
        'tokFlow_comments.data.comment',
    ];
}
