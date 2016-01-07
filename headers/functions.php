<?php
function getRequest($url){
	$curl = curl_init();
	$url = $_SERVER['SERVER_NAME']."/api2/public/".$url;
	$cookie = tempnam("/tmp","CURLCOOKIE");
	curl_setopt_array($curl, array(
			
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_COOKIEJAR => $cookie,
			CURLOPT_COOKIE => $cookie,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_URL => $url,
			CURLOPT_USERAGENT => 'Internal DJLand Request'
		)
	);
	
	$response = curl_exec($curl);
	curl_close($curl);
	return $response;
}

function getPodcasts($member_id){
	global $pdo_db,$djland_permission_levels;
	// If the user is staff or admin, they can access all channels, otherwise they only can see their own podcasts
	if(permission_level() >= $djland_permission_levels['staff'] ){
		$query = "SELECT s.name,s.id,count(pe.id) AS num_episodes FROM podcast_episodes AS pe INNER JOIN shows AS s ON s.id = pe.show_id GROUP BY s.id ORDER BY s.name ASC";
		$statement = $pdo_db->prepare($query);

	}else{
		$query = "SELECT s.name,s.id,count(pe.id) AS num_episodes FROM podcast_episodes AS pe INNER JOIN shows AS s ON s.id = pe.show_id INNER JOIN member_show AS ms ON s.id = ms.show_id WHERE ms.member_id =:member_id GROUP BY s.id ORDER BY s.name ASC";
		$statement = $pdo_db->prepare($query);
		$statement -> bindValue(':member_id',$member_id);
	}
	try{
		$statement->execute();
		$result = $statement->fetchAll(PDO::FETCH_ASSOC);

	}catch(PDOexception $pdoe){
		echo $pdoe->getMessage();
		return -1;
	}
	return $result;
}


function getPlaysheetInfo($playsheet_id){
	$show_id = getShowFromPlaysheet($playsheet_id);
	if($show_id > 0){
		$channel_id = getChannelFromShow($show_id);
		if($channel_id > 0){
			$playsheet = new stdClass();
			$playsheet->show_id = $show_id;
			$playsheet->channel_id = $channel_id;
		}else{
			return -1;
		}
	}
	return $playsheet;
}

function getShowFromPlaysheet($playsheet_id){
	global $pdo_db,$djland_permission_levels;
	//Get the show ID from the playsheet ID
	$query = "SELECT show_id FROM playsheets WHERE playsheet_id=:playsheet_id";
	$statement = $pdo_db->prepare($query);
	$statement->bindValue(":playsheet_id",$playsheet_id);
	try{
		$statement->execute();
		$result = $statement->fetchObject();
	}catch(PDOexception $pdoe){
		echo $pdoe->getMessage();
		return -1;
	}
	return $result['show_id'];
}


