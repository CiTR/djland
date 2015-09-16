<?php

use App\User as User;
use App\Member as Member;
use App\MembershipYear as MembershipYear;
use App\Permission as Permission;
use App\Show as Show;
use App\Channel as Channel;
use App\Showtime as Showtime;
use App\Host as Host;
use App\Social as Social;
use App\Playsheet as Playsheet;
use App\Playitem as Playitem;
use App\Podcast as Podcast;
use App\Song as Song;
use App\Ad as Ad;

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
				$user->save();
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
						$shows->shows[] = ['id'=>$show->id,'show'=>$show,'name'=>$show->name,'host'=>Show::find($show->id)->host,'channel'=>$show->channel];
					}
				}else{
					$member_shows = Member::find($member_id)->shows;
					foreach($member_shows as $show){
						$shows->shows[] = ['id'=>$show->id,'show'=>$show,'name'=>$show->name,'host'=>Show::find($show->id)->host,'channel'=>$show->channel];
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

		//Attach new owners
		foreach($owners as $owner){
			Show::find($show->id)->members()->attach($owner['id']);
		}
		//Create new social entries, this table is really dumb.
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
	
	//Searching by Show ID
	Route::group(array('prefix'=>'{id}'),function($id){
		Route::get('/',function($id = id){
			$show = Show::find($id);
			$show->host = Show::find($id)->host->name;
			$show->social = Show::find($id)->social;
			$show->channel = $show->channel;
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
		Route::get('episodes',function($id){
			$podcasts = Show::find($id)->channel->podcasts()->orderBy('id','desc')->get();

			foreach($podcasts as $podcast){
				$playsheet = $podcast->playsheet;
				if($playsheet != null){
					if($podcast -> duration == 0){
						$podcast -> duration_from_playsheet();
					}
					unset($podcast->playsheet);
					$episode = ['playsheet'=>$playsheet,'podcast'=>$podcast];
				}
				$episodes[] = $episode;
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

	});
	
});

Route::get('/social/{id}',function($show_id = id){
	return Social::where('show_id','=',$show_id)->get();
});


/* Playsheet Routes */
Route::group(array('prefix'=>'playsheet'),function(){
	Route::get('/',function(){
		return Playsheet::orderBy('id','desc')->select('id')->get();
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
		$response = new stdClass();
		$response->id = $ps->id;
		$response->podcast_id = $podcast->id;
		return Response::json($response);
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
				$playsheet -> channel = $show->channel;
				$playsheet -> host = Host::find($playsheet->show->host_id);
				$playsheet -> podcast = Playsheet::find($id)->podcast;
				
				$ads = Playsheet::find($id)->ads;
				foreach($ads as $key => $value){
					//Get Ad Names From SAM
					if($using_sam && is_numeric($value['name'])){
						$ad_info =  DB::connection('samdb')->table('songlist')->select('*')->where('id','=',$value['name'])->get()[0];
						$ads[$key]['name'] = $ad_info->title;
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

			$playitems = Input::get()['playitems'];
			foreach($ps->playitems as $delete){
				$delete->delete();
			}
			foreach($playitems as $playitem){
				Playitem::create($playitem);
			}		
		});

		Route::post('episode',function($id){
			$playsheet = Playsheet::find($id);
			$podcast = $playsheet->podcast;
			return Response::json( $playsheet -> update((array) Input::get()['playsheet']) && $podcast -> update((array) Input::get()['podcast']) ? "true" : "false");
		});
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
		foreach(Playsheet::orderBy('id','desc')->whereIn('show_id',$show_ids)->limit('500')->get() as $ps){
			$playsheet = new stdClass();
			$playsheet = $ps;
			$playsheet -> show_info = Show::find($ps->show_id);
			$playsheet -> host_info = Show::find($ps->show_id)->host;
			$playsheets[] = $playsheet;
		}
		return Response::json($playsheets);	
	});
	Route::get('host/{id}',function($id = id){
		return  DB::table('playsheets')
		->join('hosts','hosts.id','=','playsheets.host_id')
		->join('shows','shows.id','=','playsheets.show_id')
		->select('hosts.name AS host_name','playsheets.id AS id','playsheets.start_time AS start_time','shows.name AS show_name')
		->where('hosts.id','=',$id)
		->get();
	});

	Route::get('list',function(){
		return DB::table('playsheets')
		->join('hosts','hosts.id','=','playsheets.host_id')
		->select('playsheets.id','hosts.name','playsheets.start_time')
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
				$ps -> hosts = Show::find($playsheet->show_id)->hosts;
				$list[] = $ps;
			}
		}
		return Response::json($list);
	});
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
	Route::get('/channels/write_xml',function(){
		$channels = Channel::all();
		foreach($channels as $channel){
			$result = $channel->make_xml();
			$results[] = $result;
		}
		//return json_encode($results);
	});


//SAM
Route::get('/ads/{unixtime}',function($unixtime = unixtime){
	require_once($_SERVER['DOCUMENT_ROOT'].'/config.php');
	$ads = Ad::where('time_block','=',$unixtime)->get(); 
	foreach($ads as $key => $value){
		if($using_sam && is_numeric($value['name'])){
			$ad_info =  DB::connection('samdb')->table('songlist')->select('title')->where('id','=',$value['name'])->get()[0];
			$ads[$key]['name'] = $ad_info->title;
		}else{
			$ads[$key]['name'] = html_entity_decode($ads[$key]['name'],ENT_QUOTES);
		}
	}
	return Response::json($ads);
});
Route::get('/SAM/recent/{offset}',function($offset = offset){
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
Route::get('/SAM/range',function(){
	$from = Input::get()['from'];
	$to = Input::get()['to'];
	$sam_plays = DB::connection('samdb')
	->table('songlist')
	->join('historylist','songlist.id','=','historylist.songID')
	->selectRaw('songlist.artist,songlist.title,songlist.album,songlist.composer,songlist.mood,historylist.date_played,historylist.duration')
	->where('songlist.songtype','=','S')
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


