<?php


require_once('../api_common_private.php');

//$rawdata = get_array('playlists');

$rawdata = array();

global $_GET;
global $db;

if(isset($_GET['OFFSET'])) $offset = $_GET['OFFSET']; else $offset = 0;
if(isset($_GET['LIMIT'])) $limit = $_GET['LIMIT']; else $limit = 100;



$query = '
    SELECT *,
    playsheets.id as ps_id,
    podcast_episodes.id as ep_id
    FROM playsheets
    LEFT JOIN podcast_episodes on playsheets.podcast_episode = podcast_episodes.id
    WHERE playsheets.show_id = '.users_show().'
    ORDER BY
      playsheets.start_time
    DESC limit ' . $limit . ' OFFSET ' . $offset;


if ($result = mysqli_query($db, $query) ) {

  while ($row = mysqli_fetch_assoc($result)) {

    $rawdata [] = $row;

  }
} else {
  $error .= mysqli_error($db);
}




$data = $rawdata;

finish();