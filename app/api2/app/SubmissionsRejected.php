<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class SubmissionsRejected extends Model
{
    protected $table = 'submissions_rejected';
    protected $fillable = array( 'id','email','artist','title','submitted' );
}
