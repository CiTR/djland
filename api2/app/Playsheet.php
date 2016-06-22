<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Playsheet extends Model
{
    const CREATED_AT = 'create_date';
    const UPDATED_AT = 'edit_date';
    protected $table 	= 'playsheets';
    //protected $hidden 	= array();
   	//protected $fillable	= array('show_id','host_id','spokenword','spokenword_duration','crtc','lang','type','edit_date','edit_name','podcast_episode');
    protected $guarded	= array('id');
    public function show(){
     	return $this->belongsTo('App\Show');
    }
    public function playitems(){
    	return $this->hasMany('App\Playitem');
    }
    public function podcast(){
        return $this->hasOne('App\Podcast');
    }
    public function channel(){
        return $this->belongsTo('App\Show')->join('podcast_channels','shows.id','='.'podcast_channels.show_id');
    }
    public function ads(){
        return $this->hasMany('App\Ad');
    }
    public function is_socan(){
        $socan = Socan::all();
        foreach($socan as $period){
            if(
			(strtotime($period['socanStart']) <= strtotime($this->start_time) && strtotime($period['socanEnd']) >= strtotime($this->end_time) ||
			(strtotime($period['socanStart']) > strtotime($this->start_time) && strtotime($period['socanEnd']) >= strtotime($this->end_time) ||
			(strtotime($period['socanStart']) <= strtotime($this->start_time) && strtotime($period['socanEnd']) < strtotime($this->end_time)
			){
                return true;
            }
        }
        return false;
    }
}
