<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class SubmissionsArchive extends Model
{
    protected $table = 'submissions_archive';
    protected $fillable = array( 'id','contact','catalog','artist','title',
    'submitted','format_id','cancon','femcon','local','label','review_comments',
    'created_at', 'updated_at' );
}
