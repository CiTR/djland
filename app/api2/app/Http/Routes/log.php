<?php
//Routes in this file inplement DB-based logger routes for DJLand

use App\Log as Log;

//All routes in this group require that we be logged in to use
Route::group(array('middleware'=>'auth'),function(){
	Route::group(array('prefix'=>'log'),function(){
		//Most recent first with optional limit/offset (optional limit and offset passed in query string);
		Route::get('/',function(){
			//Support limit/offset if we're looking for a certain amount
			if( Input::get('limit') ) {
				$limit=Input::get('limit');
				if(!is_numeric($limit) ){
					$input = "Input was: \"" . $limit . "\", offset was: \"" . Input::get('offset') . "\"";
					$error= array('error'=>'Non-numerical limit passed to /api2/public/log/?limit=&offset=', 'data'=>$input,'user'=>'API_CALL');
					Log::create($error);
					return "API error, check DJLand API Log";
				}
			} else {
				$limit=500;
			}
			if( Input::get('offset')){
				$offset=Input::get('offset');
				if(!is_numeric($offset)){
					$input = "Input was: \"" . $limit . "\", offset was: \"" . $offset . "\"";
					$error= array('error'=>'Non-numerical offset passed to /api2/public/log/?limit=&offset=', 'data'=>$input,'user'=>'API_CALL');
					Log::create($error);
					return "API error, check DJLand API Log";
				}
			} else {
				$offset=0;
			}
			return Response::json( Log::select('index','error','data','user','DATE_CREATED')->offset($offset)->limit($limit)->get() );
		});
		//Most recent first with limit/offset
		Route::get('/bylimitoffset/{limit}/{offset}/',function($limit=limit,$offset=offset){
			$input = 'Input was: \"' . $limit . '\", offset was: \"' . $offset . "\"";
			if(is_numeric($limit) && is_numeric($offset) ){
				return Response::json( Log::select('index','error','data','user','DATE_CREATED')->offset($offset)->limit($limit)->get() );
			} else{
				$error= array('error'=>'Non-numerical limit or offset passed to /api2/public/log/bylimitoffset/{limit}/{offset}', 'data'=>$input,'user'=>'API_CALL');
				Log::create($error);
				return "API error, check DJLand API Log";
			}
		});
		//dates are to be in 'yyyy-mm-dd hh:mm:ss' format (24-hour)
		//Optional limit/offset via query string
		Route::get('/bydatebefore/{date}',function($date=date){
			$_date = (new Datetime($date))->format('Y-m-d H:i:s');
			//var_dump($_date);
			if( Input::get('limit') ) {
				$limit=Input::get('limit');
				if(!is_numeric($limit) ){
					$input = "Input was: \"" . $limit . "\", offset was: \"" . Input::get('offset') . "\"";
					$error= array('error'=>'Non-numerical limit passed to /api2/public/log/bydatebefore/{date}/?limit=&offset=', 'data'=>$input,'user'=>'API_CALL');
					Log::create($error);
					return "API error, check DJLand API Log";
				}
			} else {
				$limit=500;
			}
			if( Input::get('offset')){
				$offset=Input::get('offset');
				if(!is_numeric($offset)){
					$input = "Input was: \"" . $limit . "\", offset was: \"" . $offset . "\"";
					$error= array('error'=>'Non-numerical offset passed to /api2/public/log/bydatebefore/{date}/?limit=&offset=', 'data'=>$input,'user'=>'API_CALL');
					Log::create($error);
					return "API error, check DJLand API Log";
				}
			} else {
				$offset=0;
			}
			return Response::json(Log::select('index','error','data','user','DATE_CREATED')->where('DATE_CREATED','<', date($_date))->offset($offset)->limit($limit)->get());
		});
		//dates are to be in 'yyyy-mm-dd hh:mm:ss' format (24-hour)
		//Optional limit/offset via query string
		Route::get('/bydateafter/{date}',function($date=date){
			$_date = (new Datetime($date))->format('Y-m-d H:i:s');
			if( Input::get('limit') ) {
				$limit=Input::get('limit');
				if(!is_numeric($limit) ){
					$input = "Input was: \"" . $limit . "\", offset was: \"" . Input::get('offset') . "\"";
					$error= array('error'=>'Non-numerical limit passed to /api2/public/log/bydateafter/{date}/?limit=&offset=', 'data'=>$input,'user'=>'API_CALL');
					Log::create($error);
					return "API error, check DJLand API Log";
				}
			} else {
				$limit=500;
			}
			if( Input::get('offset')){
				$offset=Input::get('offset');
				if(!is_numeric($offset)){
					$input = "Input was: \"" . $limit . "\", offset was: \"" . $offset . "\"";
					$error= array('error'=>'Non-numerical offset passed to /api2/public/log/bydateafter/{date}/?limit=&offset=', 'data'=>$input,'user'=>'API_CALL');
					Log::create($error);
					return "API error, check DJLand API Log";
				}
			} else {
				$offset=0;
			}
			return Response::json(Log::select('index','error','data','user','DATE_CREATED')->where('DATE_CREATED','>', date($_date))->offset($offset)->limit($limit)->get());
		});
		//Start and end dates are to be in 'yyyy-mm-dd hh:mm:ss' format (24-hour)
		//Date ranges are start and end inclusive
		//Optional limit/offset via query string
		Route::get('/bydaterange/{start}/{end}/',function($start=start,$end=end){
			$_start = (new Datetime($start))->format('Y-m-d H:i:s');
			$_end = (new Datetime($end))->format('Y-m-d H:i:s');
			if( Input::get('limit') ) {
				$limit=Input::get('limit');
				if(!is_numeric($limit) ){
					$input = "Input was: \"" . $limit . "\", offset was: \"" . Input::get('offset') . "\"";
					$error= array('error'=>'Non-numerical limit passed to /api2/public/log/bydaterange/{start}/{end}/?limit=&offset=', 'data'=>$input,'user'=>'API_CALL');
					Log::create($error);
					return "API error, check DJLand API Log";
				}
			} else {
				$limit=500;
			}
			if( Input::get('offset')){
				$offset=Input::get('offset');
				if(!is_numeric($offset)){
					$input = "Input was: \"" . $limit . "\", offset was: \"" . $offset . "\"";
					$error= array('error'=>'Non-numerical offset passed to /api2/public/log/bydaterange/{start}/{end}/?limit=&offset=', 'data'=>$input,'user'=>'API_CALL');
					Log::create($error);
					return "API error, check DJLand API Log";
				}
			} else {
				$offset=0;
			}
			return Response::json(Log::select('index','error','data','user','DATE_CREATED')->where('DATE_CREATED','>=', date($_start))->where('DATE_CREATED','<=', date($_end))
				->offset($offset)->limit($limit)->get());
		});
		Route::get('byid/{id}',function($id=id){
			if(is_numeric($id)){
				return Response::json(Log::where('index','=',$id)->get());
			} else {
				$error= array('error'=>'Non-numerical id passed to /api2/public/log/byid/{id}', 'data'=>"The id entered was: ".$id,'user'=>'API_CALL');
				Log::create($error);
				return "API error, check DJLand API Log";
			}
		});
		Route::get('/byuser/{user}',function($user=user){
			return Response::json(Log::where('user','=',$user)->get() );
		});
		/* Not used - probably never a use case where an external body would want to write to djland's log
		   Unless of course we can figure out how to only get djland itself to be able to write to it's DB log through it's own api
		   Not sure how to do that -Scott
		Route::post('/',function(){
		});
		Route::delete('/{id}',function(){
		});
		Route::put('/{id}',function(){

		});
		*/
	});
});
