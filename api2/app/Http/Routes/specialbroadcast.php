<?php

	use App\SpecialBroadcasts as SpecialBroadcasts;

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