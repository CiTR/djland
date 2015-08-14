<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Playitem extends Model
{
    //
    protected $table = 'playitems';
    public function playsheet(){
    	return $this->hasOne('App\Playsheet');
    }
}
