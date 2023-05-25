<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LogLevel extends Model
{
  protected $table = 'loglevel';
  protected $primaryKey = 'loglevel_id';
  protected $fillable = array('level');
  public $timestamps = false;

  public function log()
  {
    $this->belongsTo('App\Log');
  }
}
