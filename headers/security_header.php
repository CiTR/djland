<?php
//SECURITY HEADER
//session_start();
require_once("db_header.php");
require_once("login_header.php");
date_default_timezone_set($station_info['timezone']);

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
	$query = "SELECT * FROM group_members AS g INNER JOIN user AS u ON u.userid = g.userid WHERE u.username = '".$_SESSION['sv_username']."'";
	$result = $db->query($query);
	$permissions = $result->fetch_assoc();
	
	if($permissions[$test_group] == '1' || $permissions['operator'] == '1' || $permissions['administrator'] == '1'){
		return true;
	}else{
		return false;
	}
}

function permission_level(){
	global $db, $sv_username, $djland_permission_levels;
	$query = "SELECT gm.operator,gm.administrator,gm.staff,gm.workstudy,gm.volunteer,gm.dj,gm.member FROM group_members AS gm INNER JOIN user AS u ON u.id = gm.user_id WHERE u.username='".$_SESSION['sv_username']."'";
	$result = $db->query($query);
	$level = -1; //failure return value
	if($result){
		$permissions = $result->fetch_object();
	    
		foreach($permissions as $level => $value){
			if( $value == '1'){
				$level = $djland_permission_levels[$level];
				break;
			}	
		}

	    if(is_paid()==false && ( $level < $djland_permission_levels['staff'] )){
	        $level = 0;
	    }
	}else{
		echo "Database Error:".mysqli_error($db);
	}

    return $level;
}

function is_paid(){
    global $pdo_db;
    //Session contains member id.
    $query = "SELECT paid FROM membership_years WHERE member_id=:member_id ORDER BY membership_year DESC";
    $statement = $pdo_db->prepare($query);
    $statement->bindValue(':member_id',$_SESSION['sv_id']);
    try{
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_NUM);
    }catch(PDOException $pdoe){
        echo $pdoe->getMessage();
    }
    if($result[0][0] == '1'){
        return true;
    }else{
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

//If not logged in, check for cookies, then make them log in...
if(!(is_logged_in() || cookie_login())) {
	logout();
	header("Location: .");
}

function cleanArray($array){
	if(is_array($array)){
		foreach($array as $key=>$value){
            //Integers cannot have anything bad in them, so ignore them
            if(is_string($value)) {
                $value = preg_replace("/script/i", "scrip t", $value); //no easy javascript injection
                $value = preg_replace("/union/i", "uni on", $value); //no easy common mysql temper

                $value = htmlentities($value, ENT_QUOTES); //encodes the string nicely
                $value = addslashes($value); //mysql_real_escape_string() //htmlentities

                if ($key == "UserID" || $key == "PageID") { //List variables that MUST be integers. Look at your mysql scheme and find every int(*) field.
                    $value = filter_var($value, FILTER_SANITIZE_NUMBER_INT); //Forces an integer
                }/* EXAMPLES
			elseif($key == "CountryCode" || $key == "StateCode"){
				$value = substr(trim($value),0,2); //Forces a max two character string
			}elseif($key == "arrivalDate" || $key == "departureDate"){
				$value = substr(trim($value),0,10); //Forces a max 10 character string. Could be also be tested by regular expression for a date value.
			}
			*/
                else {
                    $value = substr($value, 0, 800);
                    $value = trim(filter_var($value, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW)); //All weird chars will be stripped. I usually also limit the characters to (alpha)nummeric, spaces, and punctuation.
                }

                $array[$key] = $value;
            }
		}
	} else{
		return false;
	}

	return $array;
}
/*$_GET = cleanArray($_GET);
$_POST = cleanArray($_POST);*/

//END SECURITY HEADER
?>