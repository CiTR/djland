<?php
/**
 * Created by PhpStorm.
 * User: brad
 * Date: 3/5/15
 * Time: 8:23 PM
 */


require_once('../api_common.php');

if (isset($_GET['ID'])){
  $id = $_GET['ID'];
} else {
  $error .= 'please supply episode id ( /episode?ID=## )';
  $blame_request = true;
}

if (!is_numeric($id)){
  $error .= ' ID parameter should not be a string ';
  $blame_request = true;
}

if ($error != '') finish();

$query ="
    SELECT `podcast_episodes`.`id`,
    `podcast_episodes`.`title`,
    `podcast_episodes`.`subtitle`,
    `podcast_episodes`.`summary`,
    `podcast_episodes`.`date`,
    `podcast_episodes`.`channel_id`,
    `podcast_episodes`.`url`,
    `podcast_episodes`.`edit_date`
FROM `podcast_episodes`
WHERE podcast_episodes.id = ".$id.";";

$rawdata = array();

if ($result = mysqli_query($db['link'], $query) ) {
  if (mysqli_num_rows($result) == 0) {
    $error .= "no podcast episode found with this ID: ".$id;
    $blame_request = true;
  }
  while ($row = mysqli_fetch_assoc($result)) {
    $rawdata = $row;

  }

  if($error != '') finish();
  $plays = array();

  $query = 'SELECT playsheets.id as playlist_id FROM playsheet WHERE playsheets.podcast_episode = '.$id;

  if ($result2 = mysqli_query($db['link'], $query)){
    if (mysqli_num_rows($result2) == 0){
      $rawdata['playlist_id'] = '';
    } else {

      while ($row = mysqli_fetch_assoc($result2)){
        $rawdata['playlist_id'] = $row['playlist_id'];
      }

    }
  } else {
    $error .= '<br/>'.mysqli_error($db['link']);
  }

  $query = 'SELECT id AS show_id FROM shows WHERE podcast_channel_id = '.$rawdata['channel_id'];

  if ($result2 = mysqli_query($db['link'], $query)){
    if (mysqli_num_rows($result2) == 0){
      $rawdata['show_id'] = '0';
    } else {

      while ($row = mysqli_fetch_assoc($result2)){
        $rawdata['show_id'] = $row['show_id'];
      }



    }
  } else {
    $error .= '<br/>'.mysqli_error($db['link']);
  }





} else {
  $error .= '<br/>'.mysqli_error($db['link']);
}


$data = $rawdata;


finish();