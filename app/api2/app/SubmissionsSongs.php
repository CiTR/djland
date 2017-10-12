<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class SubmissionsSongs extends Model
{
    protected $table = 'submission_songs';
    protected $guarded = array('song_id');
    protected $fillable = array( 'submission_id', 'artist',
    'album_artist','album_title', 'song_title', 'credit', 'track_num',
    'tracks_total', 'genre', 's/t', 'v/a', 'compilation','composer', 'crtc',
    'year', 'length', 'file_location', 'updated_at', 'created_at');

    public function submission()
    {
        return $this->belongsTo('App\Submissions');
    }
}
