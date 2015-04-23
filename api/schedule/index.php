<?php



require_once('../api_common.php');

$rawdata = array();
$error = "";

$query = "SELECT show_times.start_day as start_day,
            show_times.start_time as start_time,
            show_times.end_day as end_day,
            show_times.end_time as end_time,
            show_times.alternating as alternating,
            show_times.show_id as show_time_id,
             shows.id as show_id,
             shows.active as active
            FROM show_times join shows on show_times.show_id = shows.id
            WHERE active = 1";

if ($result = mysqli_query($db,$query)){

  while ( $row = mysqli_fetch_assoc($result)){
    $rawdata []= $row;
  }

} else {
  $error .= mysqli_error($db);
}

$data = $rawdata;
finish();
