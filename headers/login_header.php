<?php
//LOGIN HEADER
//$sv_username = "";
//$sv_login_fails = 0;
include_once('password.php');
$cookiename_id = "login";
$cookiename_pass = "pass";
function is_logged_in() {
	return isset($_SESSION['sv_username']) ? true : false;
}
function get_username() {
	return (is_logged_in() ? $_SESSION['sv_username'] : "Unknown");
}
function login ($username, $raw_password, $set_cookie) {
	//Got to do the global
	global $db,$pdo_db, $sv_username, $sv_login_fails;
	global $cookiename_id, $cookiename_pass;
	
	// Query for information relating to account.
	$user_query = "SELECT u.username, u.id, u.member_id, u.login_fails, u.password AS hash, gm.operator, gm.administrator, gm.staff, gm.workstudy,gm.volunteer,gm.dj,gm.member
					FROM user AS u INNER JOIN group_members AS gm ON u.id = gm.user_id WHERE (u.username = :username AND gm.operator='1') OR (u.username= :username AND u.status LIKE '%Enabled%')";
	
	
	try{
		$user_statement = $pdo_db->prepare($user_query);
		$user_statement->bindValue(':username',$username);
		$user_statement->execute();
		$user_result = $user_statement->fetch(PDO::FETCH_ASSOC);	


		if(password_verify($raw_password,$user_result['hash'])){
			session_start();
			$_SESSION['sv_username'] = $user_result['username'];
			$_SESSION['sv_id'] = $user_result['member_id'];
			$_SESSION['sv_login_fails'] = $user_result['login_fails'];
			/*	NOT USING COOKIES
				$cookie_value = hash(time());
				$insert_cookie = "UPDATE users SET cookie = :cookie WHERE username = :username";
				$cookie_statement = $pdo_db->prepare($insert_cookie);
				$cookie_statement->bindValue(':username',)
				if($set_cookie){
					setcookie($cookiename_id, $username, time() + 2678400);
					setcookie($cookiename_pass, time(), time() + 2678400);
				}
			*/
			return true;
		}else{
			echo "Incorrect Password";
			
			$login_fail_query = "UPDATE users SET login_fails = :login_fails WHERE id=:id";
			$login_fail_statement = $pdo_db->prepare($login_fail_query);
			$login_fail_statement ->bindValue(':login_fails',$user_result['login_fails']+1);
			$login_fail_statement ->bindValue(':id',$user_result['id']);

			return false;
			exit();
		}
	}catch(PDOException $pdoe){
		echo $pdoe->getMessage();
		return false;
		exit();
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