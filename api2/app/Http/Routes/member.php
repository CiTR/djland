<?php
use App\Option as Option;
use App\User as User;
use App\Member as Member;
use App\MembershipYear as MembershipYear;
use App\Permission as Permission;
Route::group(['middleware' => 'auth'], function(){
	// Old Member Creation Routes
	//TODO:: Move these into rest format.
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

		Route::get('/report/{year_start}/{year_end}',function($start=start,$end=end){
			return Member::report($start,$end);
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
			//Returns if the user has administrator priveledges or not.
			Route::get('/staff',function($id){
				return Response::json(Member::find($id)->isStaff());
			});
			Route::post('/comments',function($id){
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
			Route::group(array('prefix'=>'permission'),function($id){
				Route::post('/',function($id){
					$permission = Member::find($id)->user->permission;
					$permissions = Member::find($_SESSION['sv_id'])->user->permission;
					if($permissions['operator'] == 1 || $permissions['administrator']==1 || $permissions['staff'] == 1 ) return $permission->update((array) json_decode(Input::get()['permission'] )) ? "true": "false";
					else return "Nope";
				});
				Route::get('/',function($member_id = id){
					$permission_levels = Member::find($member_id)->user->permission;
					unset($permission_levels->user_id);
					$permission = new stdClass();
					$permission->permissions = $permission_levels;
					return $permission_levels;
				});
			});
			Route::group(array('prefix'=>'years'),function($id){
				Route::get('/',function($id){
					$m_years = Member::find($id)->membershipYears()->orderBy('membership_year','desc')->get();
					foreach($m_years as $year){
						$years[$year->membership_year] = $year;
					}
					return Response::json($years);
				});
				Route::post('/',function($id){
					$m = Member::find($id);
					$m_years = (array) json_decode(Input::get()['years']);
					$years = $m->membershipYears;
					foreach($years as $year){
						$year -> update( (array) $m_years[$year->membership_year]);
					}
					return "true";
				});
			});

			Route::post('password',function($id){
				$m = Member::find($id);
				$user = $m->user;
				$user->password = password_hash(Input::get()['password'],PASSWORD_DEFAULT);
				$permissions = Member::find($_SESSION['sv_id'])->user->permission;
				if($permissions['operator'] == 1 || $permissions['administrator']==1 || $permissions['staff'] == 1  || $id = $_SESSION['sv_id']) return $user->save() ? "true":"false";
				else return "Nope";
			});

			Route::get('shows', function($member_id = id){
				$shows = new StdClass();
				if(Member::find($member_id)->member_type == 'Staff'){
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

				$shows = new stdClass();
				if(Member::find($member_id)->isStaff()){
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
});
