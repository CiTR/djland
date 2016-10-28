<?php
use App\Friends as Friends;
use App\Member as Member;
use App\Permission as Permission;
use App\User as User;

//Fundrive Routes

Route::group(array('prefix'=>'fundrive'),function(){

	Route::group(array('middleware'=>'auth'),function(){
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
	// Fundrive amount raised total, Externally accessible
	Route::get('/total',function(){
		include_once($_SERVER['DOCUMENT_ROOT']."/headers/session_header.php");
		$donation_list = Donor::select('donation_amount')->get();
		$total = 0;
		foreach ($donation_list as $donation) {
	 	//str_replace is to deal with commas, as donation_amount is a varchar in the db and some people will enter in values with commas
			$total = $total + floatval(str_replace(",","",$donation->donation_amount));
		}
		return $total;
	});
});
