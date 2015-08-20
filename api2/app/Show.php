<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Show extends Model
{
    //
    protected $table = 'shows';

    public function members(){
        return $this->belongsToMany('App\Member','member_show');
    }

    public function playsheets(){
    	return $this->hasMany('App\Playsheet','show_id','id');
    }

    public function host(){
    	return $this->hasOne('App\Host','id','host_id');
    }

}
