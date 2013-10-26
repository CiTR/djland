<?php

$action = $_POST['action'];

require("headers/db_header.php");


if($action=='save'){
	echo 'action is save-----------------';
$ads = $_POST['ads'];

$insert_q = "no query";
foreach( $ads as $i => $array ){
	
	$insert_q = "INSERT INTO scheduled_ads (time_block,sam_song_id_list) VALUES (".$array[0].",'".$array[2]."')";
	$update_q = "UPDATE scheduled_ads SET sam_song_id_list='".$array[2]."' WHERE time_block=".$array[0];
	
	if ($result_ads = $db->query($insert_q)){ 
		
	echo $insert_q.'</br>';	
		
}	else if ($result_ads_update = $db->query($update_q)){
		
	echo $update_q.'</br>';	
}
		

//	print($i.': '.$j.': '.$v.'.<br/>');
	
}

//$db->close();

}


if($action=='load'){
	
	
	
	
}

echo 'end of PHP-----------------';
?>