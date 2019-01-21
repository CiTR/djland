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
        'language',
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

            if (empty($song->language)) {
                $song->language = array_keys(config('djland.languages', ['en'=>'en']))[0];
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


    /**
     * Mutate the length from m:ss to integer seconds
     * 
     * @param mixed $value The length attribute
     * @return int The length in seconds
     */
    public function setLengthAttribute($value)
    {
        if (preg_match('/^([0-9]*)[:]([0-9]*)$/', $value, $matches)) {
            $value = $matches[1]*60+$matches[2];
        }

        $this->attributes['length'] = $value;
    }
}
