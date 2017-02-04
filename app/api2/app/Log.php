<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table = 'log';
	const CREATED_AT = 'DATE_CREATED';
	//We don't update these fields ever so we keep this as NULL because laravel writes expect it
    const UPDATED_AT = NULL;
    protected $fillable = array('index','error','data','user','DATE_CREATED');

	//Writes an error/data pair to the error log table
	//Grabs the username from the session data
	//Defaults the user field to null if not logged in, which is to indicate that DJLand itself generated the error,
	//As opposed to a logged-in user action
	public static function write($error, $data){
		if(is_string($error) && is_string($data)){
			//Member::find($_SESSION['sv_id'])->user->username returns null if not logged in
			$error= array('error'=>$error, 'data'=>$data,'user'=>Member::find($_SESSION['sv_id'])->user->username);
			Log::create($error);
		}
		else{
			$error= array('error'=>"Bad call to Log::write (have you passed it something other than a string for any of it's parameters?)", 'data'=>'','user'=>Member::find($_SESSION['sv_id'])->user->username);
			Log::create($error);
		}
	}
}
