<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Response;
use App\Option;

class MembershipYear extends Model
{
    protected $table = 'membership_years';
    const CREATED_AT = 'create_date';
    const UPDATED_AT = 'edit_date';
    protected $primaryKey = 'id';
    protected $fillable = array(
        'member_id',
        'membership_year',
        'paid',
        'sports',
        'news',
        'arts',
        'music',
        'podcasting',
        'show_hosting',
        'live_broadcast',
        'tech',
        'programming_committee',
        'ads_psa',
        'promotions_outreach',
        'discorder_illustrate',
        'discorder_write',
        'digital_library',
        'photography',
        'tabling',
        'dj',
        'other',
        'womens_collective',
        'indigenous_collective',
        'accessibility_collective',
        'music_affairs_collective',
        'ubc_affairs_collective',
        'poc_collective',
        'lgbt_collective',
        'create_date',
        'edit_date',
    );

    public function member()
    {
        return $this->belongsTo('App\Member');
    }
    public static function rollover()
    {
        // Should only be called through staff middleware
        $cutoff = Option::where('djland_option', '=', 'membership_cutoff')->first();
        $cutoff_values = explode('/', $cutoff->value);
        $new_cutoff = ($cutoff_values[0]+1)."/".($cutoff_values[1]+1);
        return Option::where('djland_option', '=', 'membership_cutoff')->update(['value'=>$new_cutoff]) == 1 ? $new_cutoff: 'Failed' ;
    }
    public static function rollback()
    {
        // Should only be called through staff middleware
        $cutoff = Option::where('djland_option', '=', 'membership_cutoff')->first();
        $cutoff_values = explode('/', $cutoff->value);
        $new_cutoff = ($cutoff_values[0]-1)."/".($cutoff_values[1]-1);
        return Option::where('djland_option', '=', 'membership_cutoff')->update(['value'=>$new_cutoff]) == 1 ? $new_cutoff : 'Failed' ;
    }
}
