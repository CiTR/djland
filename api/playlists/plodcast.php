<?php

require_once('../api_common_private.php');
//$rawdata = get_array('playlists');

$rawdata = array();

global $_GET;
global $db;


if(isset($_GET['show'])) $show_id = $_GET['show']; else $show_id = 0;

if (!has_show_access($show_id) ){
  $error .= 'sorry, you do not have access to this show\'s podcasts';
  finish();
}
$query = '
    SELECT *
    FROM playsheets
    WHERE playsheets.show_id = '.$show_id.'
    ORDER BY
      playsheets.start_time
    DESC ';

if ($result = mysqli_query($db, $query) ) {
  $playlists = array();

  while ($row = mysqli_fetch_assoc($result)) {

    $playlists [] = $row;

  }
} else {
  $error .= mysqli_error($db);
  finish();
}

$query = 'SELECT podcast_channel_id FROM shows WHERE id = '.$show_id;


if ($result = mysqli_query($db, $query) ) {

  $channel_id = mysqli_fetch_assoc($result);
  $channel_id = $channel_id['podcast_channel_id'];

} else {
  $error .= ' cannot get channel id ';
  finish();
}

$query2 = '
  SELECT * FROM podcast_episodes
  WHERE channel_id = '.$channel_id;


if ($result2 = mysqli_query($db, $query2) ) {

  $podcasts = array();

  while ($row = mysqli_fetch_assoc($result2)) {

    $podcasts [] = $row;

  }

} else {
  $error .= mysqli_error($db);
  finish();
}


foreach($playlists as $i => $playlist){

  $podcast_id = $playlist['podcast_episode'];

    foreach($podcasts as $j => $podcast){

      if (array_key_exists('duration', $podcast) &&
          $podcast['duration'] == 0 &&
          array_key_exists('end_time', $playlist)){

        $start = strtotime($podcast['date']);
        $end = strtotime($playlist['end_time'], $start);
        $podcast['duration'] = $end - $start;
      }

      if ($podcast['id'] == $podcast_id){
        $rawdata []= ['playlist' => $playlist, 'podcast' => $podcast];


      }
    }
}


$data = $rawdata;

finish();