<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Show extends Model
{
    //
    protected $table = 'shows';
    const CREATED_AT = 'create_date';
    const UPDATED_AT = 'edit_date';
    protected $fillable = array('podcast_channel_id', 'name', 'primary_genre_tags', 'secondary_genre_tags', 'weekday', 'start_time', 'end_time', 'pl_req', 'cc_req', 'indy_req', 'fem_req', 'last_show', 'edit_date', 'edit_name', 'active', 'crtc_default', 'lang_default', 'website', 'rss', 'show_desc', 'notes', 'show_img', 'sponsor_name', 'sponsor_url', 'showtype', 'alerts');
    public function members(){
        return $this->belongsToMany('App\Member','member_show');
    }

    public function playsheets(){
    	return $this->hasMany('App\Playsheet','show_id','id');
    }

    public function host(){
    	return $this->hasOne('App\Host','id','host_id');
    }
    public function social(){
        return $this->hasMany('App\Social');
    }

}
