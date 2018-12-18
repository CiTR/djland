<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Song;

class Album extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'artist',
        'title',
        'label',
        'catalog',
        'description',
    ];

    /**
     * Album contains many Songs
     *
     * @return Illuminate\Database\Eloquent\Relations\Relation
     */
    public function songs()
    {
        return $this->hasMany(Song::class);
    }
}
