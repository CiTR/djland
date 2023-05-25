<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Socan extends Model
{
  protected $table = 'socan';
  protected $fillable = array('socanStart', 'socanEnd');
}
