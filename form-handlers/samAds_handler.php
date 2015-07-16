<?php
	session_start();
require_once("../headers/db_header.php");
require_once("../headers/function_header.php");
require_once("../adLib.php");
$today = date('m/d/Y');
$from = $today;
$to = $today;
$filter = false;
if(isset($_POST['from'])){
	$from = $_POST['from'];
	$to = $_POST['to'];
}
if(isset($_POST['adname'])){
	$adname = $_POST['adname'];
	$filter = true;
}
$from = strtotime($from);
$to = strtotime($to)+ 24*60*60;
$from = date("Y-m-d H:i:s",$from);
$to = date("Y-m-d H:i:s",$to);
if($filter == true){
	$query = "SELECT filename, date_played FROM historylist WHERE date_played >= '$from' AND date_played <= '$to' AND songtype = 'A' AND filename LIKE '%".$adname."%' ORDER BY date_played DESC";
}else{
	$query = "SELECT filename, date_played FROM historylist WHERE date_played >= '$from' AND date_played <= '$to' AND songtype = 'A' ORDER BY date_played DESC";
}

if($result = $mysqli_sam->query($query)){
	$adPlays=array();
	while($row = mysqli_fetch_array($result)){
		$row['date_unix'] = strtotime($row['date_played']);
		$adPlays[] = $row;
	}
}
echo json_encode($adPlays);
$result->close();
?>