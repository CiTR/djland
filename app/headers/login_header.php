<?php
//LOGIN HEADER
//$sv_username = "";
//$sv_login_fails = 0;

/*$cookiename_id = "login";
$cookiename_pass = "pass";*/

include_once("session_header.php");
//include_once('password.php');
function is_logged_in() {
	return (isset($_SESSION['sv_username']) && isset($_SESSION['sv_id']) ) ? true : false;
}
function get_username() {
	return (is_logged_in() ? $_SESSION['sv_username'] : "Unknown");
}
function login ($username, $raw_password) {
	//Got to do the global
	global $db, $sv_username, $sv_login_fails;
	global $cookiename_id, $cookiename_pass;

	// Query for information relating to account.
	$user_query = "SELECT u.username, u.id, u.member_id, u.login_fails, u.password AS hash, gm.operator, gm.administrator, gm.staff, gm.workstudy,gm.volunteer,gm.dj,gm.member
					FROM user AS u INNER JOIN group_members AS gm ON u.id = gm.user_id WHERE (u.username = :username AND gm.operator='1') OR (u.username= :username AND u.status LIKE '%Enabled%')";

	try{
		$user_statement = $db['pdo_link']->prepare($user_query);
		$user_statement->bindValue(':username',$username);
		$user_statement->execute();
		$user_result = $user_statement->fetch(PDO::FETCH_ASSOC);

		if(password_verify($raw_password,$user_result['hash'])){
			$status = session_status();
			switch($status){
				case PHP_SESSION_NONE:
					session_start();
					break;
				case PHP_SESSION_ACTIVE:
					session_destroy();
					session_start();
					break;
				default:
					//sessions not enabled
					break;
			}
			$_SESSION['sv_username'] = $user_result['username'];
			$_SESSION['sv_id'] = $user_result['member_id'];
			$_SESSION['sv_login_fails'] = $user_result['login_fails'];
			return true;
		}else{
			$login_fail_query = "UPDATE users SET login_fails = :login_fails WHERE id=:id";
			$login_fail_statement = $db['pdo_link']->prepare($login_fail_query);
			$login_fail_statement ->bindValue(':login_fails',$user_result['login_fails']+1);
			$login_fail_statement ->bindValue(':id',$user_result['id']);
			return false;
		}
	}catch(PDOException $pdoe){
		echo $pdoe->getMessage();
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
	if(!is_logged_in()) return;
	unset($_SESSION['sv_username']);
	unset($_SESSION['sv_id']);
	unset($_SESSION['sv_login_fails']);
	session_destroy();
	header("Location: index.php");
}
//END LOGIN HEADER
?>
