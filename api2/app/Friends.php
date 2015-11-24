<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Friends extends Model
{
    const CREATED_AT = 'created';
    const UPDATED_AT = 'edited';
    protected $table = 'friends';
    
}
