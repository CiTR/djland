<?php
/**
 * Created by PhpStorm.
 * User: brad
 * Date: 3/5/15
 * Time: 8:23 PM
 */


require_once('../api_common.php');

if (isset($_GET['ID'])) $id = $_GET['ID']; else $error = 'no id specified';

$query = 'SELECT
          playlists.id as playlist_id,
          playlists.show_id,
          playlists.start_time,
          playlists.end_time,
          playlists.edit_date,
          playlists.type as playlist_type,
          playlists.spokenword as transcript,
          playlists.podcast_episode as pid,
          hosts.name as host_name,
          podcast_episodes.summary as description
          FROM playlists
          JOIN shows on shows.id = playlists.show_id
          LEFT JOIN hosts on hosts.id = playlists.host_id
          LEFT JOIN podcast_episodes on podcast_episodes.id = playlists.podcast_episode
          WHERE playlists.status = 2 AND playlists.id ='.$id;

$rawdata = array();

if ($result = mysqli_query($db, $query) ) {

  while ($row = mysqli_fetch_assoc($result)) {
    $rawdata = $row;

  }

  $plays = array();

  $query = 'SELECT songs.artist, songs.title, songs.song, songs.composer FROM playitems JOIN songs ON playitems.song_id = songs.id WHERE playitems.playsheet_id='.$id;

  if ($result2 = mysqli_query($db, $query)){
      if (mysqli_num_rows($result2) == 0){
        $error = "no plays in this playlist!";
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




$data = $rawdata;


finish();