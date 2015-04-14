<?php
//LOGIN HEADER
//$sv_username = "";
//$sv_login_fails = 0;
$cookiename_id = "login";
$cookiename_pass = "pass";
function is_logged_in() {
	return isset($_SESSION['sv_username']) ? true : false;
}
function get_username() {
	return (is_logged_in() ? $_SESSION['sv_username'] : "Unknown");
}
function login ($the_username, $the_password, $set_cookie) {
	//Got to do the global
	global $db, $sv_username, $sv_login_fails;
	global $cookiename_id, $cookiename_pass;
	
	//Operator accounts cannot be disabled...
	
	$result = $db->query("SELECT * FROM group_members INNER JOIN user on user.userid = group_members.userid WHERE user.username = '".$the_username."' AND group_members.operator='1'");
	
	$is_operator = mysqli_num_rows($result);
	if($is_operator) {
		$result = $db -> query ("SELECT * FROM user WHERE username = '".$the_username."'");
	}

	$row = $result->fetch_assoc();
	$hash = $row['password'];
	//if match found, log in, clear login failures
	$success = password_verify($the_password,$hash);
	
	if($success){
		if($set_cookie) {		
			setcookie($cookiename_id, $the_username, time() + 2678400);
			setcookie($cookiename_pass, $the_password, time() + 2678400);
		}
		$_SESSION['sv_username'] = mysqli_result_dep($result,0,"username");
		$_SESSION['sv_id'] = mysqli_result_dep($result,0,"member_id");
		$_SESSION['sv_login_fails'] = mysqli_result_dep($result,0,"login_fails");
		$result = $db-> query("UPDATE user SET login_fails='0' WHERE username = '".$the_username."'");
		return true;
	}
	else {
		$result = $db->query("SELECT login_fails FROM user WHERE username = '".$the_username."'");
		$row = mysqli_fetch_assoc($result);
		$login_fails = $row['login_fails'] + 1;
		if($login_fails >= 20){
			$db->query("UPDATE user SET login_fails='".$login_fails."', status='Disabled' WHERE username = '".$the_username."'");
		}else{
			$db->query("UPDATE user SET login_fails='".$login_fails."' WHERE username = '".$the_username."'");
		}
		return false;
	}	
}
function cookie_login () {
	global $cookiename_id, $cookiename_pass;
	if(isset($_COOKIE[$cookiename_id]) && isset($_COOKIE[$cookiename_pass])) {
		$the_username = $_COOKIE[$cookiename_id];
		$the_password = $_COOKIE[$cookiename_pass];
		if(login($the_username, $the_password, true)) {
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