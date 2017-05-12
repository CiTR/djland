<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Show extends Model
{
    protected $table = 'shows';
    const CREATED_AT = 'create_date';
    const UPDATED_AT = 'edit_date';
    protected $fillable = array('name', 'host', 'primary_genre_tags', 'secondary_genre_tags', 'weekday', 'start_time', 'end_time', 'pl_req', 'cc_20_req', 'cc_30_req', 'indy_req', 'fem_req', 'last_show', 'create_date', 'create_name', 'edit_date', 'edit_name', 'active', 'crtc_default', 'lang_default', 'website', 'rss', 'show_desc', 'notes', 'image', 'sponsor_name', 'sponsor_url', 'showtype', 'alerts', 'podcast_xml', 'podcast_slug', 'podcast_title', 'podcast_subtitle', 'podcast_summary', 'podcast_author');

    public function members()
    {
        return $this->belongsToMany('App\Member', 'member_show');
    }
    public function playsheets()
    {
        return $this->hasMany('App\Playsheet', 'show_id', 'id');
    }
    public function podcasts()
    {
        return $this->hasMany('App\Podcast');
    }
    public function social()
    {
        return $this->hasMany('App\Social');
    }
    public function showtimes()
    {
        return $this->hasMany('App\Showtime');
    }
    public function images()
    {
        return $this->hasMany('App\Upload', 'relation_id', 'id');
    }
    public function nextShowTime()
    {
        date_default_timezone_set('America/Los_Angeles');

        $showtimes = $this->showtimes;

        //Get Today
        $time = strtotime('now');
        //Get Day of Week (0-6)
        $day_of_week = date('w', $time);
        //Get mod 2 of (current unix minus days to last sunday) then divide by 8.64E7 * 7 to get number of weeks elapsed since epoch start.
        $current_week = floor(($time - intval($day_of_week*60*60*24)) /(60*60*24*7));
        if ((int) $current_week % 2 == 0) {
            $current_week = 1;
        } else {
            $current_week = 2;
        };

        //Get Current Time (0-23:0-59:0-59)
        $current_time = date('H:i:s', strtotime('now'));

        //Making sure if today is sunday, it does not get last sunday instead of today.
        if ($day_of_week == 0) {
            $week_0_start = strtotime('today');
            $week_1_start = strtotime('+1 week', $week_0_start);
            $week_2_start = strtotime('+1 week', $week_1_start);
        } else {
            $week_0_start = strtotime('last sunday 00:00:00');
            $week_1_start = strtotime('+1 week', $week_0_start);
            $week_2_start = strtotime('+1 week', $week_1_start);
        }

        //Constants (second conversions)
        $one_day = 24*60*60;
        $one_hour = 60*60;
        $one_minute = 60;

        foreach ($showtimes as $show_time) {
            $show_time_day_offset = ($show_time['start_day']) * $one_day;
            $show_time_hour_offset = date_parse($show_time['start_time'])['hour'] * $one_hour;
            $show_time_minute_offset = date_parse($show_time['start_time'])['minute'] * $one_minute;
            $show_time_unix_offset = $show_time_day_offset + $show_time_hour_offset + $show_time_minute_offset;

            if ($show_time['start_day'] != $show_time['end_day']) {
                $show_duration = (24 - date_parse($show_time['start_time'])['hour'] + date_parse($show_time['end_time'])['hour'])*$one_hour + (60 - date_parse($show_time['start_time'])['minute'] + date_parse($show_time['end_time'])['minute'])*$one_minute;
            } else {
                $show_end_time_unix_offset = $show_time['end_day'] * $one_day + date_parse($show_time['end_time'])['hour'] * $one_hour + date_parse($show_time['end_time'])['minute'] * $one_minute;
                $show_duration = abs($show_end_time_unix_offset - $show_time_unix_offset);
            }

            //Unix timestamp of possible show start times
            $week_0_show_unix = $week_0_start + $show_time_unix_offset;
            $week_1_show_unix = $week_1_start + $show_time_unix_offset;
            $week_2_show_unix = $week_2_start + $show_time_unix_offset;

            //DST Offset
            if ((date('I', $week_0_start)=='1') && (date('I', $week_0_show_unix) == '0')) {
                $week_0_show_unix += 3600;
            }
            if ((date('I', $week_0_start) =='0') && (date('I', $week_0_show_unix)=='1')) {
                $week_0_show_unix -= 3600;
            }
            if ((date('I', $week_1_start)=='1') && (date('I', $week_1_show_unix) == '0')) {
                $week_1_show_unix += 3600;
            }
            if ((date('I', $week_1_start) =='0') && (date('I', $week_1_show_unix)=='1')) {
                $week_1_show_unix -= 3600;
            }
            if ((date('I', $week_2_start)=='1') && (date('I', $week_2_show_unix) == '0')) {
                $week_2_show_unix += 3600;
            }
            if ((date('I', $week_2_start) =='0') && (date('I', $week_2_show_unix)=='1')) {
                $week_2_show_unix -= 3600;
            }


            // if a showtime's day has already been passed. If no, add it to week 0, if yes we have to add it to week 2 instead of week 0
                if ($show_time['start_day'] == $day_of_week || $show_time['start_day'] > $day_of_week) {
                    //Hasn't happened yet, look at weeks 0 and 1
                    if ($show_time['alternating'] == '0') {
                        //Occurs Weekly, add to this week
                        $next_show = $week_0_show_unix;
                    } elseif ($show_time['alternating'] == $current_week) {
                        //Occurs this week, add to remainder of this week
                        $next_show = $week_0_show_unix;
                    } else {
                        //Doesn't occur this week, add to week 1
                        $next_show = $week_1_show_unix;
                    }
                } else {
                    //Already occured this week
                    if ($show_time['alternating'] == '0') {
                        //Occurs weekly, add to week 1
                        $next_show = $week_1_show_unix;
                    } elseif ($show_time['alternating'] == $current_week) {
                        //Occurs this week, add to week 2
                        $next_show = $week_2_show_unix;
                    } else {
                        //Doesn't occur this week, add to week 1
                        $next_show = $week_1_show_unix;
                    }
                }

            $end = $next_show + $show_duration;
            $candidates []= array('start' => $next_show, 'end' => $end, 'week'=>$current_week);
        }
        //Find the minimum start time
        if (isset($candidates)) {
            $min = $candidates[0];
            foreach ($candidates as $i => $v) {
                if ($v['start'] < $min['start']) {
                    $min = $candidates[$i];
                }
            }
        } else {
            //No showtimes for this show, create one.
        $seconds_elapsed = strtotime('now')%$one_hour;

            if ($seconds_elapsed <=15*$one_minute) {
                $start = strtotime('now') - $seconds_elapsed;
            } elseif ($seconds_elapsed > 15*$one_minute && $seconds_elapsed <= 45*$one_minute) {
                $start = strtotime('now') - $seconds_elapsed + 30*$one_minute;
            } else {
                $start = strtotime('now') - $seconds_elapsed + $one_hour;
            }
            $end = $start + $one_hour;
            $min = array('start'=>$start,'end'=>$end,'mins'=>$seconds_elapsed/$one_minute);
        }
        return $min;
    }
    public function make_show_xml()
    {
        require(dirname($_SERVER['DOCUMENT_ROOT']).'/config.php');
        error_reporting(E_ALL);

        //Get objects
        $show = $this;
        $episodes = $this->podcasts()->where('active', '=', '1')->orderBy('date', 'desc')->get();

        $file_name = $this['podcast_slug'].'.xml';
        $url_path = 'http://playlist.citr.ca/podcasting/xml/';
        $response['show_name'] = $this->name;


        //Remove Legacy Encoding issues
        $show = $this->getAttributes();

        $show["subtitle"] = substr($show["show_desc"], 0, 200);
        foreach ($show as $k=>$field) {
            $show[$k] = Show::clean($show[$k]);
        }

        $xml[] = '<?xml version="1.0" encoding="UTF-8" ?>';
        $xml[] = '<?xml-stylesheet title="XSL_formatting" type="text/xsl" href="../xsl/podcast.xsl"?>';
        $xml[] = '<rss xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd" version="2.0" xml:lang="en-US" >';


        $xml[] = "<channel>";
        $xml[] = "<title>". $show['podcast_title'] . "</title>";
        $xml[] = "<description>" . $show['show_desc'] . "</description>";
        $xml[] = "<language>en-us</language>";
        $xml[] = "<itunes:summary>" . $show["show_desc"]. "</itunes:summary>";
        if ($show["host"]) {
            $xml[] = "<itunes:author>" . $show["host"]. "</itunes:author>";
        }
        $xml[] = "<itunes:keywords>". str_replace('/', ',', htmlspecialchars(html_entity_decode($show["primary_genre_tags"])))."</itunes:keywords>";
        $xml[] = "<itunes:subtitle>" . $show["subtitle"] . "</itunes:subtitle>";
        $xml[] = "<itunes:owner>";
        $xml[] = "<itunes:name>CiTR 101.9 Vancouver</itunes:name>";
        $xml[] = "<itunes:email>Technicalservices@citr.ca</itunes:email>";
        $xml[] = "</itunes:owner>";
        $xml[] = "<itunes:explicit>".($show['explicit'] == '0' ? 'no' : 'yes')."</itunes:explicit>";

        $xml[] = "<itunes:category text='Music'>";
        $primary_genres = preg_split('/(\/|,)/', str_replace(' ', '', $show['primary_genre_tags']));
        $xml[] = "<itunes:category text='Radio'></itunes:category>";
        foreach ($primary_genres as $genre) {
            $xml[] = "<itunes:category text='{$genre}'></itunes:category>";
        }

        $xml[] = "</itunes:category>";

        if ($show["image"]) {
            $xml[] = '<itunes:image href="'. $show["image"].'"/>';
        }

        $xml[] = "<image>";
        $xml[] = "<link>http://www.citr.ca</link>";
        $xml[] = "<url>" . $show["image"]. "</url>";
        $xml[] = "<title>" . htmlspecialchars(html_entity_decode($show["podcast_title"])) . "</title>";
        $xml[] = "</image>";
        $xml[] = "<link>" .$show["website"]. "</link> ";
        $xml[] = "<generator>CiTR Radio Podcaster</generator>";

        //Build Each Podcast
        $key = array_keys($episodes->toArray());
        $num = count($key);
        $count = 0;
        foreach ($episodes as $episode) {
            if ($count >= $num || $count >= 300) {
                break;
                //TODO:: Implement archive XML once greater than 300.
            } else {
                //Get Objects
                $playsheet = $episode->playsheet;
                $episode = $episode->getAttributes();
                if (strlen($episode['subtitle'] < 10)) {
                    $episode['subtitle'] = substr($episode['summary'], 0, 200);
                }

                foreach ($episode as $index=>$var) {
                    $episode[$index] = Show::clean($episode[$index]);
                }
                $xml[] = "<item>";
                $xml[] =  "<title>" . $episode["title"] . "</title>";
                $xml[] =  "<pubDate>" . $episode["iso_date"] . "</pubDate>";
                $xml[] =  "<itunes:subtitle>" . $episode["subtitle"] . "</itunes:subtitle>";
                $xml[] =  "<itunes:summary>" . $episode["summary"] . "</itunes:summary>";
                $xml[] =  "<description>" . $episode["summary"] . "</description>";
                $xml[] = '<enclosure url="'. $episode['url'] . '" length="' . $episode['length'] . '" type="audio/mpeg" />';
                $xml[] = '<guid isPermaLink="true">' . $episode['url'] . '</guid>';
                $xml[] = "</item>";
            }
            $count ++;
        }
        $xml[] = "</channel>";
        $xml[] = "</rss>";



        if (!$testing_environment) {
            $target_dir = $path['xml_base'].'/';
        } else {
            $target_dir = $path['test_xml_base'].'/';
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0774);
            }
        }

        //$target_dir = 'audio/'.$year.'/';
        $target_file_name = $target_dir.$file_name;
        //Open local file
        $target_file = fopen($target_file_name, 'wb');
        $num_bytes = 0;

        //If we open local file
        if ($target_file) {
            //Writing Line By Line to reduce memory footprint.
            for ($i = 0; $i < count($xml); $i ++) {
                $num_bytes += fwrite($target_file, $xml[$i]."\n");
                if ($xml[$i] == '</item>' || strpos($xml[$i], '</generator>') > 0) {
                    fwrite($target_file, "\n");
                }
            }
            $response['response'] = array(
                'filename' => $file_name,
                'size' => $num_bytes,
                'url' => $url_path.$file_name
                );
        }

        while (is_resource($target_file)) {
            //Handle still open
           fclose($target_file);
        }
        return $response;
    }
    public static function clean($string)
    {
        $string = html_entity_decode($string, ENT_QUOTES, 'UTF-8');
        #iconv ( string $in_charset , string $out_charset , string $str )
        #//IGNORE silently discards characters that can't be represented in the target charset
        $string = iconv('UTF-8', 'UTF-8//IGNORE', $string);
        #Do not use htmlentities for XML; it’s intended for HTML and not XML. XML does only know the five entities amp, lt, gt, apos and quot. But htmlentities will use a lot more (those that are registered for HTML).
        #Only 3 or 4 characters need to be escaped in a string of XML content: >, <, &, and optional ". Please read http://www.w3.org/TR/REC-xml/ "2.4 Character Data and Markup" and "4.6 Predefined Entities". THEN YOU can use 'htmlentities'
        $string = htmlspecialchars($string);
        return $string;
    }
}
