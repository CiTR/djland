<?php


require_once("../api_common_private.php");


$episode_data = $incoming_data;

$episode_id = $episode_data['id'];

if (array_key_exists('id',$episode_data)) unset($episode_data['id']);
if (array_key_exists('playlist_id',$episode_data)) unset($episode_data['playlist_id']);
if (array_key_exists('show_id',$episode_data)) unset($episode_data['show_id']);
if (array_key_exists('edit_date',$episode_data)) unset($episode_data['edit_date']);

if (array_key_exists('date_unix',$episode_data)) unset($episode_data['date_unix']);
if (array_key_exists('author',$episode_data)) unset($episode_data['author']);
if (array_key_exists('duration',$episode_data)) unset($episode_data['duration']);
if (array_key_exists('length',$episode_data)) unset($episode_data['length']);


$message = update_row_in_table('podcast_episodes', $episode_data, $episode_id);

if ($error == ''){
	echo json_encode(array('message' => $message));
} else {
	header('HTTP/1.0 400 '.json_encode(array('message' => $message)));
}



/*


header('access-control-allow-origin: *');
require_once('../headers/db_header.php');


// if episode request is for an existing mp3 url (treated as GUID), then assume we are editing one
// if a new one, then adding an episode.

if (isset($_POST['url']) && isset($_POST['channel']) && isset($_POST['data'])){

	$url = $_POST['url'];
	$channel = $_POST['channel'];
	$episode = $_POST['data'];

	$upsert_query =
		'INSERT INTO podcast_episodes (`id`,`title`,`subtitle`,`summary`,`date`,`channel_id`,`url`,`duration`,`author`,`active`)'.
		'VALUES ('.
		$episode['id'].',"'.
		$episode['title'].'","'.
		$episode['subtitle'].'","'.
		$episode['summary'].'","'.
		$episode['date'].'","'.
		$channel.'","'.
		$url.'","'.
		$episode['duration'].'","'.
		$episode['author'].'","'.
		$episode['active'].'") '.
		'ON DUPLICATE KEY UPDATE '.
		'`id` = "'.$episode['id'].'",'.
		'`title` = "'.$episode['title'].'",'.
		'`subtitle` ="'.$episode['subtitle'].'",'.
		'`summary` = "'.$episode['summary'].'",'.
		'`date` ="'.$episode['date'].'",'.
		'`channel_id` = "'.$channel.'",'.
		'`url` = "'.$url.'",'.
		'`duration` = "'.$episode['duration'].'",'.
		'`author` = "'.$episode['author'].'",'.
		'`active` = "'.$episode['active'].'"';

	if($result = mysqli_query($db['link'], $upsert_query)){

		echo 'success';
	} else {
		echo 'database problem: '.$upsert_query;
	}

} else {


	echo 'not all get parameters supplied: <pre>';
	print_r($_POST);

}
/*
echo '<hr/>DEBUG:<hr/>post:';
echo '<pre>';
print_r($_POST);
echo '</pre>';
echo 'insert query: '.$insert_query;
echo '<br/>update query: '.$query;
*/

?>
