<?php
/**
 * Created by PhpStorm.
 * User: brad
 * Date: 3/5/15
 * Time: 8:23 PM
 */


require_once('../api_common.php');


$id = isset($_GET['ID']) && is_numeric($_GET['ID']) ? $_GET['ID'] * 1 : 0;

if (!$id) {
	$error = "[ERROR] please supply a numeric playlist id ( /playlist?ID=##) ";
	$blame_request = true;
	finish();
	exit;
}

$query = "SELECT
          playlists.id as playlist_id,
          playlists.show_id,
		  playlisrs.show_name,
          playlists.start_time,
          playlists.end_time,
          GREATEST(playlists.edit_date, COALESCE(podcast_episodes.edit_date,'0000-00-00 00:00:00')) as edit_date,
          playlists.type as playlist_type,
          playlists.spokenword as transcript,
          hosts.name as host_name,
          playlists.podcast_episode as episode_id,
          podcast_episodes.summary as episode_description,
          podcast_episodes.title as episode_title,
          podcast_episodes.url as episode_audio
          FROM playlists
          join shows on shows.id = playlists.show_id
          LEFT JOIN hosts on hosts.id = playlists.host_id
          LEFT JOIN podcast_episodes on podcast_episodes.id = playlists.podcast_episode

          WHERE playlists.status = 2 AND playlists.id=$id";

$rawdata = array();

if ( $result = mysqli_query($db, $query) ) {
  if (mysqli_num_rows($result) == 0) {
    //$error = " no playlist found with this ID: ".$id;
    //$blame_request = true;
    $data = array(
    	'api_message' => '[NO RECORD FOUND]',
    	'message'     => 'no playlist found with this ID: '.$id,
    );
    finish();
	exit;
  }
  while ($row = mysqli_fetch_assoc($result)) {
    $rawdata = $row;
    break;
  }

  $plays = array();

  $query = 'SELECT songs.artist, songs.title, songs.song, songs.composer, playitems.id FROM playitems JOIN songs ON playitems.song_id = songs.id WHERE playitems.playsheet_id='.$id .' order by playitems.id asc';

  if ($result2 = mysqli_query($db, $query)){
      if (mysqli_num_rows($result2) == 0){
        //$error .= " no plays in this playlist! ";
        //$blame_request = true;
      } else {

        while ($row = mysqli_fetch_assoc($result2)){
          foreach($row as $i => $v){
            $row[$i] = html_entity_decode($v, ENT_QUOTES);
          }
          $plays [] = $row;
        }



      }
  } else {
    $error .= '<br/>'.mysqli_error($db);
  }

  $rawdata['songs'] = $plays;


} else {
  $error .= '<br/>'.mysqli_error($db);
}

if(isset($rawdata['episode_audio']) && $rawdata['episode_audio'] == ""){
  $rawdata['episode_description'] = '';
  $rawdata['episode_subtitle'] = '';
  $rawdata['episode_title'] = '';
  $rawdata['episode_audio'] = '';
}

$data = $rawdata;

finish();