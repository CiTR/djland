<?php

	use App\SpecialBroadcast as SpecialBroadcast;

	Route::group(array('prefix'=>'specialbroadcasts'),function(){
		Route::get('/',function(){
			return SpecialBroadcast::orderBy('id','desc')->get();
		});
		Route::put('/',function(){
			$specialbroadcast = new SpecialBroadcast;
			$specialbroadcast->save();
			return $specialbroadcast;
		});
		Route::post('/',function(){
			$specialbroadcasts = Input::get()['specialbroadcasts'];
			foreach($specialbroadcasts as $specialbroadcast){
				$s = SpecialBroadcast::find($specialbroadcast['id']);
				unset($specialbroadcast['id']);
				$s->update((array) $specialbroadcast);
			}
			return Response::json($specialbroadcasts);
		});

		Route::group(array('prefix'=>'/{id}'),function($id = id){
			Route::delete('/',function($id =id){
				return Response::json(SpecialBroadcast::find($id)->delete());
			});
		});

	});
