<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Album;

class Song extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'album_id',
        'title',
        'artist',
        'length',
        'lyrics',
    ];

    /**
     * Boot the model and set listeners
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($song) {
            if (empty($song->artist) && !empty($song->album)) {
                $song->artist = $song->album->artist;
            }

            return true;
        });

        static::saving(function ($song) {
            if (preg_match('/^([0-9]*)[:]([0-9]*)$/', $song->length, $matches)) {
                $song->length = $matches[1]*60+$matches[2];
            }

            return true;
        });
    }

    /**
     * Song belongs to an album
     *
     * @return Illuminate\Database\Eloquent\Relations\Relation
     */
    public function album()
    {
        return $this->belongsTo(Album::class);
    }
}
