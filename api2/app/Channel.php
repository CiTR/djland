<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    protected $table = 'podcast_channels';
    public function podcasts(){
    	return $this->hasMany('App\Podcast');
    }
}
