<?php
	
require("headers/db_header.php");

	$result_id = mysqli_query($db,"SELECT id FROM playlists ORDER BY create_date DESC LIMIT 1");
	
	$the_id = mysqli_result($result_id,0,"id");

	echo $the_id; 
?>