<?php
	require("../headers/db_header.php");
	require("../headers/function_header.php");
	require("../headers/password.php");
	

	$username = htmlentities($_POST['username'],ENT_QUOTES,'UTF-8');
	$password = htmlentities($_POST['password'],ENT_QUOTES,'UTF-8');
	$firstname = htmlentities($_POST['firstname'],ENT_QUOTES,'UTF-8');
	$lastname = htmlentities($_POST['lastname'],ENT_QUOTES,'UTF-8');
	$address = htmlentities($_POST['address'],ENT_QUOTES,'UTF-8');
	$city = htmlentities($_POST['city'],ENT_QUOTES,'UTF-8');
	$province = $_POST['province'];
	$postalcode = $_POST['postalcode'];
	$canadian_citizen = $_POST['canadian_citizen'];
	$member_type = $_POST['member_type'];
	if($member_type == 'Student'){
		$integrate = $_POST['integrate'];
		$faculty = $_POST['faculty'];
		$schoolyear = $_POST['schoolyear'];
		$student_no = $_POST['student_no'];
	}else{
		$integrate = null;
		$faculty = null;
		$schoolyear = null;
		$student_no = null;
	}
	$has_show = $_POST['has_show'];
	$show_name = htmlentities ($_POST['show_name'],ENT_QUOTES,'UTF-8');
	$is_new = $_POST['is_new'];
	$alumni = $_POST['alumni'];
	$since = $_POST['since'];
	$email = htmlentities ($_POST['email'],ENT_QUOTES,'UTF-8');
	$primary_phone = $_POST['primary_phone'];
	$secondary_phone = $_POST['secondary_phone'];
	$music = $_POST['music'];
	$sports = $_POST['sports'];
	$live_broadcast = $_POST['live_broadcast'];
	$ads_psa = $_POST['ads_psa'];
	$discorder = $_POST['discorder'];
	$discorder_2 = $_POST['discorder_2'];
	$news = $_POST['news'];
	$tech = $_POST['tech'];
	$outreach = $_POST['outreach'];
	$show_hosting = $_POST['show_hosting'];
	$arts = $_POST['arts'];
	$prog_comm = $_POST['prog_comm'];
	$digital_library = $_POST['digital_library'];
	$photography = $_POST['photography'];
	$tabling = $_POST['tabling'];
	$dj = $_POST['dj'];
	$other = htmlentities($_POST['other'],ENT_QUOTES,'UTF-8');
	$about = htmlentities($_POST['about'],ENT_QUOTES,'UTF-8');
	$skills = htmlentities ($_POST['skills'],ENT_QUOTES,'UTF-8');
	$exposure = htmlentities ($_POST['exposure'],ENT_QUOTES,'UTF-8');
	
	$today = date("Y-m-d H:i:s");
	$joined = $today;
	
	//Check to see if we are before the end of school year or not (end of August)
	$year = idate('Y');
	$today_date = date('m/d/Y',strtotime("today"));
	$cutoff_date = date('09/31/'.$year);

	//Check to see if we are in a new school year or not.
	if(strtotime($today-date) < strtotime($cutoff_date)){
		$year--;
	}
	$nextyear = $year +1;
	$membership_year = $year."/".$nextyear;


	if($member_type != 'Student'){
		$insert_membership = "INSERT INTO membership (firstname,lastname,address,city,province,postalcode,canadian_citizen,member_type,is_new,alumni,since,has_show,show_name,email,primary_phone,secondary_phone,about,skills,exposure,joined) VALUES ('".$firstname."','".$lastname."','".$address."','".$city."','".$province."','".$postalcode."','".$canadian_citizen."','".$member_type."','".$is_new."','".$alumni."','".$since."','".$has_show."','".$show_name."','".$email."','".$primary_phone."','".$secondary_phone."','".$about."','".$skills."','".$exposure."','".$joined."');";	
	}else{
		$insert_membership = "INSERT INTO membership (firstname,lastname,address,city,province,postalcode,canadian_citizen,member_type,is_new,alumni,since,faculty,schoolyear,integrate,student_no,has_show,show_name,email,primary_phone,secondary_phone,about,skills,exposure,joined) VALUES ('".$firstname."','".$lastname."','".$address."','".$city."','".$province."','".$postalcode."','".$canadian_citizen."','".$member_type."','".$is_new."','".$alumni."','".$since."','".$faculty."','".$schoolyear."','".$integrate."','".$student_no."','".$has_show."','".$show_name."','".$email."','".$primary_phone."','".$secondary_phone."','".$about."','".$skills."','".$exposure."','".$joined."');";	
	}
	$insert_membership_year = "INSERT INTO membership_years (member_id,membership_year,paid,sports,music,arts,show_hosting,live_broadcast,ads_psa,tech,news,programming_committee,promotions_outreach,discorder,discorder_2,digital_library,photography,tabling,dj,other) VALUES (LAST_INSERT_ID(),'".$membership_year."','0','".$sports."','".$music."','".$arts."','".$show_hosting."','".$live_broadcast."','".$ads_psa."','".$tech."','".$news."','".$prog_comm."','".$outreach."','".$discorder."','".$discorder_2."','".$digital_library."','".$photography."','".$tabling."','".$dj."','".$other."');";
	$insert_user = " INSERT INTO user (member_id,username,password,status,create_date) VALUES(LAST_INSERT_ID(),'".$username."','".password_hash($password,PASSWORD_DEFAULT)."','enabled','".$joined."');";
	$insert_group_member = " INSERT INTO group_members (userid,member,dj,administrator,adduser,addshow,editdj,library,membership,editlibrary,operator) VALUES (LAST_INSERT_ID(),'1','0','0','0','0','0','0','0','0','0');";
	$fail=false;
	$db->query("START TRANSACTION");
	$error[0] = "ERROR";
	
	$result = $db -> query($insert_membership);
	if(!$result){
		$fail = true;
		$error[1] = "Error with member insert ";
		$error[2] = mysqli_error($db);
	}
	else{
		$result = $db -> query($insert_membership_year);
		if(!$result){
			$fail = true;
			$error[1] = "Error with membership year insert ";
			$error[2] = mysqli_error($db);
		}
		else{
			$result = $db -> query($insert_user);
			if(!$result){
				$error[1] = "Error with user insert ";
				$error[2] = mysqli_error($db);
				$fail = true;
			}
			else{
				$result = $db -> query($insert_group_member);
				if(!$result){
					$error[1] = "Error with group insert ";
					$error[2] = mysqli_error($db);
					$fail = true;
				}
			}
		}
	}
	if(!$_POST){
		$error [1] = "This is not the page you're looking for";
		$error [2] = "<img src = http://i.imgur.com/to4ZTET.gif>";
	}
	
	if($fail){
		if( !( $db -> rollback() ) ){
			$error[1] = " Rollback failed";
			$error[2] = $insert_membership.$insert_membership_year.$insert_user.$insert_group_member;
		}
		echo json_encode($error);
	}else{
		if( !( $db -> commit() ) ){
			$error[1] = " Commit failed";
			$error[2] = $insert_membership.$insert_membership_year.$insert_user.$insert_group_member;
			echo json_encode($error);
		}
		else echo json_encode(true);
	}
?>