<?php
require_once("headers/db_header.php");
require_once('config.php');
?>
<html>
	<head>
	<title>report from SAM</title>
	</head>
	<body class='wallpaper'>
		<div id="SamListRecent">
		<?php
		define ("HISTORY_COUNT", 50);
		if ($result_sam = $mysqli_sam->query("SELECT s.artist,s.title,s.album,s.composer,s.mood,h.date_played,h.duration FROM historylist as h INNER JOIN songlist as s ON s.id = h.songID WHERE s.songtype='S' order by h.date_played desc LIMIT ".HISTORY_COUNT)) {

			while($row = $result_sam->fetch_array())
			{
			$rows[] = $row;
			}
			$result_sam->close();
			$index = 0;
		}
		foreach($rows as $row)
		{
			$index ++;
			?>
			<div class="samsong" id="song-<?php echo $id; ?>">
				<span id="thisArtist"><?php echo $row['artist']; ?></span> - 
				<span id="thisSong"><?php echo $row['song']; ?></span>
				<span id="thisAlbum" class="invisible"><?php echo $row['$album']; ?></span>
				<span id="thisComposer" class="invisible"><?php echo $row['$composer']; ?></span>
				<span id="thisDate" class="date"><?php echo $row['$date']; ?></span>
				<span id="thisHour" class="invisible"><?php echo date("g",strtotime($row['date_played'])) ?></span>
				<span id="thisMinute" class="invisible"><?php echo  date("i",strtotime($row['date_played'])) ?></span>
				<span id="durMin" class="invisible"><?php echo (intVal($row['$duration']/60000) ?></span>
				<span id="durSec" class="invisible"><?php echo ($row['duration']/1000)%60) ?></span>
				<span id="pmCheck" class="invisible"><?php echo date("a",strtotime($row['date_played'])) ?></span>
				<span id="cancon" class="invisible"><?php echo (indexOf('cancon',$row['mood']) > 0 ? 1 : 0) ?></span>
				<span id="femcon" class="invisible"><?php echo (indexOf('femcon',$row['mood']) > 0 ? 1 : 0) ?></span>
				<span id="songCategory" class="invisible">
			</div>
		<?php
		}
		?>
		</div>
	</body>
</html>
