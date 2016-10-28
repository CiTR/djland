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
	Route::group(array('prefix'=>'image'),function($id){
		//Gets friend images
		Route::get('/',function($id){
			return Response::json(Show::find($id)->image);
		});
		//Uploads a friend image
		Route::post('/',function($id){
			if(Input::hasFile('image')){
				$file = Input::file('image');
				try{
					$upload = Upload::create(array('category'=>'friend_image','relation_id'=>$id,'size'=>$file->getClientSize(),'file_type'=>$file->getClientOriginalExtension()));
					return Response::json($upload->uploadImage($file));
				}catch(Exception $iae){
					return Response::json($iae->getMessage(),500);
				}
			}else{
				return Response::json('No File',500);
			}
		});
	});
});
