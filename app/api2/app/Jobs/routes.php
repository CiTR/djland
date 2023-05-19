<?php
//Include all of our routing files
foreach ( File::allFiles(__DIR__.'/Routes') as $partial )
{
    require $partial->getPathname();
}

//DJLand classes
use App\Social as Social;
use App\Option as Option;
use App\Show as Show;
use App\Member as Member;
use App\Permission as Permission;
use App\SpecialBroadcasts as SpecialBroadcasts;

//Anything inside the auth middleware requires an active session (user to be logged in)
Route::group(['middleware' => 'auth'], function(){
	//Member Resource Routes
	Route::group(array('prefix'=>'resource'),function(){
		Route::get('/',function(){
			return Option::where('djland_option','=','member_resources')->get();
		});
		Route::post('/',function(){
			$resource = Option::where('djland_option','=','member_resources')->first();
			$resource -> value = Input::get()['resources'];
			return Response::json($resource->save());
		});
	});
});

Route::get('/social',function(){
	return Social::all();
});

Route::get('/nowplaying',function(){
	require_once($_SERVER['DOCUMENT_ROOT'].'/config.php');
	//Since we aren't calling our security header, we need to ensure the timezone is set.
	date_default_timezone_set($station_info['timezone']);
	$result = array();
	$result['music'] = null;

	//CiTR uses week alternation, need to find out what week it is currently.
	$day_of_week = date('w');
	//Get mod 2 of (current unix minus days to last sunday) then divide by 8.64E7 * 7 to get number of weeks elapsed since epoch start.
	$current_week = floor( (date(strtotime('now')) - intval($day_of_week*60*60*24)) /(60*60*24*7) );
    if ((int) $current_week % 2 == 0){
        $current_week_val = 1;
    } else {
        $current_week_val = 2;
    };

	//We use 0 = Sunday instead of 7
	$yesterday = ($day_of_week - 1);
	$tomorrow = ($day_of_week + 1);
	$result['current_week'] = $current_week_val;
	$specialbroadcast = SpecialBroadcasts::whereRaw('start <= '.$now.' and end >= '.$now)->get();
	if($specialbroadcast->first()){
		//special broadcast exists
		$specialbroadcast = $specialbroadcast->first();
		$result['showId'] = $specialbroadcast->show_id;
		$result['showName'] = $specialbroadcast->name;
		$start_time = date('H:i:s',$specialbroadcast->start);
		$end_time = date('H:i:s',$specialbroadcast->end);
		$result['showTime'] = "{$start_time} - {$end_time}";
		$result['lastUpdated'] = date('D, d M Y g:i:s a',strtotime('now'));
	}else{
		//Get the current show if no special broadcast
		$current_show = DB::select(DB::raw(
		"SELECT s.*,sh.name as name,NOW() as time from show_times AS s INNER JOIN shows as sh ON s.show_id = sh.id
			WHERE
				CASE
					WHEN s.start_day = s.end_day THEN s.start_day={$day_of_week} AND s.end_day={$day_of_week} AND s.start_time <= CURTIME() AND s.end_time > CURTIME()
					WHEN s.start_day != s.end_day AND CURTIME() <= '23:59:59' AND CURTIME() > '12:00:00 'THEN s.start_day={$day_of_week} AND s.end_day = {$tomorrow} AND s.start_time <= CURTIME() AND s.end_time >= '00:00:00'
					WHEN s.start_day != s.end_day AND CURTIME() < '12:00:00' AND CURTIME() >= '00:00:00' THEN s.start_day= {$yesterday} AND s.end_day = {$day_of_week} AND s.end_time > CURTIME()
				END
				AND sh.active = 1
				AND (s.alternating = 0 OR s.alternating = {$current_week_val});"));
		if( count($current_show) > 0 ){
			$current_show = $current_show[0];
			$result['showId'] = $current_show->show_id;
			$result['showName'] = $current_show->name;
			$result['showTime'] = "{$current_show->start_time} - {$current_show->end_time}";
			$result['lastUpdated'] = date('D, d M Y g:i:s a',strtotime($current_show->time));
		}else{
			$result['showName'] = "CiTR Ghost Mix";
			$result['showId'] = null;
			$result['showTime'] = "";
			$result['lastUpdated'] = date('D, d M Y g:i:s a',strtotime('now'));
		}
	}
	return Response::json($result);
});

Route::group(array('prefix'=>'tools'),function(){
	//re-writes all the show xmls.
	Route::get('/write_show_xmls',function(){
		$shows = Show::orderByDesc('edit_date')->get()->sortByDesc('active');
		echo "<pre>";
		$index = 0;
		foreach($shows as $show){
			$index++;
			if($show->podcast_slug){
				$result = $show->make_show_xml();
				$result['index'] = $index;
				print_r($result);
				$results[] = $result;
			}
		}
	});
});

// Table Helper Routes
Route::get('/table',function(){
	return  DB::select('SHOW TABLES');
});

Route::get('/table/{table}',function($table_name =table){
	echo "<table>";
	echo "<tr><th>Field<th>Type<th>Null<th>Key<th>Extra</tr>";
	$table = DB::select('DESCRIBE '.$table_name);
	foreach($table as $column){
		echo "<tr>";
		foreach($column as $item){
			echo "<td>".$item."</td>";
		}
		echo "</tr>";
	}
	echo "</table>";
	foreach($table as $column){
		echo "'".$column->Field."', ";
	}
});
Route::post('/error',function(){
	date_default_timezone_set('America/Los_Angeles');
	$from = $_SERVER['HTTP_REFERER'];
	$error = Input::get()['error'];
	$date = date('l F jS g:i a',strtotime('now'));
	$out = '<hr>';
	$out .= '<h3>'.$date.'</h3>';
	$out .= '<h4>'.$from.'</h4>';
	$out .= '<p>'.$error.'</p>';
	$result = file_put_contents($_SERVER['DOCUMENT_ROOT'].'/log.html',$out.PHP_EOL,FILE_APPEND);
	return $result;
});
