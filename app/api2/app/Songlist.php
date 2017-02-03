<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Songlist extends Model
{
    //Please Note: This is a model for the SAM database, not the local DJLand database
    protected $connection = "samdb";
    protected $table = "songlist";
    public function categorylist(){
    	return $this->hasMany('App\Categorylist');
    }
    
}
