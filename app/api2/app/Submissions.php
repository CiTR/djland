<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class Submissions extends Model
{
    protected $table = 'submissions';
    protected $fillable = array( 'id','format_id','catalog','crtc','cancon','femcon','local','playlist','compilation','digitized','status','artist','title','label','genre','tags','submitted','releasedate','assignee','reviewed','approved','decscription','location','email','songlist','credit','art_url','review_comments','staff_comment');
}
