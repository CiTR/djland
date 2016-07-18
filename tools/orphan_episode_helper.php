<html>
    <head>
        <link rel='stylesheet' href='../../../css/bootstrap.min.css'></script>
    </head>
    <body>
        <table class='table'>
            <tr><th>Podcast ID</th><th>Title</th><th>SQL Operation Status</th></tr>  
<?php
require_once('../headers/db_header.php');
//This is the fake playsheet generator


$orphan_podcast_query = "SELECT podcast_episodes.*,shows.host from podcast_episodes INNER JOIN shows ON shows.id = podcast_episodes.show_id WHERE playsheet_id = 0;";
$episode_statement = $pdo_db->prepare($orphan_podcast_query);
try{
	$episode_statement -> execute();
	$episodes  = $episode_statement->fetchAll(PDO::FETCH_ASSOC);
	
	$select_near_show_playsheets = "SELECT id,start_time FROM playsheets WHERE start_time LIKE :guess AND show_id = :show_id";
	$match_attempt_statement = $pdo_db->prepare($select_near_show_playsheets);

	$insert_fake_playsheet_query = "INSERT INTO playsheets (show_id,host,start_time,end_time,title,summary,create_date) 
	VALUES (:show_id,:host,:start_time,:end_time,:title,:summary,:create_date);";
	$insert_statement = $pdo_db->prepare($insert_fake_playsheet_query);
	
	$update_query = "UPDATE podcast_episodes SET playsheet_id = :playsheet_id WHERE id = :episode_id";
	$update_statement = $pdo_db->prepare($update_query);

	$response = array();
	$error = "";
	foreach($episodes as $episode){
		
		$match_attempt_string = date('Y-m-d',strtotime($episode['date']));
		$start_time = date('Y-m-d h:i:s',strtotime($episode['date']));
		$end_time = date('Y-m-d h:i:s',strtotime($start_time) + $episode['duration']);
		try{
			$match_attempt_statement->bindValue(':guess','%'.$match_attempt_string.'%');
			$match_attempt_statement->bindValue(':show_id',$episode['show_id']);
			$match_attempt_statement->execute();
			$matches = $match_attempt_statement->fetchAll(PDO::FETCH_ASSOC);

			if(count($matches) == 1){
				$best_match = $matches[0];
				$playsheet_id = $best_match['id'];				
			}else{		
				$insert_statement->bindValue(':show_id',$episode['show_id']);
				$insert_statement->bindValue(':host',$episode['host']);
				$insert_statement->bindValue(':start_time',$start_time);
				$insert_statement->bindValue(':end_time',$end_time);
				$insert_statement->bindValue(':title',$episode['title']);
				$insert_statement->bindValue(':summary',$episode['subtitle']." ".$episode['summary']);
				$insert_statement->bindValue(':create_date',$start_time);
				try{
					$insert_statement->execute();
					$playsheet_id = $pdo_db->lastInsertId();
				}catch(PDOException $pdoe){
					$error = "[QUERY] {$insert_fake_playsheet_query} [THROWS] ".$pdoe->getMessage();
				}
			}
			$update_statement->bindValue(':playsheet_id',$playsheet_id);
			$update_statement->bindValue(':episode_id',$episode['id']);

			try{
				$update_statement->execute();
			}catch(PDOException $pdoe){
				$error = "[QUERY] {$update_query} [THROWS] ".$pdoe->getMessage();
			}


			
			
		}catch(PDOException $pdoe){
			$error = "[QUERY] {$select_near_show_playsheets} [THROWS] ".$pdoe->getMessage();
		}
		echo "<tr". $error != "" ? " class='danger'" : "" . "><td>{$episode['id']}</td><td>".$episode['title']."</td><td>" . $error != "" ? $error : "Successful, playsheet id=".$playsheet_id. "</td></tr>";
	}
}catch(PDOException $pdoe){
	echo "[QUERY] {$orphan_podcast_query} [THROWS] ".$pdoe->getMessage();
}
?>
		</table>
	</body>
</html>