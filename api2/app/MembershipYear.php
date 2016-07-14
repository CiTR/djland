<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MembershipYear extends Model
{
    protected $table = 'membership_years';
    const CREATED_AT = 'create_date';
    const UPDATED_AT = 'edit_date';
    protected $primaryKey = 'member_id';
    protected $fillable = array('member_id', 'membership_year', 'paid', 'sports', 'news', 'arts', 'music', 'show_hosting', 'live_broadcast', 'tech', 'programming_committee', 'ads_psa', 'promotions_outreach', 'discorder_illustrate', 'discorder_write', 'digital_library', 'photography', 'tabling', 'dj', 'other', 'womens_collective', 'indigenous_collective', 'accessibility_collective', 'create_date', 'edit_date');

    function member(){
    	return $this->belongsTo('App\Member');
    }
	function rollover(){
		include($_SERVER['DOCUMENT_ROOT'].'/config.php');
		if( (permission_level() >= $djland_permission_levels['staff']['level']) && isset($_POST) && isset($_POST['year'])) {

		}else return false;

	}
	function rollback(){
		include($_SERVER['DOCUMENT_ROOT'].'/config.php');
		if( (permission_level() >= $djland_permission_levels['staff']['level']) && isset($_POST) && isset($_POST['year'])) {

		}else return false;
	}
}
