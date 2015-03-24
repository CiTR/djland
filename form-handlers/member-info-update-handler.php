<?php
	session_start();
	require("../headers/db_header.php");
	$json = str_replace('&quot;','"', $_POST['member']);
	$member = json_decode($json,true);
	//echo json_last_error();
	print_r($member);
	$member_id = $member['id'];
	//$userid = $member['userid'];
	$firstname = htmlentities($member['firstname'],ENT_QUOTES,'UTF-8');
	$lastname = htmlentities($member['lastname'],ENT_QUOTES,'UTF-8');
	$address = htmlentities($member['address'],ENT_QUOTES,'UTF-8');
	$city = htmlentities($member['city'],ENT_QUOTES,'UTF-8');
	$province = $member['province'];
	$postalcode = $member['postalcode'];
	$canadian_citizen = $member['canadian_citizen'];
	$member_type = $member['member_type'];
	if($member_type == 'Student' || $member_type=='student'){
		$integrate = $member['integrate'];
		$faculty = $member['faculty'];
		$schoolyear = $member['schoolyear'];
		$student_no = $member['student_no'];
	}else{
		$integrate = null;
		$faculty = null;
		$schoolyear = null;
		$student_no = null;
	}
	$has_show = $member['has_show'];
	$show_name = htmlentities($member['show_name'],ENT_QUOTES,'UTF-8');
	$is_new = $member['is_new'];
	$alumni = $member['alumni'];
	$since = $member['since'];
	$email = htmlentities($member['email'],ENT_QUOTES,'UTF-8');
	$primary_phone = $member['primary_phone'];
	$secondary_phone = $member['secondary_phone'];
	$about = htmlentities($member['about'],ENT_QUOTES,'UTF-8');
	$skills = htmlentities($member['skills'],ENT_QUOTES,'UTF-8');
	$exposure = htmlentities($member['exposure'],ENT_QUOTES,'UTF-8');
	$comments = htmlentities($member['comments'],ENT_QUOTES,'UTF-8');


	if($member_type != 'Student' && $member_type != 'student'){
		$update_membership = "UPDATE membership SET firstname='".$firstname."',lastname='".$lastname."',address='".$address."',city='".$city."',province='".$province."',postalcode='".$postalcode."',canadian_citizen='".$canadian_citizen."',member_type='".$member_type."',is_new='".$is_new."',alumni='".$alumni."',since='".$since."',has_show='".$has_show."',show_name='".$show_name."',email='".$email."',primary_phone='".$primary_phone."',secondary_phone='".$secondary_phone."',about='".$about."',skills='".$skills."',exposure='".$exposure."',".($comments != null ? "comments='".$comments : "")."' WHERE id='".$member_id."';";	
	}else{
		$update_membership = "UPDATE membership SET firstname='".$firstname."',lastname='".$lastname."',address='".$address."',city='".$city."',province='".$province."',postalcode='".$postalcode."',canadian_citizen='".$canadian_citizen."',member_type='".$member_type."',is_new='".$is_new."',alumni='".$alumni."',since='".$since."',faculty='".$faculty."', schoolyear='".$schoolyear."',integrate='".$integrate."',student_no='".$student_no."',has_show='".$has_show."',show_name='".$show_name."',email='".$email."',primary_phone='".$primary_phone."',secondary_phone='".$secondary_phone."',about='".$about."',skills='".$skills."',exposure='".$exposure."',".($comments != null ? "comments='".$comments : "")."' WHERE id='".$member_id."';"; 	
	}
	//$result = $db->prepare("UPDATE membership SET")
	$fail = false;
	$result = $db->query($update_membership);
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