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
        'member_since',
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
     * Get the membership type record associated with the member.
     */
    public function membership_type()
    {
        return $this->belongsTo('App\MembershipType');
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

    /**
     * Format member since property. If using school years 
     * (eg 2017/2018) then the smaller of the 2 years will be used.
     * 
     * 	
     * @param [string|int] $value The year the member started in
     */
    public function setMemberSinceAttribute($value)
    {
        if (preg_match('/^(\d{4})/', $value, $matches)) {
            $value = $matches[1];
        } elseif (preg_match('/(\d{4})$/', $value, $matches)) {
            $value = $matches[1];
        }

        $this->attributes['member_since'] = $value;
    }
}
