<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Show extends Model
{
    //
    protected $table = 'shows';
    public function playsheet(){
    	return $this->hasMany('App\Playsheet');
    }
    public function hosts(){
    	return $this->hasMany('App\Host','id','host_id');
    }
}
