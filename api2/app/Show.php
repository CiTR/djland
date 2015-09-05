<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
class Show extends Model
{
    protected $table = 'shows';
    const CREATED_AT = 'create_date';
    const UPDATED_AT = 'edit_date';
    protected $fillable = array('podcast_channel_id', 'name', 'primary_genre_tags', 'secondary_genre_tags', 'weekday', 'start_time', 'end_time', 'pl_req', 'cc_req', 'indy_req', 'fem_req', 'last_show', 'edit_date', 'edit_name', 'active', 'crtc_default', 'lang_default', 'website', 'rss', 'show_desc', 'notes', 'show_img', 'sponsor_name', 'sponsor_url', 'showtype', 'alerts');
    
    public function members(){
        return $this->belongsToMany('App\Member','member_show');
    }
    public function playsheets(){
    	return $this->hasMany('App\Playsheet','show_id','id');
    }
    public function host(){
    	return $this->hasOne('App\Host','id','host_id');
    }
    public function social(){
        return $this->hasMany('App\Social');
    }
    public function showtimes(){
        return $this->hasMany('App\Showtime');
    }
    public function channel(){
        return $this->hasOne('App\Channel');
    }
    public function nextShowTime($start_time){
        date_default_timezone_set('America/Los_Angeles');
        $time = $start_time;
        $showtimes = $this->showtimes;
        foreach($showtimes as $key=>$value){

            //Get Current week since Epoch
            $current_week = Date('W', strtotime('tomorrow',strtotime($time)));
            if ((int) $current_week % 2 == 0){
                $current_week_is_even = true;
            } else {
                $current_week_is_even = false;
            };
            
            //See if show is this week
            $this_week = ( $value['alternating'] == '0' ) || ($current_week_is_even && $value['alternating'] == '2') || (!$current_week_is_even && $value['alternating'] == '1');
            
            //Get Previous Sunday
            $last_sunday = strtotime('last sunday');
            //Offest start day by 7 if show is next week
            $startday =  (int) $value['start_day'];
            if (!$this_week) $startday +=7;
            
            //Offset for last sunday
            $showtime_if_it_was_on_last_sunday = strtotime($value['start_time'],  $last_sunday);
            //Corrected show time
            $actual_show_time = strtotime('+'.$startday.' days',$showtime_if_it_was_on_last_sunday);
            $start_time = strtotime($value['start_time'], $last_sunday );

            //If unix string is greater than the actual show time we have had our show this week. Go to next show time
            if ($actual_show_time < strtotime($time)){
                if ( $value['alternating'] == '0') {
                    $actual_show_time = strtotime('+ 1 week', $actual_show_time);
                } else {
                    $actual_show_time = strtotime('+ 2 week', $actual_show_time);
                }
            }

            //Add days since last sunday start
            $start = $last_sunday + $startday*24*60*60;
            
            //Add days since last sunday to end
            $end = strtotime($value['end_time'], strtotime($time));
            $endday = (int) $value['end_day'];
            $end = ($endday)*24*60*60 + $end;

            //Overrwite it? wtf.
            $end = strtotime($value['end_time'], $actual_show_time);
            

            $candidates []= array('start' => $actual_show_time, 'end' => $end);
        }
        //Find the minimum start time
        $min = $candidates[0];
        foreach($candidates as $i => $v){
            if ($v['start'] < $min['start']){
                $min = $candidates[$i];
            }
        }
        return $min;
    }

}
