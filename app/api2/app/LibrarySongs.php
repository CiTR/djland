<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class LibrarySongs extends Model
{
  protected $table = 'library_songs';
  protected $guarded = array('id');
  protected $fillable = array(
    'library_id', 'artist',
    'album_artist', 'album_title', 'song_title', 'credit', 'track_num',
    'tracks_total', 'genre', 's/t', 'v/a', 'compilation', 'composer', 'crtc',
    'year', 'length', 'file_location', 'updated_at', 'created_at'
  );

  public function album()
  {
    return $this->belongsTo('App\Library', 'library_id');
  }
}
