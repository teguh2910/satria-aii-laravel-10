<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class sj_error extends Model
{
    protected $table = 'sj_errors';

    protected $fillable = [
        'doaii',
        'user_scan',
    ];
}
