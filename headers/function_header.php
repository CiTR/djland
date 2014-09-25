<?php
//FUNCTION HEADER - playlist.citr.ca

error_reporting(0);

date_default_timezone_set($station_info['timezone']);
/*//Membership status, index by Name/ID
$fresult = mysqli_query($db,"SELECT * FROM membership_status ORDER BY 'sort', 'name'");
$fnum_rows = mysqli_num_rows($fresult);
$fcount = 0;
while($fcount < $fnum_rows) {
	$fmembership_status_name[mysqli_result_dep($fresult,$fcount,"id")] = mysqli_result_dep($fresult,$fcount,"name");
	$fmembership_status_id[mysqli_result_dep($fresult,$fcount,"name")] = mysqli_result_dep($fresult,$fcount,"id");
	$fcount++;
}



//Music format types, index by Name/ID
$fresult = mysqli_query($db,"SELECT * FROM types_format ORDER BY 'sort', 'name'");
$fnum_rows = mysqli_num_rows($fresult);
$fcount = 0;

while($fcount < $fnum_rows) {
	$fformat_name[mysqli_result_dep($fresult,$fcount,"id")] = mysqli_result_dep($fresult,$fcount,"name");
	$fformat_id[mysqli_result_dep($fresult,$fcount,"name")] = mysqli_result_dep($fresult,$fcount,"id");
	$fcount++;
}

//Show names, index by Name/ID

$fshow_name = array();
$fshow_id = array();
$fshow_name_active_2 = array();
$fshow_id_active_2 = array();
$query = "SELECT id, name, active FROM shows ORDER BY name ASC";
if( $result = $db->query($query) ){
	while ($row = $result->fetch_assoc()){
		$thisName = $row["name"];
		$thisID = $row["id"];
		$thisActive = $row["active"];
		
		$fshow_id[$thisName]= $thisID;
		
		if($row["active"]==1) 
			$fshow_id_active[$thisName] = $thisID;
	}
$fshow_name = array_flip($fshow_id);
$fshow_name_active = array_flip($fshow_id_active);
}

//Host names, index by Name/ID
$query = "SELECT id,name FROM hosts ORDER BY 'name'";

if($result = $db->query($query)) {
	while ($row = $result->fetch_assoc()){
	$fhost_id[$row["name"]] = $row["id"];
	}
	$fhost_name = array_flip($fhost_id);
}*/


//gets the id of a name, adds the name if it does not exist.
function fget_id($name, $table, $do_insert) {
	
	global $db;

	$table = fas($table);
	$name = fas($name);

	if(mysqli_num_rows($result = mysqli_query($db,"SELECT * FROM `$table` WHERE (name = '$name')" ))) {
		return mysqli_result_dep($result,0,"id");
	}
	else if($do_insert){
		mysqli_query($db,"INSERT INTO `$table` (name) VALUES ('$name')");
		return mysqli_insert_id($db);
	}
	return false;
}

//gets the id of a name, adds the name if it does not exist.
function fget_song_id($artist, $title, $song) {
	
	global $db;

	$artist = fas($artist);
	$title = fas($title);
	$song = fas($song);

	if(mysqli_num_rows($result = mysqli_query($db,"SELECT * FROM `songs` WHERE (artist='$artist' AND title='$title' AND song='$song')"))) {
		return mysqli_result_dep($result,0,"id");
	}
	else {
		mysqli_query($db,"INSERT INTO `songs` (artist, title, song) VALUES ('$artist', '$title', '$song')");
		return mysqli_insert_id($db);
	}
}

function oldIE(){
preg_match('/MSIE (.*?);/', $_SERVER['HTTP_USER_AGENT'], $matches);
if (count($matches)>1){
	  //Then we're using IE
	  $version = $matches[1];
	  switch(true){
	    case ($version<=8):
			return true;
	      break;
	    default:
			return false;
		// can add more cases if we want to target other browsers
		// ...
	  }
	}
}

$dow[0] = "Sunday";
$dow[1] = "Monday";
$dow[2] = "Tuesday";
$dow[3] = "Wednesday";
$dow[4] = "Thursday";
$dow[5] = "Friday";
$dow[6] = "Saturday";



function getFormatName($format_id, $db){ 
	
	$query = "SELECT name FROM types_format WHERE id=".$format_id;
	
	if( $result = $db->query($query)){
		while($row = $result->fetch_assoc()){
					return $row['name'];
		}
	
	} else {	
	 return null;
	}
	
}
// given $min and $max as unix timestamps
function grabPlaylists($min,$max, $db){
	
	// convert to string representation
	$min = date("Y-m-d H:i:s",$min);
	$max = date("Y-m-d H:i:s",$max);
	
$showList = array();
$query = "SELECT * FROM playlists WHERE start_time >= '$min' AND start_time <= '$max' ORDER BY start_time ASC";

if( $result = $db->query($query)){
	while($row = $result->fetch_assoc()){
		
		$showList []=$row;		
	}
//	print_r($showList);
//	echo $query;
	return $showList;

} else {	
 return null;
}


}

function grabPlayitems($min,$max, $db){
	$min = date("Y-m-d",$min);
	$max = date("Y-m-d",$max);
	
$plays = array();


$query = 	"SELECT * FROM playitems INNER JOIN songs ON playitems.song_id=songs.id 
			WHERE playitems.show_date >= '$min' AND playitems.show_date <= '$max' ORDER BY playitems.id ASC";
			

//SELECT * FROM table1 INNER JOIN table2 ON table1.id=table2.id;
if( $result = $db->query($query) ){
	while($row = $result->fetch_assoc()){
		
		$plays []= $row;
	}
	return $plays;
	$good = true;
	
} else {
echo "CiTR database problem :(";
echo $query;
	$good = false;
}


$song_IDs = array();
foreach($plays as $a => $aplay){
$song_IDs []= $aplay['song_id'];
}

$song_IDs = implode(',',$song_IDs);

$song_query = 'SELECT * FROM songs WHERE id IN ('.$song_IDs.')';

if( $good && ($result = $db->query($song_query) ) ){
	while($row = $result->fetch_assoc()){
		
		$song_items []= $row;
	}
	
	$good = true;
	
} else {
echo "citr database error :(";
return 0;
}



}
// $numrows is the number of recent playlists you want
// $filter (optional) show id to filter by
function getRecentPlaylists($db, $numrows,$filter){
	//if we are filtering by a showname then filter our query.
	
	$playlists = array();
	
	if($filter)
	{
	//query playlists for saved playlists with show id = to show we are filtering
	$query="SELECT id, show_id, start_time, status, star FROM playlists WHERE show_id =".$filter." ORDER BY start_time DESC LIMIT ".$numrows;
	
	}
	else
	{
	//query playlists database for ALL saved playlists
	$query = "SELECT id, show_id, start_time, status, star FROM playlists ORDER BY start_time DESC LIMIT ".$numrows;
	}
	
	if ($result = mysqli_query($db,$query)){
	
			while($row = $result->fetch_array()){
			
				$playlists []= $row;
		
	
	
			}
	} else {
	return ' there was a  problem in the db';
	}
//	print_r($playlists);
	return $playlists;

}

//END FUNCTION HEADER
?>