<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Socan extends Model
{
	protected $table = 'socan';
	protected $primaryKey = 'idSocan';
	protected $guarded = 'idSocan';
	protected $fillable = array('socanStart','socanEnd');
}
