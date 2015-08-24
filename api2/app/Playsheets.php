<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Playsheet extends Model
{
    //
    protected $table 	= 'playsheets';
    protected $hidden 	= array();
    protected $fillable	= array('show_id','host_id','spokenword','spokenword_duration','crtc','lang','type','edit_date','edit_name','podcast_episode');
    protected $guarded	= array('id');
}
