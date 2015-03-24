<?php // CRTC REPORT REQUEST HANDLER

//***************************************************
//***********      REQUIREMENTS       ***************
//***************************************************

$cc_reg_req = 35; // mysqli_result_dep($result,$count,"cc_req");
$cc_spec_req = 12;
$cc_spec_ethnic_req = 7;
$pl_req = 60;// mysqli_result_dep($result,$count,"pl_req");
$fe_req = 35;// mysqli_result_dep($result,$count,"fem_req");
$inst_req = 35;// mysqli_result_dep($result,$count,"fem_req");
$hit_req = 10;// mysqli_result_dep($result,$count,"fem_req");
$locally_produced_req = 15; // (spoken word)
$max_ad_mins_per_week = 504;

$SOCAN_FLAG;
//***************************************************
//***************************************************
//***************************************************

require_once("../headers/db_header.php");
require_once("../headers/function_header.php");
require_once("../headers/showlib.php");

require_once("../headers/socan_header.php");
$showlib = new Showlib($db);

if($using_sam && $enabled['adscheduler']){
	require_once("../adLib.php");
	$adLib = new AdLib($mysqli_sam,$db);
}

$SOCAN_FLAG =socanCheck($db);
// CRTC Broadcast Day hours - 6:00am to midnight
if(isset($_POST['min_time']) && isset($_POST['max_time'])){
$min_time = $_POST['min_time'];
$max_time = $_POST['max_time'];
} else { // uncomment when know post loading works
 $min_time = 6;
 $max_time = 24;
}

if(isset($_POST['from'])){
	$from = $_POST['from'];
	$to = $_POST['to'] ;

	$from = strtotime($from);
	$to = strtotime($to)+ 24*60*60; //add one day to make the request include last day in range

} else {
//	$from = new Date(1397631600);
//	$to =  new Date(1398322800);
	$from = 1397631600;
	$to =  1398322800;
}
$total_hours = ($max_time-$min_time)*($to-$from)/(24*60*60);

$max_ad_mins = round(($max_ad_mins_per_week * ($total_hours / 126)) , 1);

$samFrom = date("Y-m-d H:i:s", $from);
$samTo = date("Y-m-d H:i:s", $to);  

$samPlays = array();
$adsLogged = array();

// see headers/function_header.php
// available fields: id, show_id, start_time, end_time, unix_time
$showList = grabPlaylists($from,$to, $db);
$plays = grabPlayitems($from,$to, $db);
echo 'FROM: '.$from.', TO: '.$to;

// if no data, return and print 'no data for this period'



// filter out shows that didn't air during broadcast day hours
// make two windows out of max and min to represent nighttime non-broadcast day hours
if ($max_time <= 24){
$min_first = 0;
$max_first = $min_time;

$min_second = $max_time;
$max_second = 24;
} else {
$min_first = $max_time - 24;
$max_first = $min_time;
$min_second = 24;
$max_second = 24;
}

$temp = array();
foreach($showList as $i => $v){
	$thisStartHr = explode(' ',$v['start_time']);
	$thisStartHr = explode(':',$thisStartHr[1]);
	$thisStartHr = $thisStartHr[0];
	
	$end_array = explode(':',$v['end_time']);
	$thisEndHr = $end_array[0];
	$thisEndMin = $end_array[1];
	
		if (	(	($thisStartHr >= $min_first)&&($thisStartHr <= $max_first)
				||	($thisStartHr >= $min_second)&&($thisStartHr <= $max_second)
				)&&
				(	($thisEndHr >= $min_first)&&($thisEndHr <= $max_first)
				||	($thisEndHr >= $min_second)&&($thisEndHr <= $max_second)
				)
			){ 	// both the start time and end time of the show fall inside 
				// a "non-broadcast day" period (so do not keep)
			} else {
			$temp []= $v;
			}
}
$showList = $temp;

$output_body = "";
$output_summary = "";
//find minimum / earliest playsheet id (and latest)

$min = $showList[0]['id'];
$last = array_slice($showList,-1);
$max = $last[0]['id'];



if($enabled['adscheduler']){

	// GRAB ADS THAT CORRESPOND WITH PLAYLIST RANGE
	$query = "SELECT playsheet_id, name, played, sam_id FROM adlog WHERE playsheet_id >= '$min' AND playsheet_id <= '$max' AND LEFT(type,2) = 'AD'  ORDER BY playsheet_id ASC";

	if( $result = $db->query($query)){
		while($row = $result->fetch_assoc()){
			$thisID = $row['playsheet_id'];
			$adsLogged[$thisID] []=$row;		
		}
	} else {
	$output_summary .= "citr database problem :(";	
	}

}

// TODO: implement this helper function also
// $adsLogged = grabAds(...)

if ($using_sam){
	$query = "SELECT songID, artist, title, date_played, duration, songtype FROM historylist WHERE date_played >= '$samFrom' AND date_played <= '$samTo' AND (songtype = 'A' OR songtype = 'I') ORDER BY date_played ASC";

	if( $result = $mysqli_sam->query($query)){
		while($row = $result->fetch_assoc()){
			
			$row['date_unix'] = strtotime($row['date_played']); 
			$samPlays []=$row;		
		}
	} else {
	$output_summary .= "SAM database problem :(";	
	}
}

// sample sam play:
/* Array ( [songID] => 67917 [artist] => SLED [title] => June 22 2013 [date_played] => 2013-05-29 19:10:44 [date_unix] => 1369879844 ) 
*/
$totalSpokenWord = 0;

foreach($showList as $i => $v){		
		$thisSpokenWord = $v['spokenword_duration'];
		$totalSpokenWord = $totalSpokenWord + $thisSpokenWord;
}



$output_summary .= "<h2 class=header-left><font color=black>CRTC Report - ";
$output_summary .= $station_info['call_letters']." ";
$output_summary .= $station_info['frequency']." ";
$output_summary .= $station_info['city'].", ";
$output_summary .= $station_info['province'].", ";
$output_summary .= $station_info['country']." - ";
$output_summary .= $station_info['website'];
$output_summary .= "</font></h2>";
$output_summary .= "<div id='report-summary'>";
$output_summary .= "<h2 class=header-left>Summary</h2>";
$output_summary .= "	from: ".date("D, F jS, Y",$from)."<br/>
		to: ".date("D, F jS, Y",$to-1)."<br/>";
$output_summary .= "Broadcast hours: ".$min_time.":00 to ".$max_time.":00 (".$total_hours." hours)";
$output_summary .= "<br/>";

//$output_summary .= "Total spoken word (cat 12): ".floor($totalSpokenWord/60)." hours and ".($totalSpokenWord%60)." minutes.";

$total_items = count($plays);
$total_reg = 0;
$total_spec = 0;
$total_pl = 0;
$total_cc_reg = 0;
$total_cc_spec = 0;
$total_fe = 0;
$total_inst = 0;
$total_hit = 0;

if ( $total_items <= 0 ){
	echo ' <br/><br/> no data found for this date period ';
	return;
}

foreach($plays as $i => $play){
			// for each show, no need to load the show's CRTC requirements
	$show_id = mysqli_result_dep($result,$count,"id");
	
	if($play['is_playlist']==1) $total_pl++;
	if(($play['is_canadian']==1)&&($play['crtc_category']==20)) $total_cc_reg++;
	if(($play['is_canadian']==1)&&($play['crtc_category']==30)) $total_cc_spec++;
	if($play['is_fem']==1) $total_fe++;
	if($play['is_inst']==1) $total_inst++;
	if($play['is_hit']==1) $total_hit++;
	
	if($play['crtc_category']==20) $total_reg++;
	if($play['crtc_category']==30) $total_spec++;
	
	
}
$output_summary .= "<br/><br/>";


$total_ads = 0;


foreach($showList as $i => $v){
		$thisID = $v['id'];
		$thisHost = $v['host_id'];
		
		$thisSpokenWord = $v['spokenword_duration'];
		$totalSpokenWord = $totalSpokenWord + $thisSpokenWord;
		
	
// OPTION 1 - get start time from playsheet's unix time
//		$start_unix = $v['unix_time'];
// OPTION 2 - get start time from playsheet's declared start time from form	
		$start_unix = strtotime($v['start_time']);
		$end_unix = strtotime($v['end_time'],$start_unix);
		
		
		if ($end_unix < $start_unix) // late night show that ends next day
		{	$end_unix += 60*60*24;	}
		
	//	if($SOCAN_FLAG){ $output_body .= '<div id="reportShowSOCAN">'; }
	//	else {
			 $output_body .= '<div id="reportShow">'; 
		//}
			$output_body .= '<h3 class=header-left> ';
			$showObj = $showlib->getShowById($v['show_id']);
			$output_body .= $showObj->name;
			$output_body .= ' (hosted by '.$fhost_name[$thisHost].')';
			$output_body .= '</h3>';
			$output_body .='<h4 class=header-left>'.date("D M j"."<b\\r/>"." g:i a ",$start_unix);
			$output_body .= ' - '.date("g:i a ",$end_unix);
			$output_body .= "</h4>";
			$output_body .= '<h5>'.$showObj->show_desc."</h5>";
			$output_body .= '<h4 class=header-left>CRTC category: '.$v['crtc'].'<br/>Language: '.$v['lang'].'</h4>';
			$output_body .= "<div class='songreport'>";
			$output_body .= "<h4 class=header-left>Music Played:</h4>";
			
			
			
			$total_pl_show = 0;
			$total_cc_reg_show = 0;
			$total_cc_spec_show = 0;
			$total_fe_show = 0;
			$total_inst_show = 0;
			$total_hit_show = 0;
			
			$count_show = 0;
			$total_reg_show = 0;
			$total_spec_show = 0;
			
			foreach($plays as $in => $pl){
				
				
					if	( $pl['playsheet_id']== $thisID) 
					{
						
					$count_show++;
					if($pl['crtc_category']==20) $total_reg_show++; else{}
					if($pl['crtc_category']==30) $total_spec_show++; else{}
					
					
					
					$output_body .= 	'<div class=dotted-underline><div class=report-entry>';
					
					if ($pl['insert_song_start_hour']) $output_body .= '['.$pl['insert_song_start_hour'].':'.str_pad($pl['insert_song_start_minute'],2,'0', STR_PAD_LEFT).'] ';
					
					$output_body .=  html_entity_decode($pl['artist']).' - '. html_entity_decode($pl['song']).
							' <span class=entry-lang>(Lang: '.$pl['lang'].') ';
					if($pl['composer'])	
						$output_body .= '(Composer: '.html_entity_decode($pl['composer']).') ';
					
					if($pl['insert_song_length_minute'])
						$output_body .= '('.$pl['insert_song_length_minute'].'m '.$pl['insert_song_length_second'].'s)';
					$output_body .= '</span></div>';
					$output_body .= '<span class=report-icons>'."<a>(".$pl['crtc_category'].")&nbsp;</a>";
						if ($pl['is_playlist']==1) {
							$output_body .= "<img src='images/pl.png' class=report_img>";
							$total_pl_show++;
							}
							else $output_body .= "<img src='images/nothing.png' class=report_img>";
						
						if ($pl['is_canadian']==1) {
							$output_body .= "<img src='images/CAN.png' class=report_img>";
							
							if($pl['crtc_category']==20) $total_cc_reg_show++; else{}
							if($pl['crtc_category']==30) $total_cc_spec_show++; else{}
						}
							else $output_body .= "<img src='images/nothing.png' class=report_img>";
							
						if ($pl['is_fem']==1) {
							$output_body .= "<img src='images/fe.png' class=report_img>";
							$total_fe_show++;
						}
							else $output_body .= "<img src='images/nothing.png' class=report_img>";
							
							
						if ($pl['is_inst']==1) {
							$output_body .= "<img src='images/inst.png' class=report_img>";
							$total_inst_show++;
						}
							else $output_body .= "<img src='images/nothing.png' class=report_img>";		
							
						if ($pl['is_part']==1) {
							$output_body .= "<img src='images/part.png' class=report_img>";
						}
							else $output_body .= "<img src='images/nothing.png' class=report_img>";	
							
						if ($pl['is_hit']==1) {
							$output_body .= "<img src='images/hit.png' class=report_img>";
							$total_hit_show++;
						}
						else $output_body .= "<img src='images/nothing.png' class=report_img>";	
						
				//		if($SOCAN_FLAG){
						if ($pl['is_theme']==1) {
							$output_body .= "<img src='images/background.png' class=report_img>";
						}
						else $output_body .= "<img src='images/nothing.png' class=report_img>";
						
						if ($pl['is_background']==1) {
							$output_body .= "<img src='images/theme.png' class=report_img>";
						}
						else $output_body .= "<img src='images/nothing.png' class=report_img>";
				//		}
						
						$output_body .= '</span></div>';
					//	$output_body .= '<br/>';
					}
				}
				
				$output_body .= "<br/>
					<div id='report-show-compliance'>";
				$output_body .= $count_show." songs (".$total_reg_show." regular and ".$total_spec_show." specialty):<br/>
								cancon 2: ".$total_cc_reg_show." / ".$total_reg_show."<span id='show-percent'>";
									if(($total_cc_reg_show==0)&&($total_reg_show==0)){
										$output_body .= "--</span><br/>";
								}	else{
										$output_body .= "(".round((100*$total_cc_reg_show/$total_reg_show),2)."%)</span> <br/>";
								}
							$output_body .= "cancon 3: ".$total_cc_spec_show." / ".$total_spec_show."<span id='show-percent'>";
									if(($total_cc_spec_show==0)&&($total_spec_show==0)){
										$output_body .= "--</span><br/>";
								}	else{
										$output_body .= "(".round((100*$total_cc_spec_show/$total_spec_show),2)."%)</span> <br/>";
								}
							$output_body .= "playlist: &nbsp;&nbsp;&nbsp;&nbsp;".$total_pl_show." / ".$count_show." <span id='show-percent'>(";

								if($count_show!=0)	{
									$output_body .= round((100*$total_pl_show/$count_show),2);
									$output_body .= "%)</span> <br/>
									femcon: &nbsp;&nbsp;&nbsp;".$total_fe_show." / ".$count_show." <span id='show-percent'>(".round((100*$total_fe_show/$count_show),2)."%)</span> <br/>
									hits:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									".$total_hit_show." / ".$count_show." <span id='show-percent'>(".round((100*$total_hit_show/$count_show),2)."%)</span> <br/>";
									}
									$output_body .="
													</div>
												</div>";
											
				if($using_sam){
					$output_body .= "<div class='crtcadreport'><h4 class=header-left>Spoken Word:</h4>";
						$output_body .= 'Tracked Plays:<br/>';
					
					foreach($samPlays as $j => $w){
					
					if(	( $w['date_unix']>= $start_unix) && 
						( $w['date_unix']<= $end_unix) ) {
						$time_played = explode(' ',$w['date_played']);
						$time_played = date("g:i a ",strtotime($time_played[1]));
						$duration = round(($w['duration']/1000),0);
						$type = $w['songtype'];
						if ($type=='I') $type = 'Station ID (43)'; else{}
						if ($type=='A') {
								$type = 'Advertisement (51)';
								$total_ads_show += $duration;
						
							} else{}
						$output_body .= $time_played.' - '.$type.' - "'.$w['artist'].' '.$w['title'].'" ('.$duration.' secs)<br/>';
						
						}
					}
					
					$output_body .= '<br/>Station IDs:<br/>';
				}
				
				if($using_sam && $enabled['adscheduler']){
			//		returns array($times,$types,$names,$playeds);
					$jackson = $adLib->loadAdsForReport($v['id']);
					
					
					$times_list = $jackson[0];
					$types_list = $jackson[1];
					$names_list = $jackson[2];
					$playeds = $jackson[3];
					
					if(!empty($times_list))	{
										foreach( $times_list as $x => $time_val){
											
											if( $playeds[$x] && $types_list[$x]=='Station ID'){
												$output_body .= $times_list[$x].': Station ID<br/>';
											}
										}
									}
				}
				
				
				
				
				
//				print_r($v);
				
				if ($thisSpokenWord>0){
				$output_body .= "<br/>This show's spoken word (cat 12) duration: ";
				$output_body .= $thisSpokenWord." minutes";
				}
				
			$output_body .= "</div>";
		$output_body .= "</div>";
		
}// end of for each show


$output_summary .= "<table class='report-table'><tr class='report-header'><td>Category</td><td>Count</td><td>Percentage</td><td>Requirement</td>
			<tr>
				<td>
					total playlist
				</td>
				<td>".
					$total_pl.
				"</td>
				<td>".
					round((($total_pl / $total_items)*100),2)."%".
				"</td>
				<td>".
					$pl_req."%".
				"</td>
			</tr>
			<tr>
				<td>
					total femcon
				</td>
				<td>".
					$total_fe.
				"</td>
				<td>".
					round((($total_fe / $total_items)*100),2)."%".
				"</td>
				<td>".
					$fe_req."%".
				"</td>
			</tr>
			<tr>
				<td>
					total instrumental
				</td>
				<td>".
					$total_inst.
				"</td>
				<td>".
					round((($total_inst / $total_items)*100),2)."%".
				"</td><td>&nbsp;</td>
			</tr>
			<tr>
				<td>
					total hits
				</td>
				<td>".
					$total_hit.
				"</td>
				<td>".
					round((($total_hit / $total_items)*100),2)."%".
				"</td>
				<td>".
					$hit_req."% (max)".
				"</td>
			</tr>
			<tr class='summarybold'>
				<td>
					total number of songs
				</td>
				<td>".
					$total_items.
				"</td><td>&nbsp;</td><td>&nbsp;</td>
			</tr>
			<tr class='report-header'><td colspan=4>Cancon Summary</td>
			</tr>
			<tr>
				<td>
					total cancon (cat 2)
				</td>
				<td>".
					$total_cc_reg.
				"</td>
				<td>".
					round((($total_cc_reg / $total_reg)*100),2)."%".
				"</td>
				<td>".
					$cc_reg_req."%".
				"</td>
			</tr>
			<tr class='summarybold'>
				<td>
					total songs (cat 2)
				</td>
				<td>".
					$total_reg.
				"</td><td>&nbsp;</td><td>&nbsp;</td>
			</tr>
			<tr>
				<td>
					total cancon (cat 3)
				</td>
				<td>".
					$total_cc_spec.
				"</td>
				<td>".
					round((($total_cc_spec / $total_spec)*100),2)."%".
				"</td>
				<td>".
					$cc_spec_req."%".
				"</td>
			</tr>
			<tr class='summarybold'>
				<td>
					total songs (cat 3)
				</td>
				<td>".
					$total_spec.
				"</td><td>&nbsp;</td><td>&nbsp;</td>
			</tr>
			<tr class='report-header'><td colspan=4>Spoken Word</td>
			</tr>
				<tr>
				<td>
					Locally Produced  (cat 12)
				</td>
				<td>".
					$totalSpokenWord." min".
				"</td>
				<td>".
					round((($totalSpokenWord / ($total_hours*60))*100),2)."%".
				"</td>
				<td>".
					$locally_produced_req."%".
				"</td>
			</tr>
			</tr>
				<tr>
				<td>
					Ads (cat 51)
				</td>
				<td>".
					round(($total_ads/60),2)." min".
				"</td>
				<td>".
				"</td>
				<td>".
					$max_ad_mins." mins (max)".
				"</td>
			</tr>
		</table>";
			
						
//			$total_items = ($total_items) ? $total_items / 100 : 1;
//			printf("<tr><td>%s</td><td>%2.0f%%</td><td>%2.0f%%</td><td>%2.0f%%</td><td>%2.0f%%</td><td>%2.0f%%</td><td>%2.0f%%</td></tr>", "Total", $total_pl/$total_items, $total_cc_reg/$total_items,$total_cc_spec/$total_items, $total_yo/$total_items, $total_in/$total_items, $total_fe/$total_items);
//			printf("</table><br>");

$output_summary .= "<hr><h2 class=header-left>Content Breakdown by Show</h2><h3 class=header-left>Legend</h3>Content Flags:<br/><img src='images/pl.png' class=report_img>: New<br/>";
$output_summary .= "<img src='images/CAN.png' class=report_img>: Canadian Content<br/>";
$output_summary .= "<img src='images/fe.png' class=report_img>: Female Content<br/>";
$output_summary .= "<img src='images/inst.png' class=report_img>: Instrumental<br/>";
$output_summary .= "<img src='images/part.png' class=report_img>: Partial Play<br/>";
$output_summary .= "<img src='images/hit.png' class=report_img>: Hit<br/>";
$output_summary .= "<img src='images/background.png' class=report_img>: Background Music<br/>";
$output_summary .= "<img src='images/theme.png' class=report_img>: Theme Song<br/>";
$output_summary .= '<br/><br/> Sample music entry field:';
$output_summary .= '<div class="dotted-underline"><div class="report-entry">
      [Time Played] Artist - Song  
    <span class="entry-lang">(Language) (Composer) (Duration)</span></div><span class="report-icons"><a>(category) [content flags]</a> </span></div>';

$output_summary .= "</div>";



echo $output_summary.$output_body;

?>