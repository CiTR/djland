<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use stdClass;
class Ad extends Model
{
    //
    protected $table = 'adlog';
    protected $fillable = array('playsheet_id', 'num', 'time', 'type', 'name', 'played', 'sam_id', 'time_block');
    public  $timestamps = false;
    public function playsheet(){
    	return $this->belongsTo('App\Playsheet');
    }
    public static function generateAds($show_start_unix,$show_duration){
    	$one_minute = 60;
    	for($offset = 0; $offset < $show_duration; $offset += (20*$one_minute)){
			$date = date($show_start_unix + $offset);

			//If the top of the hour, add a station ID
			if(date('i',$date) == '00'){
				$id = new stdClass();
					$id->type = 'station id';
					$id->name = '';
					$id->time_block = $show_start_unix;
					$id->time = date('g:i a',$date);
				$week_ads[] = $id;
			}elseif(date('i',$date) == '10'){
				//Show started on a half hour, so 20 minute increments land us on 10 minutes past.
				$id = new stdClass();
					$id->type = 'station id';
					$id->name = '';
					$id->time_block = $show_start_unix;
					$id->time = date('g:i a',date($show_start_unix + $offset - 10*$one_minute));
				$week_ads[] = $id;
			}

			//Add an ad + psa
			$ad = new stdClass();
				$ad->type = 'ad';
				$ad->name = '';
				$ad->time_block = $show_start_unix;
				$ad->time = date('g:i a',$date);
			$psa = new stdClass();
				$psa->type = 'psa';
				$psa->name = '';
				$psa->time_block = $show_start_unix;
				$psa->time = date('g:i a',$date);
			
			if($offset != 0){
				$week_ads[] = $ad;
				$week_ads[] = $psa;
			}
			

		}

		//Add announcement and promo 5 minutes before end of show.
		$promo = new stdClass();
			$promo->type = 'promo';
			$promo->name = '';
			$promo->time_block = $show_start_unix;
			$promo->time = date('g:i a',$show_start_unix + $show_duration - 5* $one_minute);
		$announcement = new stdClass();
			$announcement->type = 'announcement';
			$announcement->name = 'Please announce the upcoming program';
			$announcement->time_block = $show_start_unix;	
			$announcement->time = date('g:i a',$show_start_unix + $show_duration - 5* $one_minute);

		$week_ads[] = $promo;
		$week_ads[] = $announcement;

		return $week_ads;
    }
    

    
}
