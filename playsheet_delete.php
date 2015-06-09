<?php
/**
 * Created by PhpStorm.
 * User: brad
 * Date: 3/20/15
 * Time: 9:24 AM
 */



$delete_query = "DELETE FROM playsheets WHERE id='".$_GET['id']."'";
$delete_playitems = "DELETE FROM playitems WHERE playsheet_id = '".$_GET['id']."'";

if( $result = mysqli_query($db,$delete_query)) {

  if($result2 = mysqli_query($db,$delete_playitems)){
    echo '<center><br/><br/>you just deleted playsheet id #'.$_GET['id'];
    echo '<br/><a href="playsheet.php?action=list">back to list</a></center>';
  }
} else {
  echo 'could not delete the playsheet';
  echo '<hr/>'.$delete_query;
}