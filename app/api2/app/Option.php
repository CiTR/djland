<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
	protected $table = 'djland_options';
	protected $primaryKey = 'index';
	protected $fillable = array('value','djland_option'); 

}
