<?php 
	session_start();
	require("../headers/db_header.php");
	$membership_year = json_decode($_POST['membership_year'],true);
	$query = "UPDATE membership_years SET ";
	$end = end(array_keys($membership_year));
	foreach($membership_year as $key => $var){
		//ignore primary keys id and membership_year
		if($key != 'id' && $key != 'membership_year') {
			$query .= $key." =:".$key;
			//no comma on last entry
			if($key != $end){
				$query .= ",";
			}
		}
	}
	$query .= " WHERE member_id=:id AND membership_year=:membership_year;";
	$statement = $pdo_db->prepare($query);
	foreach($membership_year as $key => $value){
			$statement->bindValue($key,$membership_year[$key]);		
	}
	try{
		if($statement->execute()) echo json_encode(true);
	}catch(PDOException $e){
		echo $e->getMessage();
	}
	
?>