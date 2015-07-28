<?php
	
require_once("headers/db_header.php");

	$result_id = mysqli_query($db,"SELECT id FROM playsheets ORDER BY create_date DESC LIMIT 1");
	
	$the_id = mysqli_result_dep($result_id,0,"id");

	echo $the_id; 
?>