<?php
	$reply = new stdClass();
	$reply->error=mysql_error();
	echo json_encode($reply);
?>