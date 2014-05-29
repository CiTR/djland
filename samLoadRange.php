<html>
<head>
<title>report from SAM</title>
</head>
<body>

<div id="SamListRange">
<?php

require_once("headers/db_header.php");

require_once('headers/config.php');
date_default_timezone_set($station_info['timezone']);
session_start();

if($_POST['from']){
$starting = $_POST['from'];
$finishing = $_POST['to'];
} else {
	
	$starting = '2013-05-29 12:00:00';
	$finishing = '2013-05-29 15:00:00 ';
}

if ($result_sam = $mysqli_sam->query("SELECT * FROM historylist WHERE date_played >= '".$starting."' AND date_played <= '".$finishing."' AND songtype='S' order by date_played desc ")) {


//   printf("Select returned %d rows.\n", $result->num_rows);

	
while($row = $result_sam->fetch_array())
{
$rows[] = $row;
}

$index = 0;
foreach($rows as $row)
{
	$id = $index++;
	$date = $row['date_played'];
	$catalog = $row['ISRC'];
	$artist = html_entity_decode($row['artist']);
	$song = html_entity_decode($row['title']);
	$album = html_entity_decode($row['album']);
	$composer = $row['composer'];
	$durMin =  intval($row['duration']/60000);
	$durSec = ($row['duration']/1000)%60;
	$samSongID = $row['songID'];
	$samSongType = $row['songtype'];

	$hour = date("g",strtotime($row['date_played']));
	$minute = date("i",strtotime($row['date_played']));
	$pmCheck = date("a",strtotime($row['date_played']));


echo ' <div class="samsong" id="song-'.$id.'"> ';  
echo '<span id="thisArtist">'.$artist.'</span> - ';
echo '&quot;<span id="thisSong">'.$song.'</span>&quot; ';
echo '<span id="thisAlbum" class="invisible">'.$album.'</span>';
echo '<span id="thisComposer" class="invisible">'.$composer.'</span>';
echo '<span id="thisDate" class="date">'.$date.'</span>';
echo '<span id="thisHour" class="invisible">'.$hour.'</span>';
echo '<span id="thisMinute" class="invisible">'.$minute.'</span>';
echo '<span id="durMin" class="invisible">'.$durMin.'</span>';
echo '<span id="durSec" class="invisible">'.$durSec.'</span>';
echo '<span id="pmCheck" class="invisible">'.$pmCheck.'</span>';

//if ($result_citr = $db->query("SELECT cancon,femcon FROM library WHERE catalog=".$catalog)) {


if ($result_citr = $db->query("SELECT cancon,femcon FROM library WHERE catalog=".$catalog)) {
	
$content = $result_citr->fetch_array();
echo '<span id="cancon" class="invisible">'.$content['cancon'].'</span><span id="femcon" class="invisible">'.$content['femcon'].'</span>';
$result_citr->close();
} else echo 'content not available';


$query = "SELECT lyrics FROM songlist WHERE id=".$samSongID;
if ($result_sam = $mysqli_sam->query($query)) {
	$songType = $result_sam->fetch_array();	

//echo '<span id="songType" class="invisible">'.$songType['lyrics'].'</span>';
$result_sam->close();


} else echo ' not available - ';

echo '</div>';



// end for each row
}



    /* free result set */
    $result_sam->close();
}

?>
</div>
</body>
</html>
<?php


?>