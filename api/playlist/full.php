<?php
/**
 * Created by PhpStorm.
 * User: brad
 * Date: 3/5/15
 * Time: 8:23 PM
 */

$LIST_OF_BOOLEANS = array(

            "is_playlist",
            "is_canadian",
            "is_fem",
            "is_theme",
            "is_background",
            "is_part",
            "is_inst",
            "is_hit"
);

require_once('../api_common.php');

if (isset($_GET['ID'])){
  $id = $_GET['ID'];
} else {
  $error .= ' please supply playlist id ( /playlist?ID=## ) ';
  $blame_request = true;
}

if (!is_numeric($id)){
  $error .= ' ID parameter should not be a string ';
  $blame_request = true;
}

if ($error != '') finish();

$rawdata = array();


$query_for_playsheet = 'SELECT playlists.*,
            hosts.name as host_name
            FROM playlists
            LEFT JOIN hosts on hosts.id = playlists.host_id
            WHERE playlists.id = '.$id;

if ( $result = mysqli_query($db,$query_for_playsheet)){
  $rawdata['playlist'] = mysqli_fetch_assoc($result);
} else {
  $error .= mysqli_error($db);
  finish();
}

if (is_numeric($rawdata['playlist']['podcast_episode'])){

    $query_for_podcast = 'SELECT * FROM podcast_episodes
            WHERE id = '.$rawdata['playlist']['podcast_episode'];

    if ($result = mysqli_query($db,$query_for_podcast)){
      $rawdata['playlist']['podcast'] = mysqli_fetch_assoc($result);
    }

}

$query_for_ads = 'SELECT
            *
            FROM adlog
          WHERE adlog.playsheet_id ='.$id;

if ($result = mysqli_query($db,$query_for_ads)){
    $rawdata['ads'] = array();
  while ($row = mysqli_fetch_assoc($result)){
    $rawdata['ads'] []= $row;
  }
} else {
  $error .= mysqli_error($db);
  finish();
}



$query_for_songs = 'SELECT *

          FROM playitems
          WHERE playitems.playsheet_id ='.$id.'
          ORDER BY id DESC';


if ( $result = mysqli_query($db, $query_for_songs) ) {
  $rawdata['plays'] = array();

  while ($row = mysqli_fetch_assoc($result)) {

    $song_q = 'SELECT * from songs where ID = '.$row['song_id'];

    if ($result2 = mysqli_query($db, $song_q)){
      while ($row2 = mysqli_fetch_assoc($result2)){
        $row['song'] = $row2;
      }
    }

    $rawdata['plays'] []= $row;


  }

  foreach($rawdata['plays'] as $i => $play){

    foreach($LIST_OF_BOOLEANS as $j => $bool){
      $rawdata['plays'][$i][$bool] = ($play[$bool] == 1)? true : false;

    }
    foreach($rawdata['plays'][$i]['song'] as $j => $song_item){
      $rawdata['plays'][$i]['song'][$j] = convertEntities($song_item);
    }


    $rawdata['plays'][$i]['lang'] = convertEntities($play['lang']);
error_reporting(E_ALL);
    $rawdata['plays'][$i]['insert_song_start_hour'] = str_pad(strval($play['insert_song_start_hour']), 2, "0", STR_PAD_LEFT);
    $rawdata['plays'][$i]['insert_song_start_minute'] = str_pad(strval($play['insert_song_start_minute']), 2, "0", STR_PAD_LEFT);
    $rawdata['plays'][$i]['insert_song_length_minute'] = str_pad(strval($play['insert_song_length_minute']), 2, "0", STR_PAD_LEFT);
    $rawdata['plays'][$i]['insert_song_length_second'] = str_pad(strval($play['insert_song_length_second']), 2, "0", STR_PAD_LEFT);

  }

  foreach($rawdata['ads'] as $i => $ad){

    $rawdata['ads'][$i]['played'] = ($ad['played'] == 1) ? true : false;

    $rawdata['ads'][$i]['name'] = convertEntities($ad['name']);

    if(is_numeric($ad['name'])){

      $ad_q = "SELECT artist, title FROM songlist WHERE ID = ".$ad['name'];
          if( $result = mysqli_query($mysqli_sam,$ad_q)){
            $sam_ad = $result->fetch_assoc();
            if (is_array($sam_ad)) {

              $rawdata['ads'][$i]['name'] = /*$ad['artist'].' - '.*/$sam_ad['title'];
            }
          }
        }


    }

  $start_unix = strtotime($rawdata['playlist']['start_time']);
  $start = getdate($start_unix);
  $start_h = $start['hours'];

  $end_unix = strtotime($rawdata['playlist']['end_time'], $start_unix);

  $end_components = explode(':',$rawdata['playlist']['end_time']);
  $end_unix = 60*60*$end_components[0] + 60*$end_components[1] + $end_components[2];

  $end_h = $end_components[0];

  if($end_h < $start_h){
    // add $end_unix to 24h + $start unix
    $end_unix = date('Y-m-d H:i:s',strtotime('today + 1 day', $start_unix) + $end_unix );
  } else {
    // add $end_unix to $start_unix
    $end_unix = date('Y-m-d H:i:s',strtotime('today', $start_unix) + $end_unix );
  }

  $rawdata['playlist']['end_time'] = $end_unix;



  if (array_key_exists('host',$rawdata['playlist']) &&  ($rawdata['playlist']['host'] !='') ){

  } else {
    $rawdata['playlist']['host'] = $rawdata['playlist']['host_name'];

  }
  unset($rawdata['playlist']['host_id']);
  unset($rawdata['playlist']['host_name']);



  $rawdata['playlist']['spokenword_minutes'] = $rawdata['playlist']['spokenword_duration']%60;

  $rawdata['playlist']['spokenword_hours'] = ($rawdata['playlist']['spokenword_duration'] - $rawdata['playlist']['spokenword_minutes'])/60;

  $rawdata['playlist']['id'] = $id;


  $rawdata['playlist']['start_time'] = Date(DATE_RFC2822,strtotime($rawdata['playlist']['start_time']));
  $rawdata['playlist']['end_time'] = Date(DATE_RFC2822,strtotime($rawdata['playlist']['end_time']));

} else {
  $error .= '<br/>'.mysqli_error($db).'<br/>'.$query;
}
unset($rawdata['playlist']['edit_date']);

$data = $rawdata;


finish();