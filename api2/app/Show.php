<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class Show extends Model
{
    protected $table = 'shows';
    const CREATED_AT = 'create_date';
    const UPDATED_AT = 'edit_date';
    protected $fillable = array('name', 'host', 'primary_genre_tags', 'secondary_genre_tags', 'weekday', 'start_time', 'end_time', 'pl_req', 'cc_req', 'indy_req', 'fem_req', 'last_show', 'create_date', 'create_name', 'edit_date', 'edit_name', 'active', 'crtc_default', 'lang_default', 'website', 'rss', 'show_desc', 'notes', 'show_img', 'sponsor_name', 'sponsor_url', 'showtype', 'alerts', 'podcast_xml', 'podcast_slug', 'podcast_title', 'podcast_subtitle', 'podcast_summary', 'podcast_author');
    
    public function members(){
        return $this->belongsToMany('App\Member','member_show');
    }
    public function playsheets(){
    	return $this->hasMany('App\Playsheet','show_id','id');
    }
    public function podcasts(){
        return $this->hasMany('App\Podcast');
    }
    public function social(){
        return $this->hasMany('App\Social');
    }
    public function showtimes(){
        return $this->hasMany('App\Showtime');
    }
    public function nextShowTime(){
        date_default_timezone_set('America/Los_Angeles');
        
        $showtimes = $this->showtimes;

        //Get Today
        $time = strtotime('now');
        //Get Day of Week (0-6)
        $day_of_week = date('w',$time);
        //Get mod 2 of (current unix - time since start of last sunday divided by one week). Then add 1 to get 2||1 instead of 1||0
        $current_week = floor( ($time - intval($day_of_week*60*60*24)) /(60*60*24*7) ) % 2 + 1;

        //Get Current Time (0-23:0-59:0-59)
        $current_time = date('H:i:s',strtotime('now'));
        
        //Making sure if today is sunday, it does not get last sunday instead of today.
        if($day_of_week == 0){
            $week_0_start = strtotime('today');
            $week_1_start = strtotime('+1 week',$week_0_start);
            $week_2_start = strtotime('+1 week',$week_1_start);
        }else{
            $week_0_start = strtotime('last sunday 00:00:00');
            $week_1_start = strtotime('+1 week',$week_0_start);
            $week_2_start = strtotime('+1 week',$week_1_start);
        }

        //Constants (second conversions)
        $one_day = 24*60*60;
        $one_hour = 60*60; 
        $one_minute = 60;
        
        foreach($showtimes as $show_time){                      
            $show_time_day_offset = ($show_time['start_day']) * $one_day;
            $show_time_hour_offset = date_parse($show_time['start_time'])['hour'] * $one_hour;
            $show_time_minute_offset = date_parse($show_time['start_time'])['minute'] * $one_minute;            
            $show_time_unix_offset = $show_time_day_offset + $show_time_hour_offset + $show_time_minute_offset;
            
            if($show_time['start_day'] != $show_time['end_day']){
                $show_duration = (24 - date_parse($show_time['start_time'])['hour'] + date_parse($show_time['end_time'])['hour'])*$one_hour + (60 - date_parse($show_time['start_time'])['minute'] + date_parse($show_time['end_time'])['minute'])*$one_minute;
            }else{
                $show_end_time_unix_offset = $show_time['end_day'] * $one_day + date_parse($show_time['end_time'])['hour'] * $one_hour + date_parse($show_time['end_time'])['minute'] * $one_minute;
                $show_duration = abs($show_end_time_unix_offset - $show_time_unix_offset);
            }

            //Unix timestamp of possible show start times
            $week_0_show_unix = $week_0_start + $show_time_unix_offset;
            $week_1_show_unix = $week_1_start + $show_time_unix_offset;
            $week_2_show_unix = $week_2_start + $show_time_unix_offset;

            //DST Offset
            if( (date('I',$week_0_show_unix)=='0') ){
                //$week_0_show_unix += 3600;
            }
            if( (date('I',$week_1_show_unix)=='0') ){
                //$week_1_show_unix += 3600;
            }
            if( (date('I',$week_2_show_unix)=='0') ){
                //$week_2_show_unix += 3600;
            }

            // if a showtime's day has already been passed. If no, add it to week 0, if yes we have to add it to week 2 instead of week 0
                if( $show_time['start_day'] == $day_of_week || $show_time['start_day'] > $day_of_week){
                    //Hasn't happened yet, look at weeks 0 and 1
                    if($show_time['alternating'] == '0'){
                        //Occurs Weekly, add to this week
                        $next_show = $week_0_show_unix;
                    }else if($show_time['alternating'] == $current_week){
                        //Occurs this week, add to remainder of this week
                        $next_show = $week_0_show_unix;
                    }else{
                        //Doesn't occur this week, add to week 1
                        $next_show = $week_1_show_unix;
                    }

                }else{
                    //Already occured this week
                    if($show_time['alternating'] == '0'){
                        //Occurs weekly, add to week 1
                        $next_show = $week_1_show_unix;
                    }else if($show_time['alternating'] == $current_week){
                        //Occurs this week, add to week 2
                        $next_show = $week_2_show_unix;
                    }else{
                        //Doesn't occur this week, add to week 1
                        $next_show = $week_1_show_unix;
                    }
                }

            $end = $next_show + $show_duration;
            $candidates []= array('start' => $next_show, 'end' => $end);
        }
        //Find the minimum start time
        if(isset($candidates)){
            $min = $candidates[0];
            foreach($candidates as $i => $v){
                if ($v['start'] < $min['start']){
                    $min = $candidates[$i];
                }
            }   
        }else{
            $min = null;
        }
        
        
        return $min;
    }
    public function make_show_xml(){
        include($_SERVER['DOCUMENT_ROOT'].'/config.php');
        error_reporting(E_ALL);

        //Get objects
        $show = $this;
        $episodes = $this->podcasts()->orderBy('date','desc')->get();
        
        $file_name = $this['podcast_slug'].'.xml';
        $url_path = 'http://playlist.citr.ca/podcasting/xml/';
        $response['show_name'] = $this->name;

    
        //Remove Legacy Encoding issues
        $show = $this->getAttributes();
        foreach ($show as $field) {
            $field = Show::clean($field);
            }

        $xml[] = '<?xml version="1.0" encoding="ISO-8859-1" ?>';
        $xml[] = '<?xml-stylesheet title="XSL_formatting" type="text/xsl" href="../xsl/podcast.xsl"?>';
        $xml[] = '<rss xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd" version="2.0" >';
        $xml[] = "<channel>";
        $xml[] = "<title>". htmlspecialchars(html_entity_decode($show['podcast_title'])) . "</title>";
        
        $xml[] = "<description>" . htmlspecialchars(html_entity_decode($show['show_desc'])) . "</description>";
        $xml[] = "<itunes:summary>" . htmlspecialchars(html_entity_decode($show["show_desc"])). "</itunes:summary>";
        if($show["host"]) $xml[] = "<itunes:author>" . htmlspecialchars(html_entity_decode($show["host"])). "</itunes:author>";
        $xml[] = "<itunes:keywords>". str_replace('/',',',htmlspecialchars(html_entity_decode($show["primary_genre_tags"])))."</itunes:keywords>";
        $xml[] = "<itunes:subtitle>" . htmlspecialchars(html_entity_decode($show["podcast_summary"])) . "</itunes:subtitle>";
        $xml[] = "<itunes:owner>";
        $xml[] = "<itunes:name>CiTR 101.9 Vancouver</itunes:name>";
        $xml[] = "<itunes:email>Technicalservices@citr.ca</itunes:email>";
        $xml[] = "</itunes:owner>";
        if($show["show_img"]) $xml[] = '<itunes:image href="'. $show["show_img"].'"/>';

        $xml[] = '<itunes:link rel="image" type="video/jpeg" href="'.$show["show_img"].'">'. $show["podcast_title"] . '</itunes:link>';

        $xml[] = "<image>";
        $xml[] = "<link>www.citr.ca</link>";
        $xml[] = "<url>" . $show["show_img"]. "</url>";
        $xml[] = "<title>" . htmlspecialchars(html_entity_decode($show["podcast_title"])) . "</title>";
        $xml[] = "</image>";
        $xml[] = "<link>" .$show["website"]. "</link> ";
        $xml[] = "<generator>CiTR Radio Podcaster</generator>";

        //Build Each Podcast
        $key = array_keys($episodes->toArray());
        $num = count($key);
        if($testing_environment) $num = 6;
        else $num = 100;
        $i = 0;
        $count = 0;
        while( $count < $num ) {
            $episode = $episodes[$key[$i]];


            //Get Objects
            $playsheet = $episode->playsheet;
            $episode = $episode->getAttributes();
            if($episode["active"]== '1' || $episode["active"]!= 0) {
                if($testing_environment) echo $episode['date']."\n".$count."\n";
                $count ++;
                foreach($episode as $index=>$var){
                   $episode[$index] = Show::clean($episode[$index]); 
                }

                $xml[] = "<item>";
                $xml[] =  "<title>" . $episode["title"] . "</title>";
                $xml[] =  "<pubDate>" . $episode["iso_date"] . "</pubDate>";
                $xml[] =  "<description>" . $episode["summary"] . "</description>";
                $xml[] =  "<itunes:subtitle>" . substr($episode["summary"],0,255) . "</itunes:subtitle>";
                $xml[] =  "<itunes:summary>" . $episode["summary"] . "</itunes:summary>";
                $xml[] =  "<summary>" . $episode["summary"] . "</summary>";
                $xml[] = '<enclosure url="'. $episode['url'] . '" length="' . $episode['length'] . '" type="audio/mpeg" />';
                $xml[] = '<guid ispermaLink="true">' . $episode['url'] . '</guid>';
                $xml[] = "</item>";

            }
            $i++;
        }
        $xml[] = "</channel>";
        $xml[] = "</rss>";

        

        if(!$testing_environment){
            $target_dir = '/home/playlist/public_html/podcasting/xml/';
         }else{
            $target_dir = $_SERVER['DOCUMENT_ROOT'].'/test-xml/';
        }

        //$target_dir = 'audio/'.$year.'/';     
        $target_file_name = $target_dir.$file_name;
        //Open local file
        $target_file = fopen($target_file_name,'wb');
        $num_bytes = 0;
        
        //If we open local file
        if($target_file){
            //Writing Line By Line to reduce memory footprint.
            for($i = 0; $i < count($xml); $i ++){
                $num_bytes += fwrite($target_file, $xml[$i]."\n");
                if($xml[$i] == '</item>' || strpos($xml[$i],'</generator>') > 0) fwrite($target_file, "\n");
            }
             $response['reponse'] = array(
                'filename' => $file_name,
                'size' => $num_bytes,
                'url' => $url_path.$file_name
                );
        }
        
        while(is_resource($target_file)){
           //Handle still open
           fclose($target_file);
        }
        return $response;  
    }
    public static function clean($string){
        $find = array('/&/',"/'/",'/"/');
        $replace = array('&amp;',"&apos;","&quot;");
        $string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
        $string = preg_replace($find,$replace,$string);
        return $string;
    }

}
