<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Showtime extends Model
{
    const CREATED_AT = null;
    const UPDATED_AT = null;
    protected $table = 'show_times';
    protected $primaryKey = 'show_id';
    protected $fillable = array('show_id', 'start_day', 'start_time', 'end_day', 'end_time', 'alternating');
    public function shows(){
        return $this->belongsTo('App\Show','id','show_id');
    }
}
