<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\User;
use App\Episode;

class Show extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'host',
        'weekday',
        'start_time',
        'end_time',
        'last_show',
        'is_active',
        'is_explicit',
        'website',
        'rss',
        'podcast_xml',
    ];

    /**
     * Show belongs to users
     *
     * @return Illuminate\Database\Eloquent\Relations\Relation
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Show has many episodes
     *
     * @return Illuminate\Database\Eloquent\Relations\Relation
     */
    public function episodes()
    {
        return $this->hasMany(Episode::class);
    }

    /**
     * Fill Hosts field if it's empty
     *
     * @return string
     */
    public function getHostAttribute()
    {
        if (!empty($this->attributes['host'])) {
            return $this->attributes['host'];
        }

        $hosts = array();

        foreach ($this->users as $user) {
            $hosts[] = $user->name;
        }

        if (count($hosts) < 2) {
            return implode(', ', $hosts);
        } elseif (count($hosts) == 2) {
            return implode(' & ', $hosts);
        }

        $last_host = array_pop($hosts);
        return implode(', ', $hosts).' & '.$last_host;
    }
}
