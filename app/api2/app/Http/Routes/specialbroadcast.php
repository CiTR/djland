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
		Route::group(array('prefix'=>'image'),function($id){
			//Gets broadcast images
			Route::get('/',function($id){
				return Response::json(Show::find($id)->image);
			});
			//Uploads a braodcast image
			Route::post('/',function($id){
				if(Input::hasFile('image')){
					$file = Input::file('image');
					try{
						$upload = Upload::create(array('category'=>'special_broadcast_image','relation_id'=>$id,'size'=>$file->getClientSize(),'file_type'=>$file->getClientOriginalExtension()));
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
