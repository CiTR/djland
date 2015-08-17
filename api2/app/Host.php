<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Host extends Model
{
    //
    protected $table = 'hosts';
    public function playsheet(){
    	return $this->hasMany('App\Playsheet');
    }
    public function shows(){
    	return $this->belongsToMany('App\Show','id','host_id');
    }
}
