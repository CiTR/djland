<?php
/**
 * Created by PhpStorm.
 * User: brad
 * Date: 4/20/15
 * Time: 3:10 PM
 */


require_once('../api_common_private.php');

$data = $incoming_data;


if ( ($unix_start = strtotime($data['start'])) && ($unix_end = strtotime($data['end']))){
  $data['start'] = $unix_start;
  $data['end'] = $unix_end;
} else {
  $error .= 'invalid time format';
}


// TO DO: add host_id code
if($error == '') {

  $new_id = insert_row_in_table('special_events', $data);

}


if ($error == ''){

  echo json_encode(array('id' => $new_id));

} else {
  header('HTTP/1.0 400 ');
  echo json_encode(array('message' => $error));
}
