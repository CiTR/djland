<?php

namespace App;

use InvalidArgumentException;
use Illuminate\Database\Eloquent\Model;

class TypesFormat extends Model
{
  protected $table = 'types_format';
  protected $primaryKey = 'id';
  protected $fillable = array('name', 'sort');
}
