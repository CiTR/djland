<?php
use App\User as User;
use App\Member as Member;
use App\Permission as Permission;
use App\Show as Show;
use App\Host as Host;
use App\Playsheet as Playsheet;
use App\Playitem as Playitem;
use App\Song as Song;

Route::get('/', function () {
    //return view('welcome');
    return "welcome to laravel";
});
Route::group(['middleware' => 'auth'], function(){
	/* Member Routes */
	Route::get('/member',function(){
		return  DB::table('membership')->select('id','firstname','lastname')->get();
	});

	Route::get('/member/{id}',function($id=id){
		return DB::table('membership')
		->select('*')
		->where('id','=',$id)
		->get();
	});
	Route::get('member/{id}/shows',function($member_id=id){
		$permissions = Member::find($member_id)->user->permission;
		if($permissions->staff ==1 || $permissions->administrator==1){
			$all_shows = Show::orderBy('name','asc')->get();
			foreach($all_shows as $show){
				$shows->shows[$show->id] = ['id'=>$show->id,'name'=>$show->name,'host'=>Show::find($show->id)->host->name];
			}
		}else{
			$member_shows =  Member::find($member_id)->shows;
			foreach($member_shows as $show){
				$shows->shows[$show->id] = ['id'=>$show->id,'name'=>$show->name,'host'=>Show::find($show->id)->host->name];
			}
		}
		return  Response::json($shows);
	});
});

/* Show Routes */
Route::get('/show',function(){
	//return DB::table('shows')->select('id','name'->get();
	return Show::all('id','name');
});
Route::get('/show/{id}',function($show_id = id){
	//return DB::table('shows')->select('id','name'->get();
	return Show::find($show_id);
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
		$playitems = Playsheet::find($id)->playitems;
		foreach($playitems as $p){
			$p->song = Playitem::find($p->id)->song;
		}
		$playsheet -> playitems = $playitems;
		$playsheet -> show = Playsheet::find($id)->show;
		$playsheet -> host = Host::find($playsheet->show->host_id);		
	}
	return Response::json($playsheet);
});


// Table Helper Routes 
Route::get('/table',function(){
	return  DB::select('SHOW TABLES');
});

Route::get('/table/{table}',function($table_name =table){
	echo "<pre>";
	print_r(DB::select('DESCRIBE '.$table_name));
});


