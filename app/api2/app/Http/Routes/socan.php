<?php

use App\Socan as Socan;

Route::group(array('prefix'=>'socan'),function(){

	Route::get('/',function(){
		return Socan::all();
	});
	Route::put('/',function(){
		return Socan::create((array) Input::get());
	});
	Route::group(array('prefix'=>'/{id}'),function($id = id){
		Route::post('/',function($id){
			return Response::json(Socan::find($id)->update((array) Input::get()['socan']));
		});
		Route::delete('/',function($id){
			return Response::json(Socan::find($id)->delete());
		});
	});
	//Check to see if the time requested is inside a socan period.
	Route::group(array('prefix'=>'check'),function(){
		Route::get('/{time}',function($unixtime = time){
			$now = $unixtime;
			$socan = Socan::all();
			foreach($socan as $period){
				if( strtotime($period['socanStart']) <= $now && strtotime($period['socanEnd']) >= $now){
					return Response::json(true);
				}
			}
			return Response::json(false);
		});
		Route::get('/',function(){
			$now = strtotime('now');
			$socan = Socan::all();
			foreach($socan as $period){
				if( strtotime($period['socanStart']) <= $now && strtotime($period['socanEnd']) >= $now){
					return Response::json(true);
				}
			}
			return Response::json(false);
		});
	});

});
