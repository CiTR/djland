<?php
//All CiTR wordpress go in here
//This is in a seperate file to avoid having to worry about keeping endpoints for DJLand also compatible with CiTR.ca
//In addition it also ensures that should others want to connect DJLand to their site, they can just delete this file or drop another in with suitable routes

use App\Show as Show;
use App\Showtime as Showtime;

Route::group(array('prefix'=>'DJLandConnector'),function(){
	Route::group(array('prefix'=>'show'),function(){
		//just one show with given ID
		Route::get('/{id}',function($id=id){
			if(!$show = Show::find($id)) return null;

			$show->id = Show::find($id)->id;
			$show->edit_date = Show::find($id)->edit_date;
			return Response::json($show);
		});
	});
	Route::group(array('prefix'=>'shows'),function(){
		//Return list of shows in LIMIT/OFFSET FORMAT
		Route::get('/{LIMIT}/{OFFSET}',function($limit=LIMIT,$offset=OFFSET){
			return Show::select('id','edit_date')->offset($offset)->limit($limit)->get();
		});
	});
	Route::group(array('prefix'=>'playlist'),function(){
		Route::get('/{id}',function($id=id){
			return Show::find($id)->playsheets()->orderBy('start_time','desc')->get();
		});
	});
	Route::group(array('prefix'=>'playlists'),function(){
		Route::get('/{limit}/{offset}',function($limit=limit, $offset=offset){
			return Show::select('id','edit_date')->playsheets()->offset($offset)->limit($limit)->get();
		});
	});
	Route::group(array('prefix'=>'schedule'),function(){
		Route::get('/',function(){
			//Query from APIV1:
			/*
			$query = "SELECT show_times.start_day as start_day,
			            show_times.start_time as start_time,
			            show_times.end_day as end_day,
			            show_times.end_time as end_time,
			            show_times.alternating as alternating,
			            show_times.show_id,
			             shows.id as show_id,
			             shows.active as active
			            FROM show_times join shows on show_times.show_id = shows.id
			            WHERE active = 1";
			*/
			//TODO
			return DB::table('show_times')->join('shows', 'show_times.show_id', '=', 'shows.id')->where('shows.active', '=', 1)->get();
		});
	});
	//We currently have a perfectly fine endpoint at api2/public/specialbroadcasts
	//If that ends up changing we'll use this enpoint to maintain compatibility with wordpress
	/*Route::group(array('prefix'=>'specialevents'),function(){
		Route::get('/',function(){
		});
	});
	*/
});
