<?php

use App\Show as Show;

//Helpers
use App\Showtime as Showtime;
use App\Host as Host;
use App\Social as Social;
use App\Member as Member;
use App\Upload as Upload;
use App\Socan as Socan;

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
	//Return shows that are active
	Route::get('/active',function(){
		return Show::select('id','name')->where('active','=','1')->orderBy('name','ASC')->get();
	});
	//Return alerts for all shows
	Route::get('/alert',function(){
			return Show::select('id','name','edit_date','alerts')
			->where('alerts','!=','')->where('alerts','!=','NULL')->where('active','=','1')
			->orderBy('edit_date','DESC')->get();
	});
	//Searching by Show ID
	Route::group(array('prefix'=>'{id}'),function($id=id){
		//Returns show object including social, showtimes
		Route::get('/',function($id){
			if(!$show = Show::find($id)) return null;

			$show->social = Show::find($id)->social;
			$show->show_times = Show::find($id)->showtimes;
			return Response::json($show);
		});
		Route::post('/',function($id){
			return Response::json(Show::find($id)->update((array) Input::get()['show']));
		});
		Route::group(array('prefix'=>'image'),function($id){
			Route::get('/',function($id){
				return Response::json(Show::find($id)->images);
			});
			Route::post('/',function($id){
				if(Input::hasFile('image')){
					$file = Input::file('image');
					try{
						$upload = Upload::create(array('category'=>'show_image','relation_id'=>$id,'size'=>$file->getClientSize(),'file_type'=>$file->getClientOriginalExtension()));
						return Response::json($upload->uploadImage($file));
					}catch(Exception $iae){
						return Response::json($iae->getMessage(),500);
					}
				}else{
					return Response::json('No File',500);
				}
			});
		});
		Route::group(array('prefix'=>'social'),function($id){
			//return all social entries for a show
			Route::get('/',function($id){
				return Response::json(Show::find($id)->social);
			});
			//Requires active session
			Route::group(['middleware' => 'auth'], function(){
				//Create a new social entry for a show
				Route::put('/',function($id){
					$social = Social::create(Input::get()['social']);
					return Response::json(Show::find($id)->attach($social));
				});
				Route::group(array('prefix'=>'{social_id}'),function($id,$social_id=null){
					//Update a social entry
					Route::post('/',function($id,$social_id){
						return Response::json(Social::find($social_id)->update((array) Input::get()['social']));
					});
					//Delete a social entry from a show
					Route::delete('/',function($id,$social_id){
						return Response::json(Social::delete($social_id));
					});
				});
			});
		});
		//Requires staff permission level to access
		Route::group(array('middleware'=>'staff'),function($id){
			Route::group(array('prefix'=>'owner'),function($id){
				//Get list of show owners
				Route::get('/',function($id){
					return Response::json(Show::find($id)->members()->select('membership.id','firstname','lastname')->get());
				});
				//Add a new owner to the show
				Route::put('/',function($id){
					return Response::json(Show::find($id)->members->attach(Input::get()['member_id']));
				});
				Route::group(array('prefix'=>'{member_id}'),function($id,$member_id=null){
					Route::delete('/',function($id,$member_id){
						return Response::json(Show::find($id)->members()->detach($member_id));
					});
				});
			});
		});


		// //Requires active session
		// Route::group(['middleware' => 'auth'], function(){
		// 	Route::get('/owners',function($id=id){
		// 		$members = Show::find($id)->members;
		// 		$owners = new stdClass();
		// 		$owners->owners = [];
		// 		foreach ($members as $member) {
		// 			$owners->owners[] = ['id'=>$member->id,'firstname'=>$member->firstname,'lastname'=>$member->lastname];
		// 		}
		// 		if(Member::find($_SESSION['sv_id'])->isStaff() ) return Response::json($owners);
		// 		return "Nope";
		// 	});
		// });


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
