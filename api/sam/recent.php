<?php


require_once('../api_common.php');


if ($result_sam = $mysqli_sam->
  query("SELECT historylist.* FROM historylist
WHERE songtype='S'
order by date_played desc LIMIT 50")) {

  //   printf("Select returned %d rows.\n", $result->num_rows);
$rawdata = array();

  while ($row = $result_sam->fetch_assoc()) {
    $rawdata[] = $row;
  }

  $result_sam->close();
  $index = 0;
  foreach ($rawdata as $i => $row) {
    $id = $index++;
    $date = date("M j, g:ia", strtotime($row['date_played']));
/*
    $row['ISRC'];
    $row['artist'];
    $row['title'];
    $row['album'];
    $row['composer'];
*/
    $rawdata[$i]['durMin'] = intval($row['duration'] / 60000);
    $rawdata[$i]['durSec']= ($row['duration'] / 1000) % 60;

    $rawdata[$i]['hour'] = date("g", strtotime($row['date_played']));
    $rawdata[$i]['minute'] = date("i", strtotime($row['date_played']));
    $rawdata[$i]['pmCheck'] = date("a", strtotime($row['date_played']));
  }
  $data = $rawdata;
  finish();
}

else {
  $error .= mysqli_error($mysqli_sam);
  finish();
}