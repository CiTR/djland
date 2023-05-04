<?php


namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{

    protected $table = 'user';
    const CREATED_AT = 'create_date';
    const UPDATED_AT = 'edit_date';
    protected $fillable = array('member_id', 'username', 'password', 'status', 'create_date', 'edit_date', 'edit_name', 'login_fails');
    function member(){
        return $this->belongsTo('App\Member');
    }
    function permission(){
        return $this->hasOne('App\Permission');
    }
}
