<?php


require_once('../api_common_private.php');

//$rawdata = get_array('playlists');

$rawdata = array();

global $_GET;
global $db;

if(isset($_GET['OFFSET'])) $offset = $_GET['OFFSET']; else $offset = 0;
if(isset($_GET['LIMIT'])) $limit = $_GET['LIMIT']; else $limit = 100;

$query = '
    SELECT *
    FROM playlists
    WHERE playlists.show_id = '.users_show().'
    ORDER BY
      playlists.start_time
    DESC limit ' . $limit . ' OFFSET ' . $offset;

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