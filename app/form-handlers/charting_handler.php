<?php

include_once("../headers/session_header.php");
require("../headers/db_header.php");
require("../headers/function_header.php");

$today = date('Y-m-d');
$from = strtotime('-1 week last friday');
$to = strtotime('last thursday');
if(isset($_POST['from'])){
	$from = $_POST['from'];
	$to = $_POST['to'];
	$from = strtotime($from);
	$to = strtotime($to);
}

$from = date("Y/m/d",$from);
$to = date("Y/m/d",$to);
/*
 * Returns: song,artist,album,is_can,is_pl,date,show_name
 */

$query = "SELECT pi.song, pi.artist,pi.album, pi.is_canadian,pi.is_playlist, p.start_time AS date, sh.name AS show_name, p.status AS status, p.id
	FROM playitems as pi INNER JOIN shows as sh ON sh.id = pi.show_id
	INNER JOIN playsheets as p ON pi.playsheet_id = p.id
	WHERE pi.show_date >= :from AND  pi.show_date <= :to
	ORDER BY p.start_time ASC ";

$statement = $pdo_db->prepare($query);
$statement->bindValue(':from',$from);
$statement->bindValue(':to',$to);

try{
	$statement->execute();
	$result = new stdClass();
	$result = $statement -> fetchAll(PDO::FETCH_ASSOC);
	echo json_encode($result);
}catch(PDOException $pdoe){
	echo "ERROR:". $pdoe->getMessage();
 	http_response_code(500);
}

?>
