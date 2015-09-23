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

$query = 'SELECT
          playsheets.id as playlist_id,
          playsheets.show_id,
          playsheets.start_time,
          playsheets.end_time,
          playsheets.edit_date,
          playsheets.type as playlist_type,
          playsheets.host as host_name,
          podcast_episodes.id as episode_id,
          playsheets.summary as episode_description,
          playsheets.title as episode_title,
          podcast_episodes.url as episode_audio
          FROM playsheets
          LEFT JOIN podcast_episodes on podcast_episodes.playsheet_id = playsheets.id
          WHERE playsheets.status = 2 AND playsheets.id ='.$id;

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

  $query = 'SELECT artist, album as title, song, composer, playitems.id FROM playitems WHERE playitems.playsheet_id='.$id .' order by playitems.id asc';

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

/*if (!(is_null($rawdata['host']) || ($rawdata['host'])=='') ){
  $rawdata['host_name'] = $rawdata['host_name'];
}
unset($rawdata['host']);*/

if(isset($rawdata['episode_audio']) && $rawdata['episode_audio'] == ""){
  $rawdata['episode_description'] = '';
  $rawdata['episode_subtitle'] = '';
  $rawdata['episode_title'] = '';
  $rawdata['episode_audio'] = '';
}

$data = $rawdata;

finish();