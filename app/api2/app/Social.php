<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Social extends Model
{
	protected $table = 'social';
    //
    const CREATED_AT = null;
    const UPDATED_AT = null;
    protected $primaryKey = 'id';
	protected $fillable = array('id','show_id','social_name','social_url');
    public function shows(){
        return $this->belongsTo('App\Show','id','show_id');
    }
}
