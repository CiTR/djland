<?php
	if(isset($_POST('username'))){
		$query = "SELECT case when count(username) FROM user where username like %'$username'
		if($result = $db->query($query)){	
	}
?>