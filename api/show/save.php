<?php
/**
 * Created by PhpStorm.
 * User: brad
 * Date: 3/27/15
 * Time: 4:50 PM
 */

require_once("../api_common_private.php");


$show_data = $incoming_data;

$show_id = $show_data['show_id'];

if (array_key_exists('show_id',$episode_data)) unset($episode_data['show_id']);


// TO DO: add host_id code
$message = update_row_in_table('shows', $show_data, $show_id);

if ($error == ''){
  echo json_encode(array('message' => $message));
} else {
  header('HTTP/1.0 400 '.json_encode(array('message' => $message)));
}

?>