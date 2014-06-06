<?

require("../headers/db_header.php");
require("../headers/function_header.php");
require("../adLib.php");
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
$query = "SELECT s.song AS song, s.artist AS artist, s.title AS album, pi.is_canadian AS is_can, pi.is_playlist AS is_pl, pi.show_date AS date, sh.name AS show_name, pl.status AS status FROM songs AS s INNER JOIN playitems AS pi ON s.id = pi.song_id INNER JOIN shows AS sh ON sh.id = pi.show_id INNER JOIN playlists AS pl ON pi.playsheet_id = pl.id WHERE pi.show_date >= '2014-05-28' AND pi.show_date <= '2014-05-29' ORDER BY pi.show_id";

if($result = $db->query($query)){
	$charting = array();
	while($row = mysqli_fetch_array($result)){
		$charting[] = $row;
	}
}
echo json_encode($charting);
$result->close

?>