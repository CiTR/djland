<?php
use App\User as User;
use App\Member as Member;
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
	Route::get('/member',function(){
		return  DB::table('membership')->select('id','firstname','lastname')->get();
	});
	Route::get('/member/list',function(){
		$full_list = Member::select('id','firstname','lastname')->get();
		foreach ($full_list as $m) {
			$members[] = ['id'=>$m->id,'firstname'=>$m->firstname,'lastname'=>$m->lastname];
		}
		return $members;
	});
	Route::get('/member/{id}',function($id=id){
		return DB::table('membership')
		->select('*')
		->where('id','=',$id)
		->get();
	});
	Route::post('/member/{id}',function($id = id){
		$member = new Member(Input::get());
		$m = Member::find($id);
		return $m->update($member);
	});
	
	Route::get('member/{id}/permission',function($member_id = id){
		
		$permission_levels = Member::find($member_id)->user->permission;
		unset($permission_levels->user_id);
		$permission = new stdClass();
		$permission->permissions = $permission_levels;
		return $permission_levels;
	});
	Route::get('member/{id}/shows', function($member_id = id){
		$permissions = Member::find($member_id)->user->permission;
		if($permissions->staff ==1 || $permissions->administrator==1){
			$all_shows = Show::orderBy('name','asc')->get();
			foreach($all_shows as $show){
				$shows->shows[] = ['id'=>$show->id,'show'=>$show,'host'=>Show::find($show->id)->host['name'],'channel'=>$show->channel];
			}
		}else{
			$member_shows = Member::find($member_id)->shows;
			foreach($member_shows as $show){
				$shows->shows[] = ['id'=>$show->id,'name'=>$show->name,'host'=>Show::find($show->id)->host['name'],'channel'=>$show->channel];
			}
		}
		return  Response::json($shows);
	});
});




/* Show Routes */
	Route::get('/show',function(){
		return Show::all('id','name');
	});
	Route::get('/show/{id}',function($show_id = id){
		$show = Show::find($show_id);
		$show->host = Show::find($show_id)->host->name;
		$show->social = Show::find($show_id)->social;
		$show->channel = $show->channel;
		return Response::json($show);
	});
	Route::post('/show/{id}',function($show_id = id){
		$show = Input::get()['show'];
		$social = Input::get()['social'];
		$owners = Input::get()['owners'];
		$showtimes = Input::get()['showtimes'];
		print_r($showtimes);
		$s = Show::find($show_id);
		$s->update($show);

		//Detach current owners
		foreach(Show::find($show_id)->members as $current_owner){
			$s->members()->detach($current_owner->id);
		}
		//Attach new owners
		foreach($owners as $owner){
			Show::find($show_id)->members()->attach($owner['id']);
		}

		//Delete all social entries
		$delete = Social::find($show_id);
		if($delete !=null) $delete->delete();
		//Create new social entries, this table is really dumb.
		foreach($social as $item){
			Social::create($item);
		}

		//Delete all showtime entries
		$delete = Showtime::find($show_id);
		if($delete != null) $delete->delete();
		//Recreate show times
		foreach($showtimes as $showtime){
			Showtime::create($showtime);
		}
	});
	Route::get('show/{id}/owners',function($show_id = id){
		$members = Show::find($show_id)->members;
		$owners = new stdClass();
		$owners->owners = [];
		foreach ($members as $member) {
			$owners->owners[] = ['id'=>$member->id,'firstname'=>$member->firstname,'lastname'=>$member->lastname];
		}
		return Response::json( $owners );
	});
	Route::get('/show/{id}/social',function($show_id = id){
		return Show::find($show_id)->social;
	});
	Route::get('/show/{id}/times',function($show_id = id){
		//return Showtimes::where('show_id','=',$show_id)->get();
		return Show::find($show_id)->showtimes;
	});
	Route::get('/social/{id}',function($show_id = id){
		return Social::where('show_id','=',$show_id)->get();
	});



/* Playsheet Routes */
	Route::get('/playsheet/member/{member_id}',function($member_id = member_id){
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
	Route::get('/playsheet/host/{id}',function($id = id){
		return  DB::table('playsheets')
		->join('hosts','hosts.id','=','playsheets.host_id')
		->join('shows','shows.id','=','playsheets.show_id')
		->select('hosts.name AS host_name','playsheets.id AS id','playsheets.start_time AS start_time','shows.name AS show_name')
		->where('hosts.id','=',$id)
		->get();
	});
	Route::get('/playsheet',function(){
		return Playsheet::orderBy('id','desc')->select('id')->get();
	});
	Route::get('/playsheet/list',function(){
		return DB::table('playsheets')
		->join('hosts','hosts.id','=','playsheets.host_id')
		->select('playsheets.id','hosts.name','playsheets.start_time')
		->limit('100')
		->orderBy('playsheets.id','desc')
		->get();
	});
	Route::get('/playsheet/list/{limit}',function($limit = limit){
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
	Route::get('/playsheet/{id}',function($id = id){
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
				$ad_info =  DB::connection('samdb')->table('songlist')->select('*')->where($value['name'],'=','id')->get();
				$ads[$key]['name'] = $ad_info['artist'].' '.$ad_info['title'];
			}
			$playsheet -> ads = $ads;
		}
		return Response::json($playsheet);
	});

	Route::post('/playsheet/{id}',function($playsheet_id = id){
		$ps = Playsheet::find($playsheet_id);
		$ps->update(Input::get()['playsheet']);

		$playitems = Input::get()['playitems'];
		foreach($ps->playitems as $delete){
			$delete->delete();
		}
		foreach($playitems as $playitem){
			Playitem::create($playitem);
		}		
	});
	Route::post('/playsheet',function(){
		$ps = Playsheet::create(Input::get()['playsheet']);
		$podcast_in = Input::get()['podcast'];
		$podcast_in ['playsheet_id'] = $ps->id;
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
	Route::get('/ads/{unixtime}',function($unixtime = unixtime){
		$ads = Ad::where('time_block','=',strtotime($unixtime))->get(); 
		foreach($ads as $key => $value){
			$ad_info =  DB::connection('samdb')->table('songlist')->select('*')->where($value['name'],'=','id')->get();
			$ads[$key]['name'] = $ad_info['artist'].' '.$ad_info['title'];
		}
		return $ads;
	});
	Route::post('/podcast/{id}',function($id = id){
		$podcast = Podcast::find($id);
		$podcast->update(Input::get()['podcast']);
	});
	Route::post('/podcast/{id}/audio',function($id = id){
		$podcast = Podcast::find($id);
		$result = $podcast->make_podcast();
		//return $result;
	});
	Route::get('/channels/write_xml',function(){
		$channels = Channel::all();
		foreach($channels as $channel){
			if($channel->make_xml()){
				echo "Successfully wrote {$channel->name}";
			}
		}
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


