<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    //
    protected $table = 'adlog';
    protected $fillable = array('playsheet_id', 'num', 'time', 'type', 'name', 'played', 'sam_id', 'time_block');
    public  $timestamps = false;
    public function playsheet(){
    	return $this->belongsTo('App\Playsheet');
    }
    

    
}
