<?php
	include_once("../headers/session_header.php");
	require_once(dirname($_SERVER['DOCUMENT_ROOT']).'/config.php');
date_default_timezone_set($station_info['timezone']);
$today = date("Y-m-d H:i:s");
if($_POST != null){
	$log_me = "<hr/> Error Logged at: ".$today."<br> Occured on page: ".$_SERVER['HTTP_REFERER']." <br/> Error: ".$_POST['data'];
	if(file_put_contents (dirname(__DIR__) .  '\logs\log.html' , $log_me, FILE_APPEND)){
		echo true;
	}
}


?>
