<?php
use App\Playsheet as Playsheet;
use App\Show as Show;
use App\Host as Host;
use App\Playitem as Playitem;
Route::get('/', function () {
    //return view('welcome');
    return "welcome to laravel";
});
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
/* Show Routes */
Route::get('/show',function(){
	//return DB::table('shows')->select('id','name'->get();
	return Show::all('id','name');
});
/* Playsheet Routes */
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
	//return DB::table('playsheets')->select('id')->orderBy('playsheets.id','desc')->get();
});
Route::get('/playsheet/list',function(){
	return DB::table('playsheets')
	->join('hosts','hosts.id','=','playsheets.host_id')
	->select('playsheets.id','hosts.name','playsheets.start_time'
	->limit('100')
	->orderBy('playsheets.id','desc')
	->get();
});
Route::get('/playsheet/list/{limit}',function($limit = limit){
	$playsheet = new stdClass();
	$playsheet = Playsheet::orderBy('id','desc')->limit($limit)->select('*')->get();
	//$playsheet->host  = Playsheet::orderBy('id','desc')->limit($limit)->hosts->name;
	//$playsheet->show = Playsheet::orderBy('id','desc')->limit($limit)->shows->name;
	//$playsheet-> Playsheet::all()->hosts;
	//$playsheet = Playsheet::all()->shows;
	return $playsheet;

	//return DB::table('playsheets')->join('hosts','hosts.id','=','playsheets.host_id')->select('playsheets.id','hosts.name','playsheets.start_time')->limit($limit)->orderBy('playsheets.id','desc')->get();
});
Route::get('/playsheet/{id}',function($id = id){
	//return DB::table('playsheets')->select('*')->where('id','=',$id)->get();
	$playsheet = new stdClass();
	$playsheet = Playsheet::find($id);
	$playsheet->playitems = Playsheet::find($id)->playitems;
	return Response::json($playsheet);
});


/* Table Helper Routes */
Route::get('/table',function(){
	return  DB::select('SHOW TABLES');
});
Route::get('/table/{table}',function($table_name =table){
	return  DB::select('DESCRIBE '.$table_name);
});

