<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Social extends Model
{
	protected $table = 'social';
    //

    public function shows(){
        return $this->belongsTo('App\Show');
    }
}
