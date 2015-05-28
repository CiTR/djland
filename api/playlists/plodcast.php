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
    FROM playlists
    WHERE playlists.show_id = '.$show_id.'
    ORDER BY
      playlists.start_time
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

$query2 = '
  SELECT * FROM podcast_episodes
  WHERE channel_id = '.users_channel();


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

      if ($podcast['id'] == $podcast_id){
        $rawdata []= ['playlist' => $playlist, 'podcast' => $podcast];
      }
    }
}


$data = $rawdata;

finish();