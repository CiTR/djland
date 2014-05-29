<?php

require("../headers/db_header.php");
require("../headers/function_header.php");
require("../headers/showlib.php");
require("../adLib.php");

$showlib = new Showlib($db);
$adLib = new AdLib($mysqli_sam,$db);



if(isset($_POST['from'])){
$from = $_POST['from'];
$to = $_POST['to'];
}

echo "from: ".$from."<br/> to: ".$to;
echo "<br/>";
$from = strtotime($from);
$to = strtotime($to)+ 24*60*60; //add one day to make the request include last day in range


$samFrom = date("Y-m-d H:i:s", $from);
$samTo = date("Y-m-d H:i:s", $to);  

$showList = grabPlaylists($from,$to, $db);
$samPlays = array();
$adsLogged = array();

$filtering = false;
if(isset($_POST['filteredAd']) && $_POST['filteredAd']!=0){
	$filtering = true;
	$filteredAd = $_POST['filteredAd'];
	echo 'filtering ad id#'.$filteredAd;
} else {
	echo 'not filtering by ad';
}
//find minimum / earliest playsheet id (and latest)

$min = $showList[0]['id'];

$last = array_slice($showList,-1);
$max = $last[0]['id'];

//$query = "SELECT playsheet_id, name, played, sam_id FROM adlog WHERE playsheet_id >= '$min' AND playsheet_id <= '$max' AND LEFT(type,2) = 'AD'  ORDER BY playsheet_id ASC";
$query = "SELECT playsheet_id, name, played, sam_id FROM adlog WHERE playsheet_id >= '$min' AND playsheet_id <= '$max' ORDER BY playsheet_id ASC";

if( $result = $db->query($query)){
	while($row = $result->fetch_assoc()){
		$thisID = $row['playsheet_id'];
		$adsLogged[$thisID] []=$row;		
	}
} else {
echo "citr database problem :(";	
}

$query = "SELECT songID, artist, title, date_played FROM historylist WHERE date_played >= '$samFrom' AND date_played <= '$samTo' AND songtype = 'A' ";

if($filtering) {
	$query .= "AND songID ='".$filteredAd."' ";
}

$query .= " ORDER BY date_played ASC";

if( $result = $mysqli_sam->query($query)){
	while($row = $result->fetch_assoc()){
		
		$row['date_unix'] = strtotime($row['date_played']); 
		$samPlays []=$row;		
	}
} else {
echo "SAM database problem :(";	
}

// sample sam play:
/* Array ( [songID] => 67917 [artist] => SLED [title] => June 22 2013 [date_played] => 2013-05-29 19:10:44 [date_unix] => 1369879844 ) 
*/

		$count = 0;
		
foreach($showList as $i => $v){
		$thisID = $v['id'];
	
// OPTION 1 - get start time from playsheet's unix time
//		$start_unix = $v['unix_time'];
// OPTION 2 - get start time from playsheet's declared start time from form	
		$start_unix = strtotime($v['start_time']);
		$end_unix = strtotime($v['end_time'],$start_unix);
		
		if ($end_unix < $start_unix) // late night show that ends next day
		{	$end_unix += 60*60*24;	}
		
		
		// IF FILTERING, check to see if there are any ads - if not don't show the show block at all!
		
		$yesAds = false;
		$ads = '';
		foreach($samPlays as $j => $w){
			
			if(	( $w['date_unix']>= $start_unix) && 
				( $w['date_unix']<= $end_unix) ) {
					$yesAds = true;
					$ads .= $w['artist']." - ".$w['title']."<br/>".
					"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (".
					date("D M j, g:i a ",strtotime($w['date_played'])).")<br/>";
					$count++;
				} else{
					
				}
			}
			
			
			
			
		if(!$filtering || ($filtering && $yesAds) ) {	
			
			echo "<h3> ";
			$showObj = $showlib->getShowById($v['show_id']);
			echo $showObj->name;
			echo "</h3><h5>".date("D M j g:i a ",$start_unix);
			echo " until ".date("D M j g:i a ",$end_unix);
	//		echo "<br/>";
	//		echo "start unix:<br/> ".$start_unix." | end unix:<br/> ".$end_unix;
			echo "<br/>".($end_unix-$start_unix)/(60*60);
			echo " hour(s) </h5>";
			echo "<div class='adreport'>Scheduled:<br/><br/>";
			
			//SHOW LENGTH:
			//		$duration = showBlock::getShowBlockLength($showBlocks[$i]);
	 		echo $thisID;
				foreach($adsLogged[$thisID] as $x => $y){
					
					if (is_numeric($y['name']) ){
						echo "&nbsp;&nbsp;&nbsp;";
						echo $adLib->getAdNameFromID($y['name']);
						if ($y['played']==1) echo " &#10004;"; // html entity for a checkmark
						echo "<br/>";
					}
				}
			echo "</div>";
			
				echo "<div class='adreport'>SAM plays:<br/><br/>";
				
				// ECHO EACH SAM PLAY
				echo $ads;
				
			echo "</div>";
		}
		
}

if($filtering){
echo '<div id=adcount>the selected ad was played '.$count.' times.</div>';
}

?>