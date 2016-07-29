<?php
	include_once("../headers/session_header.php");
	require_once("../headers/db_header.php");
	require_once("../headers/function_header.php");
	
	if(isset($_POST['student_no'])){
		$student_no = $_POST['student_no'];
		if($student_no != null){
			if(isset($_POST['member_id'])){
				$query = "SELECT id FROM membership WHERE student_no =:student_no AND id !=:id";
			}else{
				$query = "SELECT id FROM membership WHERE student_no =:student_no";
			}
			$statement = $pdo_db->prepare($query);
			$statement->bindValue(':student_no',$student_no);
			if(isset($_POST['member_id'])){
				$statement->bindValue(':id',$_POST['member_id']);
			}
			try{
				$statement->execute();
				$num = count($statement->fetchAll());
			}catch(PDOException $pdoe){
				http_response_code(400);
				echo $pdoe->getMessage();
			}
			if($num > 0){
				http_response_code(200);
				echo "true";
			}else{
				http_response_code(200);
				echo "false";
			}
		}else{
			http_response_code(400);
			echo "Student Number is Null";
		}
	}else{
		http_response_code(400);
		echo "Student Number is not set";
	}
?>



