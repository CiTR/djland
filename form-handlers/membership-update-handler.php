<?php
	require("../headers/db_header.php");
	require("../headers/function_header.php");
	require("../headers/password.php");
	
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
	$paid = $_POST['paid'];
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
	$dj = $_POST['dj'];
	$tabling = $_POST['tabling'];
	$other = htmlentities($_POST['other'],ENT_QUOTES,'UTF-8');
	$about = htmlentities($_POST['about'],ENT_QUOTES,'UTF-8');
	$skills = htmlentities($_POST['skills'],ENT_QUOTES,'UTF-8');
	$exposure = htmlentities($_POST['exposure'],ENT_QUOTES,'UTF-8');
	$comments = htmlentities($_POST['comments'],ENT_QUOTES,'UTF-8');
	$membership_year = $_POST['membership_year'];
	$is_member = $_POST['is_member'];
	$is_dj = $_POST['is_dj'];
	$is_administrator = $_POST['is_administrator'];
	$is_add_user = $_POST['is_add_user'];
	$is_add_show = $_POST['is_add_show'];
	$is_edit_dj = $_POST['is_edit_dj'];
	$is_library = $_POST['is_library'];
	$is_membership = $_POST['is_membership'];
	$is_edit_library = $_POST['is_edit_library'];
	$new_password = $_POST['password'];


	if($member_type != 'Student' && $member_type != 'student'){
		$update_membership = "UPDATE membership SET firstname='".$firstname."',lastname='".$lastname."',address='".$address."',city='".$city."',province='".$province."',postalcode='".$postalcode."',canadian_citizen='".$canadian_citizen."',member_type='".$member_type."',is_new='".$is_new."',alumni='".$alumni."',since='".$since."',has_show='".$has_show."',show_name='".$show_name."',email='".$email."',primary_phone='".$primary_phone."',secondary_phone='".$secondary_phone."',about='".$about."',skills='".$skills."',exposure='".$exposure."',comments='".$comments."' WHERE id='".$member_id."';";	
	}else{
		$update_membership = "UPDATE membership SET firstname='".$firstname."',lastname='".$lastname."',address='".$address."',city='".$city."',province='".$province."',postalcode='".$postalcode."',canadian_citizen='".$canadian_citizen."',member_type='".$member_type."',is_new='".$is_new."',alumni='".$alumni."',since='".$since."',faculty='".$faculty."', schoolyear='".$schoolyear."',integrate='".$integrate."',student_no='".$student_no."',has_show='".$has_show."',show_name='".$show_name."',email='".$email."',primary_phone='".$primary_phone."',secondary_phone='".$secondary_phone."',about='".$about."',skills='".$skills."',exposure='".$exposure."',comments='".$comments."' WHERE id='".$member_id."';"; 	
	}
	$update_membership_year = "UPDATE membership_years SET paid='".$paid."',sports='".$sports."',music='".$music."',arts='".$arts."',show_hosting='".$show_hosting."',live_broadcast='".$live_broadcast."',ads_psa='".$ads_psa."',tech='".$tech."',news='".$news."',programming_committee='".$prog_comm."',promotions_outreach='".$outreach."',discorder='".$discorder."',discorder_2='".$discorder_2."',digital_library='".$digital_library."',photography='".$photography."',dj='".$dj."',tabling='".$tabling."',other='".$other."' WHERE member_id='".$member_id."' AND membership_year='".$membership_year."';";
	if($new_password != null){
		$update_user = "UPDATE user SET password='".password_hash($new_password,PASSWORD_DEFAULT)."' WHERE userid='".$userid."';";
	}else{
		$update_user = "Password Not changed";
	}
	$update_group_member = "UPDATE group_members  SET member='".$is_member."',dj='".$is_dj."',administrator='".$is_administrator."',adduser='".$is_add_user."',addshow='".$is_add_show."',editdj='".$is_edit_dj."',library='".$is_library."',membership='".$is_membership."',editlibrary='".$is_edit_library."' WHERE userid ='".$userid."';";
	$fail=false;
	$db->query("START TRANSACTION");
	$error[0] = "ERROR";
	
	$result = $db -> query($update_membership);
	if(!$result){
		$fail = true;
		$error[1] = "Error with member update ";
		$error[2] = mysqli_error($db);
		$error[3] = $update_membership;
	}
	else{
		$result = $db -> query($update_membership_year);
		if(!$result){
			$fail = true;
			$error[1] = "Error with membership year update ";
			$error[2] = mysqli_error($db);
		}
		else{
			$result = $db -> query($update_group_member);
			if(!$result){
				$error[1] = "Error with permission update";
				$error[2] = mysqli_error($db);
				$fail = true;
			}else{
				if($new_password != null){
					$result = $db -> query($update_user);
					if(!$result){
					$error[1] = "Error with user update ";
					$error[2] = mysqli_error($db);
					$fail = true;
					}
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
			$error[2] = $update_membership.$update_membership_year.$update_user.$update_group_member;
		}
		echo json_encode($error);
	}else{
		if( !( $db -> commit() ) ){
			$error[1] = " Commit failed";
			$error[2] = $update_membership.$update_membership_year.$update_user.$update_group_member;
			echo json_encode($error);
		}
		else echo json_encode($update_membership.$update_membership_year.$update_user.$update_group_member);
	}
	$db->close();
?>