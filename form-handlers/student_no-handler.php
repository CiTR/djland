<?php
	require("../headers/db_header.php");
	require("../headers/function_header.php");
	
	if(isset($_POST['student_no'])){
		$student_no = $_POST['student_no'];
		if($student_no!=null){
			$query = "SELECT id FROM membership WHERE student_no ='".$student_no."'";
			$result = $db->query($query);
			if(mysqli_num_rows($result)>=1){
				echo json_encode(true);
			}
			else{
				echo json_encode(false);
			}
			$result->close();
		}else{
			echo json_encode(false);
		}
		
	}
?>



