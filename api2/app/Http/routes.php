<?php
//DJLAND Classes
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
use App\Friends as Friends;

//SAM CLASSES
use App\Songlist as Songlist;
use App\Categorylist as Categorylist;
use App\Historylist as Historylist;



Route::get('/', function () {
    //return view('welcome');
    return "Welcome to DJLand API 2.0";
});
//Use to ensure an id is numberic
Route::filter('numeric',function($route,$request){
	if(!is_numeric($route->parameter('id'))){
		http_response_code(400);
		return "Non Numeric ID";
	}
});
//Area requires an active session "Private" api
Route::group(['middleware' => 'auth'], function(){
	//Member Routes 
	Route::group(array('prefix'=>'member'), function(){
		//Create a new Member
		Route::put('/',function(){
			return Response::json(Member::create());
		});
		//Get a nice list
		Route::get('list/{limit}/{offset}',function(){
			return Response::json(Member::select('id','firstname','lastname')->limit($limit)->offset($offset)->get());
		});
		//Searching by member ID
		Route::group(array('prefix'=>'{id}','before'=>'numeric'), function($id = id){
			//Get a member
			Route::get('/',function($id){
				return Member::find($id);
			});
			//Update a member
			Route::post('/',function($id){
				return Response::json(Member::find($id)->update((array) json_decode(Input::get()['member']) ));
			});
			//Delete a member
			Route::delete('/',function($id){
				return Response::json(Member::find($id)->delete());
			});
			Route::group(array('prefix'=>'user'),function($id){
				//Get associated user
				Route::get('/',function($id){
					return Response::json(Member::find($id)->user()->select('id','login_fails','username')->get());
				});
				//Create associated User
				Route::put('/',function($id){
					return Response::json(User::create(array('member_id'=>$id)));
				});
				//Update associated user
				Route::post('/',function($id){
					return Response::json(Member::find($id)->user->update(Input::get()['user']));
				});
				//Delete not required, as database deletes via foreign key

				//Update user password
				Route::post('password',function($id){
					$m = Member::find($id);
					$user = $m->user;
					$user->password = password_hash(Input::get()['password'],PASSWORD_DEFAULT);
					return $user->save() ? "true":"false";
				});
			});
			Route::group(array('prefix'=>'permission'),function($id){
				//Get associated permissions
				Route::get('/',function($id){
					return Member::find($id)->user->permission;
				});
				//Create associated permissions
				Route::put('/',function($id){
					return Response::json(permission::create(array('user_id'=>Member::find($id)->user()->get('id'))));
				});
				//Update associated permissions
				Route::post('/',function($id){
					return Response::json(Member::find($id)->user->permission->update(Input::get()['user']));
				});
				//Delete not required, as database deletes via foreign key
			});
			Route::group(array('prefix'=>'year'),function($id){
				//Get membership years
				Route::get('/',function($id){
					return Response::json(Member::find($id)->years);
				});
				//Add a new membership year
				Route::put('/',function($id){
					return Response::json(Member::find($id)->years->create(array('member_id'=>$id)));
				});
				//Update membership year
				Route::post('{year_id}',function($id,$year_id=year_id){
					return Response::json(MembershipYear::find($year_id)->update((array) Input::get()['year']));
				});
				//Delete not required, as database deletes via foreign key
			});
			Route::get('playsheet/{offset}',function($id,$offset = offset){
				if(Member::find($id)->is_admin()){
					return Response::json(Playsheet::join('shows','shows.id','=','playsheets.show_id')->select('shows.name','playsheets.id','shows.host','playsheets.start_time','playsheets.edit_date','playsheets.status')->limit(100)->offset($offset)->orderBy('playsheets.id','desc')->get());
				}else{
					return Response::json(Member::join('member_show','membership.id','=','member_show.member_id')->join('shows','member_show.show_id','=','shows.id')->join('playsheets','member_show.show_id','=','playsheets.show_id')->select('shows.name','playsheets.id','shows.host','playsheets.start_time','playsheets.edit_date','playsheets.status')->where('membership.id','=',$id)->limit(100)->offset($offset)->get());
				}
			});
			//Update the comments for a member
			Route::post('/comments',function($id){
				$member = Member::find($id);
				$member -> comments = json_decode(Input::get()['comments']);
				return Response:: json($member -> save());
			});
			//Check to see if a member has been trained
			Route::get('trained',function($id){
				$member = Member::find($id);
				return $member->station_tour =='1' && $member->technical_training =='1' &&  $member->programming_training =='1' &&  $member->production_training == '1';
			});
			//Get member shows
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
			//Get member shows that are active
			Route::get('active_shows', function($member_id = id){
				$permissions = Member::find($member_id)->user->permission;
				$shows = array();
				if($permissions->staff ==1 || $permissions->administrator==1){
					$all_shows = Show::where('active','=','1')->orderBy('name','asc')->get();
					foreach($all_shows as $show){
						$shows[] = $show;
					}
				}else{
					$member_shows = Member::find($member_id)->shows;
					foreach($member_shows as $show){
						if($show->active == 1){
							$shows[] = $show;	
						}
					}
				}
				return  Response::json($shows);
			});
		});
	});
});

/* Show Routes */
Route::group(array('prefix'=>'show'),function(){
	//Create show
	Route::put('/',function(){
		return Response::json(Show::create());
	});
	//Return all shows
	Route::get('/',function(){
		return Show::all('id','name');
	});
	//Return active shows
	Route::get('/active',function(){
		return Show::select('id','name')->where('active','=','1')->get();
	});
	Route::group(array('prefix'=>'{id}'),function($id=id){
		//Get show + social entries
		Route::get('/',function($id){
			$show = Show::find($id);
			$show->social = Show::find($id)->social;
			return Response::json($show);
		});
		//Delete show
		Route::delete('/',function($id){
			return Response::json(Show::find($id)->delete());
		});
		//Update Show
		Route::post('/',function($id){
			return Response::json(Show::find($id)->update((array) Input::get()['show']));
		});
		Route::group(array('prefix'=>'playsheet'),function($id){
			//Create Playsheet
			Route::put('/',function($id){
				return Response::json(Show::find($id)->playsheets()->create(['show_id'=>$id]));
			});
		});
		Route::group(array('prefix'=>'social'),function($id){
			//Return all social
			Route::get('/',function($id){
				return Response::json(Show::find($id)->social);
			});
			//Create new social entry for a show
			Route::put('/',function($id){
				return Response::json(Show::find($id)->social()->create(array('show_id'=>$id)));
			});
			//Update a social entry
			Route::post('{social_id}',function($id,$social_id = social_id){
				return Response::json(Social::find($social_id)->update(Input::get()['social']));
			});
			//Delete social entry for a show
			Route::delete('{social_id}',function($id,$social_id = social_id){
				return Response::json(Social::find($social_id)->delete());
			});
		});
		Route::group(array('prefix'=>'social'),function($id){
			//Get owners for a show
			Route::get('/',function($id){
				return Response::json(Show::find($id)->members()->select('membership.id','firstname','lastname')->get());
			});
			//Add a show owner
			Route::put('{member_id}',function($id,$member_id = member_id){
				return Response::json(Show::find($id)->members()->attach($member_id));
			});
			//Remove a show owner
			Route::delete('{member_id}',function($id,$member_id = member_id){
				return Response::json(Show::find($id)->members()->detach($member_id));
			});
		});
		Route::group(array('prefix'=>'showtime'),function($id){
			//Get showtimes
			Route::get('/',function($id){
				return Response::json(Show::find($id)->showtimes()->get());
			});
			//Add a showtime
			Route::put('/',function($id){
				return Response::json(Show::find($id)->showtimes()->create(array('show_id'=>$id)));
			});
			//Remove a show owner
			Route::delete('{member_id}',function($id,$member_id = member_id){
				return Response::json(Show::find($id)->members()->detach($member_id));
			});
		});

		//Get all playsheets
		Route::get('playsheets',function($id){
			return Show::find($id)->playsheets;
		});
		//Get next show time from inputted unix time
		Route::get('nextshow',function($id){

			return Response::json(Show::find($id)->nextShowTime(date(strtotime('now'))));
		});
		//Re-write the xml file
		Route::get('update_xml',function($id){
			return Show::find($id)->make_show_xml();
		});
		//Get 50 playsheet/podcast pairs. Offset by offset
		Route::get('episodes/{offset}',function($id,$offset = offset){
			$podcasts = Show::find($id)->podcasts()->orderBy('id','desc')->limit(50)->offset($offset)->get();
			$episodes = array();
			foreach($podcasts as $podcast){
				$playsheet = $podcast->playsheet;
				if($playsheet != null){
					$playsheet->socan = $playsheet->is_socan();
					if($podcast -> duration == 0){
						$podcast -> duration_from_playsheet();
					}
					unset($podcast->playsheet);
					$episodes[] = ['playsheet'=>$playsheet,'podcast'=>$podcast];
				}else{
					$episode = ['podcast'=>$podcast];
					$episodes[] = $episode;
				}
			}
			return Response::json($episodes);
		});
	});
});

Route::group(array('prefix'=>'playsheet'),function(){
	//Get all playsheets
	Route::get('/',function(){
		return $playsheets = Playsheet::orderBy('id','desc')->select('id','edit_date')->get();
	});
	//Get playsheets with limit & offset
	Route::get('limit/{limit}/{offset}',function($limit = limit,$offset = offset){
		return $playsheets = Playsheet::orderBy('id','desc')->select('id','edit_date')->limit($limit)->offset($offset)->get();
	});
	//Get playsheets with limit & offset. Select ordering
	Route::get('limit/{limit}/{offset}/{orderBy}',function($limit = limit,$offset = offset,$orderBy = orderBy){
		return $playsheets = Playsheet::orderBy($orderBy,'desc')->select('id','edit_date')->limit($limit)->offset($offset)->get();
	});
	//Create method is via show

	Route::group(array('prefix'=>'where'),function(){
		//Get playsheet with a specific unix time
		Route::get('unixtime/{unixtime}',function($unixtime = unixtime){
			return Response::json(Playsheet::where('unix_time','=',$unixtime)->orderBy('id','desc')->get());
		});
		//Get playsheets in a range
		Route::get('range/{startunix}/{endunix}',function($startunix = startunix,$endunix = endunix){
			return Response::json(Playsheet::where(DB::raw('UNIX_TIMESTAMP(start_time)'),'>=',$startunix)->where(DB::raw('UNIX_TIMESTAMP(end_time)'),'<=',$endunix)->get());
		});
	});
	Route::group(array('prefix'=>'{id}'),function($id = id){
		//Get Existing Playsheet
		Route::get('/',function($id){
			return Response::json(['playsheet'=>Playsheet::find($id),'playitems'=>Playsheet::find($id)->playitems()->orderBy('position','asc')->get(),'podcast'=>Playsheet::find($id)->podcast,'promotions'=>Playsheet::find($id)->ads]);
		});
		//Save Existing Playsheet
		Route::post('/',function($id){
			return Response::json(Playsheet::find($id)->update((array) Input::get()['playsheet']));
		});
		//Delete Existing Playsheet
		Route::delete('/',function($id){
			return Response::json(Playsheet::find($id)->delete());
		});
		Route::group(array('prefix'=>'podcast'),function($id){
			//Create a podcast
			Route::put('/',function($id){
				return Response::json(Podcast::create(['playsheet_id'=>$id,'show_id'=>Playsheet::find($id)->show()->first()['id'] ]));
			});
			Route::post('/',function($id){
				return Response::json(Playsheet::find($id)->podcast->update(Input::get()['podcast']));
			});
			//Make initial audio for a podcast
			Route::post('audio',function($id){
				return Response::json(Playsheet::find($id)->podcast->make_podcast());
			});
			//Overwrite the podcast audio
			Route::post('overwrite',function($id){
				return Response::json(Playsheet::find($id)->podcast->overwrite_podcast());
			});
		});
		Route::group(array('prefix'=>'ad'),function($id){
			//Get Ads
			Route::get('/',function($id){
				return Response::json(Playsheet::find($id)->ads);
			});
			//Set ad to played
			Route::post('/{$ad_id}/played',function($id,$ad_id = ad_id){
				return Response::json(Ad::find($ad_id)->update(['playsheet_id'=>$id,'played'=>'1']));
			});
			//Set ad to unplayed
			Route::post('/{$ad_id}/unplayed',function($id,$ad_id = ad_id){
				return Response::json(Ad::find($ad_id)->update(['playsheet_id'=>$id,'played'=>'0']));
			});
		});
		Route::group(array('prefix'=>'playitem'),function($id){
			//Get playitems
			Route::get('/',function($id){
				return Response::json(Playsheet::find($id)->playitems()->orderBy('position','asc')->get());
			});
			//Add a playitem
			Route::put('/',function($id){
				$show = Playsheet::find($id)->show;
				return Response::json(Playsheet::find($id)->playitems()->create(['playsheet_id'=>$id,'show_id'=>$show->id,'lang'=>$show['lang'],'crtc_category'=>$show['crtc']]));
			});
			//Save Existing Playsheet
			Route::post('/',function($id){
				return Response::json(Playsheet::find($id)->update((array) Input::get()['playsheet']));
			});
		});
	});
	Route::group(array('prefix'=>'list'),function(){
		//Nice to view list, limited to 100
		Route::get('/',function(){
			return Response::json(Playsheet::join('shows','shows.id','=','playsheets.show_id')->select('playsheets.id','shows.host','playsheets.start_time')->limit('100')->orderBy('playsheets.id','desc')->get());
		});
		//Nice to view list with limit, and offset
		Route::get('{limit}/{offset}',function($limit = limit,$offset = offset){
			return Response::json(Playsheet::join('shows','shows.id','=','playsheets.show_id')->select('playsheets.id','shows.host','playsheets.start_time')->limit($limit)->offset($offset)->orderBy('playsheets.id','desc')->get());
		});
	});
});


Route::group(array('prefix'=>'friend'),function(){
	//Get friends
	Route::get('/',function(){
		return Friends::all();
	});
	//Create a new friend
	Route::put('/',function(){
		return Response::json(Friend::create());
	});
	//Update friend
	Route::post('{id}',function($id = id){
		return Response::json(Friends::find($id)->update((array) Input::get()['friend']));
	});
	//Delete friend
	Route::delete('{id}',function($id = id){
		return Response::json(Friends::find($id)->delete());
	});
	//Write Static friends page
	Route::get('/static',function(){
		return Friends::write_static();
	});
});


Route::group(array('prefix'=>'specialbroadcasts'),function(){
	//Get special broadcasts
	Route::get('/',function(){
		return SpecialBroadcasts::orderBy('id','desc')->get();
	});
	//Create new special broadcast
	Route::put('/',function(){
		return Response::json(SpecialBroadcasts::create());
	});
	//Update special broadcast
	Route::post('{id}',function($id = id){
		return Response::json(SpecialBroadcasts::find($id)->update((array) Input::get()['specialbroadcast']));
	});
	//Delete Special broadcast
	Route::delete('{id}',function($id =id){
		return Response::json(SpecialBroadcasts::find($id)->delete());
	});
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

Route::get('/promotions/{unixtime}-{duration}',function($unixtime = unixtime,$duration = duration){
	$ads = Ad::where('time_block','=',$unixtime)->orderBy('num','asc')->get(); 
	if(sizeof($ads) > 0) return Response::json($ads);
	else return Ad::generateAds($unixtime,$duration);
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
			echo (strtotime($last_track->date_played) + floor(($last_track->duration)/1000) );
				echo "<br/>".$now;
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

	$specialbroadcast = SpecialBroadcasts::whereRaw('start <= '.$now.' and end >= '.$now)->get();
	if($specialbroadcast->first()){
		//special broadcast exists
		$specialbroadcast = $specialbroadcast->first();
		$result['showId'] = $specialbroadcast->show_id;
		$result['showName'] = $specialbroadcast->name;
		$start_time = date('H:i:s',$specialbroadcast->start);
		$end_time = date('H:i:s',$specialbroadcast->end);
		$result['showTime'] = "{$start_time} - {$end_time}";
		$result['lastUpdated'] = date('D, d M Y g:i:s a',strtotime('now'));
	}else{
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
