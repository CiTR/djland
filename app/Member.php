<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'last_name',
        'first_name',
        'preferred_name',
        'is_canadian_citizen',
        'address',
        'city',
        'province',
        'postal_code',
        'membership_type_id',
        'is_new',
        'is_alumni',
        'is_approved',
        'is_discorder_contributor',
        'member_year_id',
        'faculty',
        'school_year',
        'student_no',
        'course_integrate',
        'primary_phone',
        'secondary_phone',
        'comments',
        'about',
        'skills',
        'exposure',
        'taken_station_tour',
        'taken_tech_training',
        'taken_prog_training',
        'taken_prod_training',
        'taken_spoken_training',
    ];

    /**
     * Get the user record associated with the member.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Format postal codes properly
     * 	
     * @param [string] $value The incoming postal code
     */
    public function setPostalCodeAttribute($value)
    {
    	$value = preg_replace('/\s+/', '', $value);
    	$value = strtoupper($value);

    	$this->attributes['postal_code'] = $value;
    }
}
