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
    public function nextShowTime($start_time){
        date_default_timezone_set('America/Los_Angeles');
        $time = $start_time;
        $showtimes = $this->showtimes;
        foreach($showtimes as $key=>$value){

            //Get Current week since Epoch
            $current_week = Date('W', strtotime('tomorrow',strtotime($time)));
            if ((int) $current_week % 2 == 0){
                $current_week_is_even = true;
            } else {
                $current_week_is_even = false;
            };
            
            //See if show is this week
            $this_week = ( $value['alternating'] == '0' ) || ($current_week_is_even && $value['alternating'] == '2') || (!$current_week_is_even && $value['alternating'] == '1');
            
            //Get Previous Sunday
            $last_sunday = strtotime('last sunday');
            //Offest start day by 7 if show is next week
            $startday =  (int) $value['start_day'];
            if (!$this_week) $startday +=7;
            
            //Offset for last sunday
            $showtime_if_it_was_on_last_sunday = strtotime($value['start_time'],  $last_sunday);
            //Corrected show time
            $actual_show_time = strtotime('+'.$startday.' days',$showtime_if_it_was_on_last_sunday);
            $start_time = strtotime($value['start_time'], $last_sunday );

            //If unix string is greater than the actual show time we have had our show this week. Go to next show time
            if ($actual_show_time < strtotime($time)){
                if ( $value['alternating'] == '0') {
                    $actual_show_time = strtotime('+ 1 week', $actual_show_time);
                } else {
                    $actual_show_time = strtotime('+ 2 week', $actual_show_time);
                }
            }

            //Add days since last sunday start
            $start = $last_sunday + $startday*24*60*60;
            
            //Add days since last sunday to end
            $end = strtotime($value['end_time'], strtotime($time));
            $endday = (int) $value['end_day'];
            $end = ($endday)*24*60*60 + $end;

            //Overrwite it? wtf.
            $end = strtotime($value['end_time'], $actual_show_time);
            

            $candidates []= array('start' => $actual_show_time, 'end' => $end);
        }
        //Find the minimum start time
        $min = $candidates[0];
        foreach($candidates as $i => $v){
            if ($v['start'] < $min['start']){
                $min = $candidates[$i];
            }
        }
        return $min;
    }
    public function make_show_xml(){
        include($_SERVER['DOCUMENT_ROOT'].'/config.php');
        error_reporting(E_ALL);
        //Set up FTP access
        $ftp = $ftp_xml;
        $ftp->target_path = '/';
        $ftp->url_path = 'http://playlist.citr.ca/podcasting/xml/';

        //Get objects
        $show = $this;
        $episodes = $this->podcasts;
        $file_name = $this['podcast_slug'].'.xml';

        $response['show_name'] = $this->name;
        //Remove Legacy Encoding issues
        $show = $this->getAttributes();
        foreach ($show as $field) {
            $field = htmlspecialchars(html_entity_decode($field,ENT_QUOTES),ENT_QUOTES);
            $field = str_replace("&","&amp;",$field);
            }


        $xml_head = '<?xml version="1.0" encoding="ISO-8859-1" ?><rss xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd" version="2.0" > ';
        $xml = '';
        $xml .= $xml_head;
        $xml .= '<channel>';
        $xml .= '<title>'. $show['podcast_title'] . '</title>';
        
        $xml .= '<description>' . ($show['show_desc'] ? $show['show_desc'] : "") . '</description>';
        $xml .= '<itunes:summary>' . ($show['show_desc'] ? $show['show_desc'] : "" ). '</itunes:summary>';
        if($show['host']) $xml .= '<itunes:author>' . htmlspecialchars(html_entity_decode($show['host'])). '</itunes:author>';
        $xml .= "<itunes:keywords>".$show['primary_genre_tags']."</itunes:keywords>";
        $xml .= '<itunes:subtitle>' . htmlspecialchars(html_entity_decode($show['podcast_summary'])) . '</itunes:subtitle>';
        $xml .= '<itunes:owner>' .
            '<itunes:name>CiTR</itunes:name>' .
            '<itunes:email>Technicalservices@citr.ca</itunes:email>' .
            '</itunes:owner>';
        if($show['show_img']) $xml .= '<itunes:image href="' . $show['show_img']. '"/>';

        $xml .= '<itunes:link rel="image" type="video/jpeg" href="'.$show['show_img'].'">' . $show['name'] . '</itunes:link>';

        $xml .= '<image>' .
            '<link>citr.ca</link>' .
            '<url>' . $show['show_img']. '</url>' .
            '<title>' . htmlspecialchars(html_entity_decode($show['name'])) . '</title>' .
            '</image>';
        $xml .= '<link>' .$show['website']. '</link> ';
        $xml .= '<generator>CiTR Radio Podcaster</generator>';

        //Build Each Podcast
        foreach ($episodes as $episode) {
            //Get Objects
            $playsheet = $episode->playsheet;
            $episode = $episode->getAttributes();
            if($episode['active']== 1) {
                
                //Remove Legacy Encoding issues
                foreach ($episode as $field) {
                    $field = htmlspecialchars(html_entity_decode($field,ENT_QUOTES), ENT_QUOTES);
                }
                $xml .=
                    '<item>' .
                    '<title>' . htmlspecialchars(html_entity_decode($episode['title'],ENT_QUOTES),ENT_QUOTES) . '</title>' .
                    '<pubDate>' . $episode['date'] . '</pubDate>' .
                    '<description>' . htmlspecialchars(html_entity_decode($episode['subtitle'],ENT_QUOTES),ENT_QUOTES) . '</description>' .
                    '<itunes:subtitle>' . htmlspecialchars(html_entity_decode($episode['subtitle'])) . '</itunes:subtitle>';
                $xml .= ($episode['duration'] > 0) ? '<itunes:duration>' . $episode['duration'] . '</itunes:duration> ' : '';

                $xml .=
                    '<enclosure url="' . $episode['url'] . '" length="' . $episode['length'] . '" type="audio/mpeg"></enclosure>' .
                    '<guid isPermaLink="true">' . $episode['url'] . '</guid></item>';
            }
        }
        $xml .= '</channel></rss>';

        $ftp_connection = ftp_connect($ftp->url, $ftp->port);
        if($ftp_connection){
            if(ftp_login($ftp_connection,$ftp->username ,$ftp->password)){
                //Set to passive mode? It worked...
                ftp_pasv($ftp_connection, true);
                
                //Create a temporary file to hold the xml
                $temporary_file = tmpfile();
                if($temporary_file){
                    $metaDatas = stream_get_meta_data($temporary_file);
                    $temporary_file_name = $metaDatas['uri'];
                    $num_bytes = file_put_contents($temporary_file_name,$xml);
                    //fclose($temporary_file);
                    if($num_bytes > 16){
                        //Check to see if directory exists, if not then create it
                            /*if(!ftp_chdir($ftp_connection,$ftp->target_path)){
                            ftp_chdir($ftp_connection,"/");
                            ftp_mkdir($ftp_connection, $ftp->target_path);
                        }*/
                        
                        if(ftp_fput($ftp_connection, $ftp->target_path.$file_name, $temporary_file, FTP_BINARY)){
                            //Successfully Uploaded the file
                            $response['reponse'] = array(
                                'filename' => $file_name,
                                'size' => $num_bytes,
                                'url' => $ftp->url_path.$file_name
                                );

                        }else{
                            $response['reponse'] = "Failed to write to FTP server";
                        }
                    }else{
                        $response['reponse'] = "Failed to connect to write temp file";
                    }
                    while(is_resource($temporary_file)){
                       //Handle still open
                       fclose($temporary_file);
                    }  
                }else{
                    $response['reponse'] = "Failed to open file";
                }

            }else{
                $response['reponse'] = "Failed to login";
            }
            //Make sure we close our connection
            ftp_close($ftp_connection);
            
        }
        return($response);
    }

}
