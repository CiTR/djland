<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Episode;
use App\Song;

class EpisodeItem extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'episode_id',
        'order',
        'artist',
        'title',
        'album',
        'language',
        'start_datetime',
        'duration',
        'song_id',
    ];

    /**
     * Boot the model and set listeners
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::saving(function ($episode_item) {
            if (!empty($episode_item->song_id)) {
                if (empty($episode_item->song)) {
                    $episode_item->load('song');
                }

                $song = $episode_item->song;
                
                if (empty($episode_item->duration) && !empty($song->length)) {
                    $episode_item->duration = $song->length;
                }

                if (empty($episode_item->title) && !empty($song->title)) {
                    $episode_item->title = $song->title;
                }

                if (empty($episode_item->artist) && !empty($song->artist)) {
                    $episode_item->artist = $song->artist;
                }

                if (empty($episode_item->language) && !empty($song->language)) {
                    $episode_item->language = $song->language;
                }

                if (empty($episode_item->album) && !empty($song->album) && !empty($song->album->title)) {
                    $episode_item->album = $song->album->title;
                }
            }

            return true;
        });

        static::creating(function ($episode_item) {
            if (empty($episode_item->order)) {
                $episode_item->order = EpisodeItem::where('episode_id', '=', $episode_item->episode_id)->count() + 1;
            }

            return true;
        });
    }

    /**
     * EpisodeItem belongs to Episode
     *
     * @return Illuminate\Database\Eloquent\Relations\Relation
     */
    public function episode()
    {
        return $this->belongsTo(Episode::class);
    }

    /**
     * EpisodeItem belongs to Song
     *
     * @return Illuminate\Database\Eloquent\Relations\Relation
     */
    public function song()
    {
        return $this->belongsTo(Song::class);
    }
}
