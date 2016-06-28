<?php

use App\Option as Option;
use App\User as User;
use App\Member as Member;
use App\MembershipYear as MembershipYear;
use App\Permission as Permission;
use App\Show as Show;
use App\Showtime as Showtime;
use App\Host as Host;
use App\Social as Social;
use App\Playsheet as Playsheet;
use App\Playitem as Playitem;
use App\Podcast as Podcast;
use App\Ad as Ad;
use App\Socan as Socan;
use App\SpecialBroadcasts as SpecialBroadcasts;

use App\Donor as Donor;
//SAM CLASSES
use App\Songlist as Songlist;
use App\Categorylist as Categorylist;
use App\Historylist as Historylist;

use App\Friends as Friends;

Route::get('/', function () {
    //return view('welcome');
    return "Welcome to DJLand API 2.0";
});


//Anything inside the auth middleware requires an active session (user to be logged in)
Route::group(['middleware' => 'auth'], function(){

	//Fundrive Routes
	Route::group(array('prefix'=>'fundrive'),function(){
		//Donor Subsection
		Route::group(array('prefix'=>'donor'),function(){
			//Create a new Donor
			Route::put('/',function(){
				return Donor::create();
			});

			Route::get('/',function(){
				$permissions = Member::find($_SESSION['sv_id'])->user->permission;
				if($permissions['operator'] == 1 || $permissions['administrator']==1 || $permissions['staff']==1 ) return Donor::all();
				else return "Nope";
			});

			//Donor By ID
			Route::group(array('prefix'=>'{id}'),function($id = id){
				//Get a donor
				Route::get('/',function($id){
					$permissions = Member::find($_SESSION['sv_id'])->user->permission;
					if($permissions['operator'] == 1 || $permissions['administrator']==1 || $permissions['staff']==1 ) return Donor::find($id);
					else return "Nope";
				});
				//Update a donor
				Route::post('/',function($id){
					return Response::json(Donor::find($id)->update( (array) Input::get()['donor']));
				});
				//Delete a donor
				Route::delete('/',function($id){
					$permissions = Member::find($_SESSION['sv_id'])->user->permission;
					if($permissions['operator'] == 1 || $permissions['administrator']==1 || $permissions['staff']==1 ) return Donor::delete();
					else return "Nope";
				});
			});
		});
	});


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

	//Member Routes
	Route::group(array('prefix'=>'member'), function(){

		Route::get('/',function(){
			return  DB::table('membership')->select('id','firstname','lastname')->get();
		});
		// Searching Membership
		Route::get('/search',function(){
			$_GET = Input::get();
			return Member::search($_GET['search_parameter'],$_GET['search_value'],$_GET['paid'],$_GET['membership_year'],$_GET['has_show'],$_GET['order_by']);
		});
		//Membership email List
		Route::get('/email_list',function(){
			$_GET = Input::get();
			return Member::email_list($_GET['from'],$_GET['to'],$_GET['type'],$_GET['value'],$_GET['year']);
		});

		//Searching by member ID
		Route::group(array('prefix'=>'{id}'), function($id = id){

			Route::get('/',function($id){
				$permissions = Member::find($_SESSION['sv_id'])->user->permission;
				if($permissions['operator'] == 1 || $permissions['administrator']==1 || $permissions['staff'] == 1 || $id = $_SESSION['sv_id']) return Member::find($id);
				else return "Nope";

			});
			Route::post('/',function($id){
				$m = Member::find($id);
				return $m->update((array) json_decode(Input::get()['member']) ) ? "true": "false";
			});
			Route::delete('/',function($id){
				$permissions = Member::find($_SESSION['sv_id'])->user->permission;
				if($permissions['operator'] == 1 || $permissions['administrator']==1 || $permissions['staff'] == 1 ) return Member::find($id)->delete() ? "true":"false";
				else return "Nope";
			});

			Route::post('comments',function($id){
				$member = Member::find($id);
				$member -> comments = json_decode(Input::get()['comments']);
				return Response:: json($member -> save());

			});
			Route::get('training',function($id){
				$member =  Member::find($id);
				if($member->station_tour == '0' || $member->technical_training == '0' || $member->programming_training == '0' || $member->production_training == '0'){
					return 0;
				}else{
					return 1;
				}
			});
			Route::get('user',function($id){
				$permissions = Member::find($_SESSION['sv_id'])->user->permission;
				if($permissions['operator'] == 1 || $permissions['administrator']==1 || $permissions['staff'] == 1  || $id = $_SESSION['sv_id']) return Member::find($id)->user;
				else return "Nope";
			});
			Route::post('user',function($id){
				$m = Member::find($id);
				$permissions = Member::find($_SESSION['sv_id'])->user->permission;
				if($permissions['operator'] == 1 || $permissions['administrator']==1 || $permissions['staff'] == 1  || $id = $_SESSION['sv_id']) return $m->user->update(Input::get()['user']) ? "true": "false";
				else return "Nope";
			});
			Route::post('permission',function($id){
				$permission = Member::find($id)->user->permission;
				$permissions = Member::find($_SESSION['sv_id'])->user->permission;
				if($permissions['operator'] == 1 || $permissions['administrator']==1 || $permissions['staff'] == 1 ) return $permission->update((array) json_decode(Input::get()['permission'] )) ? "true": "false";
				else return "Nope";
			});
			Route::get('years',function($id){
				$m_years = Member::find($id)->membershipYears()->orderBy('membership_year','desc')->get();
				foreach($m_years as $year){
					$years[$year->membership_year] = $year;
				}
				return Response::json($years);
			});
			Route::post('years',function($id){
				$m = Member::find($id);
				$m_years = (array) json_decode(Input::get()['years']);
				$years = $m->membershipYears;
				foreach($years as $year){
					$year -> update( (array) $m_years[$year->membership_year]);
				}
				return "true";
			});
			Route::post('password',function($id){
				$m = Member::find($id);
				$user = $m->user;
				$user->password = password_hash(Input::get()['password'],PASSWORD_DEFAULT);
				$permissions = Member::find($_SESSION['sv_id'])->user->permission;
				if($permissions['operator'] == 1 || $permissions['administrator']==1 || $permissions['staff'] == 1  || $id = $_SESSION['sv_id']) return $user->save() ? "true":"false";
				else return "Nope";

			});
			Route::get('permission',function($member_id = id){
				$permission_levels = Member::find($member_id)->user->permission;
				unset($permission_levels->user_id);
				$permission = new stdClass();
				$permission->permissions = $permission_levels;
				return $permission_levels;
			});
			Route::get('shows', function($member_id = id){
				$permissions = Member::find($member_id)->user->permission;
				if($permissions->staff ==1 || $permissions->administrator==1){
					$all_shows = Show::orderBy('name','asc')->get();
					foreach($all_shows as $show){
						$shows->shows[] = ['id'=>$show->id,'show'=>$show,'name'=>$show->name];
					}
				}else{
					$member_shows = Member::find($member_id)->shows;
					foreach($member_shows as $show){
						$shows->shows[] = ['id'=>$show->id,'show'=>$show,'name'=>$show->name];
					}
				}
				return  Response::json($shows);
			});
			Route::get('active_shows', function($member_id = id){
				$permissions = Member::find($member_id)->user->permission;
				$shows = new stdClass();
				if($permissions->staff ==1 || $permissions->administrator==1){
					$all_shows = Show::where('active','=','1')->orderBy('name','asc')->get();
					foreach($all_shows as $show){
						$shows->shows[] = ['id'=>$show->id,'show'=>$show,'name'=>$show->name,'crtc'=>$show->crtc_default,'lang'=>$show->lang_default];
					}
				}else{
					$member_shows = Member::find($member_id)->shows;;
					foreach($member_shows as $show){
						if($show->active == 1){
							$shows->shows[] = ['id'=>$show->id,'show'=>$show,'name'=>$show->name,'crtc'=>$show->crtc_default,'lang'=>$show->lang_default];
						}
					}
				}
				return  Response::json($shows);
			});
		});

	});
	//Show Private method
	Route::get('/show/{id}/owners',function($id=id){
		$members = Show::find($id)->members;
		$owners = new stdClass();
		$owners->owners = [];
		foreach ($members as $member) {
			$owners->owners[] = ['id'=>$member->id,'firstname'=>$member->firstname,'lastname'=>$member->lastname];
		}
		$permissions = Member::find($_SESSION['sv_id'])->user->permission;
		if($permissions['operator'] == 1 || $permissions['administrator']==1 || $permissions['staff'] == 1 ) return Response::json($owners);
		return "Nope";
	});
});

// Member Creation Routes
	Route::post('/member',function(){
		$member = Member::create( (array) json_decode(Input::get()['member']));
		return $member->id;
	});
	Route::post('/user',function(){
		$user = json_decode(Input::get()['user']);
		$user->password = password_hash($user->password,PASSWORD_DEFAULT);
		$user->status = 'enabled';
		$user->login_fails = '0';
		$user = User::create((array) $user);
		$permissions = array('user_id'=> $user->id,'administrator'=>"0",'dj'=> "0",'member'=> "1",'staff'=> "0",'volunteer'=> "0",'workstudy'=> "0");
		Permission::create($permissions);
		return $user->id;
	});
	Route::post('/member/{id}/year',function($id = id){
		$member = Member::find($id);
		$membership_year = json_decode(Input::get()['year']);
		$membership_year->member_id = $id;
		return MembershipYear::create((array) $membership_year) ? "true" : "false";
	});


/* Show Routes */
Route::group(array('prefix'=>'show'),function(){
	//Creating new Show
	Route::put('/',function(){
		$show = Show::create((array) Input::get()['show']);
		$owners = Input::get()['owners'];
		$social = Input::get()['social'];
		$showtimes = Input::get()['showtimes'];

		$socials = array();
		$show_times = array();
		//Create owners
		foreach($owners as $owner){
			Show::find($show->id)->members()->attach($owner['id']);
		}
		//Create social entries, this table is really dumb.
		foreach($social as $s){
			$s['show_id'] = $show['id'];
			$socials[] = Social::create($s);
		}
		//Create Showtimes
		foreach($showtimes as $key=>$showtime){
			$showtime['show_id'] = $show['id'];
			$show_times[] = Showtime::create( (array) $showtime);
		}
		return Response::json(array('show'=>$show,'social'=>$socials,'owners'=>$owners,'showtimes'=>$show_times));
	});


	Route::get('/',function(){
		return Show::all('id','name');
	});

	Route::get('/active',function(){
		return Show::select('id','name')->where('active','=','1')->orderBy('name','ASC')->get();
	});

	//Searching by Show ID
	Route::group(array('prefix'=>'{id}'),function($id=id){

		Route::get('/',function($id){
			$show = Show::find($id);
			$show->social = Show::find($id)->social;
			return Response::json($show);
		});
		Route::post('/',function($id){
			$show = Input::get()['show'];
			$social = Input::get()['social'];
			$owners = Input::has('owners') ? Input::get()['owners'] : null;
			$showtimes = Input::get()['showtimes'];
			$s = Show::find($id);
			$s->update($show);

			$socials = array();
			$show_times = array();

			if($owners){
				//Detach current owners
				foreach(Show::find($id)->members as $current_owner){
					$s->members()->detach($current_owner->id);
				}
				//Attach new owners
				foreach($owners as $owner){
					Show::find($id)->members()->attach($owner['id']);
				}
			}


			//Find out which entries no longer exist. (This is because entries aren't deleted when the button is pressed in UI, ie. not RESTful)
			$existing_to_delete =  Show::find($id)->social;
			foreach($existing_to_delete as $key=>$e){
				$still_exists = false;
				foreach($social as $item){
					if(isset($item['id']) && $e['id'] == $item['id']){
						$still_exists = true;
					}
				}
				if(!$still_exists) Social::find($e['id'])->delete();
			}
			//Create or update social entries
			foreach($social as $item){
				if(isset($item['id'])){
					$s = Social::find($item['id']);
					unset($item['id']);
					$socials[] = $s->update($item);
				}else{
					$socials[] = Social::create($item);
				}
			}
			//Find out which entries no longer exist. (This is because entries aren't deleted when the button is pressed in UI, ie. not RESTful)
			$existing_to_delete =  Show::find($id)->showtimes;
			foreach($existing_to_delete as $key=>$e){
				$still_exists = false;
				foreach($showtimes as $item){
					if(isset($item['id']) && $e['id'] == $item['id']){
						$still_exists = true;
					}
				}
				if(!$still_exists) Showtime::find($e['id'])->delete();
			}
			//Create or update social entries
			foreach($showtimes as $item){
				if(isset($item['id'])){
					$s = Showtime::find($item['id']);
					unset($item['id']);
					$show_times[] = $s->update($item);
				}else{
					$show_times[] = Showtime::create($item);
				}
			}
			return Response::json(array('show'=>$show,'social'=>$socials,'owners'=>$owners,'showtimes'=>$show_times));
		});
		Route::get('episodes/{offset}',function($id,$offset = offset){
			$podcasts = Show::find($id)->podcasts()->orderBy('id','desc')->limit(50)->offset($offset)->get();
			$episodes = array();
			$socan = Socan::all();
			foreach($podcasts as $podcast){
				$playsheet = $podcast->playsheet;

				if($playsheet != null){
					$playsheet->socan = false;
					foreach($socan as $period){
						if( strtotime($period['socanStart']) <= strtotime($playsheet->start_time) && strtotime($period['socanEnd']) >= strtotime($playsheet->end_time)){
							$playsheet->socan = true;
						}
					}
					if($podcast -> duration == 0){
						$podcast -> duration_from_playsheet();
					}
					unset($podcast->playsheet);
					$episode = ['playsheet'=>$playsheet,'podcast'=>$podcast];
					$episodes[] = $episode;
				}else{
					$episode = ['podcast'=>$podcast];
					$episodes[] = $episode;
				}

			}
			return Response::json($episodes);

		});
		Route::get('playsheets/{offset}',function($id,$offset = offset){
			if($offset) return Show::find($id)->playsheets()->orderBy('start_time','desc')->offset($offset)->limit('200')->get();
			else return Show::find($id)->playsheets()->orderBy('start_time','desc')->get();
		});
		Route::get('playsheets',function($id){
 			return Show::find($id)->playsheets()->orderBy('start_time','desc')->get();
		});


		Route::get('social',function($id){
			return Show::find($id)->social;
		});

		Route::get('times',function($id){
			//return Showtimes::where('show_id','=',$show_id)->get();
			return Show::find($id)->showtimes;
		});
		Route::get('nextshow/{current_time}',function($id,$time = current_time){
			return Response::json(Show::find($id)->nextShowTime($time));
		});
		Route::get('xml',function($id){
			return Show::find($id)->make_show_xml();
		});
	});
});

Route::get('/social/{id}',function($show_id = id){
	return Social::where('show_id','=',$show_id)->get();
});


/* Playsheet Routes */
Route::group(array('prefix'=>'playsheet'),function(){
	//Get: Return List of Playsheets descending by date updated.
	Route::get('/',function(){
		return Playsheet::orderBy('EDITED_AT','desc')->select('id','EDITED_AT');
	});
	//Create a new playsheet
	Route::put('/',function(){
		return Playsheet::create((array)Input::get()['playsheet']);
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
		foreach(Input::get()['promotions'] as $ad){
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
	Route::post('/report',function(){
		include_once($_SERVER['DOCUMENT_ROOT']."/config.php");
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

			if($using_sam){
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

	//Searching by Playsheet ID
	Route::group(array('prefix'=>'{id}'),function($id = id){
		//Get Existing Playsheet
		Route::get('/',function($id){
			require_once($_SERVER['DOCUMENT_ROOT'].'/config.php');
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
			$response['playsheet'] = $ps;
			$ps->update(Input::get()['playsheet']);
			$ps->podcast->update((array) Input::get()['podcast']);
			$response['podcast'] = $ps->podcast;

			$playitems = Input::get()['playitems'];
			foreach($ps->playitems as $delete){
				$delete->delete();
			}
			foreach($playitems as $playitem){
				$response['playitems'][] = Playitem::create((array)$playitem);
			}
			if(isset(Input::get()['promotions'])){
				foreach(Input::get()['promotions'] as $ad){
					if(isset($ad['id'])){
						$ad['playsheet_id'] = $ps->id;
						$a = Ad::find($ad['id']);
						unset($ad['id']);
						$response['promotions'][] = $a->update((array) $ad);
					}else{
						$response['promotions'][] = Ad::create((array) $ad);
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
		$permissions = Member::find($member_id)->user->permission;
		if($permissions->staff ==1 || $permissions->administrator==1){
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
	Route::get('/shows/write_xml',function(){

		$shows = Show::orderBy('id')->get();
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
		//return Response::json($results);
	});
	Route::get('/shows',function(){
		$shows = Show::all();
		echo "<pre>";
		foreach($shows as $show){
			print_r(array($show->name,$show->id,$show->podcast_slug));
		}
	});


 // Fundrive amount raised total, Externally accessible
  Route::get('/fundrive/total',function(){
	 include_once($_SERVER['DOCUMENT_ROOT']."/headers/session_header.php");
	$donation_list = Donor::select('donation_amount')->get();
	$total = 0;
		  foreach ($donation_list as $donation) {
	  //str_replace is to deal with commas, as donation_amount is a varchar in the db and some people will enter in values with commas
			  $total = $total + floatval(str_replace(",","",$donation->donation_amount));
		  }
	return $total;
  });

Route::post('/adschedule',function(){
	$post = array();
	parse_str(Input::get('ads'),$post);

	foreach($post['show'] as $ad){
		if($ad['id']){
			$a = Ad::find($ad['id']);
			unset($ad['id']);
			$a->update($ad);
		}else{
			$a = Ad::create($ad);
		}
		$ads[]=$a;
	}
	return Response::json($ads);
});

Route::get('/adschedule',function(){

	date_default_timezone_set('America/Los_Angeles');
	$date = implode('-',explode('/',$_GET['date']));
	$formatted_date = date('Y-m-d',strtotime($date));
	$unix = strtotime($formatted_date);
	$parsed_date = date_parse($formatted_date);
	if($parsed_date["error_count"] == 0 && checkdate($parsed_date["month"], $parsed_date["day"], $parsed_date["year"])){
		//Constants (second conversions)
		$one_day = 24*60*60;
		$one_hour = 60*60;
		$one_minute = 60;


		//Get Day of Week (0-6)
		$day_of_week = date('w',strtotime($date));
        	//Get mod 2 of (current unix - time since start of last sunday divided by one week). Then add 1 to get 2||1 instead of 1||0
        	$week = (floor( (strtotime($date) - intval($day_of_week*$one_day)) /($one_day*7) ) % 2) + 1;


		if($formatted_date == date('Y-M-d',strtotime('now'))){
			//Set cutoff time to right now if we are loading today
			$time = date('H:i:s',strtotime('now'));
		}else{
			//Set cutoff time to 00:00:00
			$time = '00:00:00';
		}

		//Select active shows that run during the date specified.
		$shows =
		Show::selectRaw('shows.id,shows.name,show_times.start_day,show_times.start_time,show_times.end_day,show_times.end_time')
		->join('show_times','show_times.show_id','=','shows.id')
		->where('show_times.start_day','=',$day_of_week)
		->where('show_times.start_time','>=',$time)
		->whereRaw('(show_times.alternating = '.$week.' OR show_times.alternating = 0)')
		->where('shows.active','=','1')
		->orderBy('show_times.start_time','ASC')
		->get();

		//for each show time get the ads, or create them.
		foreach($shows as $show_time){
			$start_hour_offset = date_parse($show_time['start_time'])['hour'] * $one_hour;
			$start_minute_offset = date_parse($show_time['start_time'])['minute'] * $one_minute;
			$start_unix_offset = $start_hour_offset + $start_minute_offset;
			$end_hour_offset = date_parse($show_time['end_time'])['hour'] * $one_hour;
			$end_minute_offset = date_parse($show_time['end_time'])['minute'] * $one_minute;
			$end_unix_offset = $end_hour_offset + $end_minute_offset;
			if( $show_time['end_day'] != $show_time['start_day'] ){
				$end_unix_offset += $one_day;
			}

			$show_time->start_unix = $unix + $start_unix_offset;
			$show_time->end_unix = $unix + $end_unix_offset;
			$show_time->duration = $show_time->end_unix - $show_time->start_unix;

			/*if( date('I',strtotime($show_time->start_unix))=='0' ){
                $show_time->start_unix += 3600;
                $show_time->end_unix += 3600;
            }*/

			$ads = Ad::where('time_block','=',$show_time->start_unix)->get();
			$show_time->generated = false;
			if(count($ads) == 0){
				$show_time->generated = true;
				$show_time->ads = Ad::generateAds($show_time->start_unix,$show_time->duration,$show_time->id);
			}else{
				$show_time->ads = $ads;
			}
			$show_time->date = date('l F jS g:i a',$show_time->start_unix);
			$show_time->start = date('g:i a',$show_time->start_unix);
		}
		return Response::json($shows);
	}else{
		http_response_code('400');
		return "Not a Valid Date: {$formatted_date}";
	}


});



Route::get('/promotions/{unixtime}-{duration}/{show_id}',function($unixtime = unixtime,$duration = duration,$show_id = show_id){
	$ads = Ad::where('time_block','=',$unixtime)->orderBy('num','asc')->get();
	if(sizeof($ads) > 0) return Response::json($ads);
	else return Ad::generateAds($unixtime,$duration,$show_id);
});

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
Route::get('/nowplaying',function(){
	require_once($_SERVER['DOCUMENT_ROOT'].'/config.php');
	date_default_timezone_set('America/Los_Angeles');
	$result = array();
	if($using_sam){
		$last_track = DB::connection('samdb')->table('historylist')->selectRaw('artist,title,album,date_played,songtype,duration')
			->where('songtype','=','S')->orderBy('date_played','DESC')->limit('1')->get();
		$now = strtotime('now');
		if(count($last_track) > 0){
			$last_track = $last_track[0];
			if( (strtotime($last_track->date_played) + floor(($last_track->duration)/1000) ) >= $now ){
				$result['music'] = $last_track;
			}else{
				$result['music'] = null;
			}
		}else{
			$result['music'] = null;
		}
	}else{
		$result['music'] = null;
	}
	$day_of_week = date('w');
	//Get mod 2 of (current unix - time since start of last sunday divided by one week). Then add 1 to get 2||1 instead of 1||0
	$current_week = floor( (date('now') - intval($day_of_week*60*60*24)) /(60*60*24*7) ) % 2 + 1;
    if ((int) $current_week % 2 == 0){
        $current_week_val = 1;
    } else {
        $current_week_val = 2;
    };

	//We use 0 = Sunday instead of 7
	$yesterday = ($day_of_week - 1);
	$tomorrow = ($day_of_week + 1);

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

Route::get('/socan',function(){
	$now = strtotime('now');
	$socan = Socan::all();
	foreach($socan as $period){
		if( strtotime($period['socanStart']) <= $now && strtotime($period['socanEnd']) >= $now){
			return Response::json(true);
		}
	}
	return Response::json(false);
});
Route::get('/socan/{time}',function($unixtime = time){
	$now = $unixtime;
	$socan = Socan::all();
	foreach($socan as $period){
		if( strtotime($period['socanStart']) <= $now && strtotime($period['socanEnd']) >= $now){
			return Response::json(true);
		}
	}
	return Response::json(false);
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

});

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
});
