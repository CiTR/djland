<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Friends extends Model
{
    const CREATED_AT = 'created';
    const UPDATED_AT = 'edited';
    protected $table = 'friends';
   	protected $guarded	= array('id');
    protected $fillable = array('name','address', 'phone', 'website','discount','image_url',);
}
