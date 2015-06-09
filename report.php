<?php



session_start();
require_once("headers/security_header.php");
require_once("headers/function_header.php");
require_once("headers/menu_header.php");
if( permission_level() >= $djland_permission_levels['dj']){
printf("<html><head><meta name=ROBOTS content=\"NOINDEX, NOFOLLOW\">");
printf("<link rel=stylesheet href=css/style.css type=text/css>");
printf("<title>DJLAND | Report</title></head><body class='wallpaper'>");

print_menu();

	printf("<br><table align=center class=playsheet><tr><td>");
	printf("<center>");
	printf("<FORM METHOD=POST ONSUBMIT=\"\" ACTION=\"%s?action=report\" name=\"playsheet\">", $_SERVER['SCRIPT_NAME']);
	printf("<center><h1>DJ PLAYSHEET REPORT</h1></center>");


	//playsheet Start Date
	printf("<table border=0><tr><td align=right>Start Date: ");
	printf("(<SELECT NAME=pl_date_year>\n<OPTION>%s", date('Y'));
	for($i=2002; $i <= 2011; $i++) printf("<OPTION>%s", $i); 
	printf("</SELECT>-");
	printf("<SELECT NAME=pl_date_month>\n<OPTION>%s", date('m'));
	for($i=1; $i <= 12; $i++) printf("<OPTION>%02d", $i); 
	printf("</SELECT>-");
	printf("<SELECT NAME=pl_date_day>\n<OPTION>%02d", date('d'));
	for($i=1; $i <= 31; $i++) printf("<OPTION>%02d", $i); 
	printf("</SELECT>)");

	printf("<br>End Date: ");
	printf("(<SELECT NAME=end_date_year>\n<OPTION>%s", date('Y'));
	for($i=2002; $i <= 2011; $i++) printf("<OPTION>%s", $i); 
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
		$total_cc_reg = 0;
		$total_cc_spec = 0;
		$total_yo = 0;
		$total_in = 0;
		$total_fe = 0;

		//Do All Shows
		if($_POST['showtitle'] == "All Shows") {
			printf("<table border=1 class=report align=center><tr><td>Show Title</td><td>playsheet</td><td>Canadian (reg)</td><td>Canadian (spec)</td><td>Your Own</td><td>Indy</td><td>Female</td></tr>");
			$result = mysqli_query($db, "SELECT * FROM shows WHERE last_show >= '$start_time' ORDER BY name");
			$num_rows = mysqli_num_rows($result);
			$count = 0;
			while($count < $num_rows) {
						// for each show, no need to load the show's CRTC requirements
				$show_id = mysqli_result_dep($result,$count,"id");
				$show_cc_req_regular = 35; // mysqli_result_dep($result,$count,"cc_req");
				$show_cc_req_specialty = 7;
				$show_pl_req = 60;// mysqli_result_dep($result,$count,"pl_req");
				$show_in_req = 70;// mysqli_result_dep($result,$count,"indy_req");
				$show_fe_req = 35;// mysqli_result_dep($result,$count,"fem_req");
				$the_query = "SELECT COUNT(*) FROM playitems WHERE show_date >= '$start_date' AND show_date <= '$end_date' AND show_id='$show_id'";
				$total_items += $count_items = mysqli_result_dep(mysqli_query($db, $the_query),0);
				$total_pl += $count_pl = mysqli_result_dep(mysqli_query( $db,$the_query." AND is_playsheet"),0);
				$total_cc_reg += $count_cc_reg = mysqli_result_dep(mysqli_query($db,$the_query." AND is_canadian AND crtc_category DIV 10 = 2"),0);
				$total_cc_spec += $count_cc_spec = mysqli_result_dep(mysqli_query($db,$the_query." AND is_canadian AND crtc_category DIV 10 = 3"),0);
				$total_yo += $count_yo = mysqli_result_dep(mysqli_query($db,$the_query." AND is_yourown"),0);
				$total_in += $count_in = mysqli_result_dep(mysqli_query($db, $the_query." AND is_indy"),0);
				$total_fe += $count_fe = mysqli_result_dep(mysqli_query($db, $the_query." AND is_fem"),0);
				
				//new ones for new compliant cancon totals
				$count_regular = mysqli_result_dep(mysqli_query($db, $the_query." AND crtc_category DIV 10 = 2"),0);
				$count_specialty = mysqli_result_dep(mysqli_query($db, $the_query." AND crtc_category DIV 10 = 3"),0);
							
				
				
				
				if($count_items) {
					$count_items = $count_items / 100;
					$count_regular = $count_regular / 100;
					$count_specialty = $count_specialty / 100;
					printf("<tr><td>%s</td>", $fshow_name[$show_id]);
					printf("<td class=%s>%2.0f%% / %2.0f%%</td>", (($count_pl/$count_items >= $show_pl_req) ? "reqmeet" : "reqfail"), $count_pl/$count_items, $show_pl_req);
					printf("<td class=%s>%d / %d = %2.0f%%</td>", ((($count_regular==0) || ($count_cc_reg/$count_regular >= $show_cc_req_regular)) ? "reqmeet" : "reqfail"), $count_cc_reg, 100*$count_regular, $count_cc_reg/$count_regular);
					printf("<td class=%s>%d / %d = %2.0f%%</td>", ((($count_specialty==0) || ($count_cc_spec/$count_specialty >= $show_cc_req_specialty)) ? "reqmeet" : "reqfail"), $count_cc_spec, 100*$count_specialty, $count_cc_spec/$count_specialty);
					printf("<td class=%s>%2.0f%%</td>", "reqmeet", $count_yo/$count_items);
					printf("<td class=%s>%2.0f%% / %2.0f%%</td>", (($count_in/$count_items >= $show_in_req) ? "reqmeet" : "reqfail"), $count_in/$count_items, $show_in_req);
					printf("<td class=%s>%2.0f%% / %2.0f%%</td>", (($count_fe/$count_items >= $show_fe_req) ? "reqmeet" : "reqfail"), $count_fe/$count_items, $show_fe_req);
					printf("</tr>");
				}
				else {
					printf("<tr><td>%s</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td></tr>", $fshow_name[$show_id]);
				}
				$count++;
			}
			$total_items = ($total_items) ? $total_items / 100 : 1;
			printf("<tr><td>%s</td><td>%2.0f%%</td><td>%2.0f%%</td><td>%2.0f%%</td><td>%2.0f%%</td><td>%2.0f%%</td><td>%2.0f%%</td></tr>", "Total", $total_pl/$total_items, $total_cc_reg/$total_items,$total_cc_spec/$total_items, $total_yo/$total_items, $total_in/$total_items, $total_fe/$total_items);
			printf("</table><br>");
		}
		//Do Single Show
		else {
			printf($_POST['showtitle']);
			printf("<table cellpadding=5 border=1 class=report align=center><tr><td>Playsheet Date</td><td>playsheet</td><td>Canadian (reg)</td><td>Canadian (spec)</td><td>Your Own</td><td>Indy</td><td>Female</td></tr>");
			$show_id = $fshow_id[$_POST['showtitle']];
			$result = mysqli_query($db, "SELECT * FROM shows WHERE id='$show_id'");
			$show_cc_req_regular = 35; // mysqli_result_dep($result,$count,"cc_req");
			$show_cc_req_specialty = 7;
			$show_pl_req = 60;// mysqli_result_dep($result,$count,"pl_req");
			$show_in_req = 70;// mysqli_result_dep($result,$count,"indy_req");
			$show_fe_req = 35;// mysqli_result_dep($result,$count,"fem_req");
			$result = mysqli_query($db, "SELECT * FROM playsheets WHERE start_time >= '$start_time' AND start_time <= '$end_time' AND show_id='$show_id'");
			$num_rows = mysqli_num_rows($result);
			$count = 0;
			while($count < $num_rows) {
				$playsheet_id = mysqli_result_dep($result,$count,"id");
				$the_query = "SELECT COUNT(*) FROM playitems WHERE playsheet_id='$playsheet_id'";
				$total_items += $count_items = mysqli_result_dep(mysqli_query($db,$the_query),0);
				$total_pl += $count_pl = mysqli_result_dep(mysqli_query($db,$the_query." AND is_playsheet"),0);
				$total_cc_reg += $count_cc_reg = mysqli_result_dep(mysqli_query($db,$the_query." AND is_canadian AND crtc_category DIV 10 = 2"),0);
				$total_cc_spec += $count_cc_spec = mysqli_result_dep(mysqli_query($db,$the_query." AND is_canadian AND crtc_category DIV 10 = 3"),0);
				$total_yo += $count_yo = mysqli_result_dep(mysqli_query($db,$the_query." AND is_yourown"),0);
				$total_in += $count_in = mysqli_result_dep(mysqli_query($db,$the_query." AND is_indy"),0);
				$total_fe += $count_fe = mysqli_result_dep(mysqli_query($db,$the_query." AND is_fem"),0);
				
				//new ones for new compliant cancon totals
				$total_regular += $count_regular = mysqli_result_dep(mysqli_query($db,$the_query." AND crtc_category DIV 10 = 2"),0);
				$total_specialty += $count_specialty = mysqli_result_dep(mysqli_query($db,$the_query." AND crtc_category DIV 10 = 3"),0);
//printf("total regular: %d", $total_regular);
//printf("total specialty: %d", $total_specialty);
				if($count_items) {
					$count_items = $count_items / 100;
					$count_regular = $count_regular / 100;
					$count_specialty = $count_specialty / 100;
					printf("<tr><td>%s</td>", mysqli_result_dep($result,$count,"start_time"));
					printf("<td class=%s>%2.0f%% / %2.0f%%</td>", (($count_pl/$count_items >= $show_pl_req) ? "reqmeet" : "reqfail"), $count_pl/$count_items, $show_pl_req);
					printf("<td class=%s>%d / %d = %2.0f%%</td>", ((($count_regular==0) || ($count_cc_reg/$count_regular >= $show_cc_req_regular)) ? "reqmeet" : "reqfail"), $count_cc_reg, 100*$count_regular, $count_cc_reg/$count_regular);
					printf("<td class=%s>%d / %d = %2.0f%%</td>", ((($count_specialty==0) || ($count_cc_spec/$count_specialty >= $show_cc_req_specialty)) ? "reqmeet" : "reqfail"), $count_cc_spec, 100*$count_specialty, $count_cc_spec/$count_specialty);
					printf("<td class=%s>%2.0f%%</td>", "reqmeet", $count_yo/$count_items);
					printf("<td class=%s>%2.0f%% / %2.0f%%</td>", (($count_in/$count_items >= $show_in_req) ? "reqmeet" : "reqfail"), $count_in/$count_items, $show_in_req);
					printf("<td class=%s>%2.0f%% / %2.0f%%</td>", (($count_fe/$count_items >= $show_fe_req) ? "reqmeet" : "reqfail"), $count_fe/$count_items, $show_fe_req);
					printf("</tr>");
				}
				
				else {
					printf("<tr><td>%s</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td></tr>", mysqli_result_dep($result,$count,"start_time"));
				}
				$count++;
			}
			$total_items = ($total_items) ? $total_items / 100 : 1;
			$total_regular = $total_regular / 100;
			$total_specialty = $total_specialty / 100;
			printf("<tr><td>%s</td>", "Total");
			printf("<td class=%s>%2.0f%% / %2.0f%%</td>", (($total_pl/$total_items >= $show_pl_req) ? "reqmeet" : "reqfail"), $total_pl/$total_items, $show_pl_req);
		//	printf("<td class=%s>%2.0f%% / %2.0f%%</td>", (($total_cc/$total_items >= $show_cc_req) ? "reqmeet" : "reqfail"), $total_cc/$total_items, $show_cc_req);
		//  new cancon
			printf("<td class=%s>%d / %d = %2.0f%%</td>", ((($total_regular==0) || ($total_cc_reg/$total_regular >= $show_cc_req_regular)) ? "reqmeet" : "reqfail"), $total_cc_reg, 100*$total_regular, $total_cc_reg/$total_regular);
			printf("<td class=%s>%d / %d = %2.0f%%</td>", ((($total_specialty==0) || ($total_cc_spec/$total_specialty >= $show_cc_req_specialty)) ? "reqmeet" : "reqfail"), $total_cc_spec, 100*$total_specialty, $total_cc_spec/$total_specialty);
								
			printf("<td class=%s>%2.0f%%</td>", "reqmeet", $total_yo/$total_items);
			printf("<td class=%s>%2.0f%% / %2.0f%%</td>", (($total_in/$total_items >= $show_in_req) ? "reqmeet" : "reqfail"), $total_in/$total_items, $show_in_req);
			printf("<td class=%s>%2.0f%% / %2.0f%%</td>", (($total_fe/$total_items >= $show_fe_req) ? "reqmeet" : "reqfail"), $total_fe/$total_items, $show_fe_req);
			printf("</tr>");
			printf("</table><br>");
		}
	}
}else{
    header("Location: main.php");
}?>