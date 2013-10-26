<?php
//MENU HEADER


function print_menu() {

	printf("<table class=menu border=0 align=center><tr>");

	if(is_member("member") && get_username() != "citrdjs") {
		printf("<td class=menu><a href=useradd.php?action=list>&nbsp;Users&nbsp;</a></td>");
	}
	if(is_member("membership")) {
		printf("<td class=menu><a href=\"membership.php\">&nbsp;Membership&nbsp;</a></td>");
	}
	if(is_member("library")) {
		printf("<td class=menu><a href=\"library.php\">&nbsp;Library&nbsp;</a></td>");
	}
	if(is_member("editlibrary")) {
		printf("<td class=menu><a href=\"library.php?action=add\">&nbsp;Update Library&nbsp;</a></td>");
	}
	if(is_member("addshow")) {
		echo "<td class=menu><a href=\"shows.php?action=list\">&nbsp;Shows&nbsp;</a></td>";
		echo "<td class=menu><a href=\"adscheduler.php\">&nbsp;Ad Scheduler&nbsp;</a></td>";
		echo "<td class=menu><a href=\"adreport.php\">&nbsp;Ad Report&nbsp;</a></td>";
		echo "<td class=menu><a href=\"charting.php\">&nbsp;Charts&nbsp;</a></td>";
		echo "<td class=menu><a href=\"crtcreport.php\">&nbsp;Report2&nbsp;</a></td>";
	}
	if(is_member("dj")) {
		printf("<td class=menu><a href=\"playsheet.php?action=list\">&nbsp;Playsheets&nbsp;</a></td>");
		printf("<td class=menu><a href=\"playsheet.php\">&nbsp;New Playsheet&nbsp;</a></td>");
		//printf("<td class=menu><a href=\"playsheet.php?action=report\">&nbsp;Report&nbsp;</a></td>");
		print("<td class=menu><a href='report.php'> Report </a></td>");
		print("<td class=menu><a href='help.php' target='_blank'> Help </a></td>");
	}
	printf("<td class=menu><A HREF=\"index.php?action=logout\">&nbsp;Log Out&nbsp;</a></td>");

	printf("</tr></table>");

	if(isset($_SESSION['sv_login_fails']) && $_SESSION['sv_login_fails']) {
		printf("<br>");
		printf("<BR><center>WARNING!<BR>%s Login Failures</center>\n", $_SESSION['sv_login_fails']);
	}


}

//END MENU HEADER
?>