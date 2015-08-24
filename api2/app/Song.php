<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    //
    protected $table = 'songs';

    public function playitem(){
    	return $this->hasOne('App\Playitem');
    }
}
