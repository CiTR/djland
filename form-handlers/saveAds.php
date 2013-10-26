<?php

if( isset($_POST['action'])){
$action = $_POST['action'];
}

require("../headers/db_header.php");

// DELETE ALL ADS
/*
$delete_q = "DELETE FROM adlog";
$db->query($delete_q);
$action = 'dontsave';
*/

if(isset($_POST['ads'])){
//print_r($_POST['ads']);
}

//print_r($_POST['ads']);
if($action=='save' && isset($_POST['ads'])){
	echo 'action is save-----------------';
	$ads = $_POST['ads'];
	
	$insert_q = "no query";
	foreach( $ads as $i => $oneShow ){
		
		
		$showUnixTime = $oneShow[0];
		$showName = $oneShow[1];
		
		$delete_q = "DELETE FROM adlog WHERE time_block = '".$showUnixTime."'";
		$db->query($delete_q);
		
		foreach($oneShow[2] as $j => $showAdRow){
					
			$insert_q = "INSERT INTO adlog (time_block,time,type,name) VALUES ('".$showUnixTime."','".htmlentities($showAdRow['time'],ENT_QUOTES)."','".htmlentities($showAdRow['type'],ENT_QUOTES)."','".htmlentities($showAdRow['name'],ENT_QUOTES)."')";
		//	$update_q = "UPDATE scheduled_ads SET sam_song_id_list='".$array[2]."' WHERE time_block=".$array[0];
			
			if ($result_ads = $db->query($insert_q)){ 
				
				echo $insert_q.'</br>';	
				
			}	else {
				echo "did not insert anything, but here is the q: || ".$insert_q." ||";
			}
				
	//	print($i.': '.$j.': '.$v.'.<br/>');
	
		}
	
	//$db->close();
	
	}

}

if($action=='load'){
	
	
	
	
}

echo 'end of PHP-----------------';
?>