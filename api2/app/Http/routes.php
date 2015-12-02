<?php

use App\User as User;
use App\Member as Member;
use App\MembershipYear as MembershipYear;
use App\Permission as Permission;
use App\Show as Show;
use App\Showtime as Showtime;
use App\Host as Host;
use App\Social as Social;
use App\Playsheet as Playsheet;
use App\Playitem as Playitem;
use App\Podcast as Podcast;
use App\Ad as Ad;
use App\Socan as Socan;
use App\SpecialBroadcasts as SpecialBroadcasts;

//SAM CLASSES
use App\Songlist as Songlist;
use App\Categorylist as Categorylist;
use App\Historylist as Historylist;

use App\Friends as Friends;

Route::get('/', function () {
    //return view('welcome');
    return "Welcome to DJLand API 2.0";
});
Route::group(['middleware' => 'auth'], function(){
	
	//Member Routes 
	Route::group(array('prefix'=>'member'), function(){
		
		Route::get('/',function(){
			return  DB::table('membership')->select('id','firstname','lastname')->get();
		});
		
		Route::get('list',function(){
			$full_list = Member::select('id','firstname','lastname')->get();
			foreach ($full_list as $m) {
				$members[] = ['id'=>$m->id,'firstname'=>$m->firstname,'lastname'=>$m->lastname];
			}
			return $members;
		});
		
		//Searching by member ID
		Route::group(array('prefix'=>'{id}'), function($id = id){
			
			Route::get('/',function($id){
				return Member::find($id);
			});
			Route::post('/',function($id){
				$m = Member::find($id);
				return $m->update((array) json_decode(Input::get()['member']) ) ? "true": "false";
			});
			Route::delete('/',function($id){
				return Member::find($id)->delete() ? "true":"false";
			});

			Route::post('/comments',function($id){
				$member = Member::find($id);
				$member -> comments = json_decode(Input::get()['comments']);
				return Response:: json($member -> save());

			});
			Route::get('training',function($id){
				$member =  Member::find($id);
				if($member->station_tour == '0' || $member->technical_training == '0' || $member->programming_training == '0' || $member->production_training == '0'){
					return 0;
				}else{
					return 1;
				}
			});
			Route::get('user',function($id){
				return Member::find($id)->user;
			});
			Route::post('user',function($id){
				$m = Member::find($id);
				return $m->user->update(Input::get()['user']) ? "true": "false";
			});
			Route::post('permission',function($id){
				$permission = Member::find($id)->user->permission;
				return $permission->update((array) json_decode(Input::get()['permission'] )) ? "true": "false";
			});
			Route::get('years',function($id){
				$m_years = Member::find($id)->membershipYears()->orderBy('membership_year','desc')->get();
				foreach($m_years as $year){
					$years[$year->membership_year] = $year;
				}
				return Response::json($years);
			});
			Route::post('years',function($id){
				$m = Member::find($id);
				$m_years = (array) json_decode(Input::get()['years']);
				$years = $m->membershipYears;
				foreach($years as $year){
					$year -> update( (array) $m_years[$year->membership_year]);
				}
				return "true";
			});
			Route::post('password',function($id){
				$m = Member::find($id);
				$user = $m->user;
				$user->password = password_hash(Input::get()['password'],PASSWORD_DEFAULT);
				return $user->save() ? "true":"false";
			});
			Route::get('permission',function($member_id = id){
				$permission_levels = Member::find($member_id)->user->permission;
				unset($permission_levels->user_id);
				$permission = new stdClass();
				$permission->permissions = $permission_levels;
				return $permission_levels;
			});
			Route::get('shows', function($member_id = id){
				$permissions = Member::find($member_id)->user->permission;
				if($permissions->staff ==1 || $permissions->administrator==1){
					$all_shows = Show::orderBy('name','asc')->get();
					foreach($all_shows as $show){
						$shows->shows[] = ['id'=>$show->id,'show'=>$show,'name'=>$show->name];
					}
				}else{
					$member_shows = Member::find($member_id)->shows;
					foreach($member_shows as $show){
						$shows->shows[] = ['id'=>$show->id,'show'=>$show,'name'=>$show->name];
					}
				}
				return  Response::json($shows);
			});
			Route::get('active_shows', function($member_id = id){
				$permissions = Member::find($member_id)->user->permission;
				$shows = new stdClass();
				if($permissions->staff ==1 || $permissions->administrator==1){
					$all_shows = Show::where('active','=','1')->orderBy('name','asc')->get();
					foreach($all_shows as $show){
						$shows->shows[] = ['id'=>$show->id,'show'=>$show,'name'=>$show->name,'crtc'=>$show->crtc_default,'lang'=>$show->lang_default];
					}
				}else{
					$member_shows = Member::find($member_id)->shows;;
					foreach($member_shows as $show){
						if($show->active == 1){
							$shows->shows[] = ['id'=>$show->id,'show'=>$show,'name'=>$show->name,'crtc'=>$show->crtc_default,'lang'=>$show->lang_default];	
						}
					}
				}
				return  Response::json($shows);
			});
		});
	
	});
});

// Member Creation Routes
	Route::post('/member',function(){
		$member = Member::create( (array) json_decode(Input::get()['member']));
		return $member->id;
	});
	Route::post('/user',function(){
		$user = json_decode(Input::get()['user']);
		$user->password = password_hash($user->password,PASSWORD_DEFAULT);
		$user->status = 'enabled';
		$user->login_fails = '0';
		$user = User::create((array) $user);
		$permissions = array('user_id'=> $user->id,'administrator'=>"0",'dj'=> "0",'member'=> "1",'staff'=> "0",'volunteer'=> "0",'workstudy'=> "0");
		Permission::create($permissions);
		return $user->id;
	});
	Route::post('/member/{id}/year',function($id = id){
		$member = Member::find($id);
		$membership_year = json_decode(Input::get()['year']);
		$membership_year->member_id = $id;
		return MembershipYear::create((array) $membership_year) ? "true" : "false";
	});


/* Show Routes */
Route::group(array('prefix'=>'show'),function(){
	//Creating new Show
	Route::post('/',function(){
		$show = Show::create((array) Input::get()['show']);
		$owners = Input::get()['owners'];
		$social = Input::get()['social'];
		$showtimes = Input::get()['showitmes'];
		
		//Create owners
		foreach($owners as $owner){
			Show::find($show->id)->members()->attach($owner['id']);
		}
		//Create social entries, this table is really dumb.
		foreach($social as $social){
			$social->show_id = $show->id;
			Social::create($social);
		}
		//Create Showtimes
		foreach($showtimes as $showtime){
			$showtime->show_id = $show->id;
			Showtime::create($showtime);
		}
	});


	Route::get('/',function(){
		return Show::all('id','name');
	});

	Route::get('/active',function(){
		return Show::select('id','name')->where('active','=','1')->get();
	});
	
	//Searching by Show ID
	Route::group(array('prefix'=>'{id}'),function($id=id){
		
		Route::get('/',function($id){
			$show = Show::find($id);
			$show->social = Show::find($id)->social;
			return Response::json($show);
		});
		Route::post('/',function($id){
			$show = Input::get()['show'];
			$social = Input::get()['social'];
			$owners = Input::get()['owners'];
			$showtimes = Input::get()['showtimes'];
			$s = Show::find($id);
			$s->update($show);

		

			//Detach current owners
			foreach(Show::find($id)->members as $current_owner){
				$s->members()->detach($current_owner->id);
			}
			//Attach new owners
			foreach($owners as $owner){
				Show::find($id)->members()->attach($owner['id']);
			}

			//Delete all social entries
			$delete = Social::find($id);
			if($delete !=null) $delete->delete();
			//Create new social entries, this table is really dumb.
			foreach($social as $item){
				Social::create($item);
			}

			//Delete all showtime entries
			$delete = Showtime::find($id);
			if($delete != null) $delete->delete();
			//Recreate show times
			foreach($showtimes as $showtime){
				Showtime::create($showtime);
			}
		});
		Route::get('episodes/{offset}',function($id,$offset = offset){
			$podcasts = Show::find($id)->podcasts()->orderBy('id','desc')->limit(50)->offset($offset)->get();
			$episodes = array();
			$socan = Socan::all();
			foreach($podcasts as $podcast){
				$playsheet = $podcast->playsheet;
				
				if($playsheet != null){
					$playsheet->socan = false;
					foreach($socan as $period){
						if( strtotime($period['socanStart']) <= strtotime($playsheet->start_time) && strtotime($period['socanEnd']) >= strtotime($playsheet->end_time)){
							$playsheet->socan = true;
						}
					}
					if($podcast -> duration == 0){
						$podcast -> duration_from_playsheet();
					}
					unset($podcast->playsheet);
					$episode = ['playsheet'=>$playsheet,'podcast'=>$podcast];
					$episodes[] = $episode;
				}else{
					$episode = ['podcast'=>$podcast];
					$episodes[] = $episode;
				}
				
			}
			return Response::json($episodes);

		});
		
		Route::get('playsheets',function($id){
			return Show::find($id)->playsheets;
		});
		Route::get('owners',function($id){
			$members = Show::find($id)->members;
			$owners = new stdClass();
			$owners->owners = [];
			foreach ($members as $member) {
				$owners->owners[] = ['id'=>$member->id,'firstname'=>$member->firstname,'lastname'=>$member->lastname];
			}
			return Response::json($owners);
		});
		Route::get('social',function($id){
			return Show::find($id)->social;
		});

		Route::get('times',function($id){
			//return Showtimes::where('show_id','=',$show_id)->get();
			return Show::find($id)->showtimes;
		});
		Route::get('nextshow/{current_time}',function($id,$time = current_time){
			return Response::json(Show::find($id)->nextShowTime($time));
		});
		Route::get('xml',function($id){
			return Show::find($id)->make_show_xml();
		});
	});
});

Route::get('/social/{id}',function($show_id = id){
	return Social::where('show_id','=',$show_id)->get();
});


/* Playsheet Routes */
Route::group(array('prefix'=>'playsheet'),function(){
	Route::get('/',function(){
		return $playsheets = Playsheet::orderBy('id','desc')->select('id')->get();
	});
	Route::post('/',function(){
		$ps = Playsheet::create(Input::get()['playsheet']);
		$podcast_in = Input::get()['podcast'];
		$podcast_in ['playsheet_id'] = $ps->id;
		$podcast_in['title'] = $ps->title;
		$podcast_in['subtitle'] = $ps->summary;
		$podcast = Podcast::create($podcast_in);

		foreach(Input::get()['playitems'] as $playitem){
			$playitem['playsheet_id'] = $ps->id;
			Playitem::create($playitem);
		}
		foreach(Input::get()['ads'] as $ad){
			$ad['playsheet_id'] = $ps->id;
			if(isset($ad['id'])){
				$a = Ad::find($ad['id']);
				unset($ad['id']);
				$a->update((array) $ad);
			}else{
				$a = Ad::create((array) $ad);
			}			
		}
		$response = new stdClass();
		$response->id = $ps->id;
		$response->podcast_id = $podcast->id;
		return Response::json($response);
	});
	Route::post('/report',function(){
		$from = isset(Input::get()['from']) ? str_replace('/','-',Input::get()['from']) : null;
		$to = isset(Input::get()['to']) ? str_replace('/','-',Input::get()['to']) : null;
		$show_id = isset(Input::get()['show_id']) ? Input::get()['show_id'] : null;
		if($from != null && $to != null){
			if($show_id != null && $show_id != 'all'){
				$playsheets = Playsheet::orderBy('start_time','asc')->where('start_time','>=',$from." 00:00:00")->where('start_time','<=',$to." 23:59:59")->where('show_id','=',$show_id)->get();
			}else{
				$playsheets = Playsheet::orderBy('start_time','asc')->where('start_time','>=',$from." 00:00:00")->where('start_time','<=',$to." 23:59:59")->get();
			}
		}else{
			if($show_id != null && $show_id != 'all'){
				$playsheets = Playsheet::orderBy('start_time','asc')->where('show_id','=',$show_id)->get();
			}else{
				$playsheets = Playsheet::orderBy('start_time','asc')->get();
			}
		}
		foreach($playsheets as $p){
			$playsheet = $p;
			$playsheet->playitems = $p->playitems;
			$playsheet->show = $p->show;
			$playsheet->socan = $p->is_socan();
			if( $p->start_time && $p->end_time){
				$playsheet->ads = Historylist::where('date_played','<=',$p->end_time)->where('date_played','>=',$p->start_time)->where('songtype','=','A')->get();
			}
			$totals['cancon'][0] = 0;
			$totals['femcon'][0] = 0;
			$totals['cancon'][1] = 0;
			$totals['femcon'][1] = 0;
			$totals['hit'][0] = 0;
			$totals['hit'][1] = 0;
			foreach($playsheet->playitems as $playitem){
				//CANCON
				$totals['cancon'][0] += 1;
				if($playitem['is_canadian'] == '1') $totals['cancon'][1] += 1;
				//FEMCON
				$totals['femcon'][0] += 1;
				if($playitem['is_fem'] == '1') $totals['femcon'][1] += 1;
				//HIT
				$totals['hit'][0] += 1;
				if($playitem['is_hit'] == '1') $totals['hit'][1] += 1;
			}
			$playsheet->totals = $totals;
		}
		return $playsheets;
	});
	
	//Searching by Playsheet ID
	Route::group(array('prefix'=>'{id}'),function($id = id){
		//Get Existing Playsheet
		Route::get('/',function($id){
			require_once($_SERVER['DOCUMENT_ROOT'].'/config.php');
			$playsheet = new stdClass();
			$playsheet -> playsheet = Playsheet::find($id);
			if($playsheet -> playsheet != null){
				$playsheet -> playitems = Playsheet::find($id)->playitems;
				$show = Playsheet::find($id)->show;
				$playsheet -> show = $show;
				$playsheet -> podcast = Playsheet::find($id)->podcast;
				$ads = Playsheet::find($id)->ads;
				foreach($ads as $key => $value){
					//Get Ad Names From SAM
					if($using_sam && is_numeric($value['name'])){
						$ad_info =  DB::connection('samdb')->table('songlist')->select('*')->where('id','=',$value['name'])->get();
						if(count($ad_info) == 1) $ads[$key]['name'] = $ad_info[0]->title;
					}else{
						$ads[$key]['name'] = html_entity_decode($ads[$key]['name'],ENT_QUOTES);
					}
				}
				$playsheet -> ads = $ads;
			}
			return Response::json($playsheet);
		});
		//Save Existing Playsheet
		Route::post('/',function($id){
			$ps = Playsheet::find($id);
			$ps->update(Input::get()['playsheet']);
			$ps->podcast->update((array) Input::get()['podcast']);
			$playitems = Input::get()['playitems'];
			foreach($ps->playitems as $delete){
				$delete->delete();
			}
			foreach($playitems as $playitem){
				Playitem::create($playitem);
			}
			foreach(Input::get()['ads'] as $ad){
				$ad['playsheet_id'] = $ps->id;
				$a = Ad::find($ad['id'])->update((array) $ad);
			}	
		});
		Route::delete('/',function($id){
			return Response::json(Playsheet::find($id)->delete());
		});
		Route::post('episode',function($id){
			$playsheet = Playsheet::find($id);
			$podcast = $playsheet->podcast;
			return Response::json( $playsheet -> update((array) Input::get()['playsheet']) && $podcast -> update((array) Input::get()['podcast']) ? "true" : "false");
		});
	});
	Route::get('member/{member_id}/{offset}',function($member_id = member_id,$offset = offset){
		$permissions = Member::find($member_id)->user->permission;
		if($permissions->staff ==1 || $permissions->administrator==1){
			$shows = Show::all();
		}else{
			$shows =  Member::find($member_id)->shows;
		}
		foreach($shows as $show){
			$show_ids[] = $show->id;
		}
		foreach(Playsheet::orderBy('start_time','desc')->whereIn('show_id',$show_ids)->limit('200')->offset($offset)->get() as $ps){
			$playsheet = new stdClass();
			$playsheet = $ps;
			$playsheet -> show_info = Show::find($ps->show_id);
			$playsheet->socan = $playsheet->is_socan();
			$playsheets[] = $playsheet;
		}
		return Response::json($playsheets);	
	});
	Route::get('member/{member_id}',function($member_id = member_id){
		$permissions = Member::find($member_id)->user->permission;
		if($permissions->staff ==1 || $permissions->administrator==1){
			$shows = Show::all();
		}else{
			$shows =  Member::find($member_id)->shows;
		}
		foreach($shows as $show){
			$show_ids[] = $show->id;
		}
		$socan = Socan::all();
		foreach(Playsheet::orderBy('start_time','desc')->whereIn('show_id',$show_ids)->limit('200')->get() as $ps){
			$playsheet = new stdClass();
			$playsheet = $ps;
			$playsheet -> show_info = Show::find($ps->show_id);
			$playsheet->socan = false;

			foreach($socan as $period){
				if( strtotime($period['socanStart']) <= strtotime($playsheet->start_time) && strtotime($period['socanEnd']) >= strtotime($playsheet->end_time)){
					$playsheet->socan = true;
				}
			}
			$playsheets[] = $playsheet;
		}
		return Response::json($playsheets);	
	});

	Route::get('list',function(){
		return DB::table('playsheets')
		->join('shows','shows.id','=','playsheets.show_id')
		->select('playsheets.id','shows.host','playsheets.start_time')
		->limit('100')
		->orderBy('playsheets.id','desc')
		->get();
	});
	Route::get('list/{limit}',function($limit = limit){
		$playsheets = Playsheet::orderBy('id','desc')->limit($limit)->get();
		foreach($playsheets as $playsheet){
			if($playsheet != null){
				$ps = new stdClass();
				$ps -> id = $playsheet -> id;
				$ps -> start_time = $playsheet->start_time;
				$ps -> show = Show::find($playsheet->show_id);
				$list[] = $ps;
			}
		}
		return Response::json($list);
	});
});

	Route::put('/podcast',function(){
		$podcast = Podcast::create((array) Input::get()['podcast']);
		$podcast->duration_from_playsheet();
		return Response::json(array('id'=>$podcast->id));
	});
	Route::post('/podcast/{id}',function($id = id){
		$podcast = Podcast::find($id);
		$podcast->update(Input::get()['podcast']);
	});
	Route::post('/podcast/{id}/audio',function($id = id){
		$podcast = Podcast::find($id);
		$result = $podcast->make_podcast();
		return $result;
	});
	Route::post('/podcast/{id}/overwrite',function($id = id){
		$podcast = Podcast::find($id);
		$result = $podcast->overwrite_podcast();
		return $result;
	});
	Route::get('/shows/write_xml',function(){

		$shows = Show::orderBy('id')->get();
		echo "<pre>";
		$index = 0;
		foreach($shows as $show){
			$index++;
			if($show->podcast_slug){
				$result = $show->make_show_xml();
				$result['index'] = $index;
				print_r($result);

				$results[] = $result;
			}			
		}
		//return Response::json($results);
	});
	Route::get('/shows',function(){
		$shows = Show::all();
		echo "<pre>";
		foreach($shows as $show){
			print_r(array($show->name,$show->id,$show->podcast_slug));
		}
	});
Route::get('/adschedule/{date}',function($date = date){
	date_default_timezone_set('America/Los_Angeles');
	$date = date('Y-M-d',strtotime($date));
	$unix = strtotime($date);
	$parsed_date = date_parse($date);
	if($parsed_date["error_count"] == 0 && checkdate($parsed_date["month"], $parsed_date["day"], $parsed_date["year"])){
		//Constants (second conversions)
		$one_day = 24*60*60;
		$one_hour = 60*60;
		$one_minute = 60;

		//Get mod 2 of week since start of year(always 52 weeks so this is acceptable for next 1000 years?) Add 1 to get week 1 or 2
	    $week = (date('W',strtotime($date)) % 2) +1;
		//Get Day of Week (0-6)
		$day_of_week = date('w',strtotime($date));
		
		if($date == date('Y-M-d',strtotime('now'))){
			//Set cutoff time to right now if we are loading today
			$time = date('H:i:s',strtotime('now'));
		}else{
			//Set cutoff time to 00:00:00
			$time = '00:00:00';
		}
		
		//Select active shows that run during the date specified.
		$shows = 
		Show::selectRaw('shows.id,shows.name,show_times.start_day,show_times.start_time,show_times.end_day,show_times.end_time')
		->join('show_times','show_times.show_id','=','shows.id')
		->where('show_times.start_day','=',$day_of_week)
		->where('show_times.start_time','>=',$time)
		->whereRaw('(show_times.alternating = '.$week.' OR show_times.alternating = 0)')
		->where('shows.active','=','1')
		->orderBy('show_times.start_time','ASC')
		->get();

		//for each show time get the ads, or create them.
		foreach($shows as $show_time){
			$start_hour_offset = date_parse($show_time['start_time'])['hour'] * $one_hour;
			$start_minute_offset = date_parse($show_time['start_time'])['minute'] * $one_minute;			
			$start_unix_offset = $start_hour_offset + $start_minute_offset;
			$end_hour_offset = date_parse($show_time['end_time'])['hour'] * $one_hour;
			$end_minute_offset = date_parse($show_time['end_time'])['minute'] * $one_minute;			
			$end_unix_offset = $end_hour_offset + $end_minute_offset;
			if( $show_time['end_day'] != $show_time['start_day'] ){
				$end_unix_offset += $one_day;
			}			

			$show_time->start_unix = $unix + $start_unix_offset;
			$show_time->end_unix = $unix + $end_unix_offset;
			$show_time->duration = $show_time->end_unix - $show_time->start_unix;

			/*if( date('I',strtotime($show_time->start_unix))=='0' ){
                $show_time->start_unix += 3600;
                $show_time->end_unix += 3600;
            }*/

			$ads = Ad::where('time_block','=',$show_time->start_unix)->get();
			$show_time->generated = false;
			if(!$ads->first()){
				$show_time->generated = true;
				$ads = Ad::generateAds($show_time->start_unix,$show_time->duration);
			}
			$show_time->ads = $ads;
			$show_time->date = date('l F jS g:i a',$show_time->start_unix);
			$show_time->start = date('g:i a',$show_time->start_unix);
		}
		return $shows;
	}else{
		http_response_code('400');
		return "Not a Valid Date: {$date}";
	}
	

});
Route::get('/adschedule',function(){
	date_default_timezone_set('America/Los_Angeles');
	$active_shows = Show::select('*')->where('active','=','1')->get();
	$schedule = array();
	//Get mod 2 of current week since start of year(always 52 weeks so this is acceptable for next 1000 years?) Add 1 to get week 1 or 2
    $current_week = (date('W',strtotime('now')) % 2) +1;
	//Get Day of Week (0-6)
	$day_of_week = date('w',strtotime('now'));
	//Get Current Time (0-23:0-59:0-59)
	$current_time = date('H:i:s',strtotime('now'));
	
	//Making sure if today is sunday, it does not get last sunday instead of today.
	if($day_of_week == 0){
		$week_0_start = strtotime('today');
		$week_1_start = strtotime('+1 week',$week_0_start);
		$week_2_start = strtotime('+1 week',$week_1_start);
	}else{
		$week_0_start = strtotime('last sunday 00:00:00')  ;
		$week_1_start = strtotime('+1 week',$week_0_start);
		$week_2_start = strtotime('+1 week',$week_1_start);
	}
	
	//Constants (second conversions)
	$one_day = 24*60*60;
	$one_hour = 60*60;
	$one_minute = 60;
	$schedule = array();
	//Getting this week.
	foreach($active_shows as $show){
		//Get next showtime catching error for show having no showtime
		try{
			$times = $show->showtimes;
			foreach($times as $show_time){
				//Calculating how many seconds from start of week the showtime occurs.
				$show_time_day_offset = ($show_time['start_day']) * $one_day;
				$show_time_hour_offset = date_parse($show_time['start_time'])['hour'] * $one_hour;
				$show_time_minute_offset = date_parse($show_time['start_time'])['minute'] * $one_minute;			
				$show_time_unix_offset = $show_time_day_offset + $show_time_hour_offset + $show_time_minute_offset;
				
				if($show_time['start_day'] != $show_time['end_day']){
					$show_duration = (24 - date_parse($show_time['start_time'])['hour'] + date_parse($show_time['end_time'])['hour'])*$one_hour + (60 - date_parse($show_time['start_time'])['minute'] + date_parse($show_time['end_time'])['minute'])*$one_minute;
				}else{
					$show_end_time_unix_offset = $show_time['end_day'] * $one_day + date_parse($show_time['end_time'])['hour'] * $one_hour + date_parse($show_time['end_time'])['minute'] * $one_minute;
					$show_duration = abs($show_end_time_unix_offset - $show_time_unix_offset);
				}

				//Unix timestamp of possible show start times
				$week_0_show_unix = $week_0_start + $show_time_unix_offset;
				$week_1_show_unix = $week_1_start + $show_time_unix_offset;
				$week_2_show_unix = $week_2_start + $show_time_unix_offset;

				//DST Offset
	            if( date('I',strtotime($week_0_show_unix))=='0' ){
	                //$week_0_show_unix += 3600;
	            }
	            if( date('I',strtotime($week_1_show_unix))=='0' ){
	                //$week_1_show_unix += 3600;
	            }
	            if( (date('I',strtotime($week_2_show_unix))=='0') ){
	               // $week_2_show_unix += 3600;
	            }

				//Get Ads
				$week_0_ads = array();
				$week_0_ads = Ad::where('time_block','=',$week_0_show_unix)->get();
				$week_1_ads = array();
				$week_1_ads = Ad::where('time_block','=',$week_1_show_unix)->get();
				$week_2_ads = array();
				$week_2_ads = Ad::where('time_block','=',$week_2_show_unix)->get();	
					
				//Fill in ads if none exist. Doing it serverside, as client side was slow slow slowwww.
				if(count($week_0_ads) <= 2){
					//Insert a new entry every 20 minutes
					$week_0_ads = Ad::generateAds($week_0_show_unix,$show_duration);					
				}
				if(count($week_1_ads) <= 2){
					//Insert a new entry every 20 minutes
					$week_1_ads = Ad::generateAds($week_1_show_unix,$show_duration);					
				}
				if(count($week_2_ads) <= 2){
					//Insert a new entry every 20 minutes
					$week_2_ads = Ad::generateAds($week_2_show_unix,$show_duration);					
				}
				

				//Generate Arrays
				$week_0 = array(
					$week_0_show_unix,
					array(
						"id"		=>$show->id,
						"name"		=>$show->name,
						"start_time"=>$show_time['start_time'],
						"end_time"	=>$show_time['end_time'],
						"start_unix"=>$week_0_show_unix,
						"end_unix"	=>$week_0_show_unix + $show_duration,
						"duration"	=>$show_duration,
						"start"		=>date('g:i a',$week_0_show_unix),
						"date"		=>date('l F jS g:i a',$week_0_show_unix),
						"ads"		=>$week_0_ads
					)
				);
				$week_1 = array(
					$week_1_show_unix,
					array(
						"id"		=>$show->id,
						"name"		=>$show->name,
						"start_time"=>$show_time['start_time'],
						"end_time"	=>$show_time['end_time'],
						"start_unix"=>$week_1_show_unix,
						"end_unix"	=>$week_1_show_unix + $show_duration,
						"duration"	=>$show_duration,
						"start"		=>date('g:i a',$week_1_show_unix),
						"date"		=>date('l F jS g:i a',$week_1_show_unix),
						"ads"		=>$week_0_ads
					)
				);
				$week_2 = array(
					$week_2_show_unix,
					array(
						"id"		=>$show->id,
						"name"		=>$show->name,
						"start_time"=>$show_time['start_time'],
						"end_time"	=>$show_time['end_time'],
						"start_unix"=>$week_2_show_unix,
						"end_unix"	=>$week_2_show_unix + $show_duration,
						"duration"	=>$show_duration,
						"start"		=>date('g:i a',$week_0_show_unix),
						"date"		=>date('l F jS g:i a',$week_2_show_unix),
						"ads"		=>$week_2_ads
					)
				);

				//Check if a showtime's day has already been passed. If no, add it to week 0, if yes we have to add it to week 2 instead of week 0
				if( ($show_time['start_day'] == $day_of_week && $show_time['start_time'] >= $current_time) || $show_time['start_day'] > $day_of_week){
					//Hasn't happened yet, look at weeks 0 and 1
					if($show_time['alternating'] == '0'){
						//Occurs Weekly, Add to week 0,1
						$schedule[] = $week_0[1];
						$schedule[] = $week_1[1];
					}else if($show_time['alternating'] == $current_week){
						//Occurs this week, add to remainder of week 0
						$schedule[] = $week_0[1];
					}else{
						//Doesn't occur this week, add to week 1
						$schedule[] = $week_1[1];
					}

				}else{
					//Already occured this week, look at weeks 1 and 2
					if($show_time['alternating'] == '0'){
						//Occurs weekly, add to week 1,2
						$schedule[] = $week_1[1];
						$schedule[] = $week_2[1];
					}else if($show_time['alternating'] == $current_week){
						//Occurs this week, add to week 2
						$schedule[] = $week_2[1];
					}else{
						//Doesn't occur this week, add to week 1
						$schedule[] = $week_1[1];
					}
				}
			}
			
		}catch(Exception $e){
			//No Show time available or exception thrown.
			return "Exception Thrown: ".$e->getMessage()."<br/><pre>".$e->getTraceAsString();
		}
	}
	return Response::json($schedule);
	
});

Route::post('/adschedule',function(){
	$showtimes = Input::get()['showtimes'];
	foreach($showtimes as $showtime){
		$ads = $showtime['ads'];
		$a = array();
		$index = 1;
		$to_delete = Ad::where('time_block','=',$showtime['start_unix'])->get();
		foreach($ads as $ad){
			if(isset($ad['id'])){
				$item = Ad::find($ad['id']);
				$ad['num'] = $index++;
				$item->update($ad);
			}else{
				$ad['num'] = $index++;
				$item = Ad::create($ad);
			}
			$a[] = $item;
		}
		foreach($to_delete as $delete){
			$found = false;
			foreach($a as $item){
				if($delete['id'] == $item['id']) $found = true; 
			}
			if($found == false) Ad::find($delete['id'])->delete();
 		}
		$s[$showtime['start_unix']] = $a;
	}
	return $s;


});

Route::get('/ads/{unixtime}-{duration}',function($unixtime = unixtime,$duration = duration){
	$ads = Ad::where('time_block','=',$unixtime)->orderBy('num','asc')->get(); 
	if(sizeof($ads) > 0) return Response::json($ads);
	else return Ad::generateAds($unixtime,$duration);
});


Route::group(array('prefix'=>'SAM'),function($id = id){
	//List Tables
	Route::get('/table',function(){
		return  DB::connection('samdb')->select('SHOW TABLES');
	});
	//Get Table Fields
	Route::get('/table/{table}',function($table_name){
		echo "<table>";
		echo "<tr><th>Field<th>Type<th>Null<th>Key<th>Extra</tr>";
		$table = DB::connection('samdb')->select('DESCRIBE '.$table_name);
		foreach($table as $column){
			echo "<tr>";
			foreach($column as $item){
				echo "<td>".$item."</td>";
			}
			echo "</tr>";
		}
		echo "</table>";
		foreach($table as $column){
			echo "'".$column->Field."', ";
		}
	});
	//Get Recent plays
	Route::get('recent/{offset}',function($offset = offset){
		$sam_plays = DB::connection('samdb')
		->table('songlist')
		->join('historylist','songlist.id','=','historylist.songID')
		->selectRaw('songlist.artist,songlist.title,songlist.album,songlist.composer,songlist.mood,historylist.date_played,historylist.duration')
		->where('songlist.songtype','=','S')
		->limit('50')
		->offset($offset)
		->orderBy('historylist.date_played','desc')
		->get();
		foreach($sam_plays as $play){
			foreach($play as $item){
				if(is_string($item)){
					$item = html_entity_decode($item ,ENT_QUOTES);
				}
			}
		}
		return $sam_plays;
	});
	//Get a time range of sam plays
	Route::get('range',function(){
		$from = Input::get()['from'];
		$to = Input::get()['to'];
		$sam_plays = Historylist::select('songlist.artist','songlist.title','songlist.album','songlist.composer','songlist.mood','historylist.date_played','historylist.duration')
			->join('songlist','historylist.songID','=','songlist.ID')
			->where('historylist.date_played','>=',$from)
			->where('historylist.date_played','<=',$to)
			->orderBy('historylist.date_played','asc')
			->get();
		foreach($sam_plays as $play){
			foreach($play as $item){
				if(is_string($item)){
					$item = html_entity_decode($item ,ENT_QUOTES);
				}
			}
		}
		return $sam_plays;
	});
	//Get tracks from the songlist
	Route::group(array('prefix'=>'songlist'),function(){
		Route::get('/',function(){
			return Songlist::select('id','title')->get();
		});

	});
	//Get tracks with a specific category (Accepts category ID # and category name)
	Route::group(array('prefix'=>'categorylist'),function(){
		Route::get('{cat_id}',function($cat_id = cat_id){
			if(is_numeric($cat_id)){
				$categorylist = Categorylist::where('categoryID','=',$cat_id)->get();
			}else{
				$categorylist = Categorylist::join('category','category.id','=','categorylist.categoryID')->where('category.name','LIKE',$cat_id)->get();
			}
			foreach($categorylist as $item){
				$song = Songlist::find($item->songID);
				if($song['title'] == "" || $song['title'] == null){
					$song['title'] = $song['artist'];
				} 
				$songs[] = $song;
			}
			return Response::json($songs);
		});
	});
});
Route::get('/nowplaying',function(){
	require_once($_SERVER['DOCUMENT_ROOT'].'/config.php');
	date_default_timezone_set('America/Los_Angeles');
	$result = array();
	if($using_sam){
		$last_track = DB::connection('samdb')->table('historylist')->selectRaw('artist,title,album,date_played,songtype,duration')
			->where('songtype','=','S')->orderBy('date_played','DESC')->limit('1')->get();
		$now = strtotime('now');
		if(count($last_track) > 0){
			$last_track = $last_track[0];
			if( (strtotime($last_track->date_played) + floor(($last_track->duration)/1000) ) >= $now ){
				$result['music'] = $last_track;
			}else{
				$result['music'] = null;
			}
		}else{
			$result['music'] = null;
		}
	}else{
		$result['music'] = null;
	}

	//Get Current week since Epoch
    $current_week = Date('W', strtotime('tomorrow',strtotime('now')));
    if ((int) $current_week % 2 == 0){
        $current_week_val = 1;
    } else {
        $current_week_val = 2;
    };

	//We use 0 = Sunday instead of 7
	$day_of_week = date('w');
	$yesterday = ($day_of_week - 1);
	$tomorrow = ($day_of_week + 1);
	$current_show = DB::select(DB::raw(
		"SELECT s.*,sh.name as name,NOW() as time from show_times AS s INNER JOIN shows as sh ON s.show_id = sh.id
			WHERE 
				CASE 
					WHEN s.start_day = s.end_day THEN s.start_day={$day_of_week} AND s.end_day={$day_of_week} AND s.start_time <= CURTIME() AND s.end_time > CURTIME()
					WHEN s.start_day != s.end_day AND CURTIME() <= '23:59:59' AND CURTIME() > '12:00:00 'THEN s.start_day={$day_of_week} AND s.end_day = {$tomorrow} AND s.start_time <= CURTIME() AND s.end_time >= '00:00:00'
					WHEN s.start_day != s.end_day AND CURTIME() < '12:00:00' AND CURTIME() >= '00:00:00' THEN s.start_day= {$yesterday} AND s.end_day = {$day_of_week} AND s.end_time > CURTIME()
				END
				AND sh.active = 1
				AND (s.alternating = 0 OR s.alternating = {$current_week_val});"));
	if( count($current_show) > 0 ){
		$current_show = $current_show[0];
		$result['showId'] = $current_show->show_id;
		
		$result['showName'] = $current_show->name;
		$result['showTime'] = "{$current_show->start_time} - {$current_show->end_time}";
		$result['lastUpdated'] = date('D, d M Y g:i:s a',strtotime($current_show->time));
	}else{
		$result['showName'] = "CiTR Ghost Mix";
		$result['showId'] = null;
		$result['showTime'] = "";
		$result['lastUpdated'] = date('D, d M Y g:i:s a',strtotime('now'));
	}
	return Response::json($result);
});

Route::get('/socan',function(){
	$now = strtotime('now');
	$socan = Socan::all();
	foreach($socan as $period){
		if( strtotime($period['socanStart']) <= $now && strtotime($period['socanEnd']) >= $now){
			return Response::json(true);
		}
	}
	return Response::json(false);
});
Route::get('/socan/{time}',function($unixtime = time){
	$now = $unixtime;
	$socan = Socan::all();
	foreach($socan as $period){
		if( strtotime($period['socanStart']) <= $now && strtotime($period['socanEnd']) >= $now){
			return Response::json(true);
		}
	}
	return Response::json(false);
});

// Table Helper Routes 
Route::get('/table',function(){
	return  DB::select('SHOW TABLES');
});

Route::get('/table/{table}',function($table_name =table){
	echo "<table>";
	echo "<tr><th>Field<th>Type<th>Null<th>Key<th>Extra</tr>";
	$table = DB::select('DESCRIBE '.$table_name);
	foreach($table as $column){
		echo "<tr>";
		foreach($column as $item){
			echo "<td>".$item."</td>";
		}
		echo "</tr>";
	}
	echo "</table>";
	foreach($table as $column){
		echo "'".$column->Field."', ";
	}
});
Route::post('/error',function(){
	date_default_timezone_set('America/Los_Angeles');
	$from = $_SERVER['HTTP_REFERER'];
	$error = Input::get()['error'];
	$date = date('l F jS g:i a',strtotime('now'));
	$out = '<hr>';
	$out .= '<h3>'.$date.'</h3>';
	$out .= '<h4>'.$from.'</h4>';
	$out .= '<p>'.$error.'</p>';
	$result = file_put_contents($_SERVER['DOCUMENT_ROOT'].'/log.html',$out.PHP_EOL,FILE_APPEND);
	return $result;
});
Route::group(array('prefix'=>'friends'),function(){
	Route::get('/',function(){
		return Friends::all();
	});
	Route::put('/',function(){
		$friend = new Friends;
		$friend->save();
		return $friend;
	});
	Route::post('/',function(){
		$friends = Input::get()['friends'];
		foreach($friends as $friend){
			$f = Friends::find($friend['id']);
			unset($friend['id']);
			$f->update((array) $friend);
		}
		Friends::write_static();
		return Response::json($friends);
	});
	Route::delete('/{id}',function($id = id){
		return Response::json(Friends::find($id)->delete());
	});
	
	Route::get('/static',function(){
		return Friends::write_static();
	});

});

Route::group(array('prefix'=>'specialbroadcasts'),function(){
	Route::get('/',function(){
		return SpecialBroadcasts::orderBy('id','desc')->get();
	});
	Route::put('/',function(){
		$specialbroadcast = new SpecialBroadcasts;
		$specialbroadcast->save();
		return $specialbroadcast;
	});
	Route::post('/',function(){
		$specialbroadcasts = Input::get()['specialbroadcasts'];
		foreach($specialbroadcasts as $specialbroadcast){
			$s = SpecialBroadcasts::find($specialbroadcast['id']);
			unset($specialbroadcast['id']);
			$s->update((array) $specialbroadcast);
		}
		return Response::json($specialbroadcasts);
	});
	Route::delete('/{id}',function($id =id){
		return Response::json(SpecialBroadcasts::find($id)->delete());
	});
});