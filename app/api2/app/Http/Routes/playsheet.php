<?php
//Playsheet related classes
use App\Playsheet as Playsheet;
use App\Show as Show;
use App\Playitem as Playitem;
use App\Ad as Ad;
use App\Socan as Socan;

//Assisting Classes
use App\Member as Member;

//SAM CLASSES
use App\Songlist as Songlist;
use App\Categorylist as Categorylist;
use App\Historylist as Historylist;

/* Playsheet Routes */
Route::group(array('prefix'=>'playsheet'),function(){
	//Get: Return List of Playsheets descending by date updated.
	Route::get('/',function(){
		return Playsheet::orderBy('EDITED_AT','desc')->select('id','EDITED_AT');
	});
	//Create a new playsheet
	Route::put('/',function(){
		$playsheet_object = Playsheet::create((array) Input::get()['playsheet']);
	});
	Route::post('/report',function(){
		include_once(dirname($_SERVER['DOCUMENT_ROOT'])."/config.php");
		include_once($_SERVER['DOCUMENT_ROOT']."/headers/session_header.php");
		//Get input variables and make sure they are set, otherwise abort with 400.
		$member_id = isset($_SESSION['sv_id']) ? $_SESSION['sv_id'] : null;
		if($member_id == null) return Response::json('You are not logged in');

		$from = isset(Input::get()['from']) ? str_replace('/','-',Input::get()['from']) : null;
		$to = isset(Input::get()['to']) ? str_replace('/','-',Input::get()['to']) : null;
		if($from == null || $to == null) return Response::json("Not a valid range");

		$show_id = isset(Input::get()['show_id']) ? Input::get()['show_id'] : null;
		if($show_id == null) return Response::json("Not a valid show id");

		$report_type = isset(Input::get()['report_type']) ? Input::get()['report_type'] : null;
		if($report_type == null) return Response::json("No report type specified");

		//Initialize array for playsheets
		$playsheets = array();
		$playsheet_totals=array();

		//If the member is staff or admin, the report should be for all shows
		$permissions = Member::find($member_id)->user->permission;
		if($permissions->staff ==1 || $permissions->administrator==1){
			$shows = Show::all();
		}else{
			$shows =  Member::find($member_id)->shows;
		}
		//For each show available to the request user, get the playsheets for the period that match the specified show ID, or return all.
		foreach($shows as $show){
			if( $show_id=="all" || $show_id==$show['id']){
				$ps = Show::find($show['id'])->playsheets()->orderBy('start_time','asc')->where('start_time','>=',$from.($report_type=='crtc'? " 06:00:00":" 00:00:00"))->where('start_time','<=',$to." 23:59:59")->get();
				foreach($ps as $sheet){
					$playsheets[] = $sheet;
				}
			}
		}
		//Initialize overall totals
		$totals = new stdClass();
		$totals->total=0;
		$totals->cc_20_total=0;
		$totals->cc_20_count=0;
		$totals->cc_30_total=0;
		$totals->cc_30_count=0;
		$totals->femcon_count=0;
		$totals->hit_count=0;
		$totals->new_count=0;
		$totals->spokenword=0;
		$totals->ads=0;

		//create show_totals array
		$show_totals = array();
		//get totals for each playsheet
		foreach($playsheets as $p){
			$playsheet = $p;
			$playsheet->playitems = Playsheet::find($playsheet['id'])->playitems;
			$playsheet->show = $p->show;
			$playsheet->socan = $p->is_socan();
			$playsheet->ads = Ad::where('playsheet_id','=',$p->id)->get();

			//initialize this playsheet's totals
			$playsheet->totals = new stdClass();
			$playsheet->totals->total=0;
			$playsheet->totals->cc_20_total = 0;
			$playsheet->totals->cc_20_count=0;
			$playsheet->totals->cc_30_total=0;
			$playsheet->totals->cc_30_count=0;
			$playsheet->totals->femcon_count=0;
			$playsheet->totals->hit_count=0;
			$playsheet->totals->new_count=0;
			$playsheet->totals->spokenword=0;
			$playsheet->totals->ads=0;

			if($enabled['sam_integration']){
				if( $playsheet->start_time && $playsheet->end_time){
					$playsheet->ads_played = Historylist::where('date_played','<=',$playsheet->end_time)->where('date_played','>=',$playsheet->start_time)->where('songtype','=','A')->get();
				}
				foreach($playsheet->ads_played as $ad){
					$playsheet->totals->ads += floor($ad['duration']/1000);
				}
			}

			//If this show hasn't been seen before, initialize it
			if(!isset($show_totals[$playsheet->show_name])){
				$show_totals[$playsheet->show['name']] = new stdClass();
				$show_totals[$playsheet->show['name']]->total=0;
				$show_totals[$playsheet->show['name']]->cc_20_total = 0;
				$show_totals[$playsheet->show['name']]->cc_20_count=0;
				$show_totals[$playsheet->show['name']]->cc_30_total=0;
				$show_totals[$playsheet->show['name']]->cc_30_count=0;
				$show_totals[$playsheet->show['name']]->femcon_count=0;
				$show_totals[$playsheet->show['name']]->hit_count=0;
				$show_totals[$playsheet->show['name']]->new_count=0;
				$show_totals[$playsheet->show['name']]->spokenword=0;
				$show_totals[$playsheet->show['name']]->ads=0;
				$show_totals[$playsheet->show['name']]->show = $playsheet->show;

			}
			foreach($playsheet->playitems as $playitem){
				$playsheet->totals->total ++;
				//Cat 20 and 30
				if($playitem['crtc_category']=='20'){
					$playsheet->totals->cc_20_total ++;
					if($playitem['is_canadian'] == '1') $playsheet->totals->cc_20_count ++;
				}else{
					$playsheet->totals->cc_30_total ++;
					if($playitem['is_canadian'] == '1') $playsheet->totals->cc_30_count ++;
				}
				//Femcon
				if($playitem['is_fem'] == '1') $playsheet->totals->femcon_count ++;
				//Hit
				if($playitem['is_hit'] == '1') $playsheet->totals->hit_count ++;
				//New
				if($playitem['is_new'] == '1') $playsheet->totals->new_count ++;

				//return Response::json($playsheet->totals);
			}
			$playsheet->totals->spokenword = $playsheet->spokenword_duration;
			$playsheet_totals[] = $playsheet;
			//Update corresponding show totals, and overall
			foreach($playsheet->totals as $key=>$item){
				$show_totals[$playsheet->show['name']]->$key += $item;
				$totals->$key += $item;
			}
		}
		usort($playsheet_totals,function($a,$b){
			$s1 = strtotime($a['start_time']);
			$s2 = strtotime($b['start_time']);
			return $s1-$s2;
		});
		return Response::json(array('playsheets'=>$playsheet_totals,'totals'=>$totals,'show_totals'=>$show_totals));
	});
	Route::group(array('prefix'=>'{id}'),function($id = id){
		//Update Playsheet Information
		Route::post('/',function($id){
			return Playsheet::find($id)->update((array) Input::get()['playsheet']);
		});
		Route::group(array('prefix'=>'playitem'),function($id){
			//Add a playitem to the playsheet
			Route::put('/',function($id){
				return Playitem::create((array) Input::get()['playitem']);
			});
		});
	});

	Route::get('/',function(){
		return $playsheets = Playsheet::orderBy('id','desc')->select('id')->get();
	});

	Route::post('/',function(){
		$ps = Playsheet::create(Input::get()['playsheet']);
		$podcast_in = Input::get()['podcast'];
		$podcast_in ['playsheet_id'] = $ps->id;
		$podcast_in['title'] = $ps->title;
		$podcast_in['subtitle'] = $ps->summary;
		$podcast = Podcast::create($podcast_in);

		foreach(Input::get()['playitems'] as $playitem){
			$playitem['playsheet_id'] = $ps->id;
			Playitem::create($playitem);
		}
		foreach(Input::get()['ads'] as $ad){
			$ad['playsheet_id'] = $ps->id;
			if(isset($ad['id'])){
				$a = Ad::find($ad['id']);
				unset($ad['id']);
				$a->update((array) $ad);
			}else{
				$a = Ad::create((array) $ad);
			}
		}
		$response = new stdClass();
		$response->id = $ps->id;
		$response->podcast_id = $podcast->id;
		return Response::json($response);
	});

	//Searching by Playsheet ID
	Route::group(array('prefix'=>'{id}'),function($id = id){
		//Get Existing Playsheet
		Route::get('/',function($id){
			require_once(dirname($_SERVER['DOCUMENT_ROOT']).'/config.php');
			$playsheet = new stdClass();
			$playsheet -> playsheet = Playsheet::find($id);
			if($playsheet -> playsheet != null){
				$playsheet -> playitems = Playsheet::find($id)->playitems;
				$show = Playsheet::find($id)->show;
				$playsheet -> show = $show;
				$playsheet -> podcast = Playsheet::find($id)->podcast;
				$promotions = Playsheet::find($id)->ads;
				foreach($promotions as $key => $value){
					//Get Ad Names From SAM
					if($using_sam && is_numeric($value['name'])){
						$ad_info =  DB::connection('samdb')->table('songlist')->select('*')->where('id','=',$value['name'])->get();
						if(count($ad_info) == 1) $promotions[$key]['name'] = $ad_info[0]->title;
					}else{
						$promotions[$key]['name'] = html_entity_decode($promotions[$key]['name'],ENT_QUOTES);
					}
				}
				$playsheet -> promotions = $promotions;
			}
			return Response::json($playsheet);
		});
		//Save Existing Playsheet
		Route::post('/',function($id){
			$ps = Playsheet::find($id);
			$ps->update((array) Input::get()['playsheet']);
			$response['playsheet'] = $ps;
			$ps->podcast()->update((array) Input::get()['podcast']);
			$response['podcast'] = $ps->podcast;

			$playitems = Input::get()['playitems'];
			foreach($ps->playitems as $delete){
				$delete->delete();
			}
			foreach($playitems as $playitem){
				$response['playitems'][] = Playitem::create((array)$playitem);
			}
			if(isset(Input::get()['ads'])){
				foreach(Input::get()['ads'] as $ad){
					if(isset($ad['id'])){
						$ad['playsheet_id'] = $ps->id;
						$a = Ad::find($ad['id']);
						unset($ad['id']);
						$response['ads'][] = $a->update((array) $ad);
					}else{
						$response['ads'][] = Ad::create((array) $ad);
					}
				}
			}

			return Response::json($response);
		});
		Route::delete('/',function($id){
			return Response::json(Playsheet::find($id)->delete());
		});
		Route::post('episode',function($id){
			$playsheet = Playsheet::find($id);
			$podcast = $playsheet->podcast;
			return Response::json( $playsheet -> update((array) Input::get()['playsheet']) && $podcast -> update((array) Input::get()['podcast']) ? "true" : "false");
		});
	});
	Route::get('member/{member_id}/{offset}',function($member_id = member_id,$offset = offset){
		if(Member::find($member_id)->isStaff()){
			$shows = Show::all();
		}else{
			$shows =  Member::find($member_id)->shows;
		}
		foreach($shows as $show){
			$show_ids[] = $show->id;
		}
		foreach(Playsheet::orderBy('start_time','desc')->whereIn('show_id',$show_ids)->limit('200')->offset($offset)->get() as $ps){
			$playsheet = new stdClass();
			$playsheet = $ps;
			//$playsheet -> show_info = Show::find($ps->show_id);
			$playsheet->socan = $playsheet->is_socan();
			$playsheets[] = $playsheet;
		}
		return Response::json($playsheets);
	});
	Route::get('member/{member_id}',function($member_id = member_id){
		$permissions = Member::find($member_id)->user->permission;
		if($permissions->staff ==1 || $permissions->administrator==1){
			$shows = Show::all();
		}else{
			$shows =  Member::find($member_id)->shows;
		}
		foreach($shows as $show){
			$show_ids[] = $show->id;
		}
		$socan = Socan::all();
		foreach(Playsheet::orderBy('start_time','desc')->whereIn('show_id',$show_ids)->limit('200')->get() as $ps){
			$playsheet = new stdClass();
			$playsheet = $ps;
			$playsheet -> show_info = Show::find($ps->show_id);
			$playsheet->socan = $playsheet->is_socan();
			$playsheets[] = $playsheet;
		}
		return Response::json($playsheets);
	});

	Route::get('list',function(){
		return DB::table('playsheets')
		->join('shows','shows.id','=','playsheets.show_id')
		->select('playsheets.id','shows.host','playsheets.start_time')
		->limit('100')
		->orderBy('playsheets.id','desc')
		->get();
	});
	Route::get('list/{limit}',function($limit = limit){
		$playsheets = Playsheet::orderBy('id','desc')->limit($limit)->get();
		foreach($playsheets as $playsheet){
			if($playsheet != null){
				$ps = new stdClass();
				$ps -> id = $playsheet -> id;
				$ps -> start_time = $playsheet->start_time;
				$ps -> show = Show::find($playsheet->show_id);
				$list[] = $ps;
			}
		}
		return Response::json($list);
	});

});
