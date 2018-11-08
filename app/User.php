<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'preferred_name',
        'is_canadian_citizen',
        'address',
        'city',
        'province',
        'postal_code',
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
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

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
        $value = preg_replace('/[^a-zA-Z0-9]+/', '', $value);
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
