<?php
	session_start();
	include_once('security_header.php');
	$reply = new stdClass();
	$reply->error=mysqli_error($db);
	echo json_encode($reply);
?>