<?php

//Helpers
use App\Showtime as Showtime;


Route::group(array('prefix'=>'schedule'),function(){
	Route::get('/',function(){
		return DB::select("SELECT show_times.start_day as start_day,
	            show_times.start_time as start_time,
	            show_times.end_day as end_day,
	            show_times.end_time as end_time,
	            show_times.alternating as alternating,
	            show_times.show_id,
	             shows.id as show_id,
	             shows.active as active
	            FROM show_times join shows on show_times.show_id = shows.id
	            WHERE active = 1");
	});
});