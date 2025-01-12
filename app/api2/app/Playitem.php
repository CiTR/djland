<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Exception;

function find4BitChar($arr)
{
  // Loop through each character in the string
  foreach ($arr as $str) {
    // Loop through each character in the string
    for ($i = 0; $i < mb_strlen($str, 'UTF-8'); $i++) {
      // Get the character at position $i
      $char = mb_substr($str, $i, 1, 'UTF-8');
      // Check if the character is a 4-byte character
      if (preg_match('/[\x{10000}-\x{10FFFF}]/u', $char)) {
        return $char;
      }
    }
  }
  // Return false if no 4-byte character is found
  return false;
}

class Playitem extends Model
{
  protected $table = 'playitems';
  public $timestamps = false;
  protected $fillable = array(
    'show_id',
    'playsheet_id',
    'song_id',
    'format_id',
    'is_playlist',
    'is_canadian',
    'is_yourown',
    'is_indy',
    'is_accesscon',
    'is_afrocon',
    'is_fem',
    'is_indigicon',
    'is_poccon',
    'is_queercon',
    'is_local',
    'show_date',
    'duration',
    'is_theme',
    'is_background',
    'crtc_category',
    'lang',
    'is_part',
    'is_inst',
    'is_hit',
    'insert_song_start_hour',
    'insert_song_start_minute',
    'insert_song_length_minute',
    'insert_song_length_second',
    'artist',
    'song',
    'album',
    'composer'
  );

  public function playsheet()
  {
    return $this->belongsTo('App\Playsheet');
  }

  public function getIsFairplayAttribute()
  {
    return ($this->is_accesscon || $this->is_afrocon || $this->is_fem || $this->is_indigicon || $this->is_poccon || $this->is_queercon) ? true : false;
  }

  public static function create(array $attributes = [])
  {
    Log::info('overrided Playitem::create');

    $playitem = new self($attributes);

    $artist = $playitem->artist;
    $song = $playitem->song;
    $album = $playitem->album;
    $composer = $playitem->composer;
    $problem_character = find4BitChar([$artist, $song, $album, $composer]);

    if ($problem_character) {
      $message = "Sorry, DJ Land currently does not support the " . $problem_character." character.";
      Log::error($problem_character . " found. Should not try to insert 4-byte chars into the database.");
      throw new Exception($message);
    }
    try {
      $playitem->save();
    } catch (Exception $e) {
      $message = "Sorry, there was an encoding error. Soon DJ Land will be able to support more languages.";
      $log = $e->getMessage();
      Log::error($log);
      throw new Exception($message);
    }
    return $playitem;

  }


}
