<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MembershipYear extends Model
{
    protected $table = 'membership_years';
    const CREATED_AT = 'create_date';
    const UPDATED_AT = 'edit_date';
    protected $primaryKey = 'member_id';
    protected $fillable = array('member_id', 'membership_year', 'paid', 'sports', 'news', 'arts', 'music', 'show_hosting', 'live_broadcast', 'tech', 'programming_committee', 'ads_psa', 'promotions_outreach', 'discorder', 'discorder_2', 'digital_library', 'photography', 'tabling', 'dj', 'other', 'womens_collective', 'indigenous_collective', 'accessibility_collective', 'create_date', 'edit_date');

    function member(){
    	return $this->belongsTo('App\Member');
    }
}
