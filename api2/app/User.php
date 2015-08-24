<?php


namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{

    protected $table = 'user';
    function member(){
        return $this->belongsTo('App\Member');
    }
    function permission(){
        return $this->hasOne('App\Permission');
    }
}
