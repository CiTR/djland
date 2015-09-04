<?php
include('db_header.php');
$query = "SELECT id,summary FROM playsheets";
setlocale(LC_CTYPE, 'en_US.UTF8');

/*//UNENCODE
$result = $db->query($query);
while($row = $result->fetch_assoc() ){

	$row['summary'] = html_entity_decode(htmlspecialchars(htmlentities($row['summary'])));
	$summaries[] = $row;
}

foreach($summaries as $row){

	$query = "UPDATE podcast_episodes SET summary = '{$row['summary']}' WHERE id = ".$row['id'];
	//echo $query;
	if($db->query($query)){
		echo "{$row['id']} = success<br/>";
	}else{
		echo "{$row['id']} = fail<br/></br>";
	}
}*/
//REENCODE
$query = "SELECT id,summary FROM playsheets";
$result = $db->query($query);
while($row = $result->fetch_assoc() ){
	//$row['summary'] = html_entity_decode($row['summary']);
	$row['summary'] = htmlspecialchars(htmlentities($row['summary']), ENT_QUOTES);

	$summaries[] = $row;
}

foreach($summaries as $row){

	$query = "UPDATE podcast_episodes SET summary = '{$row['summary']}' WHERE id = ".$row['id'];
	//echo $query;
	if($db->query($query)){
		echo "{$row['id']} = success<br/>";
	}else{
		echo "{$row['id']} = fail<br/>";
	}
}
*/
