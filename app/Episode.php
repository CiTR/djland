<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Show;

class Episode extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'show_id',
        'start_time',
        'end_time',
        'title',
        'description',
        'spokenword_duration',
        'language',
        'broadcast_type',
        'is_published',
        'is_web_exclusive',
    ];

    /**
     * Episode belongs to show
     *
     * @return Illuminate\Database\Eloquent\Relations\Relation
     */
    public function show()
    {
        return $this->belongsTo(Show::class);
    }
}
