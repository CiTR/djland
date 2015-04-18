<?php
//SECURITY HEADER
require_once("db_header.php");
require_once("login_header.php");

//Remove slashes added by stupid magic quotes
if(get_magic_quotes_gpc()==1) {
	foreach($_GET as $key=>$var) $_GET["$key"] = stripslashes($var);
	foreach($_POST as $key=>$var) $_POST["$key"] = stripslashes($var);
	foreach($_COOKIE as $key=>$var) $_COOKIE["$key"] = stripslashes($var);
}

//function to addslashes
function fas($some_string) {
	return addslashes($some_string);
}

//function to check if cgi variable exists and has a value
function test_cgi($test_var) {
	if(isset($_POST[$test_var]) && $_POST[$test_var]) {
		return true;
	}
	else {
		return false;
	}
}

//function to check group permissions, administrators and operators are in all groups...
function is_member($test_group) {

	global $group_memberships,$db;


	if(!isset($_SESSION['sv_username'])) {
		return false;
	}
	$query = "SELECT * FROM group_members INNER JOIN user on user.userid = group_members.userid WHERE username='".$_SESSION['sv_username']."'";
	$result = mysqli_query($db,$query);
	$members=array();
	$row = mysqli_fetch_array($result);
	if( $row[$test_group] == '1' || $row['operator'] == '1' || $row['administrator'] == '1'){
		return true;
	}
	else{
		return false;
	}
}

function has_show_access($show_id){
	global $db;
	$query = 'SELECT member_id FROM member_show WHERE show_id = '.$show_id .' AND member_id = '.$_SESSION['sv_id'];


	if ( !isset($show_id) || $result = mysqli_query($db, $query)) {
		$access = mysqli_fetch_assoc($result);
		$access = $access['member_id'] == $_SESSION['sv_id'];
		return $access;
			} else {
		echo ' could not check for show access - db problem:'.$query;
		return false;
	}
}

function users_show(){
	global $db;
	$query = 'SELECT show_id FROM member_show WHERE member_id = '.$_SESSION['sv_id'];

	if ( $result = mysqli_query($db, $query)) {
		if(mysqli_num_rows($result) <= 0) return false;
		$show = mysqli_fetch_assoc($result);
		$show = $show['show_id'];
		return $show;
	} else {
		echo ' could not check for show access - db problem:'.$query;
		return false;
	}
}

function users_channel(){
	global $db;
	if ($show_id = users_show()){
		$query = 'SELECT podcast_channel_id FROM shows WHERE id='.$show_id;

		if ( $result = mysqli_query($db, $query)) {
			if(mysqli_num_rows($result) <= 0) return false;
			$channel = mysqli_fetch_assoc($result);
			$channel = $channel['podcast_channel_id'];
			return $channel;
		} else {
			echo ' could not check for show access - db problem:'.$query;
			return false;
		}


	} else {
		return false;

	}
}

//If not logged in, check for cookies, then make them log in...
if(!(is_logged_in() || cookie_login())) {
	logout();
	header("Location: .");
}

function cleanArray($array){
	if(is_array($array)){
		foreach($array as $key=>$value){

			$value = preg_replace("/script/i","scrip t",$value); //no easy javascript injection
			$value = preg_replace("/union/i","uni on",$value); //no easy common mysql temper

			$value = htmlentities($value, ENT_QUOTES); //encodes the string nicely
			$value = addslashes($value); //mysql_real_escape_string() //htmlentities

			if($key == "UserID" || $key == "PageID"){ //List variables that MUST be integers. Look at your mysql scheme and find every int(*) field.
				$value = filter_var($value, FILTER_SANITIZE_NUMBER_INT); //Forces an integer
			}/* EXAMPLES
			elseif($key == "CountryCode" || $key == "StateCode"){
				$value = substr(trim($value),0,2); //Forces a max two character string
			}elseif($key == "arrivalDate" || $key == "departureDate"){
				$value = substr(trim($value),0,10); //Forces a max 10 character string. Could be also be tested by regular expression for a date value.
			}
			*/
			else{
				$value = substr($value,0,800);
				$value = trim(filter_var($value, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW)); //All weird chars will be stripped. I usually also limit the characters to (alpha)nummeric, spaces, and punctuation.
			}

			$array[$key] = $value;
		}
	} else{
		return false;
	}

	return $array;
}
$_GET = cleanArray($_GET);
$_POST = cleanArray($_POST);

//END SECURITY HEADER
?>