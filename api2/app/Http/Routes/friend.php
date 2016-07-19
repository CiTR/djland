<?php
use App\Friends as Friends;

Route::group(array('prefix'=>'friends'),function(){
	Route::get('/',function(){
		return Friends::all();
	});
	Route::put('/',function(){
		$friend = new Friends;
		$friend->save();
		return $friend;
	});
	Route::post('/',function(){
		$friends = Input::get()['friends'];
		foreach($friends as $friend){
			$f = Friends::find($friend['id']);
			unset($friend['id']);
			$f->update((array) $friend);
		}
		Friends::write_static();
		return Response::json($friends);
	});
	Route::delete('/{id}',function($id = id){
		return Response::json(Friends::find($id)->delete());
	});
	Route::get('/static',function(){
		return Friends::write_static();
	});
});
