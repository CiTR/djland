<?php

require_once('../headers/db_header.php');


$query = "SELECT * FROM podcast_channels order by id DESC;";

//execute the query.

$channels = array();
if ($result = mysqli_query($db, $query) ){
	
	while($row = mysqli_fetch_array($result)) {
		$channels []= $row;


	} 


foreach($channels as $i => $channel){
	echo '<a href=edit.php?channel='.$channel['id'].'>'.html_entity_decode(stripslashes($channel['title'])).'</a>';
	echo '<a href=../podcasting.php?channel='.$channel['id'].'>  (view / edit episodes)</a>';
	echo '<br/>';
}
echo '<pre>CHANNELS:<br/>';
print_r($channels);

} else {
	
	//error
}
