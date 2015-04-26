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
            podcast_episodes.*,
            hosts.name as host
            FROM playlists
            LEFT JOIN podcast_episodes on playlists.id = podcast_episodes.id
            LEFT JOIN hosts on hosts.id = playlists.host_id
            WHERE playlists.id = '.$id;

if ( $result = mysqli_query($db,$query_for_playsheet)){
  $rawdata['playlist'] = mysqli_fetch_assoc($result);
} else {
  $error .= mysqli_error($db);
  finish();
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



$query_for_songs = 'SELECT playlists.id,
          playitems.*,
          songs.*
          FROM playlists
          LEFT JOIN playitems on playlists.id = playitems.playsheet_id
          LEFT JOIN songs on playitems.song_id = songs.id
          WHERE playlists.id ='.$id;


if ( $result = mysqli_query($db, $query_for_songs) ) {
  $rawdata['plays'] = array();
  while ($row = mysqli_fetch_assoc($result)) {
    $rawdata['plays'] []= $row;

  }

  foreach($rawdata['plays'] as $i => $play){

    foreach($LIST_OF_BOOLEANS as $j => $bool){
      $rawdata['plays'][$i][$bool] = ($play[$bool] == 1)? true : false;

    }

    $rawdata['plays'][$i]['artist'] = convertEntities($play['artist']);
    $rawdata['plays'][$i]['title'] = convertEntities($play['title']);
    $rawdata['plays'][$i]['song'] = convertEntities($play['song']);
    $rawdata['plays'][$i]['composer'] = convertEntities($play['composer']);
    $rawdata['plays'][$i]['lang'] = convertEntities($play['lang']);
  }

  foreach($rawdata['ads'] as $i => $ad){

    $rawdata['ads'][$i]['played'] = ($ad['played'] == 1) ? true : false;

    $rawdata['ads'][$i]['name'] = convertEntities($ad['name']);

    if(is_numeric($ad['name'])){


      $ad_q = "SELECT artist, title FROM songlist WHERE ID = '".$ad['name']."'";
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


//  $rawdata['playlist']['start_time'] = strtotime($rawdata['playlist']['start_time']);

} else {
  $error .= '<br/>'.mysqli_error($db).'<br/>'.$query;
}

if(isset($rawdata['episode_audio']) && $rawdata['episode_audio'] == ""){
  $rawdata['episode_description'] = '';
  $rawdata['episode_subtitle'] = '';
  $rawdata['episode_title'] = '';
  $rawdata['episode_audio'] = '';
}

$data = $rawdata;


finish();