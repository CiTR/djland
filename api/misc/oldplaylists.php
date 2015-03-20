<?php
/**
 * Created by PhpStorm.
 * User: brad
 * Date: 3/19/15
 * Time: 11:56 AM
 */

require_once('../api_common.php');

$old_playsheets = file_get_contents('playlist-until-overlap.sql');

if (!$old_playsheets){
  die('error opening file');
}

$old_playsheets = explode(';',$old_playsheets);

foreach($old_playsheets as $i => $sql){
  $result = mysqli_query($db,$sql);
  if(!$result) {
    echo mysqli_error($db);
    return;
  }
}


echo ' done! ';