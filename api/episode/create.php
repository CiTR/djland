<?php
/**
 * Created by PhpStorm.
 * User: brad
 * Date: 4/20/15
 * Time: 3:10 PM
 */

error_reporting(E_ALL);
require_once('../api_common_private.php');

$data = $incoming_data;

$new_podcast = array();

$new_podcast['title'] = '1';
$new_podcast['subtitle'] = '2';
$new_podcast['summary'] = '3';
$new_podcast['date'] = '';

$new_podcast['channel_id'] = users_channel();
$new_podcast['url'] = '';
$new_podcast['length'] = '0';
$new_podcast['author'] = 'CiTR';
$new_podcast['active'] = '0';
$new_podcast['duration'] = '0';
$new_podcast['edit_date'] = date('Y-m-d H:i:s');

// TO DO: add host_id code
if($error == '') {
  $new_id = insert_row_in_table('podcast_episodes', $new_podcast);

}

if ($error == ''){
  $new_podcast['id'] = $new_id;
  $data = $new_podcast;
  finish();

} else {
  header('HTTP/1.0 400 ');
  echo json_encode(array('message' => $error));
}
