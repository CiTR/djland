<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class Library extends Model
{
    protected $table = 'library';
    protected $fillable = array( 'id','format_id','catalog','crtc','cancon','femcon','local','playlist','compilation','digitized','status','artist','title','label','genre','added','modified','songlist_id','description','email' );
}
