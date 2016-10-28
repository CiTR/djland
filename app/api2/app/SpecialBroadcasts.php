<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SpecialBroadcasts extends Model
{
    const CREATED_AT = 'created';
    const UPDATED_AT = 'edited';
    protected $table = 'special_events';
   	protected $guarded	= array('id');
    protected $fillable = array('name', 'show_id', 'description', 'start', 'end', 'image', 'url',);
	public function image(){
		return $this->hasOne('App\Upload','relation_id','id');
	}
}
