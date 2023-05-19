<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class Library extends Model
{
    protected $table = 'library';
    const CREATED_AT = 'added';
    const UPDATED_AT = 'modified';
    protected $fillable = array( 'id','format_id','catalog','crtc','cancon',
    'femcon','local','playlist','compilation','digitized','status','artist',
    'title','label','genre','added','modified','description','email','art_url');

    public function songs(){
        //Order by track number so loops that display songs do so in order
        return $this->hasMany('App\LibrarySongs','library_id')->orderBy('track_num','asc');
    }
}
