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
}
