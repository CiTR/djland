<?php

//header('Access-Control-Allow-Origin: http://www.citr.ca');
// this is already set in yellow server config for every page on djland site

//print_r($_GET);
if (isset($_GET['show'])){
	$show_filter = $_GET['show'];	
} else {
	$show_filter = null;
}

if (isset($_GET['min'])){
	$datemin_filter = $_GET['min'];	
} else {
	$datemin_filter = null;
}

if (isset($_GET['max'])){
	$datemax_filter = $_GET['max'];	
} else {
	$datemax_filter = null;
}

if (isset($_GET['num'])){
	$num_filter = $_GET['num'];	
} else {
	$num_filter = '100';
}

//echo "min:".$datemin_filter."<hr/>";
//echo "max:".$datemax_filter."<hr/>";
// database info
$ip = "p:192.168.25.73"; // p: is for persistant connection (better for web apps)
$dbname = 'citr_live_test';
$user = 'plays';
$pass = 'dodecahedron-&^';


$db = new mysqli($ip, $user, $pass, $dbname);

if($db->connect_error){
    die('Connect Error (' . $db->connect_errno . ') '
            . $db->connect_error);
}
//echo 'Success... ' . $db->host_info . "\n";

$query = 'SELECT playitems.playsheet_id, songs.artist as artist, songs.song as song, songs.title as album,
 playitems.show_date as date, shows.name as showname, shows.genre as genre, playitems.show_id, playlists.start_time as start, playlists.end_time as end FROM playitems JOIN songs 
ON playitems.song_id = songs.id JOIN shows 
ON playitems.show_id = shows.id JOIN playlists
ON playitems.playsheet_id = playlists.id
WHERE playlists.status = 2 ';

if ($show_filter){
$query .= ' AND playitems.show_id = '.$show_filter;
}

if ($datemin_filter){
$query .= ' AND playitems.show_date >= "'.$datemin_filter.'"';
}

if ($datemax_filter){
$query .= ' AND playitems.show_date <= "'.$datemax_filter.'"';
}

//$query .=' ORDER BY playitems.id DESC LIMIT '.$num_filter;
$query .=' ORDER BY playitems.show_date DESC, playitems.id DESC LIMIT '.$num_filter;

//echo $query;
/*
$query = "
SELECT songs.artist as artist, songs.song as song, songs.title as album, 
playitems.show_date as date, shows.name as showname, shows.id FROM playitems
 JOIN songs ON playitems.song_id = songs.id JOIN shows ON 
 playitems.show_id = shows.id JOIN playlists ON playitems.playsheet_id = 
 playlists.id WHERE playlists.status = 2 AND shows.id = 200  
 ORDER BY playitems.id DESC LIMIT 1000";*/

if ($result = $db->query($query)){

	$plays = array();

	while ($row = $result->fetch_array()) {
		$plays []= $row;

	}/*
	echo 'plays:<br/><pre>';
	print_r($plays);
	echo '</pre>';*/
 // reverse each playsheet to maintain chronological order
	$playsheet = array();
	$plays_sorted = array();
	$last_playsheet_id = '';
	foreach($plays as $i => $play){
		$this_playsheet_id = $play['playsheet_id'];

		if ($last_playsheet_id == $this_playsheet_id){

		} else {
			$reversed = array_reverse($playsheet);
			$plays_sorted = array_merge($plays_sorted,$reversed);
			$playsheet = array();
		}

		$playsheet []= $play;
		$last_playsheet_id = $this_playsheet_id;
	}
//	echo "<h3>plays sorted</h3>";
			$last_date = '';
			$last_show = '';
	foreach($plays_sorted as $i => $a_play){
		$this_date = $a_play['date'];
		$this_show = $a_play['showname'];
		$this_start = date('g:ia',strtotime($a_play['start']) );
		$this_end = date('g:ia',strtotime($a_play['end']) );
		if ( is_null($show_filter) || ($show_filter == $a_play['show_id']) ){
			
			if( ( $this_date == $last_date ) && ( $this_show == $last_show ) ){
			
			} else {
				$nice_date = date('D, M j Y',strtotime($this_date));			
				
				echo "<br/><h4><b>{$a_play['showname']}</b> - {$nice_date} ({$this_start} - {$this_end}) </h4><i>{$a_play['genre']}</i><br/></br>";
			
			}

			$this_artist = html_entity_decode($a_play['artist']);
			$this_song = html_entity_decode($a_play['song']);
			$this_album = html_entity_decode($a_play['album']);

			echo "{$a_play['artist']} - {$a_play['song']} ({$a_play['album']}) <br/>";

		}
		
		$last_date = $this_date;
		$last_show = $this_show;
	}
	
	echo $query;
} else {

	echo 'query did not work:<br/>'.$query;
}

?>