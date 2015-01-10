
<?php

define ("HISTORY_COUNT", 50);

require_once("headers/db_header.php");

require_once('config.php');
date_default_timezone_set($station_info['timezone']);

if ($result_sam = $mysqli_sam->query("SELECT artist,album,title,duration,ISRC,date_played,songID FROM historylist WHERE songtype='S' order by date_played desc LIMIT ".HISTORY_COUNT)) {

 //   printf("Select returned %d rows.\n", $result->num_rows);


	while($row = $result_sam->fetch_array())
	{
	$rows[] = $row;
	}

	$result_sam->close();
	$index = 0;
		foreach($rows as $i => $row)
		{
			$id = $index++;
			$date = date("M j, g:ia",strtotime($row['date_played']));

			$catalog = $row['ISRC'];
			$artist = $row['artist'];
			$song = $row['title'];
			$album = $row['album'];
//			$composer = $row['composer'];
//			$durMin =  intval($row['duration']/60000);
//			$durSec = ($row['duration']/1000)%60;
			$samSongID = $row['songID'];
//			$samSongType = $row['songtype'];

			$hour = date("g",strtotime($row['date_played']));
			$minute = date("i",strtotime($row['date_played']));
			$pmCheck = date("a",strtotime($row['date_played']));


		//if ($result_citr = $db->query("SELECT cancon,femcon FROM library WHERE catalog=".$catalog)) {

		if ($result_citr = $db->query("SELECT cancon,femcon,info FROM library WHERE catalog=".$catalog)) {

			$content = $result_citr->fetch_array();
			$result_citr->close();

			$rows[$i]['cancon'] = $content['cancon']==1? true : false;
			$rows[$i]['femcon'] = $content['femcon']==1? true : false;
			$rows[$i]['info'] = $content['info'];

		} else {
			$rows[$i]['in_citr_lib'] = false;
			$rows[$i]['cancon'] = false;
			$rows[$i]['femcon'] = false;
		}



		if ($result_sam = $mysqli_sam->query("SELECT lyrics FROM songlist WHERE id=".$samSongID)) {
			$songType = $result_sam->fetch_array();

			$result_sam->close();

			$rows[$i]['lyrics'] = $songType['lyrics'];

		}



		}

	echo json_encode($rows);
}
$mysqli_sam->close();
$db->close();


