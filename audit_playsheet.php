<?php

include_once("headers/session_header.php");
require_once("headers/security_header.php");
require_once("headers/function_header.php");
require_once("headers/menu_header.php");

printf("<html><head><meta name=ROBOTS content=\"NOINDEX, NOFOLLOW\">");
printf("<link rel=stylesheet href=css/style.css type=text/css>");
printf("<title>CiTR 101.9</title></head><body>");

print_menu();

if( (is_member("dj") || (is_member("editdj") && isset($_POST['id']) ) ) && isset($_GET['action']) && $_GET['action'] == "submit") {

	$show_id = fget_id($_POST['showtitle'], "shows", false);
	$host_id = fget_id($_POST['host'], "hosts", true);
	$create_name = get_username();
	$create_date = date('Y-m-d H:i:s');
	$edit_name = get_username();
	$show_date = fas($_POST['pl_date_year'] . "-" . $_POST['pl_date_month'] . "-" . $_POST['pl_date_day']);
	$start_time = fas($_POST['pl_date_year'] . "-" . $_POST['pl_date_month'] . "-" . $_POST['pl_date_day'] . " " . $_POST['pl_date_hour'] . ":" . $_POST['pl_date_min'] . ":" . "00");
	$end_time = fas($_POST['end_date_hour'] . ":" . $_POST['end_date_min'] . ":" . "00");

	if(isset($_POST['id']) && $_POST['id']) {
		//Delete all play items
		$ed = $_POST['id'];
		mysqli_query( $db,"DELETE FROM `playitems` WHERE playsheet_id='$ed'");
	}
	else {
		mysqli_query($db,"INSERT INTO `playlists` (id, create_date, create_name) VALUES (NULL, '$create_date', '$create_name')");
		$ed = mysqli_insert_id($db);
	}
	mysqli_query( $db,"UPDATE `shows` SET last_show='$start_time' WHERE id='$show_id' AND last_show < '$start_time'");
	mysqli_query( $db,"UPDATE `playlists` SET show_id='$show_id', host_id='$host_id', edit_name='$edit_name', start_time='$start_time', end_time='$end_time' WHERE id='$ed'");
	for($i=0; $i < $playlist_entries; $i++) {
		if($_POST['song'.$i]) {
			mysqli_query( $db,"INSERT INTO `playitems` (playsheet_id, show_id, song_id, format_id, is_playlist, is_canadian, is_yourown, is_indy, is_fem, show_date, duration, is_theme, is_background) VALUES ('$ed', '$show_id', '".fget_song_id($_POST['artist'.$i], $_POST['title'.$i], $_POST['song'.$i])."', '".$fformat_id[$_POST['format'.$i]]."', '".(isset($_POST['pl'.$i])?1:0)."', '".(isset($_POST['cc'.$i])?1:0)."', '".(isset($_POST['yo'.$i])?1:0)."', '".(isset($_POST['indy'.$i])?1:0)."', '".(isset($_POST['fem'.$i])?1:0)."', '$show_date', '".$_POST['duration'.$i]."', '".(isset($_POST['theme'.$i])?1:0)."', '".(isset($_POST['background'.$i])?1:0)."')");
		}
	}
}
else if(isset($_GET['action']) && $_GET['action'] == 'list' ) {

	printf("<CENTER><FORM METHOD=\"GET\" ACTION=\"%s\" name=\"the_form\">\n", $_SERVER['SCRIPT_NAME']);
	printf("<INPUT type=hidden name=action value=edit>");
	printf("<SELECT NAME=\"id\" SIZE=15>\n");

	$result = mysqli_query($db,"SELECT * FROM playlists WHERE show_id!='".$fshow_id['!DELETED']."' ORDER BY start_time DESC");
	$num_rows = mysqli_num_rows($result);
	$count = 0;
	while($count < $num_rows) {

		printf("<OPTION VALUE=\"%s\">[%s] - %s\n", mysqli_result_dep($result,$count,"id"), mysqli_result_dep($result,$count,"start_time"), $fshow_name[mysqli_result_dep($result,$count,"show_id")]);
		$count++;
	}
	printf("</SELECT><BR><INPUT TYPE=submit VALUE=\"View Playsheet\">\n");
	printf("</FORM></CENTER>\n");

}
else if(is_member("member") && isset($_GET['action']) && $_GET['action'] == 'report' ) {
	printf("<br><table align=center class=playsheet><tr><td>");
	printf("<center>");
	printf("<FORM METHOD=POST ONSUBMIT=\"\" ACTION=\"%s?action=report\" name=\"playsheet\">", $_SERVER['SCRIPT_NAME']);
	printf("<center><h1>DJ PLAYSHEET REPORT</h1></center>");


	//Playlist Start Date
	printf("<table border=0><tr><td align=right>Start Date: ");
	printf("(<SELECT NAME=pl_date_year>\n<OPTION>%s", date('Y'));
	for($i=2002; $i <= 2010; $i++) printf("<OPTION>%s", $i); 
	printf("</SELECT>-");
	printf("<SELECT NAME=pl_date_month>\n<OPTION>%s", date('m'));
	for($i=1; $i <= 12; $i++) printf("<OPTION>%02d", $i); 
	printf("</SELECT>-");
	printf("<SELECT NAME=pl_date_day>\n<OPTION>%02d", date('d'));
	for($i=1; $i <= 31; $i++) printf("<OPTION>%02d", $i); 
	printf("</SELECT>)");

	printf("<br>End Date: ");
	printf("(<SELECT NAME=end_date_year>\n<OPTION>%s", date('Y'));
	for($i=2002; $i <= 2010; $i++) printf("<OPTION>%s", $i); 
	printf("</SELECT>-");
	printf("<SELECT NAME=end_date_month>\n<OPTION>%s", date('m'));
	for($i=1; $i <= 12; $i++) printf("<OPTION>%02d", $i); 
	printf("</SELECT>-");
	printf("<SELECT NAME=end_date_day>\n<OPTION>%02d", date('d'));
	for($i=1; $i <= 31; $i++) printf("<OPTION>%02d", $i); 
	printf("</SELECT>)");

	printf("</td><td width=30></td><td align=right>Show Title: <select name=\"showtitle\">");
	printf("<option>%s", "All Shows");
	foreach($fshow_name as $var_name) {
		if($var_name != '!DELETED') printf("<option>%s", $var_name);
	}
	printf("</select>");

	printf("<br><input type=submit value=\"Generate Report\">");
	printf("</td></tr></table>");
	printf("</FORM>");

	//Generate the report
	if(isset($_POST['showtitle'])) {

		$start_date = fas($_POST['pl_date_year'] . "-" . $_POST['pl_date_month'] . "-" . $_POST['pl_date_day']);
		$end_date = fas($_POST['end_date_year'] . "-" . $_POST['end_date_month'] . "-" . $_POST['end_date_day']);
		$start_time = $start_date . " " . "00" . ":" . "00" . ":" . "00";
		$end_time = $end_date . " " . "23" . ":" . "59" . ":" . "59";

		$total_items = 0;
		$total_pl = 0;
		$total_cc = 0;
		$total_yo = 0;
		$total_in = 0;
		$total_fe = 0;

		//Do All Shows
		if($_POST['showtitle'] == "All Shows") {
			printf("<table border=1 class=report align=center><tr><td>Show Title</td><td>Playlist</td><td>Canadian</td><td>Your Own</td><td>Indy</td><td>Female</td></tr>");
			$result = mysqli_query($db,"SELECT * FROM shows WHERE last_show >= '$start_time' ORDER BY name");
			$num_rows = mysqli_num_rows($result);
			$count = 0;
			while($count < $num_rows) {
				$show_id = mysqli_result_dep($result,$count,"id");
				$show_cc_req = mysqli_result_dep($result,$count,"cc_req");
				$show_pl_req = mysqli_result_dep($result,$count,"pl_req");
				$show_in_req = mysqli_result_dep($result,$count,"indy_req");
				$show_fe_req = mysqli_result_dep($result,$count,"fem_req");
				$the_query = "SELECT COUNT(*) FROM playitems WHERE show_date >= '$start_date' AND show_date <= '$end_date' AND show_id='$show_id'";
				$total_items += $count_items = mysqli_result_dep(mysqli_query($db,$the_query),0);
				$total_pl += $count_pl = mysqli_result_dep(mysqli_query($db,$the_query." AND is_playlist"),0);
				$total_cc += $count_cc = mysqli_result_dep(mysqli_query($db,$the_query." AND is_canadian"),0);
				$total_yo += $count_yo = mysqli_result_dep(mysqli_query($db, $the_query." AND is_yourown"),0);
				$total_in += $count_in = mysqli_result_dep(mysqli_query($db, $the_query." AND is_indy"),0);
				$total_fe += $count_fe = mysqli_result_dep(mysqli_query($db, $the_query." AND is_fem"),0);
				if($count_items) {
					$count_items = $count_items / 100;
					printf("<tr><td>%s</td>", $fshow_name[$show_id]);
					printf("<td class=%s>%2.0f%% / %2.0f%%</td>", (($count_pl/$count_items >= $show_pl_req) ? "reqmeet" : "reqfail"), $count_pl/$count_items, $show_pl_req);
					printf("<td class=%s>%2.0f%% / %2.0f%%</td>", (($count_cc/$count_items >= $show_cc_req) ? "reqmeet" : "reqfail"), $count_cc/$count_items, $show_cc_req);
					printf("<td class=%s>%2.0f%%</td>", "reqmeet", $count_yo/$count_items);
					printf("<td class=%s>%2.0f%% / %2.0f%%</td>", (($count_in/$count_items >= $show_in_req) ? "reqmeet" : "reqfail"), $count_in/$count_items, $show_in_req);
					printf("<td class=%s>%2.0f%% / %2.0f%%</td>", (($count_fe/$count_items >= $show_fe_req) ? "reqmeet" : "reqfail"), $count_fe/$count_items, $show_fe_req);
					printf("</tr>");
				}
				else {
					printf("<tr><td>%s</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td></tr>", $fshow_name[$show_id]);
				}
				$count++;
			}
			$total_items = ($total_items) ? $total_items / 100 : 1;
			printf("<tr><td>%s</td><td>%2.0f%%</td><td>%2.0f%%</td><td>%2.0f%%</td><td>%2.0f%%</td><td>%2.0f%%</td></tr>", "Total", $total_pl/$total_items, $total_cc/$total_items, $total_yo/$total_items, $total_in/$total_items, $total_fe/$total_items);
			printf("</table><br>");
		}
		//Do Single Show
		else {
			printf("<table cellpadding=5 border=1 class=report align=center><tr><td>Playsheet Date</td><td>Playlist</td><td>Canadian</td><td>Your Own</td><td>Indy</td><td>Female</td></tr>");
			$show_id = $fshow_id[$_POST['showtitle']];
			$result = mysqli_query( $db,"SELECT * FROM shows WHERE id='$show_id'");
			$show_cc_req = mysqli_result_dep($result,0,"cc_req");
			$show_pl_req = mysqli_result_dep($result,0,"pl_req");
			$show_in_req = mysqli_result_dep($result,0,"indy_req");
			$show_fe_req = mysqli_result_dep($result,0,"fem_req");
			$result = mysqli_query($db, "SELECT * FROM playlists WHERE start_time >= '$start_time' AND start_time <= '$end_time' AND show_id='$show_id'");
			$num_rows = mysqli_num_rows($result);
			$count = 0;
			while($count < $num_rows) {
				$playsheet_id = mysqli_result_dep($result,$count,"id");
				$the_query = "SELECT COUNT(*) FROM playitems WHERE playsheet_id='$playsheet_id'";
				$total_items += $count_items = mysqli_result_dep(mysqli_query($db, $the_query),0);
				$total_pl += $count_pl = mysqli_result_dep(mysqli_query($db,$the_query." AND is_playlist"),0);
				$total_cc += $count_cc = mysqli_result_dep(mysqli_query($db,$the_query." AND is_canadian"),0);
				$total_yo += $count_yo = mysqli_result_dep(mysqli_query($db, $the_query." AND is_yourown"),0);
				$total_in += $count_in = mysqli_result_dep(mysqli_query($db,$the_query." AND is_indy"),0);
				$total_fe += $count_fe = mysqli_result_dep(mysqli_query($db,$the_query." AND is_fem"),0);

				if($count_items) {
					$count_items = $count_items / 100;
					printf("<tr><td>%s</td>", mysqli_result_dep($result,$count,"start_time"));
					printf("<td class=%s>%2.0f%% / %2.0f%%</td>", (($count_pl/$count_items >= $show_pl_req) ? "reqmeet" : "reqfail"), $count_pl/$count_items, $show_pl_req);
					printf("<td class=%s>%2.0f%% / %2.0f%%</td>", (($count_cc/$count_items >= $show_cc_req) ? "reqmeet" : "reqfail"), $count_cc/$count_items, $show_cc_req);
					printf("<td class=%s>%2.0f%%</td>", "reqmeet", $count_yo/$count_items);
					printf("<td class=%s>%2.0f%% / %2.0f%%</td>", (($count_in/$count_items >= $show_in_req) ? "reqmeet" : "reqfail"), $count_in/$count_items, $show_in_req);
					printf("<td class=%s>%2.0f%% / %2.0f%%</td>", (($count_fe/$count_items >= $show_fe_req) ? "reqmeet" : "reqfail"), $count_fe/$count_items, $show_fe_req);
					printf("</tr>");
				}
				else {
					printf("<tr><td>%s</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td></tr>", mysqli_result_dep($result,$count,"start_time"));
				}
				$count++;
			}
			$total_items = ($total_items) ? $total_items / 100 : 1;
			printf("<tr><td>%s</td>", "Total");
			printf("<td class=%s>%2.0f%% / %2.0f%%</td>", (($total_pl/$total_items >= $show_pl_req) ? "reqmeet" : "reqfail"), $total_pl/$total_items, $show_pl_req);
			printf("<td class=%s>%2.0f%% / %2.0f%%</td>", (($total_cc/$total_items >= $show_cc_req) ? "reqmeet" : "reqfail"), $total_cc/$total_items, $show_cc_req);
			printf("<td class=%s>%2.0f%%</td>", "reqmeet", $total_yo/$total_items);
			printf("<td class=%s>%2.0f%% / %2.0f%%</td>", (($total_in/$total_items >= $show_in_req) ? "reqmeet" : "reqfail"), $total_in/$total_items, $show_in_req);
			printf("<td class=%s>%2.0f%% / %2.0f%%</td>", (($total_fe/$total_items >= $show_fe_req) ? "reqmeet" : "reqfail"), $total_fe/$total_items, $show_fe_req);
			printf("</tr>");
			printf("</table><br>");
		}
	}
}
else if(is_member("member") && isset($_GET['action']) && $_GET['action'] == 'report2' ) {

	echo "<table align=center><tr><td>";

	$result = mysqli_query($db,"SELECT *,UNIX_TIMESTAMP(start_time) AS good_date, HOUR(end_time) AS end_hour, MINUTE(end_time) AS end_min FROM playlists WHERE start_time < '2004-12-5' AND start_time >= '2004-12-1' ORDER BY start_time");
	
	$ed = true;

	$num_rows = mysqli_num_rows($result);
	$count = 0;
	while($count < $num_rows) {

		//Y-m-d H:i:s
		$pl_date_year = $ed ? date('Y', mysqli_result_dep($result, $count, "good_date")) : date('Y');
		$pl_date_month = $ed ? date('m', mysqli_result_dep($result, $count, "good_date")) : date('m');
		$pl_date_day = $ed ? date('d', mysqli_result_dep($result, $count, "good_date")) : date('d');
		$pl_date_hour = $ed ? date('H', mysqli_result_dep($result, $count, "good_date")) : date('H');
		$pl_date_min = $ed ? date('i', mysqli_result_dep($result, $count, "good_date")) : date('i');
		$end_date_hour = $ed ? mysqli_result_dep($result, $count, "end_hour") : date('H');
		$end_date_min = $ed ? mysqli_result_dep($result, $count, "end_min") : date('i');
		$host_name = $ed ? $fhost_name[mysqli_result_dep($result, $count, "host_id")] : "";
		$show_name = $ed ? $fshow_name[mysqli_result_dep($result, $count, "show_id")] : "";
		$playsheet_id = mysqli_result_dep($result, $count, "id");

		$cur_dow = date('w');
		$cur_time = date('H:i:s');

		echo "Show: " . $show_name . "<br>";
		echo "Host: " . $host_name . "<br>";
		echo "Date: (" . $pl_date_year . "-" . $pl_date_month . "-" . $pl_date_day . ")<br>";
		echo "Start Time: " . $pl_date_hour . ":" . $pl_date_min . "<br>";
		printf("End Time: %02d:%02d<br>", $end_date_hour, $end_date_min);

		printf("<table border=1 width=100%%>");
		printf("<tr><td align=center>Format</td><td align=center>Artist</td><td align=center>Title</td><td align=center>pl</td><td align=center>cc</td><td align=center>yo</td><td align=center>in</td><td align=center>fe</td><td align=center>thm</td><td align=center>bg</td><td align=center>Duration</td><td align=center>Song</td></tr>");


		$result2 = mysqli_query($db,"SELECT * FROM playitems WHERE playsheet_id='$playsheet_id' ORDER BY id");
		$num_rows2 = mysqli_num_rows($result2);
		for($i=0; $i < $num_rows2; $i++) {

			$result3 = mysqli_query($db,"SELECT * FROM songs WHERE id='".mysqli_result_dep($result2,$i,"song_id")."'");
			echo "<tr><td>" . $fformat_name[mysqli_result_dep($result2,$i,"format_id")] . "</td><td>";
			echo mysqli_result_dep($result3,0,"artist") . "</td><td>";
			echo mysqli_result_dep($result3,0,"title") . "</td><td>";
			echo (mysqli_result_dep($result2,$i,"is_playlist") ? "X" : "") . "</td><td>";
			echo (mysqli_result_dep($result2,$i,"is_canadian") ? "X" : "") . "</td><td>";
			echo (mysqli_result_dep($result2,$i,"is_yourown") ? "X" : "") . "</td><td>";
			echo (mysqli_result_dep($result2,$i,"is_indy") ? "X" : "") . "</td><td>";
			echo (mysqli_result_dep($result2,$i,"is_fem") ? "X" : "") . "</td><td>";;
			echo (mysqli_result_dep($result2,$i,"is_theme") ? "X" : "") . "</td><td>";;
			echo (mysqli_result_dep($result2,$i,"is_background") ? "X" : "") . "</td><td>";;
			echo mysqli_result_dep($result2,$i,"duration") . "</td><td>";;
			echo mysqli_result_dep($result3,0,"song") . "</td></tr>";
			echo "\n";
		}

		echo "</table><br><hr><br>";

		$count++;
	}
	echo "</td></tr></table>";

}
else if(is_member("dj")){

	if( /*is_member("editdj") && */ isset($_GET['action']) && ($_GET['action'] == 'edit' || $_GET['action'] == 'datadump')) {
		$ed = fas($_GET['id']);
		$result = mysqli_query($db,"SELECT *,UNIX_TIMESTAMP(start_time) AS good_date, HOUR(end_time) AS end_hour, MINUTE(end_time) AS end_min FROM playlists WHERE id='$ed'");
	}
	else {
		$ed = 0;
	}
	
	//Y-m-d H:i:s
	$pl_date_year = $ed ? date('Y', mysqli_result_dep($result, 0, "good_date")) : date('Y');
	$pl_date_month = $ed ? date('m', mysqli_result_dep($result, 0, "good_date")) : date('m');
	$pl_date_day = $ed ? date('d', mysqli_result_dep($result, 0, "good_date")) : date('d');
	$pl_date_hour = $ed ? date('H', mysqli_result_dep($result, 0, "good_date")) : date('H');
	$pl_date_min = $ed ? date('i', mysqli_result_dep($result, 0, "good_date")) : date('i');
	$end_date_hour = $ed ? mysqli_result_dep($result, 0, "end_hour") : date('H');
	$end_date_min = $ed ? mysqli_result_dep($result, 0, "end_min") : date('i');
	$host_name = $ed ? $fhost_name[mysqli_result_dep($result, 0, "host_id")] : "";
	$show_name = $ed ? $fshow_name[mysqli_result_dep($result, 0, "show_id")] : "";

	$cur_dow = date('w');
	$cur_time = date('H:i:s');
	if(!$ed && mysqli_num_rows($result2 = mysqli_query($db,"SELECT *,HOUR(end_time) AS end_hour, MINUTE(end_time) AS end_min, HOUR(start_time) AS start_hour, MINUTE(start_time) AS start_min FROM shows WHERE weekday='$cur_dow' AND start_time < '$cur_time' AND (end_time > '$cur_time' OR end_time < start_time) ORDER BY last_show DESC") )) {

		$pl_date_hour = mysqli_result_dep($result2, 0, "start_hour");
		$pl_date_min = mysqli_result_dep($result2, 0, "start_min");
		$end_date_hour = mysqli_result_dep($result2, 0, "end_hour");
		$end_date_min = mysqli_result_dep($result2, 0, "end_min");
		$host_name = $fhost_name[mysqli_result_dep($result2, 0, "host_id")];
		$show_name =  mysqli_result_dep($result2, 0, "name");

	}
?>
		<script language=JavaScript>
		<!--
		function EnterToTab(event)
		{
		  if (document.all)
		  {
		    if (event.keyCode == 13)
		      {  // handles IE browsers...
		        event.keyCode = 9;
		      }
		  }
		  else if (document.getElementById)
		  { // handles NS and Mozilla browsers...
		    if (event.which == 13)
		    {
		        event.keyCode = 9;
		      }
		  }
		  else if(document.layers)
		  { // handles NS ver. 4+ browsers...
		    if(event.which == 13)
		    {
		        event.keyCode = 9;
		    }
		  }
		}
		-->
		</script>
<?php
	if($ed && $_GET['action'] != 'datadump') {
		printf("<br><table class=menu border=0 align=center><tr>");
		printf("<td class=menu><a href=\"playsheet.php?action=datadump&id=%s\">&nbsp;Raw Data&nbsp;</a></td></tr></table>",$ed);
	}	
	else if ($ed){
		printf("<br><table class=menu border=0 align=center><tr>");
		printf("<td class=menu><a href=\"playsheet.php?action=edit&id=%s\">&nbsp;View Playsheet&nbsp;</a></td></tr></table>",$ed);
	}	


	printf("<br><table align=center class=playsheet><tr><td>");
	if($_GET['action'] == 'datadump') {

		printf("<br><pre>");
		printf("Artist, Title, Song, Format, Playlist, Canadian, Yourown, Indy, Female\n\n");

		if($ed) {
			$result = mysqli_query($db,"SELECT * FROM playitems WHERE playsheet_id='$ed' ORDER BY id");
			$num_rows = mysqli_num_rows($result);
		}
		else {
			$num_rows = 0;
		}
		for($i=0; $i < $num_rows; $i++) {


			$result2 = mysqli_query($db,"SELECT * FROM songs WHERE id='".mysqli_result_dep($result,$i,"song_id")."'");
			echo mysqli_result_dep($result2,0,"artist") . ", ";
			echo mysqli_result_dep($result2,0,"title") . ", ";
			echo mysqli_result_dep($result2,0,"song") . ", ";
			echo $fformat_name[mysqli_result_dep($result,$i,"format_id")] . ", ";
			echo (mysqli_result_dep($result,$i,"is_playlist") ? "true" : "false") . ", ";
			echo (mysqli_result_dep($result,$i,"is_canadian") ? "true" : "false") . ", ";
			echo (mysqli_result_dep($result,$i,"is_yourown") ? "true" : "false") . ", ";
			echo (mysqli_result_dep($result,$i,"is_indy") ? "true" : "false") . ", ";
			echo (mysqli_result_dep($result,$i,"is_fem") ? "true" : "false");
			echo "\n";
		}
		echo "</pre>";

	}
	else {

		printf("<FORM METHOD=POST ONSUBMIT=\"return confirm('Are you sure you want to save this playsheet?')\" ACTION=\"%s?action=submit\" name=\"playsheet\">", $_SERVER['SCRIPT_NAME']);
		if($ed) {
			printf("<INPUT type=hidden name=id value=%s>", $ed);
		}
		printf("<center><h1>DJ PLAYSHEET</h1></center>");

		printf("<table align=center><tr><td>");
		printf("<table border=0 align=center width=100%%><tr><td>Show Title: <select name=\"showtitle\" onkeydown=\"EnterToTab(event)\">");
		if ($ed || $show_name) printf("<option>%s", $show_name);
		foreach($fshow_name as $var_name) {
			if($var_name != '!DELETED' || $ed) printf("<option>%s", $var_name);
		}
		printf("</select></td>");

		printf("<td align=right>Host/Op: <input name=\"host\" type=text size=30 value=\"%s\" onkeydown=\"EnterToTab(event)\"></td></table>", $host_name);

		//Playlist Date
		printf("<table width=100%% border=0 align=center><tr><td>Date: ");
		printf("(<SELECT NAME=pl_date_year onkeydown=\"EnterToTab(event)\">\n<OPTION>%s", $pl_date_year);
		for($i=2002; $i <= 2010; $i++) printf("<OPTION>%s", $i); 
		printf("</SELECT>-");
		printf("<SELECT NAME=pl_date_month onkeydown=\"EnterToTab(event)\">\n<OPTION>%s", $pl_date_month);
		for($i=1; $i <= 12; $i++) printf("<OPTION>%02d", $i); 
		printf("</SELECT>-");
		printf("<SELECT NAME=pl_date_day onkeydown=\"EnterToTab(event)\">\n<OPTION>%02d", $pl_date_day);
		for($i=1; $i <= 31; $i++) printf("<OPTION>%02d", $i); 
		printf("</SELECT>)");

		printf("</td><td align=right>Start Time: [");
		printf("<SELECT NAME=pl_date_hour onkeydown=\"EnterToTab(event)\">\n<OPTION>%02d", $pl_date_hour);
		for($i=0; $i <= 23; $i++) printf("<OPTION>%02d", $i); 
		printf("</SELECT>:");
		printf("<SELECT NAME=pl_date_min onkeydown=\"EnterToTab(event)\">\n<OPTION>%02d", $pl_date_min);
		for($i=0; $i <= 59; $i++) printf("<OPTION>%02d", $i); 
		printf("</SELECT>]");

		printf("</td><td align=right>End Time: [");
		printf("<SELECT NAME=end_date_hour onkeydown=\"EnterToTab(event)\">\n<OPTION>%02d", $end_date_hour);
		for($i=0; $i <= 23; $i++) printf("<OPTION>%02d", $i); 
		printf("</SELECT>:");
		printf("<SELECT NAME=end_date_min onkeydown=\"EnterToTab(event)\">\n<OPTION>%02d", $end_date_min);
		for($i=0; $i <= 59; $i++) printf("<OPTION>%02d", $i); 
		printf("</SELECT>]");

		printf("</td></tr></table><br><table align=center border=1>");
		printf("<tr><td align=center>Format</td><td align=center>Artist</td><td align=center>Title</td><td align=center>pl</td><td align=center>cc</td><td align=center>yo</td><td align=center>in</td><td align=center>fe</td><td align=center>Duration</td><td align=center>thm</td><td align=center>bg</td><td align=center>Song</td></tr>");

		if($ed) {
			$result = mysqli_query($db,"SELECT * FROM playitems WHERE playsheet_id='$ed' ORDER BY id");
			$num_rows = mysqli_num_rows($result);
		}
		else {
			$num_rows = 0;
		}
		for($i=0; $i < $playlist_entries; $i++) {
			printf("<tr><td><select name=format%s onkeydown=\"EnterToTab(event)\">", $i);
			if($i < $num_rows) {
				printf("<option>%s", $fformat_name[mysqli_result_dep($result,$i,"format_id")]);
				$set_pl = mysqli_result_dep($result,$i,"is_playlist") ? " checked" : "";
				$set_cc = mysqli_result_dep($result,$i,"is_canadian") ? " checked" : "";
				$set_yo = mysqli_result_dep($result,$i,"is_yourown") ? " checked" : "";
				$set_indy = mysqli_result_dep($result,$i,"is_indy") ? " checked" : "";
				$set_fem = mysqli_result_dep($result,$i,"is_fem") ? " checked" : "";

				$set_theme = mysqli_result_dep($result,$i,"is_theme") ? " checked" : "";
				$set_background = mysqli_result_dep($result,$i,"is_background") ? " checked" : "";
				$set_duration = mysqli_result_dep($result,$i,"duration");

				$result2 = mysqli_query($db,"SELECT * FROM songs WHERE id='".mysqli_result_dep($result,$i,"song_id")."'");
				$set_artist = mysqli_result_dep($result2,0,"artist");
				$set_title = mysqli_result_dep($result2,0,"title");
				$set_song = mysqli_result_dep($result2,0,"song");
			}
			else {
				$set_pl = "";
				$set_cc = "";
				$set_yo = "";
				$set_indy = "";
				$set_fem = "";
				$set_artist = "";
				$set_title = "";
				$set_song = "";
				$set_duration = "";
				$set_theme = "";
				$set_background = "";
			}
			foreach($fformat_name as $var_name) {
				printf("<option>%s", $var_name);
			}
			printf("</select></td>");
			printf("<td><input name=artist%s type=text size=20 value=\"%s\" onkeydown=\"EnterToTab(event)\"></td>", $i, $set_artist);
			printf("<td><input name=title%s type=text size=20 value=\"%s\" onkeydown=\"EnterToTab(event)\"></td>", $i, $set_title);
			printf("<td><input type=checkbox name=pl%s%s onkeydown=\"EnterToTab(event)\"></td>", $i, $set_pl);
			printf("<td><input type=checkbox name=cc%s%s onkeydown=\"EnterToTab(event)\"></td>", $i, $set_cc);
			printf("<td><input type=checkbox name=yo%s%s onkeydown=\"EnterToTab(event)\"></td>", $i, $set_yo);
			printf("<td><input type=checkbox name=indy%s%s onkeydown=\"EnterToTab(event)\"></td>", $i, $set_indy);
			printf("<td><input type=checkbox name=fem%s%s onkeydown=\"EnterToTab(event)\"></td>", $i, $set_fem);

			printf("<td><input name=duration%s type=text size=4 value=\"%s\" onkeydown=\"EnterToTab(event)\"></td>", $i, $set_duration);
			printf("<td><input type=checkbox name=theme%s%s onkeydown=\"EnterToTab(event)\"></td>", $i, $set_theme);
			printf("<td><input type=checkbox name=background%s%s onkeydown=\"EnterToTab(event)\"></td>", $i, $set_background);

			printf("<td><input name=song%s type=text size=20 value=\"%s\" onkeydown=\"EnterToTab(event)\"></td></tr>", $i, $set_song);
		}
		printf("</table>");
		if(!$ed || is_member("editdj")) {
			printf("<center><input type=submit value=\"Save Playsheet\"></center>");
		}
		printf("</FORM>");

		printf("</td></tr></table>");
	}
	printf("</td></tr></table>");

}

printf("</body></html>");

?>