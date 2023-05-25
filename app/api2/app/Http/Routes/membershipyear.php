<?php
	use App\MembershipYear as MembershipYear;
	use App\Option as Option;
	Route::group(array('prefix'=>'membershipyear'),function(){
		Route::get('/',function(){
			return MembershipYear::select('membership_year')->groupBy('membership_year')->orderBy('membership_year','DESC')->get();
		});
		Route::get('/cutoff',function(){
			return Response::json(Option::select('value AS cutoff')->where('djland_option', '=', 'membership_cutoff')->first());
		});
		Route::group(array('middleware'=>'admin'),function(){
			Route::post('/rollover',function(){
				return Response::json(array('cutoff'=>MembershipYear::rollover()));
			});
			Route::post('/rollback',function(){
				return Response::json(array('cutoff'=>MembershipYear::rollback()));
			});
		});
	});
