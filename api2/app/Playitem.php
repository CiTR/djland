<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Playitem extends Model
{
    //
    protected $table = 'playitems';

    public function playsheet(){
    	return $this->belongsTo('App\Playsheet');
    }
    public function song(){
    	return $this->belongsTo('App\Song','song_id','id');
    }

}
