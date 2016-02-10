<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    protected $table = 'member_resources';
    protected $fillable = array('blurb','link','link_name','type');
}
