<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Social extends Model
{
	protected $table = 'social';
    //
    protected $primary_key = 'show_id';

    public function shows(){
        return $this->belongsTo('App\Show','id','show_id');
    }
}
