<?php 
if (file_exists('config.php')){ 
// this check is because sometimes this script is accessed from the root 
// and sometimes from a subfolder (like with an AJAX handler inside form-handlers)
	require_once('config.php');
} else {
	require_once('../config.php');
}
date_default_timezone_set($station_info['timezone']);
$today = date("Y-m-d H:i:s");
$log_me = "<hr/> Error Logged at: ".$today."<br> Occured on page: ".$_SERVER['HTTP_REFERER']." <br/> Error: ".$_POST['data'];
if(file_put_contents (dirname(__DIR__) .  '\logs\log.html' , $log_me, FILE_APPEND)){
	echo true;
}

?>