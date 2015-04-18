<?php

require_once('../api_common_private.php');

	$html_mode = false;

$query = "SELECT * FROM podcast_episodes WHERE channel_id = ".users_channel();

//execute the query.

$episodes = array();
if ($result = mysqli_query($db, $query) ){

	while($row = mysqli_fetch_assoc($result)) {
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

