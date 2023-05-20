<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class LibraryEdit extends Model
{
    protected $table = 'library_edits';
    protected $fillable = array( 'id','format_id','old_format_id','catalog','old_catalog','cancon','old_cancon','femcon','old_femcon','local','old_local','playlist','old_playlist','compilation','old_compilation','digitized','old_digitized','status','old_status','artist','old_artist','title','old_title','label','old_label','genre','old_genre','library_id','updated_at','created_at' );
}
