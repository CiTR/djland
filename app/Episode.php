<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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

    /**
     * Mutate a given value to adjust the date on a given Carbon attribute
     * 
     * @param string $value The time to set
     * @param string $attr  The name of the Carbon attribute
     * @return boolean
     */
    protected function dateOnDateTimeAttributeMutator($value, $attr) {
        $date_regex = '/(?P<year>[0-9]{2,4})[^A-Za-z0-9]*(?P<month>[0-9]{2})[^A-Za-z0-9]*(?P<day>[0-9]{2})[^A-Za-z0-9]*/';
        
        if (preg_match($date_regex, $value, $matches)) {
            if (intval($matches['year']) < 1000) {
                $matches['year'] = intval($matches['year']) + 2000;
            }

            if (!empty($this->attributes[$attr])) {
                $datetime = $this->{$attr};
                $datetime->setYear($matches['year']);
                $datetime->setMonth($matches['month']);
                $datetime->setDay($matches['day']);
            } else {
                $datetime = Carbon::create($matches['year'], $matches['month'], $matches['day']);
            }

            $this->attributes[$attr] = $datetime;

            return true;
        }

        return false;
    }

    /**
     * Mutate a given value to adjust the time on a given Carbon attribute
     * 
     * @param string $value The time to set
     * @param string $attr  The name of the Carbon attribute
     * @return boolean
     */
    protected function timeOnDateTimeAttributeMutator($value, $attr) {
        $time_regex = '/(?P<hour>[0-1][0-9]):(?P<minute>[0-5][0-9])(?P<ampm>[aApP][.]?[mM][.]?)?/';
        
        if (preg_match($time_regex, $value, $matches)) {
            if (!empty($matches['ampm'])) {
                if (str_contains(strtolower($matches['ampm']), 'p') && intval($matches['hour']) < 12) {
                    $matches['hour'] = intval($matches['hour']) + 12;
                }
            }
            if (empty($this->attributes[$attr])) {
                $datetime = Carbon::now();
            } else {
                $datetime = $this->{$attr};
            }

            $datetime->setTime($matches['hour'], $matches['minute'], 0);

            $this->attributes[$attr] = $datetime;

            return true;
        }

        return false;
    }

    /**
     * Return the formatted string from a DateTime object if not null
     *
     * @param string $attr The attribute to access
     * @param string $format The format to return
     * @return string|null
     */
    protected function dateTimeFormatAccessor($attr, $format) {
        if ($this->{$attr} instanceof \DateTime) {
            return $this->{$attr}->format($format);
        }

        return null;
    }
}
