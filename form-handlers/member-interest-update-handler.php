<?php 
//Member Interest Update Handler
	session_start();
	require("../headers/db_header.php");
	$membership_year = json_decode($_POST['membership_year'],true);
	print_r($membership_year);
	$query = "UPDATE membership_years SET ";
	foreach($membership_year as $key => $var){
		if($key != 'id' && $key != 'membership_year') {
			$query .= $key." =:".$key;
			if($key != 'other'){
				$query .= ",";
			}
		}
	}
	$query .= " WHERE member_id=".$membership_year['id']."AND membership_year=".$membership_year['membership_year'].";";
	$statement = $pdo_db->prepare($query);
	foreach($membership_year as $key => $value){
		echo "\nstatement->bind_param(".$key.",'".$value."'); Length of value = ".strlen($value);
			if($key != 'id') $statement->bindValue($key,$membership_year[$key]);		
	}
	try{
	$statement->execute();	
	}catch(PDOException $e){
		echo $e->getMessage();
	}
	
?>