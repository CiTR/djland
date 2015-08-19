<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    //
    protected $table = 'group_members';
    protected $primaryKey = 'user_id';
    function user(){
    	return $this->belongsTo('App\User');
    }
}
