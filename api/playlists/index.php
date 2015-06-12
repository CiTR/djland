<?php


require_once('../api_common.php');

//$rawdata = get_array('playlists');

$rawdata = array();

global $_GET;
global $db;

$offset = isset($_GET['OFFSET']) && is_numeric($_GET['OFFSET']) ? $_GET['OFFSET'] * 1 : 0;
$limit  = isset($_GET['LIMIT'])  && is_numeric($_GET['LIMIT'])  ? $_GET['LIMIT']  * 1 : 100;

$query =   "SELECT playlists.id,
			GREATEST(playlists.edit_date, COALESCE(podcast_episodes.edit_date,'0000-00-00 00:00:00')) as edit_date

			FROM playlists
			join shows on shows.id = playlists.show_id
			LEFT JOIN podcast_episodes on playlists.podcast_episode = podcast_episodes.id

			WHERE playlists.status = 2
			ORDER BY GREATEST(playlists.edit_date, COALESCE(podcast_episodes.edit_date,'0000-00-00 00:00:00')) DESC
			LIMIT $limit
			OFFSET $offset";

if ($result = mysqli_query($db, $query) ) {

  while ($row = mysqli_fetch_assoc($result)) {

    $rawdata [] = $row;

  }
} else {
  $error .= mysqli_error($db);
}

$data = $rawdata;

finish();