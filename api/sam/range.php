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

  $data = $rawdata;
  finish();


}else{

  $error .= mysqli_error($mysqli_sam);
  finish();

}


