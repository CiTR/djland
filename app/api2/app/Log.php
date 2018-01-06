<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table = 'log';
    protected $primaryKey = 'log_id';
    protected $fillable = array('log_level','message','data','user');

    //Writes an error/data pair to the error log table
    //Grabs the username from the session data
    //Defaults the user field to null if not logged in, which is to indicate
    //that DJLand itself generated the error,
    //As opposed to a logged-in user action
    public static function write($log_level, $message, $data)
    {
        if (is_string($message) && is_string($data)) {
            //Member::find($_SESSION['sv_id'])->user->username returns null if not logged in
            Log::create(array(
                'log_level' => $log_level,
                'message'=>$message,
                'data'=>$data,
                'user'=>Member::find($_SESSION['sv_id'])->user->username)
            );
        } else {
            Log::create(array(
                'log_level' => $this->logLevel->find('DEBUG')->select('log_id')->get(),
                'message'=>"Bad call to Log::write (have you passed it something
                other than a string for any of it's parameters?)",
                'data'=>'',
                'user'=>Member::find($_SESSION['sv_id'])->user->username)
            );
        }
    }

    public function logLevel()
    {
        return $this->hasOne('App\LogLevel', 'log_level');
    }
}
