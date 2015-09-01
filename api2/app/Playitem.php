<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Playitem extends Model
{
    protected $table = 'playitems';
    public $timestamps = false;
    protected $fillable = array('show_id', 'playsheet_id', 'song_id', 'format_id', 'is_playlist', 'is_canadian', 'is_yourown', 'is_indy', 'is_fem', 'show_date', 'duration', 'is_theme', 'is_background', 'crtc_category', 'lang', 'is_part', 'is_inst', 'is_hit', 'insert_song_start_hour', 'insert_song_start_minute', 'insert_song_length_minute', 'insert_song_length_second', 'artist', 'song', 'album', 'composer');
    public function playsheet(){
    	return $this->belongsTo('App\Playsheet');
    }
}
