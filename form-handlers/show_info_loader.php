<?php

require_once('../headers/db_header.php');
require_once('../headers/showlib.php');
require_once('../headers/config.php');
require_once('../adLib.php');

$showlib = new Showlib($db);

$adLib = new AdLib($mysqli_sam,$db);

$showid = $_POST["showid"];
$unixTime = $_POST["unixTime"]; //  unix time of midnight of the same day (NOT the start of the show)
$psid = $_POST["psid"];




if(!$showid && !$unixTime)
{
	$showid = 76;
	$unixTime = time();
}

if($psid){
	$query = "SELECT show_id, unix_time FROM playlists WHERE id ='".$psid."'";
	if($result = $db->query($query)){
		$showinfo=mysqli_fetch_array($result);
		$unixTime = $showinfo["unix_time"];
		$showid = $showinfo["show_id"];
		
		
	}
	$result->close();
}
$targetShow = $showlib->getShowById($showid);


$showname = $targetShow->name;

$showsInDay = $showlib->getBetterBlocksInSameDay($unixTime);



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

echo json_encode(array(
				'start_hour'=>'',
				'start_min'=>'',
				'end_hour'=>'',
				'end_min'=>'',
				'host'=>'',
				'crtc'=>'',
				'lang'=>'',
				'ads'=>'',
				'showID'=>''
				));
}

//echo json_encode($showsInDay);
//echo json_encode('failed');

/*
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