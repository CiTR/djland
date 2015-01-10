
<?php

define ("HISTORY_COUNT", 50);


require_once("headers/db_header.php");

require_once('config.php');
date_default_timezone_set($station_info['timezone']);

if ($result_sam = $mysqli_sam->query("SELECT * FROM historylist WHERE songtype='S' order by date_played desc LIMIT ".HISTORY_COUNT)) {

 //   printf("Select returned %d rows.\n", $result->num_rows);

	
while($row = $result_sam->fetch_array())
{
$rows[] = $row;
}

$result_sam->close();
$index = 0;
foreach($rows as $row)
{
	$id = $index++;
	$date = date("M j, g:ia",strtotime($row['date_played']));
	
	$catalog = $row['ISRC'];
	$artist = $row['artist'];
	$song = $row['title'];
	$album = $row['album'];
	$composer = $row['composer'];
	$durMin =  intval($row['duration']/60000);
	$durSec = ($row['duration']/1000)%60;
	$samSongID = $row['songID'];
	$samSongType = $row['songtype'];
	
	$hour = date("g",strtotime($row['date_played']));
	$minute = date("i",strtotime($row['date_played']));
	$pmCheck = date("a",strtotime($row['date_played']));
	
echo '<div class="samsong" id="song-'.$id.'">';  
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

if ($result_citr = $db->query("SELECT cancon,femcon,info FROM library WHERE catalog=".$catalog)) {	
	
$content = $result_citr->fetch_array();
echo '<span id="cancon" class="invisible">'.$content['cancon'].'</span><span id="femcon" class="invisible">'.$content['femcon'].'</span><span id="songCategory" class="invisible">'.$content['info'].'</span>';
$result_citr->close();
}



if ($result_sam = $mysqli_sam->query("SELECT lyrics FROM songlist WHERE id=".$samSongID)) {
	$songType = $result_sam->fetch_array();	

echo '<span id="songType" class="invisible">'.$songType['lyrics'].'</span>';
$result_sam->close();
}



echo '</div>';




}

}
$mysqli_sam->close();
$db->close();
?>
</div>
</body>
</html>
<?php


?>