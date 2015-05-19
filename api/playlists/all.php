<?php


require_once('../api_common.php');

session_start();



$rawdata = array();

global $_GET;
global $db;

if(isset($_GET['OFFSET'])) $offset = $_GET['OFFSET']; else $offset = 0;
if(isset($_GET['LIMIT'])) $limit = $_GET['LIMIT']; else $limit = 100;



  $query = '
    SELECT playlists.start_time, playlists.edit_date, playlists.id as playlist_id,
     show_id as sh_id,
     shows.name as show_name

    FROM playlists
    LEFT JOIN podcast_episodes on playlists.podcast_episode = podcast_episodes.id
    LEFT JOIN shows on shows.id = playlists.show_id
    ORDER BY
      playlists.edit_date
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