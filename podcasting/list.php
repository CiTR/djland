<?php

if(isset($_GET['channel'])) {$channel_id = $_GET['channel'];}
else {


	echo 'no channel specified. select one below:<br/>';

	$html_mode = true;





}
	$html_mode = false;

require_once('../headers/db_header.php');

$query = "SELECT * FROM podcast_episodes WHERE channel_id = ".$channel_id;

//execute the query.

$episodes = array();
if ($result = mysqli_query($db, $query) ){

	while($row = mysqli_fetch_array($result)) {
		$episodes []= $row;

	} 


	foreach($episodes as $i => $episode){

		$episodes[$i]['date_unix'] = strtotime($episode['date']);

	}
/*
	echo '<pre>';
	print_r($episodes);
	echo '</pre>';

*/

	echo json_encode($episodes);
} else { echo 'db prob';}

