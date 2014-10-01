<?php

require_once('../headers/db_header.php');
require_once('../headers/showlib.php');
require_once('../config.php');
require_once('../adLib.php');

$showlib = new Showlib($db);
$adLib = new AdLib($mysqli_sam,$db);
$showid = null;
$unix = null;
$psid = null;
$show_start = null;
$start_hour = null;
$start_min = null;
$show_end = null;
$end_hour = null;
$end_min = null;


if(isset($_POST["showid"])) $showid = $_POST["showid"];
if(isset($_POST["unixTime"])) $unix = $_POST["unixTime"]; //  unix time of midnight of the same day (NOT the start of the show)
if(isset($_POST["psid"])) $psid = $_POST["psid"];


if($showid == null){
	$showid=278;
}
if($unix == null){
//	$unix = time();
	$unix = time();
}
$weekday = getDate();
$weekday = $weekday['wday'];
//If we are selecting another show, we want to change the date and time to the next programmed time for that show.
if(isset($psid)){
	$query = "SELECT p.show_id AS show_id, p.unix_time AS unix_time, s.name AS show_name, s.start_time AS start_time, s.end_time AS end_time, s.weekday AS weekday, h.name AS host, s.crtc_default AS crtc, s.lang_default AS lang, s.show_type AS type FROM playlists AS p INNER JOIN hosts as h ON p.host_id = h.id INNER JOIN shows AS s ON s.id = p.show_id WHERE p.id='".$psid."'";
}else{
	$query = "SELECT s.id AS show_id, s.name AS show_name, st.start_time AS start_time, st.end_time AS end_time, st.start_day AS weekday, h.name AS hostname, s.crtc_default AS crtc, s.lang_default AS lang, s.showtype AS type FROM hosts AS h INNER JOIN shows AS s ON s.host_id = h.id INNER JOIN show_times AS st ON st.show_id = s.id WHERE s.id='".$showid."'";
}


if($result = $db->query($query)){
	$showinfo = mysqli_fetch_array($result);
	print_r($showinfo);
	if(isset($psid)){ $unix = $showinfo['unix_time']; }
	$show_id = $showinfo['show_id'];
	$show_name = $showinfo['show_name'];
	
	if($weekday == $showinfo['weekday']){
		$show_start = $showinfo['start_time'];
		$show_end = $showinfo['end_time'];
		$start_hour = date("H",strtotime("today ".$show_start));
		$start_min = date("i",strtotime("today ".$show_start));
		$end_hour = date("H",strtotime("today ".$show_end));
		$end_min = date("i",strtotime("today ".$show_end));
	}
	$host = $showinfo['hostname'];
	$crtc = $showinfo['crtc'];
	if($crtc == null) $crtc = '30';
	$lang = $showinfo['lang'];
	if($lang == null) $lang = 'eng';
	$type = $showinfo['type'];
	if($type == null) $type = 'Live';

	$show = array ('start_time'=>$show_start); //Fake Show Object.
	
	$show_block = array ();
	$show_block['show_obj'] = $show;
	$show_block['name'] = $show_name;
	$show_block['unix_start'] =strtotime("today ".$show_start);
	$show_block['unix_end'] = strtotime($end_hour, $unix);
	$ads = $adLib->generateTable($unix,'dj',false);

	echo json_encode(array(
		'id'=>$show_id,
		'name'=>$show_name,
		'start_hour'=>$start_hour,
		'start_min'=>$start_min,
		'end_hour'=>$end_hour,
		'end_min'=>$end_min,
		'host'=>$host,
		'crtc'=>$crtc,
		'lang'=>$lang,
		'ads'=>$ads,
		'unixTime'=>$unix,
		'showtype'=>$type,
		'showID' => $show_id
	));



}else{
	echo false;
}
/*

if(isset($psid)){
	$query = "SELECT show_id, unix_time FROM playlists  WHERE id ='".$psid."'";
	if($result = $db->query($query)){
		$showinfo = mysqli_fetch_array($result);
		$unixTime = $showinfo["unix_time"];
		$showid = $showinfo["show_id"];	
	}
	$result->close();
}





$targetShow = $showlib->getShowById($showid);
$showname = $targetShow->name;
$showsInDay = $showlib->getBetterBlocksInSameDay($unixTime);


//Get Better Block Returns Show values for that day including: 'name','unix_start','unix_end'
$send_fail_msg = true;
$array = array();
foreach( $showsInDay as $betterBlock ){
	if( $betterBlock['show_obj']->id == $showid) {
		$end_unix = $betterBlock['unix_end'];
		$start_unix = $betterBlock['unix_start'];
		$start_info = getdate($start_unix);
		$end_info = getdate($end_unix);
		
		$start_hour = sprintf('%02d', $start_info['hours']);
		$start_min = sprintf('%02d', $start_info['minutes']);
		$end_hour = sprintf('%02d', $end_info['hours']);
		$end_min = sprintf('%02d', $end_info['minutes']);
		
		$ads = $adLib->generateTable($start_unix,'dj',$betterBlock);
		
		$crtc = $betterBlock['show_obj']->crtc_default;
		$lang = $betterBlock['show_obj']->lang_default;
		$host = $betterBlock['show_obj']->host;
		$showtype = $betterBlock['show_obj']->showtype;
		
		if(!$lang) $lang = "eng";
		if(!$crtc) $crtc = "20";
	//	echo json_encode($betterBlock);
	
	
	
		echo json_encode(array(
						'start_hour'=>$start_hour,
						'start_min'=>$start_min,
						'end_hour'=>$end_hour,
						'end_min'=>$end_min,
						'host'=>$host,
						'crtc'=>$crtc,
						'lang'=>$lang,
						'ads'=>$ads,
						'unixTime'=>$start_unix,
						'showtype'=>$showtype,
						'showID' => $targetShow->id
						));
						
		$send_fail_msg = false;
	}
	
//	$array []= $betterBlock['show_obj'];
	
}

if( $send_fail_msg ){
//echo json_encode('did not find show');
	echo false;
*/















/* this code could be useful when implementing an episode editor,
that allows generation of links to skip to next episode playsheet, previous, etc
when / if playsheets also get podcast info editing in the same place this would be cool

	$time = $unixTime;
	$twoWeeksAgo = $time - 2*7*24*60*60;
	
	
	$blocks = array(
					$showlib->getAllShowBlocksByTime($twoWeeksAgo),
					$showlib->getAllShowBlocksByTime($time),
					);
	
	$targetBlocks = array();
	foreach($blocks as $i => $bigblock){
			foreach($bigblock as $j => $showrow){
				if ($showrow['show_id']==$showid){
					$targetBlocks []= $showrow;
				}
			}
				
	}
	
//	echo "<a href='playsheet.php?action=edit&id=".$episodes[0]['id']."'>&lt---".$showname." - ".$episodes[0]['start_time']."</a>";
	
	$keepgoing = true;
	foreach($targetBlocks as $i => $v){
			if($keepgoing){
			if($v['unixtime']>$time){
				$keepgoing = false;
				if(is_array($targetBlocks[$i-1]) 
			//	&& is_array($targetBlocks[$i]) && is_array($targetBlocks[$i+1])
				){
				$lastEpisode = $targetBlocks[$i-1];
			//	$thisEpisode = $targetBlocks[$i];
			//	$nextEpisode = $targetBlocks[$i+1];
				} else {
					$lastEpisodeInfo = 'error';
					$thisEpisodeInfo = 'error';
					$nextEpisodeInfo = 'error';
				}
				
			} else {
					
			}
		}
		
	}


//	$return_array = 

// array( 'earliest' => array( 'unixtime' => $targetBlocks[$i-2]['unixtime']),
//							'early' => array( 
	
	$lastEpisodeUnix = $lastEpisode['unixtime'];
	$thisEpisodeUnix = $thisEpisode['unixtime'];
	$nextEpisodeUnix = $nextEpisode['unixtime'];
	
	$lastEpisodeDate = date ( 'D, M j, g:ia', $lastEpisodeUnix);
	$thisEpisodeDate = date ( 'D, M j, g:ia', $thisEpisodeUnix);
	$nextEpisodeDate = date ( 'D, M j, g:ia', $nextEpisodeUnix);
	
	
	$a = array('unix' => $lastEpisodeUnix, 'date' => $lastEpisodeDate, 'block' => $lastEpisode, 'info' => $lastEpisodeInfo);
	$b = array('unix' => $thisEpisodeUnix, 'date' => $thisEpisodeDate, 'block' => $thisEpisode, 'info' => $thisEpisodeInfo);
	$c = array('unix' => $nextEpisodeUnix, 'date' => $nextEpisodeDate, 'block' => $nextEpisode, 'info' => $nextEpisodeInfo);
	
	
	
	
	
	//echo json_encode(array('le'=>$lastEpisode,'last'=> $a, 'now' => $b, 'next' => $c, 'showObj'=>$targetShow, 'duration'=>$duration, 'ads'=>$lastAds));

*/
	
	
//print_r($targetBlocks);



?>