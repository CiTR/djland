<?php
/**
 * Created by PhpStorm.
 * User: brad
 * Date: 4/20/15
 * Time: 2:17 PM
 */

require_once('../api_common_private.php');



$sb_data = $incoming_data;

if ( ($unix_start = strtotime($sb_data['start'])) && ($unix_end = strtotime($sb_data['end']))){
  $sb_data['start'] = $unix_start;
  $sb_data['end'] = $unix_end;
} else {
  $error .= 'invalid time format';
}

$sb_id = $sb_data['id'];

if (array_key_exists('id',$sb_data)) unset($sb_data['id']);


// TO DO: add host_id code

if($error == ''){
  $message = update_row_in_table('special_events', $sb_data, $sb_id);
}

if ($error == ''){
  echo json_encode(array('message' => $message));
} else {
  header('HTTP/1.0 400 '.json_encode(array('message' => $message)));
}
