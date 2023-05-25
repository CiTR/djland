<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class Subgenre extends Model
{
  protected $table = 'subgenres';
  protected $primaryKey = 'id';
  protected $fillable = array('subgenre', 'parent_genre_id', 'created_by', 'updated_by', 'created_at', 'updated_at');

  public function genre()
  {
    $this->belongsTo('App\Genre', 'parent_genre_id');
  }
}
