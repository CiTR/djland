<?php
header('access-control-allow-origin: *');
require_once('../headers/db_header.php');


// if episode request is for an existing mp3 url (treated as GUID), then assume we are editing one
// if a new one, then adding an episode.

if (isset($_POST['url']) && isset($_POST['channel']) && isset($_POST['data'])){

	$url = $_POST['url'];
	$channel = $_POST['channel'];
	$episode = $_POST['data'];

	$query_data_clause = '';

	$query_data_clause_arr = array();
	foreach ($episode as $field => $value){
			$query_data_clause_arr []= $field." = '".$value."'";
	}
	$query_data_clause = implode(', ', $query_data_clause_arr);

	$query = "UPDATE podcast_episodes SET ".$query_data_clause." WHERE url='" .$url. "';";

	if($result = mysqli_query($db, $query)){

		if( mysqli_affected_rows($db) != 1) {

			$pubdate = Date('r');
			$insert_query = "INSERT INTO podcast_episodes SET ".$query_data_clause.", channel_id = '".$channel."', url='" .$url. "', date='" .$pubdate. "';";
		
			if( $result_ins = mysqli_query($db,$insert_query)){
				echo 'insert success ';

				

				// FAULT TOLERANCE
				// this is where we should set some sort of job queuing stuff
				// in the event of a problem with the audio engine.
			} else {
				echo 'insert problem: '.$insert_query;
			}

		}

	} else {
		echo 'database problem: '.$query;
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