<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MembershipYear extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Get the member records associated with the membership year.
     */
    public function members()
    {
        return $this->hasMany('App\Member');
    }
}
