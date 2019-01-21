<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

use App\Show;
use App\EpisodeItem;
use App\Traits\DatetimeManipulator;

class Episode extends Model
{
    use DatetimeManipulator;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'show_id',
        'start_datetime',
        'end_datetime',
        'host',
        'title',
        'description',
        'spokenword_duration',
        'language',
        'broadcast_type',
        'is_published',
        'is_web_exclusive',
    ];

    /**
     * The accessor attributes to be added to the model at instantiation
     *
     * @var array
     */
    protected $appends = [
        'start_date',
        'end_date',
        'start_time',
        'end_time',
    ];


    /**
     * The date attributes to be instatiated as Carbon
     *
     * @var array
     */
    protected $dates = [
        'start_datetime',
        'end_datetime',
    ];

    /**
     * Boot the model and set listeners
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($episode) {
            if (empty($episode->host) && !empty($episode->show->host)) {
                $episode->host = $episode->show->host;
            }

            return true;
        });
    }

    /**
     * Episode belongs to show
     *
     * @return Illuminate\Database\Eloquent\Relations\Relation
     */
    public function show()
    {
        return $this->belongsTo(Show::class);
    }

    /**
     * Episode has many Episode Items
     *
     * @return Illuminate\Database\Eloquent\Relations\Relation
     */
    public function episodeItems()
    {
        return $this->hasMany(EpisodeItem::class);
    }

    /**
     * Return the date portion of start_datetime
     * 
     * @return string|null
     */
    public function getStartDateAttribute()
    {
        return $this->dateTimeFormatAccessor('start_datetime', 'Y-m-d');
    }

    /**
     * Return the date portion of end_datetime
     * 
     * @return string|null
     */
    public function getEndDateAttribute()
    {
        return $this->dateTimeFormatAccessor('end_datetime', 'Y-m-d');
    }

    /**
     * Return the time portion of start_datetime
     * 
     * @return string|null
     */
    public function getStartTimeAttribute()
    {
        return $this->dateTimeFormatAccessor('start_datetime', 'H:i:s');
    }

    /**
     * Return the time portion of end_datetime
     * 
     * @return string|null
     */
    public function getEndTimeAttribute()
    {
        return $this->dateTimeFormatAccessor('end_datetime', 'H:i:s');
    }

    /**
     * Mutate the start_date attribute to adjust start_datetime
     * 
     * @param string $value The date to set
     * @return boolean
     */
    public function setStartDateAttribute($value)
    {
        return $this->dateOnDateTimeAttributeMutator($value, 'start_datetime');
    }

    /**
     * Mutate the end_date attribute to adjust end_datetime
     * 
     * @param string $value The date to set
     * @return boolean
     */
    public function setEndDateAttribute($value)
    {
        return $this->dateOnDateTimeAttributeMutator($value, 'end_datetime');
    }

    /**
     * Mutate the start_time attribute to adjust start_datetime
     * 
     * @param string $value The time to set
     * @return boolean
     */
    public function setStartTimeAttribute($value)
    {
        return $this->timeOnDateTimeAttributeMutator($value, 'start_datetime');
    }

    /**
     * Mutate the end_time attribute to adjust end_datetime
     * 
     * @param string $value The time to set
     * @return boolean
     */
    public function setEndTimeAttribute($value)
    {
        return $this->timeOnDateTimeAttributeMutator($value, 'end_datetime');
    }
}
