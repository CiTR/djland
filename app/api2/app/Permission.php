<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
  //
  protected $table = 'group_members';
  protected $primaryKey = 'user_id';
  public  $timestamps = false;
  protected $fillable = array('user_id', 'administrator', 'staff', 'workstudy', 'volunteer_leader', 'volunteer', 'dj', 'member');
  function user()
  {
    return $this->belongsTo('App\User');
  }
}
