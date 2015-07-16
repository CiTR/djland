<?php

define('POD_CONFIG', true);


if ($_POST['gonsho2433456']	== 'kurbleziac_q3289476b30894276'){
require_once('podcast-config.php');


	function gettimestamp()
		{
			global $pod_nYear, $pod_nMonth, $pod_nDay;
			return mktime(0, 0, 0, $pod_nMonth, $pod_nDay, $pod_nYear);
		}


	$pod_db = mysqli_connect($pod_db_host, $pod_db_user, $pod_db_pass, $pod_db_database);
	if( !$pod_db )
	{
		die("Error connecting to the podcast Server");
		exit;
	}
	$pod_sqlcreate = "CREATE TABLE IF NOT EXISTS `podcast_timemarkers` (
	  `marker` datetime NOT NULL default '0000-00-00 00:00:00'
		) TYPE=MyISAM; ";
		
	$pod_result_create = mysqli_query($pod_db,$pod_sqlcreate);
	
	//	$pod_add_time = time();
		$pod_add_time = file_get_contents('http://137.82.188.13/time.php'); // make sure using same time as on the podcast server
		$pod_add_time_as_string = date("Y-m-d H:i:s",$pod_add_time);
		$pod_add_query = "INSERT INTO `podcast_timemarkers` (`marker`) VALUES ('$pod_add_time_as_string')";
		//echo "<h3>$pod_add_query</h3>";
		
		$pod_result_add = mysqli_query($pod_db,$pod_add_query);		
			
		if($pod_result_add)
		{
			echo "<h4>Time marker created at:</h4>$pod_add_time_as_string";
		}
		else
		{
			echo "<h4>An error occurred! Please contact technical services.</h4>";		
		}
		
}
else {
	die("nope");
	}
?>
