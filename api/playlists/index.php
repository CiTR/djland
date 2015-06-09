<?php


require_once('../api_common.php');

//$rawdata = get_array('playsheets');

$rawdata = array();

global $_GET;
global $db;

if(isset($_GET['OFFSET'])) $offset = $_GET['OFFSET']; else $offset = 0;
if(isset($_GET['LIMIT'])) $limit = $_GET['LIMIT']; else $limit = 100;



  $query = '
    SELECT playsheets.id,
      GREATEST(playsheets.edit_date, podcast_episodes.edit_date) as edit_date
    FROM playsheets
    LEFT JOIN podcast_episodes on playsheets.podcast_episode = podcast_episodes.id

    ORDER BY
      GREATEST(playsheets.edit_date, podcast_episodes.edit_date)
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