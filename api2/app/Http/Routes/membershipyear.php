<?php
	use App\MembershipYear as MembershipYear;
	Route::group(array('prefix'=>'membership_year'),function(){
		Route::get('/',function(){
			return MembershipYear::select('membership_year')->groupBy('membership_year')->orderBy('membership_year','DESC')->get();
		});
		Route::group(array('prefix'=>'rollover'),function(){
			Route::get('/',function(){
				
			});
			ROute::post('/',function(){

			});

		});
	});

?>