<?php
include_once("../headers/session_header.php");
if( isset($_POST['action'])){
$action = $_POST['action'];
}

require_once("../headers/db_header.php");

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
			if( is_numeric($showAdRow['name'])){
				$insert_q = "INSERT INTO adlog (time_block,time,type,name, sam_id)
						VALUES ('".$showUnixTime."','".
						htmlentities($showAdRow['time'],ENT_QUOTES)."','".
						htmlentities($showAdRow['type'],ENT_QUOTES)."','".
						htmlentities($showAdRow['name'],ENT_QUOTES)."','".
						htmlentities($showAdRow['name'],ENT_QUOTES)."')";

			} else {
				$insert_q = "INSERT INTO adlog (time_block,time,type,name)
					VALUES ('".$showUnixTime."','".
					htmlentities($showAdRow['time'],ENT_QUOTES)."','".
					htmlentities($showAdRow['type'],ENT_QUOTES)."','".
					htmlentities($showAdRow['name'],ENT_QUOTES)."')";
			}
			if ($result_ads = $db->query($insert_q)){ 
				
				echo $insert_q.'</br>';	
				
			}	else {
				echo "did not insert anything, but here is the q: || ".$insert_q." ||";
			}
		}
	}
}

if($action=='load'){
	//TODO: WHERE IS THIS CODE?
}
?>