<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categorylist extends Model
{
    //Please Note: This is a model for the SAM database, not the local DJLand database
    protected $connection = 'samdb';
    protected $table = 'Categorylist';
    protected $primaryKey = 'ID';
    public function songlist(){
    	return $this->belongsTo('App\Songlist','ID','songID');
    }
}
