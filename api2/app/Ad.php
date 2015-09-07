<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    //
    protected $table = 'adlog';

    public function playsheet(){
    	return $this->belongsTo('App\Playsheet');
    }
    

    
}
