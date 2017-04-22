<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class Submissions_Rejected extends Model
{
    protected $table = 'submissions_rejected';
    protected $fillable = array( 'id','email','artist','title','submitted' );
}
