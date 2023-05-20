<?php
	use App\Upload as Upload;

	Route::group(array('prefix'=>'/upload'),function(){
		Route::get('/',function(){
			return Upload::all();
		});
		Route::get('/{category}',function($category = category){
			return Upload::where('category','=',$category)->get();
		});
		Route::group(array('prefix'=>'{id}'),function($id = id){
			Route::get('/',function($id){
				return Upload::find($id);
			});
			Route::post('/',function($id){
				return Upload::find($id)->update((array) Input::get()['upload']);
			});
			Route::delete('/',function($id){
				return Response::json(Upload::find($id)->delete());
			});
		});
	});
