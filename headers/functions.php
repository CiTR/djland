<?php
//include_once('security_header.php');

function getPodcasts($member_id){
	global $pdo_db,$djland_permission_levels;
	// If the user is staff or admin, they can access all channels, otherwise they only can see their own podcasts
	if(permission_level() >= $djland_permission_levels['staff'] ){
		$query = "SELECT s.name,s.id,count(pe.id) AS num_episodes FROM podcast_episodes AS pe INNER JOIN podcast_channels AS pc ON pe.channel_id = pc.id INNER JOIN shows AS s ON s.podcast_channel_id = pc.id GROUP BY s.id ORDER BY s.name ASC";
		$statement = $pdo_db->prepare($query);

	}else{
		$query = "SELECT s.name,s.id,count(pe.id) AS num_episodes FROM podcast_episodes AS pe INNER JOIN podcast_channels AS pc ON pe.channel_id = pc.id INNER JOIN shows AS s ON s.podcast_channel_id = pc.id INNER JOIN member_show AS ms ON s.id = ms.show_id WHERE ms.member_id =:member_id GROUP BY s.id ORDER BY s.name ASC";
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
	echo $query;

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
function getChannelFromShow($show_id){
	global $pdo_db,$djland_permission_levels;
	//Get the podcast channel from the show
	$query = "SELECT podcast_channel_id AS id FROM shows WHERE id=:show_id";
	$statement = $pdo_db->prepare($query);
	$statement->bindValue(":show_id",$show_id);
	try{
		$statement->execute();
		$result = $statement->fetchObject();
	}catch(PDOexception $pdoe){
		echo $pdoe->getMessage();
		return -1;
	}
	return $result['id'];
}

