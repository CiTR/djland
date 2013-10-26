<?php

require_once('headers/db_header.php');
require_once('headers/showlib.php');
require_once('adLib.php');

$showlib = new Showlib($db);
$adLib = new AdLib($mysqli_sam,$db);

$showid = $_POST["showid"];
$unixTime = $_POST["unixTime"];

if(!$showid && !$unixTime)
{	$showid = 76;
	$unixTime = time();
	}
	

$targetShow = $showlib->getShowById($showid);

$showname = $targetShow->name;

	
//	$time = time()-(60*60*5);
//	$time = $unixTime -(60*60*5);
	$time = $unixTime;

//	$fourWeeksAgo = $time - 4*7*24*60*60;
	$twoWeeksAgo = $time - 2*7*24*60*60;
//	$twoWeeksAhead = $time + 2*7*24*60*60;
//	$fourWeeksAhead = $time + 4*7*24*60*60;
	
	
	
	$blocks = array(
					//$showlib->getAllShowBlocksByTime($fourWeeksAgo),
					$showlib->getAllShowBlocksByTime($twoWeeksAgo),
					$showlib->getAllShowBlocksByTime($time),
					//$showlib->getAllShowBlocksByTime($twoWeeksAhead)
					//,$showlib->getAllShowBlocksByTime($fourWeeksAhead)
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
				if(is_array($targetBlocks[$i-1]) /*&& is_array($targetBlocks[$i]) && is_array($targetBlocks[$i+1])*/ ){
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

/*	
	$return_array = 

array( 'earliest' => array( 'unixtime' => $targetBlocks[$i-2]['unixtime']),
							'early' => array( */
	
	$lastEpisodeUnix = $lastEpisode['unixtime'];
	$thisEpisodeUnix = $thisEpisode['unixtime'];
	$nextEpisodeUnix = $nextEpisode['unixtime'];
	
	$lastEpisodeDate = date ( 'D, M j, g:ia', $lastEpisodeUnix);
	$thisEpisodeDate = date ( 'D, M j, g:ia', $thisEpisodeUnix);
	$nextEpisodeDate = date ( 'D, M j, g:ia', $nextEpisodeUnix);
	
	
	$a = array('unix' => $lastEpisodeUnix, 'date' => $lastEpisodeDate, 'block' => $lastEpisode, 'info' => $lastEpisodeInfo);
	$b = array('unix' => $thisEpisodeUnix, 'date' => $thisEpisodeDate, 'block' => $thisEpisode, 'info' => $thisEpisodeInfo);
	$c = array('unix' => $nextEpisodeUnix, 'date' => $nextEpisodeDate, 'block' => $nextEpisode, 'info' => $nextEpisodeInfo);
	
	$duration = showBlock::getShowBlockLength($lastEpisode);
	$ads = $adLib->generateTable($lastEpisodeUnix,'dj');
	$crtc = $targetShow->crtc_default;
	$lang = $targetShow->lang_default;
	$host = $targetShow->host;
	if(!$lang) $lang = "eng";
	if(!$crtc) $crtc = "20";
	
	
	
	
	//echo json_encode(array('le'=>$lastEpisode,'last'=> $a, 'now' => $b, 'next' => $c, 'showObj'=>$targetShow, 'duration'=>$duration, 'ads'=>$lastAds));
	echo json_encode(array('name'=>$showname,
							'time'=>$lastEpisodeUnix,
							'host'=>$host,
							'duration' => $duration, 
							'crtc'=>$crtc,
							'lang'=>$lang,
							'ads'=>$ads,
							'targetshow'=>$targetShow));
	
	
//print_r($targetBlocks);



?>