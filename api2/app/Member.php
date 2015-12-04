<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $table = 'membership';
    const CREATED_AT = 'create_date';
    const UPDATED_AT = 'edit_date';
    protected $fillable = array( 'lastname', 'firstname', 'canadian_citizen', 'address', 'city', 'province', 'postalcode', 'member_type', 'is_new', 'alumni', 'since', 'faculty', 'schoolyear', 'student_no', 'integrate', 'has_show', 'show_name', 'primary_phone', 'secondary_phone', 'email', 'joined', 'comments', 'about', 'skills', 'status', 'exposure', 'station_tour', 'technical_training', 'programming_training', 'production_training', 'spoken_word_training');

    public function shows(){
        return $this->belongsToMany('App\Show','member_show');
    }
    public function years(){
    	return $this->hasMany('App\MembershipYear');
    }
    public function permissions(){
    	return $this->user->permissions();
    }
    public function user(){
    	return $this->hasOne('App\User');
    }
    public function is_admin(){
        $permissions = $this->user->permission()->first();
        return $permissions['administrator'] || $permissions['staff'];
    }
}
