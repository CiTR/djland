 <?php
require("../headers/db_header.php");
require("../headers/function_header.php");
 $psid = $_POST["psid"];
 if(isset($psid)){
 $get_playitems = "SELECT pi.id AS id, song.artist AS artist, song.title AS album, song.song AS track, pi.is_playlist AS is_pl, pi.is_canadian AS is_can, pi.is_fem AS is_fem, pi.is_theme AS is_theme, pi.is_background AS is_bg FROM Playitems AS pi INNER JOIN songs AS song WHERE pi.playsheet_id = ".$psid." and song.id = pi.song_id";
 }else{
 $get_playitems = "SELECT pi.id AS id, song.artist AS artist, song.title AS album, song.song AS track, pi.is_playlist AS is_pl, pi.is_canadian AS is_can, pi.is_fem AS is_fem, pi.is_theme AS is_theme, pi.is_background AS is_bg FROM Playitems AS pi INNER JOIN songs AS song WHERE pi.playsheet_id = '127370' and song.id = pi.song_id";
 }
	
  //$get_playitems = "SELECT * FROM playitems WHERE playsheet_id = ".$psid;
 //query database for all songs played in this playlist, encode to json object and pass back.
 
  if($playitems = $db->query($get_playitems)){
	$rows = array();
	while($r = mysqli_fetch_array($playitems)){
		$rows[] = $r;	
	}
	echo json_encode($rows);
 } 
 $playitems->close();
 
 
 ?>