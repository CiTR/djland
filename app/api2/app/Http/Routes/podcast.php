<?php
use App\Podcast as Podcast;
use App\Upload as Upload;
use App\Show as Show;
Route::group(array('prefix'=>'podcast'),function(){
	Route::put('/',function(){
		$podcast = Podcast::create((array) Input::get()['podcast']);
		$podcast->duration_from_playsheet();
		return Response::json(array('id'=>$podcast->id));
	});
	Route::group(array('prefix'=>'{id}'),function($id = id){
		Route::post('/',function($id){
			$podcast = Podcast::find($id);
			$podcast->update(Input::get()['podcast']);
		});
		Route::get('/audio',function($id ){
			$podcast = Podcast::find($id);
			$result = $podcast->make_podcast();
			return $result;
		});
		Route::post('/audio',function($id){
			if(Input::hasFile('audio')){
				$file = Input::file('audio');
				try{
					$upload = Upload::create(array('category'=>'episode_audio','relation_id'=>$id,'size'=>$file->getClientSize(),'file_type'=>$file->getClientOriginalExtension()));
					return Response::json($upload->uploadAudio($file));
				}catch(InvalidArgumentException $iae){
					return Response::json($iae->getMessage(),500);
				}
			}else{
				return Response::json('No File',500);
			}
		});
		Route::post('/overwrite',function($id){
			$podcast = Podcast::find($id);
			$result = $podcast->overwrite_podcast();
			return $result;
		});
		Route::group(array('prefix'=>'image'),function($id){
			//Gets podcast image
			Route::get('/',function($id){
				return Response::json(array('image'=>Podcast::find($id)->image()));
			});
			//Uploads a podcast imag
			Route::post('/',function($id){
				if(Input::hasFile('image')){
					$file = Input::file('image');
					try{
						$upload = Upload::create(array('category'=>'episode_image','relation_id'=>$id,'size'=>$file->getClientSize(),'file_type'=>$file->getClientOriginalExtension()));
						return Response::json($upload->uploadImage($file));
					}catch(InvalidArgumentException $iae){
						return Response::json($iae->getMessage(),500);
					}
				}else{
					return Response::json('No File',500);
				}
			});
			Route::delete('/', function($id) {
				Upload::where('relation_id',$id)->andWhere('type','episode_image')->delete();
			});
		});
	});

});
