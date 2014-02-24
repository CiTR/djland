<?php
//MENU HEADER

function print_menu() {

require('config.php');
printf("<link rel=stylesheet href=style.css type=text/css>");

	printf("<table class=menu border=0 align=center><tr>");

	if(is_member("member") && get_username() != $station_wide_login_name) {
		printf("<td class=menu><a href=useradd.php?action=list>Users</a></td>");
	}
	if(is_member("membership") && $enabled['membership']) {

		 echo '<td class=menu><a href="membership.php">Membership</a></td>';
	}
	if(is_member("library") && $enabled['library']) {
		echo '<td class=menu><a href="library.php">Library</a></td>';
	}
	if(is_member("editlibrary") && $enabled['library'] ) {
		echo '<td class=menu><a href="library.php?action=add">Update Library</a></td>';
	}
	if(is_member("addshow") ) {
	if($enabled['shows'])	echo "<td class=menu><a href='shows.php?action=list'>Shows</a></td>";
	if($enabled['adscheduler'])	echo "<td class=menu><a href='adscheduler.php'>Ad Scheduler</a></td>";
	if($enabled['adscheduler'])	echo "<td class=menu><a href='adreport.php'>Ad Report</a></td>";
	if($enabled['charts'])	echo "<td class=menu><a href='charting.php'>Charts</a></td>";
	if($enabled['report'])	echo "<td class=menu><a href='crtcreport.php'>CRTC Report</a></td>";
	}
	if(is_member("dj")) {
	if($enabled['playsheets'])	echo "<td class=menu><a href='playsheet.php?action=list'>Playsheets</a></td>";
	if($enabled['playsheets'])	echo "<td class=menu><a href='playsheet.php'>New Playsheet</a></td>";
		//printf("<td class=menu><a href='playsheet.php?action=report'>Report</a></td>");
	if($enabled['report'])	echo "<td class=menu><a href='report.php'> Report (by show) </a></td>";
		echo "<td class=menu><a href='help.php' target='_blank'> Help </a></td>";
	}
	printf("<td class=menu><A HREF='index.php?action=logout'>Log Out</a></td>");

	printf("</tr></table>");

	if(isset($_SESSION['sv_login_fails']) && $_SESSION['sv_login_fails']) {
		printf("<br>");
		printf("<BR><center>WARNING!<BR>%s Login Failures</center>\n", $_SESSION['sv_login_fails']);
	}


}

//END MENU HEADER
?>