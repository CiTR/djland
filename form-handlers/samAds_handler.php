<?php
include_once("../headers/session_header.php");
require_once("../headers/db_header.php");
require_once("../headers/function_header.php");

error_reporting(E_ALL);
$today = date('Y-m-d');
$from = $today;
$to = $today;
$adname = "";
$filter = false;
if(isset($_POST['from'])){
	$from = str_replace('/','-',$_POST['from'])." 00:00:00";
	$to = str_replace('/','-',$_POST['to'])." 23:59:59";
}else{
	$from = $from." 00:00:00";
	$to = $to." 23:59:59";
}
if(isset($_POST['adname']) && $_POST['adname'] != ""){
	$adname = $_POST['adname'];
	$filter = true;
}
if($filter == true){
	$query = "SELECT filename, date_played FROM historylist WHERE date_played > '{$from}' AND date_played <= '{$to}' AND (songtype = 'A' OR filename LIKE '%PRIORITY ADs%') AND (filename LIKE '%".$adname."%' OR title LIKE '%".$adname."%' OR artist LIKE '%".$adname."%') ORDER BY date_played DESC";
}else{
	$query = "SELECT filename, date_played FROM historylist WHERE date_played > '{$from}' AND date_played <= '{$to}' AND (songtype = 'A' OR filename LIKE '%PRIORITY ADs%') ORDER BY date_played DESC";
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
