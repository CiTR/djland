<?php
//LOGIN HEADER

//$sv_username = "";
//$sv_login_fails = 0;
$cookiename_id = "login";
$cookiename_pass = "md5";

function is_logged_in() {
	return isset($_SESSION['sv_username']) ? true : false;
}

function get_username() {
	return (is_logged_in() ? $_SESSION['sv_username'] : "Unknown");
}

function login ($the_username, $the_md5, $set_cookie) {

	//Got to do the global
	global $db, $sv_username, $sv_login_fails;
	global $cookiename_id, $cookiename_pass;

	//if they are logging in with an id, change it to username.
	if(mysqli_num_rows($result = mysqli_query($db,"SELECT * FROM user WHERE userid = '".$the_username."' AND password = '".$the_md5."'"))) {
		$the_username = mysqli_result_dep($result,0,"username");
	}

	//Operator accounts cannot be disabled...	
	$is_operator = mysqli_num_rows(mysqli_query($db,"SELECT * FROM group_members WHERE username='".$the_username."' AND groupname='operator'"));
	if($is_operator) {
		$result = mysqli_query($db,"SELECT * FROM user WHERE username = '".$the_username."' AND password = '".$the_md5."'");
	}
	else {
		$result = mysqli_query($db,"SELECT * FROM user WHERE username = '".$the_username."' AND password = '".$the_md5."' AND status!='Disabled'");
	}
	
	//if match found, log in, clear login failures
	if(mysqli_num_rows($result)) {
		if($set_cookie) {		
			setcookie($cookiename_id, mysqli_result_dep($result,0,"userid"), time() + 2678400);
			setcookie($cookiename_pass, mysqli_result_dep($result,0,"password"), time() + 2678400);
		}
		$_SESSION['sv_username'] = mysqli_result_dep($result,0,"username");
		$_SESSION['sv_login_fails'] = mysqli_result_dep($result,0,"login_fails");
		$result = mysqli_query($db,"UPDATE user SET login_fails='0' WHERE username = '".$the_username."'");
		return true;
	}
	else {
		if($set_cookie) {
			setcookie($cookiename_id);
			setcookie($cookiename_pass);
		}
		$result = mysqli_query($db,"SELECT * FROM user WHERE username = '".$the_username."'");
		if(mysqli_num_rows($result)) {
			$login_fails = mysqli_result_dep($result,0,"login_fails") + 1;
			if($login_fails > 20) {
				$result = mysqli_query($db,"UPDATE user SET login_fails='$login_fails', status='Disabled' WHERE username = '".$the_username."'");
			}
			else {
				$result = mysqli_query($db,"UPDATE user SET login_fails='$login_fails' WHERE username = '".$the_username."'");
			}
		}
		return false;
	}
}

function cookie_login () {
	global $cookiename_id, $cookiename_pass;
	if(isset($_COOKIE[$cookiename_id]) && isset($_COOKIE[$cookiename_pass])) {
		$the_username = $_COOKIE[$cookiename_id];
		$the_md5 = $_COOKIE[$cookiename_pass];
		if(login($the_username, $the_md5, true)) {
			return true;
		}
		else {
			return false;
		}
	}
	else {
		return false; //second false, should have an error level, but who cares
	}
}

function logout () {
	global $cookiename_id, $cookiename_pass;
	//unset any cookies
	if(isset($_COOKIE[$cookiename_id]) && isset($_COOKIE[$cookiename_pass])) {
		setcookie($cookiename_id);
		setcookie($cookiename_pass);
	}
	unset($_SESSION['sv_username']);
	session_destroy();
}
//END LOGIN HEADER
?>