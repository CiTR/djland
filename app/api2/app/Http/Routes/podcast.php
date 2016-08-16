<?php
use App\Podcast as Podcast;

Route::put('/podcast',function(){
	$podcast = Podcast::create((array) Input::get()['podcast']);
	$podcast->duration_from_playsheet();
	return Response::json(array('id'=>$podcast->id));
});
Route::post('/podcast/{id}',function($id = id){
	$podcast = Podcast::find($id);
	$podcast->update(Input::get()['podcast']);
});
Route::post('/podcast/{id}/audio',function($id = id){
	$podcast = Podcast::find($id);
	$result = $podcast->make_podcast();
	return $result;
});
Route::post('/podcast/{id}/overwrite',function($id = id){
	$podcast = Podcast::find($id);
	$result = $podcast->overwrite_podcast();
	return $result;
});
