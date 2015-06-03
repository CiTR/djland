<?php

require_once('headers/db_header.php');
require_once('headers/function_header.php');
require_once('headers/showlib.php');
function mysqli_result($res, $row, $field=0) { 
//	echo 'called mysqli result';
//	echo '<br/>';
//	echo 'res:'.'<br/>';
//	print_r($res);
//	echo 'row:'.$row.'<br/>';
//	echo 'field:'.$field.'<br/>';
	if(is_object($res))    
		$res->data_seek($row); 
	else 	return false;
	
	$datarow = $res->fetch_array();
	
	if(is_array($datarow))
	    return $datarow[$field];
	else 	return false;
	        
} 
$showlib = new ShowLib($db);
//$showlib-> sayHi();
$allshows =  $showlib->getAllShows(true);

function cmp($a, $b)
{
	$show1name = $a->name;
	$show2name = $b->name;
	$show1name = preg_replace('/^(a|an|the) /i', "", $show1name);
	$show2name = preg_replace('/^(a|an|the) /i', "", $show2name);
	return strcasecmp($show1name, $show2name);
}
usort($allshows, "cmp");

$numshows = count($allshows);
$numletters = 26;
$letters = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
echo '<html><body class='wallpaper'>';
/* NOTE: Set Access-Control-Allow-Origin headers in IIS for domain hosting DJland. 
 * <head><meta http-equiv="Access-Control-Allow-Origin" content="www.citr.ca"></head> will not work, as IIS intercepts it.
 */
function printShow($show) {
	if ($show->active != '1') return;
//	if (!is_null($show->show_desc)) {
		echo "<h4>{$show->name}";
		$anchorvalue = trim($show->name);
//		$anchorvalue = $anchorvalue.replace(/\s+/, "");
		echo "<a href='#".$anchorvalue."'></a>";
		if (!is_null($show->podcast)) {
			echo " <a href=\"{$show->podcast}\"   ".
			"onclick=\"trackOutboundLink('".
			$show->podcast."'".
			"); return false;\">".
			"<img src=\"http://www.citr.ca/images/rss.gif\" alt=\"RSS\"/>".
			"</a>";
		}
		echo "</h4>";
		if (!is_null($show->img_url)) {
			echo "<p style=\"margin:0\"><img style=\"float:right; max-width: 160px; padding: 6px\" src=\"{$show->img_url}\" alt=\"Show Image\" /></p>";
		}
		if (count($show->times) > 0) {
			foreach ($show->times as $time_r) {
				if ($time_r['start_day'] == $time_r['end_day']) {
					if ($time_r['alternating'] != 0) {
						echo "Every other ";
					}
					$start_t = strtotime($time_r['start_time']);
					$end_t = strtotime($time_r['end_time']);
					echo ShowTime::$dow[$time_r['start_day']]." (".date("H:i",$start_t)." - ".date("H:i",$end_t).")";
				}
				else {
					if ($time_r['alternating'] != 0) {
						echo "Every other ";
					}
					$start_t = strtotime($time_r['start_time']);
					$end_t = strtotime($time_r['end_time']);
					echo ShowTime::$dow[$time_r['start_day']]." ".date("H:i",$start_t)." - ".ShowTime::$dow[$time_r['end_day']]." ".date("H:i",$end_t);
				}
				echo "<br />";
			}
		}
		if (!is_null($show->host)) {
			echo "<strong>Host:</strong> {$show->host} <br />";
		}
		
		echo "<strong>Description:</strong><br />", nl2br($show->show_desc);
		
		echo "<br />";
		if (!is_null($show->website)) {
			echo "<strong>Website:</strong> <a href=\"{$show->website}\">{$show->website}</a><br />";
		}
		if (count($show->contact) > 0) {
			foreach ($show->contact as $contact_r) {
				echo "<strong>".$contact_r['social_name']."</strong>: ";
				if ($contact_r['unlink'] == 0) {
					echo "<a href=\"{$contact_r['social_url']}\">",($contact_r['short_name'] != "" ? $contact_r['short_name'] : $contact_r['social_url']), "</a>";
				}
				else {
					echo $contact_r['social_url'];
				}
				echo "<br />";
			}
		}
		if (!is_null($show->secondary_genre_tags)) {
			echo "<strong>Genre:</strong> {$show->secondary_genre_tags}<br />";
		}
		if (count($show->sponsors) > 0) {
			echo "<strong>Sponsored By:</strong>";
			foreach ($show->sponsors as $sponsor) {
				echo "<br />";
				if (!is_null($sponsor["url"])) {
					echo "<a href=\"{$sponsor['url']}\">{$sponsor['name']}</a>";
				}
				else {
					echo $sponsor["name"];
				}
			}
		}
		echo "<br /><br />";
		echo "<a href=\"#Top\">Back to top</a>";
//	}
}

$spos = 0;
$lpos = 0;
$startl = false;

while($spos < $numshows) {
	
	$fletter = strtolower(substr(preg_replace('/^(a|an|the) /i', "", $allshows[$spos]->name), 0, 1));
	$currletter = strtolower($letters[$lpos]);
	if (!$startl) { // Print shows before "A" if there are any
		if ($fletter == $currletter) {
			$startl = true;
			echo "<a name='{$letters[$lpos]}'></a>";
		}
		printShow($allshows[$spos]);
		$spos++;
	}
	else if ($lpos == $numletters - 1) {
		for ($spos; $spos<$numshows; $spos++) { // Print rest of shows
			printShow($allshows[$spos]);
		}
	}
	else if ($fletter != $currletter) {
		$lpos++;
		echo "<a name='{$letters[$lpos]}'></a>";
	}
	else {
		printShow($allshows[$spos]);
		$spos++;
	}
}
for ($lpos++; $lpos<$numletters; $lpos++) { // Print rest of letters
	$currletter = strtolower($letters[$lpos]);
	echo "<a name='{$letters[$lpos]}'></a>";
}

echo '<h2>Archives (2012 and newer)</h2>';

foreach($allshows as $i => $show){
	if ($show->active == '0'){

		echo $show->name;
		if (!is_null($show->podcast)) {
			echo " <a href=\"{$show->podcast}\"><img src=\"http://www.citr.ca/images/rss.gif\" alt=\"RSS\"/></a>";
		}
		if (!is_null($show->secondary_genre_tags)) {
			echo " ({$show->secondary_genre_tags})";
		}
		echo '<br/>';
	

	}

}
echo '</body></html>';

mysqli_close($db);
//mysqli_select_db("citrwordpress", $db);
?>

