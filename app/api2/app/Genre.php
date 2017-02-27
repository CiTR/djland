<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class Genre extends Model
{
    protected $table = 'genres';
    protected $fillable = array( 'id','genre','default_crtc_category','created_by','updated_by','created_at','updated_at' );
}
