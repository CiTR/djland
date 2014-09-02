<?php 
	require("../headers/password.php");
	$password = '101.9';
	$hash = password_hash($password,PASSWORD_DEFAULT); 
	$verify = password_verify($password,$hash);

	echo "Password: ".$password;
	echo "<br/>Hash: ".$hash;
	echo "<br/>Verify: ".$verify;
	?>