<?php


require_once("../headers/db_header.php");
require_once("../headers/function_header.php");
require_once("../headers/showlib.php");
require_once("../adLib.php");

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

$showList = grabPlaysheets($from,$to, $db);
$samPlays = array();
$adsLogged = array();


//find minimum / earliest playsheet id (and latest)

$min = $showList[0]['id'];

$last = array_slice($showList,-1);
$max = $last[0]['id'];

$query = "SELECT playsheet_id, name, played, sam_id FROM adlog WHERE playsheet_id >= '$min' AND playsheet_id <= '$max' AND LEFT(type,2) = 'AD'  ORDER BY playsheet_id ASC";

if( $result = $db->query($query)){
	while($row = $result->fetch_assoc()){
		$thisID = $row['playsheet_id'];
		$adsLogged[$thisID] []=$row;		
	}
} else {
echo "citr database problem :(";	
}


$query = "SELECT songID, artist, title, date_played FROM historylist WHERE date_played >= '$samFrom' AND date_played <= '$samTo' AND songtype = 'A' ORDER BY date_played ASC";

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
foreach($showList as $i => $v){
		$thisID = $v['id'];
	
// OPTION 1 - get start time from playsheet's unix time
//		$start_unix = $v['unix_time'];
// OPTION 2 - get start time from playsheet's declared start time from form	
		$start_unix = strtotime($v['start_time']);
		$end_unix = strtotime($v['end_time'],$start_unix);
		
		if ($end_unix < $start_unix) // late night show that ends next day
		{	$end_unix += 60*60*24;	}
		
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
 
			foreach($adsLogged[$thisID] as $x => $y){
				echo "&nbsp;&nbsp;&nbsp;";
				echo html_entity_decode($y['name']);
				if ($y['played']==1) echo " &#10004;"; // html entity for a checkmark
				echo "<br/>";
			}
		echo "</div>";
		
			echo "<div class='adreport'>SAM plays:<br/><br/>";
			
			foreach($samPlays as $j => $w){
			
			if(	( $w['date_unix']>= $start_unix) && 
				( $w['date_unix']<= $end_unix) ) {
				echo $w['artist']." - ".$w['title']."<br/>";
				echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (";
				echo $w['date_played'].")<br/>";
				}
			}
			
		echo "</div>";
		
}



?>