<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

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

      # request to "https://new.citr.ca/api/schedule?title="+show_title); using curl
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, "https://new.citr.ca/api/schedule?title=" . urlencode($this->name));
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      $file_contents = curl_exec($ch);
      curl_close($ch);

      $schedule = json_decode($file_contents);

      $default_start = strtotime(date('Y-m-d H:00:00'));
      $default_end = $default_start + 3600;

      $length = is_array($schedule) ? count($schedule) : 0;

      if ($length > 0){
        $start = strtotime($schedule[0]->schedule[0]->start);
        $end = strtotime($schedule[0]->schedule[0]->end);
      } else {
        $start = $default_start;
        $end = $default_end;
      }
    
    return array('start' => $start, 'end' => $end);
  }
  public function make_show_xml()
  {
    require(dirname($_SERVER['DOCUMENT_ROOT']) . '/config.php');
    error_reporting(E_ALL);

    //Get objects
    $show = $this;
    $episodes = $this->podcasts()->where('active', '=', '1')->orderBy('date', 'desc')->get();

    if ($this->podcast_slug == '') {
      // use today's date and time instead of the slug
      // include time
      $file_name = 'podcast-' . date('Y-m-d-H-i-s') . '.xml';
    } else {
      $file_name = $this['podcast_slug'] . '.xml';
    }
    $response['show_name'] = $this->name;


    //Remove Legacy Encoding issues
    $show = $this->getAttributes();

    $show["subtitle"] = substr($show["show_desc"], 0, 200);
    foreach ($show as $k => $field) {
      $show[$k] = Show::clean($show[$k]);
    }

    //Ensure HTTPS isn't used for the itunes image
    if (strpos($show['image'], 'https://djland.') === 0) {
      $show['image'] = str_replace('https://djland', 'http://djland', $show['image']);
    }

    $xml[] = '<?xml version="1.0" encoding="UTF-8" ?>';
    $xml[] = '<?xml-stylesheet title="XSL_formatting" type="text/xsl" href="../xsl/podcast.xsl"?>';
    $xml[] = '<rss xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd" version="2.0" xml:lang="en-US" >';


    $xml[] = "<channel>";
    $xml[] = "<title>" . $show['podcast_title'] . "</title>";
    $xml[] = "<description>" . $show['show_desc'] . "</description>";
    $xml[] = "<language>en-us</language>";
    $xml[] = "<itunes:summary>" . $show["show_desc"] . "</itunes:summary>";
    if ($show["host"]) {
      $xml[] = "<itunes:author>" . $show["host"] . "</itunes:author>";
    }
    $xml[] = "<itunes:keywords>" . str_replace('/', ',', htmlspecialchars(html_entity_decode($show["primary_genre_tags"]))) . "</itunes:keywords>";
    $xml[] = "<itunes:subtitle>" . $show["subtitle"] . "</itunes:subtitle>";
    $xml[] = "<itunes:owner>";
    $xml[] = "<itunes:name>CiTR 101.9 Vancouver</itunes:name>";
    $xml[] = "<itunes:email>Technicalservices@citr.ca</itunes:email>";
    $xml[] = "</itunes:owner>";
    $xml[] = "<itunes:explicit>" . ($show['explicit'] == '0' ? 'no' : 'yes') . "</itunes:explicit>";

    $xml[] = "<itunes:category text='Music'>";
    $primary_genres = preg_split('/(\/|,)/', str_replace(' ', '', $show['primary_genre_tags']));
    $xml[] = "<itunes:category text='Radio'></itunes:category>";
    foreach ($primary_genres as $genre) {
      $xml[] = "<itunes:category text='{$genre}'></itunes:category>";
    }

    $xml[] = "</itunes:category>";

    if ($show["image"]) {
      $xml[] = '<itunes:image href="' . $show["image"] . '"/>';
    }

    $xml[] = "<image>";
    $xml[] = "<link>https://www.citr.ca</link>";
    $xml[] = "<url>" . $show["image"] . "</url>";
    $xml[] = "<title>" . htmlspecialchars(html_entity_decode($show["podcast_title"])) . "</title>";
    $xml[] = "</image>";
    $xml[] = "<link>" . $show["website"] . "</link> ";
    $xml[] = "<generator>CiTR Radio Podcaster</generator>";

    //Build Each Podcast
    $key = array_keys($episodes->toArray());
    $num = count($key);
    $count = 0;
    foreach ($episodes as $episode) {
      if ($count >= $num || $count >= 600) {
        break;
        //TODO:: Implement archive XML once greater than 300.
      } else {
        //Get Objects
        $playsheet = $episode->playsheet;
        $episode = $episode->getAttributes();
        if (strlen($episode['subtitle'] < 10)) {
          $episode['subtitle'] = substr($episode['summary'], 0, 200);
        }

        foreach ($episode as $index => $var) {
          $episode[$index] = Show::clean($episode[$index]);
        }
        $xml[] = "<item>";
        $xml[] =  "<title>" . $episode["title"] . "</title>";
        $xml[] =  "<pubDate>" . $episode["iso_date"] . "</pubDate>";
        $xml[] =  "<itunes:subtitle>" . $episode["subtitle"] . "</itunes:subtitle>";
        $xml[] =  "<itunes:summary>" . $episode["summary"] . "</itunes:summary>";
        $xml[] =  "<description>" . $episode["summary"] . "</description>";
        $xml[] = '<enclosure url="' . $episode['url'] . '" length="' . $episode['length'] . '" type="audio/mpeg" />';
        $xml[] = '<guid isPermaLink="true">' . $episode['url'] . '</guid>';
        $xml[] = "</item>";
      }
      $count++;
    }
    $xml[] = "</channel>";
    $xml[] = "</rss>";



    $target_dir = $path['xml_base'] . '/';
    $url_path   =  $url['xml_base'] . '/';
    if (!file_exists($target_dir)) {
      mkdir($target_dir, 0774);
    }
    //log the dir
    //$target_dir = 'audio/'.$year.'/';
    $target_file_name = $target_dir . $file_name;

    if (file_exists($target_file_name)) {
      $target_file = fopen($target_file_name, 'wb');
    } else {
      $target_file = fopen($target_file_name, 'w');
    }
    $num_bytes = 0;

    //If we open local file
    if ($target_file) {
      //Writing Line By Line to reduce memory footprint.
      for ($i = 0; $i < count($xml); $i++) {
        $num_bytes += fwrite($target_file, $xml[$i] . "\n");
        if ($xml[$i] == '</item>' || strpos($xml[$i], '</generator>') > 0) {
          fwrite($target_file, "\n");
        }
      }
      $response['response'] = array(
        'filename' => $file_name,
        'size' => $num_bytes,
        'url' => $url_path . $file_name
      );
      $this->podcast_xml = $response['response']['url'];
    }

    while (is_resource($target_file)) {
      //Handle still open
      fclose($target_file);
    }
    $this->save();
    file_put_contents("/tmp/djland-sync", "");
    
    return $response;
  }
  public static function clean($string)
  {
    $string = htmlentities($string, ENT_QUOTES, 'UTF-8');
    $string = html_entity_decode($string, ENT_QUOTES, 'UTF-8');
    #iconv ( string $in_charset , string $out_charset , string $str )
    #//IGNORE silently discards characters that can't be represented in the target charset
    $string = iconv('UTF-8', 'UTF-8//IGNORE', $string);
    #Do not use htmlentities for XML; it’s intended for HTML and not XML. XML does only know the five entities amp, lt, gt, apos and quot. But htmlentities will use a lot more (those that are registered for HTML).
    #Only 3 or 4 characters need to be escaped in a string of XML content: >, <, &, and optional ". Please read https://www.w3.org/TR/REC-xml/ "2.4 Character Data and Markup" and "4.6 Predefined Entities". THEN YOU can use 'htmlentities'
    $string = htmlspecialchars($string);
    return $string;
  }
}
