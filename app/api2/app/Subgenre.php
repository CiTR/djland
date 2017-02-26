<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class Subgenre extends Model
{
    protected $table = 'subgenres';
    protected $fillable = array( 'id','subgenre','parent_genre_id','created_by','modified_by','created_at','updated_at' );
}
