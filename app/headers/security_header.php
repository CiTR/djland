<?php
//SECURITY HEADER
require_once("db_header.php");
require_once("login_header.php");
date_default_timezone_set($station_info['timezone']);

//function to addslashes
function fas($some_string)
{
    return addslashes($some_string);
}

//function to check if cgi variable exists and has a value
function test_cgi($test_var)
{
    if (isset($_POST[$test_var]) && $_POST[$test_var]) {
        return true;
    } else {
        return false;
    }
}


//function to check group permissions, administrators and operators are in all groups...
function is_member($test_group)
{
    global $group_memberships,$db;
    if (!isset($_SESSION['sv_username'])) {
        return false;
    }
    $query = "SELECT * FROM group_members AS g INNER JOIN user AS u ON u.id = g.user_id WHERE u.username = '".$_SESSION['sv_username']."'";
    $result = $db['link']->query($query);
    $permissions = $result->fetch_assoc();
    if ($permissions[$test_group] == '1' || $permissions['operator'] == '1' || $permissions['administrator'] == '1') {
        return true;
    } else {
        return false;
    }
}

function permission_level()
{
    global $db, $sv_username, $djland_permission_levels;
    if (!isset($_SESSION['sv_id'])) {
        return -1;
    }
    $query = "SELECT gm.* FROM group_members AS gm INNER JOIN user AS u ON u.id = gm.user_id WHERE u.username='".$_SESSION['sv_username']."'";
    $result = $db['link']->query($query);
    $level = -1; //failure return value
    if ($result) {
        $permissions = $result->fetch_object();
        foreach ($permissions as $perm_level => $value) {
            if ($perm_level != 'user_id' && $value == '1' && $djland_permission_levels[$perm_level]['level'] > $level) {
                $level = $djland_permission_levels[$perm_level]['level'];
            }
        }
    } else {
        echo "Database Error:".mysqli_error($db['link']);
    }

    if (!is_paid() && ($level < $djland_permission_levels['staff']['level'])) {
        $level = 0;
    }
    return $level;
}

function is_paid()
{
    global $db;
    //Session contains member id.
    $query = "SELECT my.paid FROM membership_years as my INNER JOIN membership as m ON my.member_id = m.id WHERE my.member_id=:member_id AND ((my.membership_year >= (SELECT value FROM djland_options WHERE djland_option='membership_cutoff' LIMIT 1) AND my.paid='1') OR m.member_type='Lifetime' OR m.member_type='Staff') ORDER BY membership_year DESC";
    $statement = $db['pdo_link']->prepare($query);
    $statement->bindValue(':member_id', $_SESSION['sv_id']);
    try {
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $pdoe) {
        echo $pdoe->getMessage();
    }
    if (sizeof($result)>0) {
        return true;
    } else {
        return false;
    }
}
function is_trained()
{
    global $pdo_db;
    //Session contains member id.
    $query = "SELECT station_tour,programming_training,technical_training,production_training FROM membership WHERE id=:member_id";
    $statement = $pdo_db->prepare($query);
    $statement->bindValue(':member_id', $_SESSION['sv_id']);
    try {
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        #Uncomment below for what trainings you require before members are deemed "trained"
        #if($result['station_tour'] != '0' &&  $result['programming_training'] != '0' &&  $result['technical_training'] != '0' &&  $result['production_training'] != '0'){
        if ($result['programming_training'] != '0' &&  $result['technical_training'] != '0' &&  $result['production_training'] != '0') {
            return true;
        } else {
            return false;
        }
    } catch (PDOException $pdoe) {
        echo $pdoe->getMessage();
        return false;
    }
}


function has_show_access($show_id)
{
    global $db,$djland_permission_levels;

    if (permission_level() >= $djland_permission_levels['staff']['level']) {
        return true;
    }

    $query = 'SELECT count(member_id) AS count FROM member_show WHERE show_id = '.$show_id .' AND member_id = '.$_SESSION['sv_id'];
    if (!isset($show_id) || $result = mysqli_query($db['link'], $query)) {
        $count = mysqli_fetch_assoc($result);
        if ($count > 0) {
            return true;
        } else {
            return false;
        }
    } else {
        echo 'function has_show_access() could not check for show access - db problem:'.$query;
        return false;
    }
}

function users_show()
{
    global $db,$djland_permission_levels;
    if (permission_level() >= $djland_permission_levels['staff']['level']) {
        $query = "SELECT show_id FROM shows WHERE active ='1'";
    } else {
        $query = 'SELECT show_id FROM member_show WHERE member_id = '.$_SESSION['sv_id'];
    }
    if ($result = mysqli_query($db, $query)) {
        if (mysqli_num_rows($result) <= 0) {
            return false;
        }
        $show = mysqli_fetch_assoc($result);
        return $show['show_id'];
    } else {
        echo 'function users_show() could not check for show access - db problem:'.$query;
        return false;
    }
}

function users_channel($show = false)
{
    global $db;
    if ($show_id = users_show() || is_numeric($show)) {
        if (is_numeric($show)) {
            $show_id = $show;
        } else {
            $show_id = users_show();
        }

        $query = 'SELECT podcast_channel_id FROM shows WHERE id='.$show_id;

        if ($result = mysqli_query($db, $query)) {
            if (mysqli_num_rows($result) <= 0) {
                return false;
            }
            $channel = mysqli_fetch_assoc($result);
            $channel = $channel['podcast_channel_id'];
            return $channel;
        } else {
            echo ' function users_channel() could not check for show access - db problem:'.$query;
            return false;
        }
    } else {
        return false;
    }
}

//If not logged in, check for cookies, then make them log in...
if (!is_logged_in()) {
    logout();
    header("Location: .");
}

function cleanArray($array)
{
    if (is_array($array)) {
        foreach ($array as $key=>$value) {
            //Integers cannot have anything bad in them, so ignore them
            if (is_string($value)) {
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
    } else {
        return false;
    }

    return $array;
}
/*$_GET = cleanArray($_GET);
$_POST = cleanArray($_POST);*/

//END SECURITY HEADER
