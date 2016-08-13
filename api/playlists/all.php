<?php


require_once('../api_common.php');

session_start();



$rawdata = array();

global $_GET;
global $db;

if(isset($_GET['OFFSET'])) $offset = $_GET['OFFSET']; else $offset = 0;
if(isset($_GET['LIMIT'])) $limit = $_GET['LIMIT']; else $limit = 100;



  $query = '
    SELECT playsheets.start_time, playsheets.edit_date, playsheets.id as playsheet_id,
     show_id as sh_id,
     shows.name as show_name

    FROM playsheets
    LEFT JOIN podcast_episodes on playsheets.podcast_episode = podcast_episodes.id
    LEFT JOIN shows on shows.id = playsheets.show_id
    WHERE playsheets.status = 2
    ORDER BY
      playsheets.edit_date
    DESC limit ' . $limit . ' OFFSET ' . $offset;


if ($result = mysqli_query($db['link'], $query) ) {

  while ($row = mysqli_fetch_assoc($result)) {

    $rawdata [] = $row;

  }
} else {
  $error .= mysqli_error($db['link']);
}




$data = $rawdata;

finish();
