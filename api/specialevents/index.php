<?php



require_once('../api_common.php');

$rawdata = array();
$error = "";

$query = "SELECT * FROM special_events";

if ($result = mysqli_query($db['link'],$query)){

  while ( $row = mysqli_fetch_assoc($result)){
    $rawdata []= $row;
  }

} else {
  $error .= mysqli_error($db);
}

$data = $rawdata;
finish();