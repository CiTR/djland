<?php
	require("../headers/db_header.php");
	require("../headers/function_header.php");
	
	$username = getPost('username');
	$password = getPost('password');
	$firstname = getPost('firstname');
	$lastname = getPost('lastname');
	$email = getPost('email');
	$phone = getPost('phone');
	$cellphone = getPost('cellphone');
	$member_type = getPost('member_type');
	if($member_type = 'Student'){
		$faculty = getPost('faculty');
	}
	else{
		$faculty = null;
	}
	$gender = getPost('gender');
	switch(getPost('canadian')){
		case 'Canadian Citizen':
			$canadian = 2;
			break;
		case 'Landed Immigrant':
			$canadian = 1;
			break;
		case 'Visitor':
			$canadian = 0;
	}
	$music = getPost('music');
	$sports = getPost('sports');
	$live_broadcast = getPost('live_broadcast');
	$ads_psa = getPost('ads_psa');
	$discorder = getPost('discorder');
	$news = getPost('news');
	$tech = getPost('tech');
	$outreach = getPost('outreach');
	$show_hosting = getPost('show_hosting');
	$arts = getPost('arts');
	$prog_comm = getPost('prog_comm');
	$about = getPost('about');
	$skills = getPost('skills');

	$insert_membership = "INSERT INTO membership ('firstname','lastname','canadian_citizen','address','city')
	$insert_user = "INSERT INTO user ('username','".password_hash('password',PASSWORD_DEFAULT)."',

?>