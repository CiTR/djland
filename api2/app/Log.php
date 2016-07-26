<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table = 'log';
    protected $fillable = array('error','referrer','user','data''CREATED_AT');
}
