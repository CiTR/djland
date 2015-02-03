<?php
	require("../headers/db_header.php");
	require("../headers/function_header.php");


	$member_id = $_POST['member_id'];
	$userid = $_POST['userid'];
	$firstname = htmlentities($_POST['firstname'],ENT_QUOTES,'UTF-8');
	$lastname = htmlentities($_POST['lastname'],ENT_QUOTES,'UTF-8');
	$address = htmlentities($_POST['address'],ENT_QUOTES,'UTF-8');
	$city = htmlentities($_POST['city'],ENT_QUOTES,'UTF-8');
	$province = $_POST['province'];
	$postalcode = $_POST['postalcode'];
	$canadian_citizen = $_POST['canadian_citizen'];
	$member_type = $_POST['member_type'];
	if($member_type == 'Student' || $member_type=='student'){
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
	$show_name = htmlentities($_POST['show_name'],ENT_QUOTES,'UTF-8');
	$is_new = $_POST['is_new'];
	$alumni = $_POST['alumni'];
	$since = $_POST['since'];
	$email = htmlentities($_POST['email'],ENT_QUOTES,'UTF-8');
	$primary_phone = $_POST['primary_phone'];
	$secondary_phone = $_POST['secondary_phone'];
	$about = htmlentities($_POST['about'],ENT_QUOTES,'UTF-8');
	$skills = htmlentities($_POST['skills'],ENT_QUOTES,'UTF-8');
	$exposure = htmlentities($_POST['exposure'],ENT_QUOTES,'UTF-8');
	$comments = htmlentities($_POST['comments'],ENT_QUOTES,'UTF-8');


	if($member_type != 'Student' && $member_type != 'student'){
		$update_membership = "UPDATE membership SET firstname='".$firstname."',lastname='".$lastname."',address='".$address."',city='".$city."',province='".$province."',postalcode='".$postalcode."',canadian_citizen='".$canadian_citizen."',member_type='".$member_type."',is_new='".$is_new."',alumni='".$alumni."',since='".$since."',has_show='".$has_show."',show_name='".$show_name."',email='".$email."',primary_phone='".$primary_phone."',secondary_phone='".$secondary_phone."',about='".$about."',skills='".$skills."',exposure='".$exposure."',".($comments != null ? "comments='".$comments : "")."' WHERE id='".$member_id."';";	
	}else{
		$update_membership = "UPDATE membership SET firstname='".$firstname."',lastname='".$lastname."',address='".$address."',city='".$city."',province='".$province."',postalcode='".$postalcode."',canadian_citizen='".$canadian_citizen."',member_type='".$member_type."',is_new='".$is_new."',alumni='".$alumni."',since='".$since."',faculty='".$faculty."', schoolyear='".$schoolyear."',integrate='".$integrate."',student_no='".$student_no."',has_show='".$has_show."',show_name='".$show_name."',email='".$email."',primary_phone='".$primary_phone."',secondary_phone='".$secondary_phone."',about='".$about."',skills='".$skills."',exposure='".$exposure."',".($comments != null ? "comments='".$comments : "")."' WHERE id='".$member_id."';"; 	
	}
	$result = $db->prepare("UPDATE membership SET")
	$result = $db -> query($update_membership);
	if(!$result){
		$fail = true;
		$error[1] = "Error with member update ";
		$error[2] = mysqli_error($db);
		$error[3] = $update_membership;
	}
	if($fail){
		if( !( $db -> rollback() ) ){
			$error[1] = " Rollback failed";
			$error[2] = $update_membership.$update_membership_year.$update_user.$update_group_member;
		}
		echo json_encode($error);
	}else{
		if( !( $db -> commit() ) ){
			$error[1] = " Commit failed";
			$error[2] = $update_membership.$update_membership_year.$update_user.$update_group_member;
			echo json_encode($error);
		}
		else echo json_encode(true);
	}
	$db->close();

?>