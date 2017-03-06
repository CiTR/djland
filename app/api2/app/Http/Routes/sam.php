<?php

//SAM CLASSES
use App\Songlist as Songlist;
use App\Categorylist as Categorylist;
use App\Historylist as Historylist;

Route::group(array('prefix'=>'SAM'),function($id = id){
	//List Tables
	Route::get('/table',function(){
		return  DB::connection('samdb')->select('SHOW TABLES');
	});
	//Get Table Fields
	Route::get('/table/{table}',function($table_name){
		echo "<table>";
		echo "<tr><th>Field<th>Type<th>Null<th>Key<th>Extra</tr>";
		$table = DB::connection('samdb')->select('DESCRIBE '.$table_name);
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
	//Get Recent plays
	Route::get('recent/{offset}',function($offset = offset){
		$sam_plays = DB::connection('samdb')
		->table('songlist')
		->join('historylist','songlist.id','=','historylist.songID')
		->selectRaw('songlist.artist,songlist.title,songlist.album,songlist.composer,songlist.mood,historylist.date_played,historylist.duration')
		->where('songlist.songtype','=','S')
		->limit('50')
		->offset($offset)
		->orderBy('historylist.date_played','desc')
		->get();
		foreach($sam_plays as $play){
			foreach($play as $item){
				if(is_string($item)){
					$item = html_entity_decode($item ,ENT_QUOTES);
				}
			}
		}
		return $sam_plays;
	});
	//Get a time range of sam plays
	Route::get('range',function(){
		$from = Input::get()['from'];
		$to = Input::get()['to'];
		$sam_plays = Historylist::select('songlist.artist','songlist.title','songlist.album','songlist.composer','songlist.mood','historylist.date_played','historylist.duration')
			->join('songlist','historylist.songID','=','songlist.ID')
			->where('historylist.date_played','>=',$from)
			->where('historylist.date_played','<=',$to)
			->orderBy('historylist.date_played','asc')
			->get();
		foreach($sam_plays as $play){
			foreach($play as $item){
				if(is_string($item)){
					$item = html_entity_decode($item ,ENT_QUOTES);
				}
			}
		}
		return $sam_plays;
	});
	//Get tracks from the songlist
	Route::group(array('prefix'=>'songlist'),function(){
		Route::get('/',function(){
			return Songlist::select('id','title')->get();
		});

	});
	//Get tracks with a specific category (Accepts category ID # and category name)
	//TODO: deal with a category being blank
	Route::group(array('prefix'=>'categorylist'),function(){
		Route::get('{cat_id}',function($cat_id = cat_id){
			if(is_numeric($cat_id)){
				$categorylist = Categorylist::where('categoryID','=',$cat_id)->get();
			}else{
				$categorylist = Categorylist::join('category','category.id','=','categorylist.categoryID')->where('category.name','LIKE',$cat_id)->get();
			}
			foreach($categorylist as $item){
				$song = Songlist::find($item->songID);
				if($song['title'] == "" || $song['title'] == null){
					$song['title'] = $song['artist'];
				}
				$songs[] = $song;
			}
			return Response::json($songs);
		});
	});
});
