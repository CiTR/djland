<?
require("../headers/db_header.php");
require("../headers/function_header.php");
require("../adLib.php");
$today = date('m/d/Y');
$from = $today;
$to = $today;
if(isset($_POST['from'])){
	$from = $_POST['from'];
	$to = $_POST['to'];
}
$from = strtotime($from);
$to = strtotime($to)+ 24*60*60;
$from = date("Y-m-d H:i:s",$from);
$to = date("Y-m-d H:i:s",$to);

$query = "SELECT filename, date_played FROM historylist WHERE date_played >= '$from' AND date_played <= '$to' AND songtype = 'A' ORDER BY date_played DESC";
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