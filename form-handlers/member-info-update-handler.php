<?php
	include_once("../headers/session_header.php");
	require("../headers/db_header.php");
	$member = json_decode($_POST['member'],true);
	$query = "UPDATE membership SET ";
	$end = end(array_keys($member));
	foreach($member as $key => $var){
		//ignore primary key id
		if($key != 'id') {
			$query .= $key." =:".$key;		
			//no comma on last entry
			if($key != $end){
				$query .= ",";
			}
		}
	}
	$query .= " WHERE id=".$member['id'].";";
	$statement = $pdo_db->prepare($query);
	foreach($member as $key => $value){
		//echo "\nstatement->bind_param(".$key.",'".$value."'); Length of value = ".strlen($value);
		if($key != 'id'){
			$statement->bindValue(":".$key,$member[$key]);
		}
	}
	try{
		if($statement->execute()) echo json_encode(true);
	}catch(PDOException $e){
		echo $e->getMessage();
	}
?>