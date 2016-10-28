<?php

require_once('../api_common_private.php');


  $starting = date('Y-m-d H:i:s',strtotime($incoming_data['min']));
  $finishing = date('Y-m-d H:i:s',strtotime($incoming_data['max']));

$rawdata = array();

if ($result_sam = $mysqli_sam->query(
      "SELECT * FROM historylist
        WHERE date_played >= '".$starting."'
        AND date_played <= '".$finishing."'
        AND songtype='S'
        order by date_played desc ")) {


  while($row = $result_sam->fetch_assoc())
  {
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

    $rawdata[$i]['hour'] = date("H", strtotime($row['date_played']));
    $rawdata[$i]['minute'] = date("i", strtotime($row['date_played']));
    $rawdata[$i]['second'] = date("s", strtotime($row['date_played']));
  }

  $data = $rawdata;
  finish();


}else{

  $error .= mysqli_error($mysqli_sam);
  finish();

}


