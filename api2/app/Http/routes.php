<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    //return view('welcome');
    return "welcome to laravel";
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
	return DB::table('playsheets')->select('id')->orderBy('playsheets.id','desc')->get();
});
Route::get('/playsheet/list',function(){
	return DB::table('playsheets')->join('hosts','hosts.id','=','playsheets.host_id')->select('playsheets.id','hosts.name','playsheets.start_time')->limit('100')->orderBy('playsheets.id','desc')->get();
});
Route::get('/playsheet/list/{limit}',function($limit = limit){
	return DB::table('playsheets')->join('hosts','hosts.id','=','playsheets.host_id')->select('playsheets.id','hosts.name','playsheets.start_time')->limit($limit)->orderBy('playsheets.id','desc')->get();
});
Route::get('/playsheet/{id}',function($id = id){
	return DB::table('playsheets')->select('*')->where('id','=',$id)->get();
});
Route::get('/table',function(){
	return  DB::select('SHOW TABLES');
});
Route::get('/table/{table}',function($table_name =table){
	return  DB::select('DESCRIBE '.$table_name);
});
Route::get('/hosts',function(){
	return  DB::table('hosts')->select('id','name')->get();
});
