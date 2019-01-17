<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class AdSchedule extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'type',
        'description',
        'minutes_into_show',
        'minutes_past_hour',
        'time_start',
        'time_end',
        'active_datetime_start',
        'active_datetime_end',
    ];

    /**
     * The date attributes to be instatiated as Carbon
     *
     * @var array
     */
    protected $dates = [
        'active_datetime_start',
        'active_datetime_end',
    ];

    /**
     * Boot the model and set listeners
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($ad_schedule) {
            if (is_null($ad_schedule->active_datetime_start)) {
                $ad_schedule->active_datetime_start = Carbon::now();
            }

            return true;
        });
    }

    /**
     * Return the date portion of active_datetime_start
     * 
     * @return string|null
     */
    public function getStartDateAttribute()
    {
        return $this->dateTimeFormatAccessor('active_datetime_start', 'Y-m-d');
    }

    /**
     * Return the date portion of active_datetime_end
     * 
     * @return string|null
     */
    public function getEndDateAttribute()
    {
        return $this->dateTimeFormatAccessor('active_datetime_end', 'Y-m-d');
    }

    /**
     * Return the time portion of active_datetime_start
     * 
     * @return string|null
     */
    public function getStartTimeAttribute()
    {
        return $this->dateTimeFormatAccessor('active_datetime_start', 'H:i:s');
    }

    /**
     * Return the time portion of active_datetime_end
     * 
     * @return string|null
     */
    public function getEndTimeAttribute()
    {
        return $this->dateTimeFormatAccessor('active_datetime_end', 'H:i:s');
    }

    /**
     * Mutate the start_date attribute to adjust active_datetime_start
     * 
     * @param string $value The date to set
     * @return boolean
     */
    public function setStartDateAttribute($value)
    {
        return $this->dateOnDateTimeAttributeMutator($value, 'active_datetime_start');
    }

    /**
     * Mutate the end_date attribute to adjust active_datetime_end
     * 
     * @param string $value The date to set
     * @return boolean
     */
    public function setEndDateAttribute($value)
    {
        return $this->dateOnDateTimeAttributeMutator($value, 'active_datetime_end');
    }

    /**
     * Mutate the start_time attribute to adjust active_datetime_start
     * 
     * @param string $value The time to set
     * @return boolean
     */
    public function setStartTimeAttribute($value)
    {
        return $this->timeOnDateTimeAttributeMutator($value, 'active_datetime_start');
    }

    /**
     * Mutate the end_time attribute to adjust active_datetime_end
     * 
     * @param string $value The time to set
     * @return boolean
     */
    public function setEndTimeAttribute($value)
    {
        return $this->timeOnDateTimeAttributeMutator($value, 'active_datetime_end');
    }

    /**
     * Set the minutes_into_show attribute and remove the minutes_past_hour attribute
     * 
     * @param numeric $value The number of minutes
     * @return null
     */
    public function setMinutesIntoShowAttribute($value)
    {
        $this->attributes['minutes_into_show'] = $value;
        if (!is_null($value)) {
            $this->attributes['minutes_past_hour'] = null;
        }
    }

    /**
     * Set the minutes_past_hour attribute and remove the minutes_into_show attribute
     * 
     * @param numeric $value The number of minutes
     * @return null
     */
    public function setMinutesPastHourAttribute($value)
    {
        $this->attributes['minutes_past_hour'] = $value;
        if (!is_null($value)) {
            $this->attributes['minutes_into_show'] = null;
        }
    }

    /**
     * Check if the ad schedule is active during a given datetime
     * 
     * @param string|DateTime|Carbon|null $datetime The datetime to check against
     * @return boolean
     */
    public function isActive($datetime = null)
    {
        if (is_null($datetime)) {
            $datetime = Carbon::now();
        } elseif (is_string($datetime) || $datetime instanceof DateTime) {
            $datetime = new Carbon($datetime);
        }

        if ($datetime->lessThan($this->active_datetime_start)) {
            return false;
        }

        if (!is_null($this->attributes['active_datetime_end']) && $datetime->greaterThan($this->active_datetime_end)) {
            return false;
        }

        if ($datetime->toTimeString() < $this->time_start) {
            return false;
        }

        if ($datetime->toTimeString() > $this->time_end) {
            return false;
        }

        return true;
    }
}
