<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Playsheet extends Model
{
    const CREATED_AT = 'create_date';
    const UPDATED_AT = 'edit_date';
    protected $table    = 'playsheets';
    protected $fillable    = array( 'show_id', 'host', 'host_id', 'start_time', 'end_time', 'end', 'create_date', 'create_name', 'edit_date', 'title', 'edit_name', 'summary', 'spokenword_duration', 'status', 'unix_time', 'star', 'crtc', 'lang', 'type', 'show_name', 'socan', 'web_exclusive');
    protected $guarded    = array('id');
    public function show()
    {
        return $this->belongsTo('App\Show');
    }
    public function playitems()
    {
        return $this->hasMany('App\Playitem');
    }
    public function podcast()
    {
        return $this->hasOne('App\Podcast');
    }
    public function channel()
    {
        return $this->belongsTo('App\Show')->join('podcast_channels', 'shows.id', '='.'podcast_channels.show_id');
    }
    public function ads()
    {
        return $this->hasMany('App\Ad');
    }
    public function is_socan()
    {
        $socan = Socan::all();
        foreach ($socan as $period) {
            if (
            (strtotime($this->start_time) <= strtotime($period['socanStart']) && strtotime($period['socanStart']) <= strtotime($this->end_time)) ||
            (strtotime($period['socanStart']) <= strtotime($this->start_time) && strtotime($this->end_time) <= strtotime($period['socanEnd'])) ||
            (strtotime($this->start_time) <= strtotime($period['socanEnd'])  && strtotime($period['socanEnd']) <= strtotime($this->end_time))
            ) {
                return true;
            }
        }
        return false;
    }
    public function is_draft()
    {
        return $this->status != 2;
    }
    public function is_published()
    {
        return $this->status == 2;
    }
}
