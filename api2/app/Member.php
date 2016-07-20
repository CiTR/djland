<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use DB;
use Response;
class Member extends Model
{
    protected $table = 'membership';
    const CREATED_AT = 'create_date';
    const UPDATED_AT = 'edit_date';
    protected $fillable = array( 'lastname', 'firstname', 'canadian_citizen', 'address', 'city', 'province', 'postalcode', 'member_type', 'is_new', 'alumni', 'since', 'faculty', 'schoolyear', 'student_no', 'integrate', 'has_show', 'show_name', 'primary_phone', 'secondary_phone', 'email', 'joined', 'comments', 'about', 'skills', 'status', 'exposure', 'station_tour', 'technical_training', 'programming_training', 'production_training', 'spoken_word_training');

    public function shows(){
        return $this->belongsToMany('App\Show','member_show');
    }
    public function playsheets(){
        return $this->hasManyThrough('App\Playsheet','App\Show');
    }
    public function membershipYears(){
    	return $this->hasMany('App\MembershipYear');
    }
    public function permissions(){
    	return $this->user->permissions();
    }
    public function user(){
    	return $this->hasOne('App\User');
    }
    public function isStaff(){
        return ($this->member_type == 'Staff' || $this->user->permissions['staff'] ==1 || $this->user->permissions['administrator']==1 || $this->user->permissions['operator'] ==1) ? true : false;
    }
    public static function search($parameter,$value,$paid,$year,$has_show,$order){
        /*
         * Search Array:
         * ['parameter'] (name,interest,member_type)
         * ['value']
         * ['paid'] (1,0,'both')
         * ['year'] ('all','2015/2016' ...)
         * ['has_show'] (1,0) *0 returns both.
         * ['order'] (renew_date,join_date,lastname,firstname,member_type)
         */

        //Create base query.
        $query = DB::table('membership as m')
        ->join('membership_years as my','m.id','=','my.member_id')
        ->join('member_show as ms','m.id','=','ms.member_id')
        ->select('m.id','m.firstname','m.lastname','my.membership_year','m.comments','m.primary_phone','m.email','m.member_type');

        //Handle Search Type
        switch($parameter){
            case 'name':
                $search_terms = explode(' ',$value);
                $search_term_count = sizeof($search_terms);
                if($search_term_count == 2){
                    //Assume we are searching "firstname lastname" or "lastname firstname"
                    $query->where(function($subquery)use($search_terms){
                        $subquery->whereRaw('(m.firstname LIKE "%'.$search_terms[0].'%" AND m.lastname LIKE "%'.$search_terms[1].'%")')->orWhereRaw('(m.firstname LIKE "%'.$search_terms[1].'%" and m.lastname LIKE "%'.$search_terms[0].'%")');
                    });
                }else{
                    //Assume general search
                    $query->where(function($subquery)use($value){
                        $subquery->where('m.firstname','LIKE','%'.$value.'%')->orWhere('lastname','LIKE','%'.$value.'%');
                    });
                }
                break;
            case 'interest':
                $query->where('my.'.$value,'=','1');
                break;
            case 'member_type':
                $query->where('m.member_type','=',$value);
                break;
            default:
                print_r('Default');
                break;
        }
        //Paid Status
        if($paid != 'both'){
            $query->where('my.paid','=',$paid);
        }

        //Return most recent membership year for member if no specific year chosen
        if($year != 'all'){
            $query->having('my.membership_year','=',$year);
        }else{
            $query->having('my.membership_year','=',DB::raw('(SELECT my2.membership_year FROM membership_years as my2 WHERE my2.id = my.id ORDER BY my2.membership_year DESC LIMIT 1)'));
        }

        //If filtering by show
        if($has_show == 1){
            $query->whereIn('m.id',function($subquery){
                $subquery->select('ms.member_id');
            });
        }
        //Ordering
        switch($order){
            case 'created':
                $query->orderBy('my.create_date','DESC');
                break;
            case 'firstname':
                $query->orderBy('m.firstname','ASC');
                break;
            case 'lastname':
                $query->orderBy('m.lastname','ASC');
                break;
            case 'member_type':
                $query->orderBy('m.member_type','DESC');
                break;
            default:
                $query->orderBy('m.id','DESC');
                break;
        }
        $result = $query->get();
        //echo $query->toSql();
        $permissions = Member::find($_SESSION['sv_id'])->user->permission;
        if($permissions['operator'] == 1 || $permissions['administrator']==1 || $permissions['staff'] == 1 ) return Response::json($result);
        else return "Nope";

    }
    public static function email_list($from,$to,$type,$value,$year){
        $query = Member::select('membership.email')->join('membership_years','membership_years.member_id','=','membership.id')->where('email','!=','null')->orderBy('email','desc');

        if($type == 'member_type'){
            if($value != 'all') $query->where('member_type','=',$value);
        }
        elseif($type == 'interest'){
            if($value != 'all'){
                $query->where('membership_years.'.$value,'=','1');
            }
        }
        else{
            http_response_code(400);
            return false;
        }
        if($from != null && $to != null){
            $query->where('membership.create_date','<=',$to);
            $query->where('membership.create_date','>=',$from);
        }
        if($year != 'all'){
            $query->where('membership_years.membership_year','=',$year);
        }
        return $query->get();
    }
	public static function report($start,$end){
		include($_SERVER['DOCUMENT_ROOT'].'/config.php');
		//TODO: Can Expand the api call to allow multi-year report queries.
		$membership_year = $start.'/'.$end;
		$query = DB::table('membership as m')->join('membership_years as my','my.member_id','=','m.id')->where('my.membership_year','=',$membership_year);
		//total members, and paid members
		$query->selectRaw('count(m.id) as count, sum(my.paid) as paid');
		//Count member types
		foreach($djland_member_types as $key=>$value){
			$query->selectRaw('sum(CASE WHEN m.member_type = "'.$value.'" THEN 1 ELSE 0 END) as '.$value);
		}
		//Counting interest types
		foreach($djland_interests as $key=>$value){
			if($value != 'other') $query->selectRaw('sum(my.'.$value.') as '.$value);
			else $query->selectRaw('sum(CASE WHEN ISNULL(my.other) or my.other="" THEN 0 ELSE 1 END) as other');
		}
		return $query->get();
    }
}
