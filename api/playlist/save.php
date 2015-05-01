<?php
/**
 * Created by PhpStorm.
 * User: brad
 * Date: 4/28/15
 * Time: 3:32 PM
 */


require_once("../api_common_private.php");

$ads = $incoming_data['ads'];
$plays = $incoming_data['plays'];

unset($incoming_data['ads']);
unset($incoming_data['plays']);

$playlist = $incoming_data;

if(array_key_exists('id',$playlist)){
  $playlist_id = $playlist['id'];
  unset($playlist['id']);

  $delete_q = 'DELETE FROM playitems WHERE playsheet_id = '.$playlist_id;

  if($error=='' && $result = mysqli_query($db,$delete_q)){

  } else {
    $error .= 'could not delete plays before adding updated plays. ';
  }
}

$podcast = $playlist['podcast'];
unset($playlist['podcast']);

$host = $playlist['host'];
unset($playlist['host']);

$spokenword_hours = $playlist['spokenword_hours'];
$spokenword_min = $playlist['spokenword_minutes'];
unset($playlist['spokenword_hours']);
unset($playlist['spokenword_minutes']);
$playlist['spokenword_duration'] = 60*$spokenword_hours + $spokenword_min;

$playlist['start_time'] = date('Y-m-d H:i:s',strtotime($playlist['start_time'] ));
$playlist['end_time'] = date('H:i:s',strtotime($playlist['end_time'] ));

update_row_in_table('playlists',$playlist,$playlist_id);

foreach($ads as $i => $ad){

  $ads[$i]['played'] = ($ad['played'])? 1 : 0;

  if (array_key_exists('id',$ad)) {
    $the_id = $ad['id'];
    unset($ads[$i]['id']);

    $message = update_row_in_table('adlog', $ads[$i], $the_id);
  }
}

$plays = array_reverse($plays);

foreach($plays as $i => $play){

  foreach($play as $field => $value){

    if (strpos($field,'is_')===0 && ($value !== 1 && $value !== 0 && $value !== "0" && $value !== "1")){
      $play[$field] = ($value)? 1 : 0;
    }
  }

  if (array_key_exists('song', $play) && is_array($play['song'])){
    $song = $play['song'];
    unset($play['song']);

    if (array_key_exists('id', $song)){
      $songID = $song['id'];
      unset($song['id']);
    }

    if($song['composer'] =='') unset($song['composer']);

    if( $actual_song_id = getIDbyRow('songs', $song)){

      // song found, so use the existing ID instead!

      $play['song_id'] = $actual_song_id;

    } else {

      $play['song_id'] = insert_row_in_table('songs',$song);

    }
      if(array_key_exists('id',$play)) unset($play['id']);

    $play['playsheet_id'] = $playlist_id;
      insert_row_in_table('playitems', $play);


  }
}


if ($error == ''){
  echo json_encode(array('message' => $error. ' '.$message));
} else {
  header('HTTP/1.0 400 '.json_encode(array('message' => $error)));
}

$episode_id = $episode_data['id'];