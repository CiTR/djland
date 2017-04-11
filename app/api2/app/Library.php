<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class Library extends Model
{
    protected $table = 'library';
    const CREATED_AT = 'added';
    const UPDATED_AT = 'modified';
    protected $fillable = array( 'id','format_id','catalog','crtc','cancon','femcon','local','playlist','compilation','digitized','status','artist','title','label','genre','added','modified','songlist_id','description','email');

    public function songs(){
        return $this->hasMany('App\LibrarySongs','library_id');
    }
}
