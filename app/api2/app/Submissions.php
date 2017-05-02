<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class Submissions extends Model
{
    protected $table = 'submissions';
    protected $guarded = array('id');
    protected $fillable = array('format_id','catalog','crtc','cancon','femcon',
    'local','playlist','compilation','digitized','status','is_trashed','artist',
    'title','label','genre','tags','submitted','releasedate','assignee',
    'reviewed','approved','description','location','email','songlist','credit',
    'art_url','review_comments','staff_comment','created_at','updated_at' );

    public function songs()
    {
        //Order by track number so loops that display songs do so in order
        return $this->hasMany('App\SubmissionsSongs', 'submission_id')->orderBy('track_num', 'asc');
    }
}
