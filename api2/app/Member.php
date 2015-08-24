<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $table = 'membership';
    
    public function shows(){
        return $this->belongsToMany('App\Show','member_show');
    }

    public function playsheets(){
        return $this->hasManyThrough('App\Playsheet','App\Show');
    }

    public function membership_years(){
    	return $this->hasMany('App\Membership_year');
    }

    public function permissions(){
    	return $this->hasMany('App\Permission');
    }
    
    public function user(){
    	return $this->hasOne('App\User');
    }
}
