<?php
//All CiTR wordpress-related enpoints go in here
//This is in a seperate file to avoid having to worry about keeping endpoints for DJLand also compatible with CiTR.ca
//In addition it also ensures that should others want to connect DJLand to their site, they can just delete this file or drop another in with suitable routes

use App\Show as Show;
use App\Showtime as Showtime;
use App\Social as Social;
use App\Playsheet as Playsheet;
use App\Podcast as Podcast;

Route::group(array('prefix'=>'DJLandConnector'),function(){
	Route::group(array('prefix'=>'show'),function(){
		//?= query string style for just one show with given ID
		Route::get('/',function(){
			$id= Input::get('ID');
			return show($id);
		});
		//just one show with given ID
		Route::get('/{id}',function($id=id){
			return show($id);
		});
	});
	Route::group(array('prefix'=>'shows'),function(){
		//Return list of shows in LIMIT/OFFSET format
		Route::get('/{LIMIT}/{OFFSET}',function($limit=LIMIT,$offset=OFFSET){
			return Show::select('id','edit_date')->offset($offset)->limit($limit)->get();
		});
		//?= query string style for LIMIT/OFFSET format
		Route::get('/', function(){
			//Return list of shows in LIMIT/OFFSET FORMAT
			$limit=Input::get('LIMIT');
			$offset=Input::get('OFFSET');
			return Show::select('id','edit_date')->offset($offset)->limit($limit)->get();
		});
	});
	//Returns a playlist given playlist ID
	Route::group(array('prefix'=>'playlist'),function(){
		// /ID/ style
		Route::get('/{id}',function($id=id){
			return playlist($id);
		});
		//?= query string style
		Route::get('/',function(){
			$id=Input::get('ID');
			return playlist($id);
		});
	});
	Route::group(array('prefix'=>'playlists'),function(){
		Route::get('/{limit}/{offset}',function($limit=limit, $offset=offset){
			return Show::select('id','edit_date')->playsheets()->offset($offset)->limit($limit)->get();
		});
		//?= query string style
		Route::get('/{limit}/{offset}',function(){
			$limit=Input::get('LIMIT');
			$offset=Input::get('OFFSET');
			return Show::select('id','edit_date')->playsheets()->offset($offset)->limit($limit)->get();
		});
	});
	Route::group(array('prefix'=>'schedule'),function(){
		Route::get('/',function(){
			return DB::table('show_times')->join('shows', 'show_times.show_id', '=', 'shows.id')->select('show_times.start_day',
			'show_times.start_time','show_times.end_day','show_times.end_time','show_times.alternating','show_times.show_id',
			'shows.id as show_id', 'shows.active as active')->where('shows.active', '=', 1)->get();
		});
	});
	//We currently have a perfectly fine endpoint at api2/public/specialbroadcasts
	//If that ends up changing we'll use this enpoint to maintain compatibility with wordpress
	/*Route::group(array('prefix'=>'specialevents'),function(){
		Route::get('/',function(){
		});
	});
	*/
});

//************
//Functions
//************

//Just one show with a given show ID
function show($id){
	//First we get the needed info from the shows table
	if(!Show::find($id)) return null;
	$data = Show::select('id as show_id',
		  'name',
		  'last_show',
		  'create_date',
		  'edit_date',
		  //above Was previously (not sure if this matters?):
		  //GREATEST(shows.edit_date,'0000-00-00 00:00:00') as edit_date,
		  'active',
		  'primary_genre_tags',
		  'secondary_genre_tags',
		  'website',
		  'rss',
		  'show_desc',
		  'alerts',
		  'image as show_img',
		  'host as host_name',
		  'podcast_title as podcast_title',
		  'podcast_subtitle AS podcast_subtitle',
		  'secondary_genre_tags as podcast_keywords',
		  'image as podcast_image_url',
		  'podcast_xml')->where('id','=',$id)->get();
	//And all the social links for that show from the social table
	$data['social_links'] = Social::select('social_name','social_url')->where('show_id','=',$id)->get();
	return Response::json( $data );
}
//Get playlist given  playlist ID
function playlist($id){
	$playsheet = Playsheet::select('id as playlist_id', 'show_id', 'start_time', 'end_time', 'edit_date', 'type as playlist_type', 'host as host_name')->where('id','=',$id)->get();
	$podcast = Podcast::select('id as episode_id', 'summary as episode_description', 'title as episode_title', 'url as episode_audio')->where('playsheet_id','=',$id)->get();
	//For some reason ->merge() didn't work so we did this and it did
	$ret = array_merge($playsheet[0]->toArray(), $podcast[0]->toArray());
	$ret['songs'] = Playsheet::find($id)->playitems;
	return Response::json($ret);
}