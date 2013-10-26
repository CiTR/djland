<?php

session_start();
require("headers/security_header.php");
require("headers/function_header.php");
require("headers/menu_header.php");

printf("<html><head><meta name=ROBOTS content=\"NOINDEX, NOFOLLOW\">");
printf("<link rel=stylesheet href=citr.css type=text/css>");
printf("<title>CiTR 101.9</title></head><body>");

print_menu();

if(is_member("membership") && isset($_GET['action']) && $_GET['action'] == "search") {

	printf("<br><table align=center class=playsheet><tr><td>");
	printf("<center><h1>Search Results</h1></center>");

	//record range
	$record_limit = 50;
	$record_start = (isset($_GET['start']) && $_GET['start']) ? (int)$_GET['start'] : 0;
	$record_prev = ($record_start >= $record_limit) ? $record_start - $record_limit : -1;

	$record_next = $record_limit + $record_start;

	if(isset($_GET['searchdesc']) && $_GET['searchdesc']) {
		$search_term = fas($_GET['searchdesc']);
		$sresult = mysqli_query($db,"SELECT *,membership_status.name AS status FROM membership,membership_status WHERE MATCH(membership.lastname,membership.firstname,membership.address,membership.city,membership.postal,membership.cell,membership.home,membership.work,membership.email,membership.comments,membership.show,membership.int_other) AGAINST ('$search_term' IN BOOLEAN MODE) AND membership_status.id = membership.status_id ORDER BY membership.lastname LIMIT $record_start, $record_limit");


		$snum_rows = mysqli_num_rows($sresult);
		if(!$snum_rows) {
			$sresult = mysqli_query($db,"SELECT *,membership_status.name AS status FROM membership,membership_status WHERE (membership.lastname LIKE '%".$search_term."%' OR membership.firstname LIKE '%".$search_term."%' OR membership.address LIKE '%".$search_term."%' OR membership.city LIKE '%".$search_term."%' OR membership.postal LIKE '%".$search_term."%' OR membership.cell LIKE '%".$search_term."%' OR membership.home LIKE '%".$search_term."%' OR membership.work LIKE '%".$search_term."%' OR membership.email LIKE '%".$search_term."%' OR membership.comments LIKE '%".$search_term."%' OR membership.show LIKE '%".$search_term."%' OR membership.int_other  LIKE '%".$search_term."%') AND membership_status.id = membership.status_id ORDER BY membership.lastname LIMIT $record_start, $record_limit");
			$snum_rows = mysqli_num_rows($sresult);
		}
	}
	else if(isset($_GET['aslastname'])) {
		$current_year = date('Y', strtotime('-8 months'));
		$search_query = "";
		$search_query .= (isset($_GET['aslastname']) && $_GET['aslastname']) ? "membership.lastname LIKE '" . fas($_GET['aslastname']) . "%' AND " : "";
		$search_query .= (isset($_GET['asfirstname']) && $_GET['asfirstname']) ? "membership.firstname LIKE '" . fas($_GET['asfirstname']) . "%' AND " : "";
		
		$search_query .= (isset($_GET['asstatus_id']) && $_GET['asstatus_id']) ? "membership.status_id LIKE '" . fas($_GET['asstatus_id']) . "%' AND " : "";
		$search_query .= (isset($_GET['asgender']) && $_GET['asgender']) ? "membership.gender LIKE '" . fas($_GET['asgender']) . "%' AND " : "";
		$search_query .= (isset($_GET['asjoined']) && $_GET['asjoined']) ? "membership.joined LIKE '" . fas($_GET['asjoined']) . "%' AND " : "";
		$search_query .= (isset($_GET['asshow']) && $_GET['asshow']) ? "membership.show LIKE '" . fas($_GET['asshow']) . "%' AND " : "";
		$search_query .= (isset($_GET['asunpaid'])) ? "(membership.status_id = '2' OR membership.status_id = '4' OR membership.status_id = '5') AND last_paid < '$current_year' AND " : "";

		$search_query .= (isset($_GET['asdjs'])) ? "membership.djs != '0' AND " : "";
		$search_query .= (isset($_GET['asmobile'])) ? "membership.mobile != '0' AND " : "";
		$search_query .= (isset($_GET['asnewsdept'])) ? "membership.newsdept != '0' AND " : "";
		$search_query .= (isset($_GET['assportsdept'])) ? "membership.sportsdept != '0' AND " : "";
		$search_query .= (isset($_GET['asboard'])) ? "membership.board != '0' AND " : "";
		$search_query .= (isset($_GET['asdiscorder'])) ? "membership.discorder != '0' AND " : "";
		$search_query .= (isset($_GET['asexecutive'])) ? "membership.executive != '0' AND " : "";
		$search_query .= (isset($_GET['aswomen'])) ? "membership.women != '0' AND " : "";
		$search_query .= (isset($_GET['asfillin'])) ? "membership.fill_in != '0' AND " : "";
		$search_query .= (isset($_GET['asdept'])) ? "membership.dept != '0' AND " : "";
		$search_query .= (isset($_GET['asint_music'])) ? "membership.int_music != '0' AND " : "";
		$search_query .= (isset($_GET['asint_arts'])) ? "membership.int_arts != '0' AND " : "";
		$search_query .= (isset($_GET['asint_spoken'])) ? "membership.int_spoken != '0' AND " : "";
		$search_query .= (isset($_GET['asint_magazine'])) ? "membership.int_magazine != '0' AND " : "";
		$search_query .= (isset($_GET['asint_promotions'])) ? "membership.int_promotions != '0' AND " : "";
		$search_query .= (isset($_GET['asint_other']) && $_GET['asint_other']) ? "membership.int_other LIKE '%" . fas($_GET['asint_other']) . "%' AND " : "";

		$search_query .= (isset($_GET['asadded']) && $_GET['asadded']) ? "membership.added LIKE '" . fas($_GET['asadded']) . "%' AND " : "";
		$search_query .= (isset($_GET['asmodified']) && $_GET['asmodified']) ? "membership.modified LIKE '" . fas($_GET['asmodified']) . "%' AND " : "";
		$search_order = "ORDER BY ". fas($_GET['asorder']) . (isset($_GET['asdescending']) ? " DESC " : " ");

		$sresult = mysqli_query($db,"SELECT *,membership_status.name AS status FROM membership,membership_status WHERE ". $search_query ."membership_status.id = membership.status_id ".$search_order."LIMIT $record_start, $record_limit");
		$snum_rows = mysqli_num_rows($sresult);
	}
	else {
		$sresult = mysqli_query($db,"SELECT *,membership_status.name AS status FROM membership,membership_status WHERE membership_status.id = membership.status_id ORDER BY membership.lastname LIMIT $record_start, $record_limit");
		$snum_rows = mysqli_num_rows($sresult);
	}

	$bulkmail = "";
	$scount = 0;
	printf("<center><table border=1 width=90%%><tr><td><b>Name</b></td><td><b>Email</b></td><td nowrap><b>Home Phone</b></td></tr>");
	while($scount < $snum_rows) {
		$title = "Name: " . htmlspecialchars(mysqli_result($sresult,$scount,"firstname") . " " . mysqli_result($sresult,$scount,"lastname"));
		$title .= "\nStatus: " . htmlspecialchars(mysqli_result($sresult,$scount,"status"));
		$title .= "\nGender: " . htmlspecialchars(mysqli_result($sresult,$scount,"gender"));
		$title .= "\nJoined: " . htmlspecialchars(mysqli_result($sresult,$scount,"joined"));
		$title .= "\nShow: " . htmlspecialchars(mysqli_result($sresult,$scount,"show"));
		$title .= "\nProgrammers: " . (htmlspecialchars(mysqli_result($sresult,$scount,"djs") ? "Yes" : "No"));
		$title .= "\nMobile Sound DJ: " . (htmlspecialchars(mysqli_result($sresult,$scount,"mobile") ? "Yes" : "No"));
		$title .= "\nNews Dept: " . (htmlspecialchars(mysqli_result($sresult,$scount,"newsdept") ? "Yes" : "No"));
		$title .= "\nSports Dept: " . (htmlspecialchars(mysqli_result($sresult,$scount,"sportsdept") ? "Yes" : "No"));
		$title .= "\nBoard: " . (htmlspecialchars(mysqli_result($sresult,$scount,"board") ? "Yes" : "No"));
		$title .= "\nDiscorder: " . (htmlspecialchars(mysqli_result($sresult,$scount,"discorder") ? "Yes" : "No"));
		$title .= "\nExecutive: " . (htmlspecialchars(mysqli_result($sresult,$scount,"executive") ? "Yes" : "No"));
		$title .= "\nWomen: " . (htmlspecialchars(mysqli_result($sresult,$scount,"women") ? "Yes" : "No"));
		$title .= "\nFill In: " . (htmlspecialchars(mysqli_result($sresult,$scount,"fill_in") ? "Yes" : "No"));
		$title .= "\nMusic Dept: " . (htmlspecialchars(mysqli_result($sresult,$scount,"dept") ? "Yes" : "No"));
		$title .= "\nAdded: " . htmlspecialchars(mysqli_result($sresult,$scount,"added"));
		$title .= "\nModified: " . htmlspecialchars(mysqli_result($sresult,$scount,"modified"));

		$bulkmail .= mysqli_result($sresult,$scount,"email") ? (mysqli_result($sresult,$scount,"email") . "; ") : "";
?>
		<tr><td align=left><a href=<?=$_SERVER['SCRIPT_NAME']?>?action=edit&id=<?=mysqli_result($sresult,$scount,"id")?> title="<?=$title?>"><?=mysqli_result($sresult,$scount,"lastname")?>, <?=mysqli_result($sresult,$scount,"firstname")?></a></td>
		<td><a href="mailto:<?=mysqli_result($sresult,$scount,"email")?>"><?=mysqli_result($sresult,$scount,"email")?></a></td><td><?=mysqli_result($sresult,$scount,"home")?></td></tr>
<?php
		$scount++;
	}
	$prev_url = (($record_prev >= 0) ? ("<a href=\"" . $_SERVER['SCRIPT_NAME'] . "?" . ereg_replace( "(.*)&start=[0-9]*", "\\1" , $_SERVER['QUERY_STRING']) . "&start=" . $record_prev . "\"><< Prev</a> | ") : "");
	$next_url = (($scount >= $record_limit) ? ("<a href=\"" . $_SERVER['SCRIPT_NAME'] . "?" . ereg_replace( "(.*)&start=[0-9]*", "\\1" , $_SERVER['QUERY_STRING']) . "&start=" . $record_next . "\">Next >></a>") : "");
	$bulkmail = ((isset($bulkmail) && bulkmail) ? ("<a href=\"mailto:" . $bulkmail . "\">Bulk Email</a> | ") : "");
	printf("</table></center><center>%s %s %s</center>", $prev_url, $bulkmail, $next_url);
?>
	<br></td></tr></table>
<?php

}
else if(is_member("membership") && isset($_GET['action']) && ($_GET['action'] == 'add' || $_GET['action'] == 'edit' || $_GET['action'] == 'submit')) {

	printf("<br><table align=center class=playsheet><tr><td>");
	
	if(isset($_GET['action']) && $_GET['action'] == 'submit') {
		$current_date = date('Y-m-d');
		if(isset($_POST['id']) && $_POST['id']) {
			$submit_edit = true;
			$ed = fas($_POST['id']);
			mysqli_query($db,"DELETE FROM `membership_years` WHERE `membership_id`='$ed'");
		}
		else {
			mysqli_query($db,"INSERT INTO `membership` (id, added) VALUES (NULL, '$current_date')");
			$ed = mysqli_insert_id($db);
		}

		//Add paid years
		$last_paid = "0000";
		foreach(split("\r\n", fas($_POST['years_paid'])) as $var_key => $var_name) {
			if($var_name) {
				if($var_name > $last_paid) $last_paid = $var_name;
				mysqli_query($db,"INSERT INTO `membership_years` (id, membership_id, paid_year) VALUES (NULL, '$ed', '$var_name')");
			}
		}

		mysqli_query($db,"UPDATE `membership` SET `last_paid`='$last_paid', `lastname`='".fas($_POST['lastname'])."', `firstname`='".fas($_POST['firstname'])."', `gender`='".fas($_POST['gender'])."', `address`='".fas($_POST['address'])."', `city`='".fas($_POST['city'])."', `postal`='".fas($_POST['postal'])."', `cell`='".fas($_POST['cell'])."', `home`='".fas($_POST['home'])."', `work`='".fas($_POST['work'])."', `email`='".fas($_POST['email'])."', `status_id`='".fas($_POST['status_id'])."', `joined`='".fas($_POST['joined'])."', `comments`='".fas($_POST['comments'])."', `show`='".fas($_POST['show'])."', `djs`='".(isset($_POST['djs'])?1:0)."', `mobile`='".(isset($_POST['mobile'])?1:0)."', `newsdept`='".(isset($_POST['newsdept'])?1:0)."', `sportsdept`='".(isset($_POST['sportsdept'])?1:0)."', `board`='".(isset($_POST['board'])?1:0)."', `discorder`='".(isset($_POST['discorder'])?1:0)."', `executive`='".(isset($_POST['executive'])?1:0)."', `women`='".(isset($_POST['women'])?1:0)."', `fill_in`='".(isset($_POST['fill_in'])?1:0)."', `dept`='".(isset($_POST['dept'])?1:0)."', `int_music`='".(isset($_POST['int_music'])?1:0)."', `int_arts`='".(isset($_POST['int_arts'])?1:0)."', `int_spoken`='".(isset($_POST['int_spoken'])?1:0)."', `int_magazine`='".(isset($_POST['int_magazine'])?1:0)."', `int_promotions`='".(isset($_POST['int_promotions'])?1:0)."', `int_other`='".fas($_POST['int_other'])."', `modified`='$current_date' WHERE id='$ed'", $db);

		//Display just added entry...
		printf("<center><br><h1>Member %s</h1><hr width=90%%>", $submit_edit ? "Updated" : "Added");
		$ed = 0;
	}
	else if(isset($_GET['action']) && $_GET['action'] == 'edit') {
		$ed = fas($_GET['id']);
		$sresult = mysqli_query($db,"SELECT * FROM membership_years WHERE membership_id='$ed' ORDER BY paid_year");
		$snum_rows = mysqli_num_rows($sresult);
		$scount = 0;
		$years_paid = "";
		while($scount < $snum_rows) {
			$years_paid .= mysqli_result($sresult,$scount,"paid_year") . "\n"; 
			$scount++;
		}

		$result = mysqli_query($db,"SELECT *,membership_status.name AS status FROM membership, membership_status WHERE membership.id='$ed' AND membership_status.id = membership.status_id");
	}
	else {
		$ed = 0;
	}

	if(!isset($submit_edit)) {
?>		
		
		<center><h1><?= $ed ? "Update" : "Add New"; ?> Member</h1>
<?php
		printf("<FORM METHOD=\"POST\" ACTION=\"%s?action=submit\" name=\"the_form\">\n", $_SERVER['SCRIPT_NAME']);
		if($ed) {
			printf("<INPUT type=hidden name=id value=%s>", $ed);
		}
		$lastname = $ed ? mysqli_result($result,0,"lastname") : "";
		$firstname = $ed ? mysqli_result($result,0,"firstname") : "";
		$gender = $ed ? mysqli_result($result,0,"gender") : "";
		$address = $ed ? mysqli_result($result,0,"address") : "";
		$city = $ed ? mysqli_result($result,0,"city") : "";
		$postal = $ed ? mysqli_result($result,0,"postal") : "";
		$cell = $ed ? mysqli_result($result,0,"cell") : "";
		$home = $ed ? mysqli_result($result,0,"home") : "";
		$work = $ed ? mysqli_result($result,0,"work") : "";
		$email = $ed ? mysqli_result($result,0,"email") : "";
		$status = $ed ? mysqli_result($result,0,"status") : "";
		$status_id = $ed ? mysqli_result($result,0,"status_id") : "";
		$joined = $ed ? mysqli_result($result,0,"joined") : "";
		$comments = $ed ? mysqli_result($result,0,"comments") : "";
		$show = $ed ? mysqli_result($result,0,"show") : "";

		$djs = ($ed && mysqli_result($result,0,"djs")) ? " checked" : "";
		$mobile = ($ed && mysqli_result($result,0,"mobile")) ? " checked" : "";
		$newsdept = ($ed && mysqli_result($result,0,"newsdept")) ? " checked" : "";
		$sportsdept = ($ed && mysqli_result($result,0,"sportsdept")) ? " checked" : "";
		$board = ($ed && mysqli_result($result,0,"board")) ? " checked" : "";
		$discorder = ($ed && mysqli_result($result,0,"discorder")) ? " checked" : "";
		$executive = ($ed && mysqli_result($result,0,"executive")) ? " checked" : "";
		$women = ($ed && mysqli_result($result,0,"women")) ? " checked" : "";
		$fill_in = ($ed && mysqli_result($result,0,"fill_in")) ? " checked" : "";
		$dept = ($ed && mysqli_result($result,0,"dept")) ? " checked" : "";
		$int_music = ($ed && mysqli_result($result,0,"int_music")) ? " checked" : "";
		$int_arts = ($ed && mysqli_result($result,0,"int_arts")) ? " checked" : "";
		$int_spoken = ($ed && mysqli_result($result,0,"int_spoken")) ? " checked" : "";
		$int_magazine = ($ed && mysqli_result($result,0,"int_magazine")) ? " checked" : "";
		$int_promotions = ($ed && mysqli_result($result,0,"int_promotions")) ? " checked" : "";
		$int_other = $ed ? mysqli_result($result,0,"int_other") : "";
		$added = $ed ? mysqli_result($result,0,"added") : "";
		$modified = $ed ? mysqli_result($result,0,"modified") : "";

?>
		<table border=0>
		<tr align=right><td>First Name: </td><td align=left><INPUT SIZE=20 TYPE=text NAME=firstname value="<?=$firstname?>"></td></tr>
		<tr align=right><td>Last Name: </td><td align=left><INPUT SIZE=20 TYPE=text NAME=lastname value="<?=$lastname?>"></td></tr>
		<tr align=right><td>Membership Status: </td><td align=left><select name=status_id>
<?php 
		if($ed) {
			printf("<option value=%s>%s", $status_id, $status);
		}
		foreach($fmembership_status_name as $var_key => $var_name) {
			printf("<option value=%s>%s", $var_key, $var_name);
		}
?>
		</select></td></tr>
		<tr align=right><td>Gender: </td><td align=left><INPUT SIZE=1 TYPE=text NAME=gender value="<?=$gender?>"></td></tr>
		<tr align=right><td>Address: </td><td align=left><INPUT SIZE=40 TYPE=text NAME=address value="<?=$address?>"></td></tr>
		<tr align=right><td>City: </td><td align=left><INPUT SIZE=20 TYPE=text NAME=city value="<?=$city?>"></td></tr>
		<tr align=right><td>Postal Code: </td><td align=left><INPUT SIZE=20 TYPE=text NAME=postal value="<?=$postal?>"></td></tr>
		<tr align=right><td>Cell Phone: </td><td align=left><INPUT SIZE=20 TYPE=text NAME=cell value="<?=$cell?>"></td></tr>
		<tr align=right><td>Home Phone: </td><td align=left><INPUT SIZE=20 TYPE=text NAME=home value="<?=$home?>"></td></tr>
		<tr align=right><td>Work Phone: </td><td align=left><INPUT SIZE=20 TYPE=text NAME=work value="<?=$work?>"></td></tr>
		<tr align=right><td>Email: </td><td align=left><INPUT SIZE=20 TYPE=text NAME=email value="<?=$email?>"></td></tr>
		<tr align=right><td>Year Joined: </td><td align=left><INPUT SIZE=20 TYPE=text NAME=joined value="<?=$joined?>"></td></tr>
		<tr align=right><td valign=top>Years Paid: </td><td align=left><TEXTAREA COLS=8 ROWS=4 NAME=years_paid><?=$years_paid?></TEXTAREA></td></tr>
		<tr align=right><td>Show: </td><td align=left><INPUT SIZE=40 TYPE=text NAME=show value="<?=$show?>"></td></tr>
		<tr align=right><td valign=top>Comments: </td><td align=left><TEXTAREA COLS=40 ROWS=4 NAME=comments><?=$comments?></TEXTAREA></td></tr>

		<tr><td colspan=2><hr></td></tr>
		<tr align=right><td>Programmers: <input type=checkbox name="djs"<?=$djs?>></td><td>
		Mobile Sound DJ: <input type=checkbox name="mobile"<?=$mobile?>></td></tr>
		<tr align=right><td>News Dept: <input type=checkbox name="newsdept"<?=$newsdept?>></td><td>
		Sports Dept: <input type=checkbox name="sportsdept"<?=$sportsdept?>></td></tr>
		<tr align=right><td>Board: <input type=checkbox name="board"<?=$board?>></td><td>
		Discorder: <input type=checkbox name="discorder"<?=$discorder?>></td></tr>
		<tr align=right><td>Executive: <input type=checkbox name="executive"<?=$executive?>></td><td>
		Women: <input type=checkbox name="women"<?=$women?>></td></tr>
		<tr align=right><td>Fill In: <input type=checkbox name="fill_in"<?=$fill_in?>></td><td>
		Music Dept: <input type=checkbox name="dept"<?=$dept?>></td></tr>

		<tr><td colspan=2><hr><center><b>Interests</b></center></td></tr>
		<tr align=right><td>Music Dept: <input type=checkbox name="int_music"<?=$int_music?>></td><td>
		Arts Dept: <input type=checkbox name="int_arts"<?=$int_arts?>></td></tr>
		<tr align=right><td>Show Hosting: <input type=checkbox name="int_spoken"<?=$int_spoken?>></td><td>
		Discorder: <input type=checkbox name="int_magazine"<?=$int_magazine?>></td></tr>
		<tr align=right><td>Promotions+Outreach: <input type=checkbox name="int_promotions"<?=$int_promotions?>></td><td>
		</td></tr>
		<tr align=right><td valign=top>Other: </td><td align=left><TEXTAREA COLS=40 ROWS=4 NAME=int_other><?=$int_other?></TEXTAREA></td></tr>
		</table>
		<br><INPUT TYPE=submit VALUE="<?= $ed ? "Update" : "Add"; ?> Member">
		</FORM>
		</center></td></tr></table>
		<script language=javascript>
			document.the_form.firstname.focus();
		</script>
<?php
	}	
}
else if(is_member("membership")){
	printf("<br><table class=menu border=0 align=center><tr>");
	printf("<td class=menu><a href=membership.php?action=add>&nbsp;Add New Member&nbsp;</a></td></tr></table>");

	printf("<br><table align=center class=playsheet><tr><td><center><br><h1>Search Membership</h1></center>");

	printf("<CENTER><FORM METHOD=\"GET\" ACTION=\"%s\" name=\"the_form\">\n", $_SERVER['SCRIPT_NAME']);
	printf("<INPUT TYPE=hidden NAME=action VALUE=search>");
	printf("<INPUT TYPE=text NAME=searchdesc>");
	printf(" <INPUT TYPE=submit VALUE=\"Basic Search\">\n");
	printf("</FORM></CENTER>\n");

	printf("<hr width=90%%><CENTER><FORM METHOD=\"GET\" ACTION=\"%s\" name=\"the_form\">\n", $_SERVER['SCRIPT_NAME']);
	printf("<INPUT TYPE=hidden NAME=action VALUE=search>");
	printf("<INPUT TYPE=submit VALUE=\"All Members\">\n");
	printf("</FORM></CENTER>\n");

	printf("<hr width=90%%><CENTER><FORM METHOD=\"GET\" ACTION=\"%s\" name=\"the_form\">\n", $_SERVER['SCRIPT_NAME']);
?>
	<table border=0 align=center><tr><td align=left nowrap>
	<INPUT TYPE=hidden NAME=action VALUE=search>
	<table border=0><tr><td align=right nowrap>Last Name: <INPUT TYPE=text NAME=aslastname size=10>
	</td><td align=right nowrap>First Name: <INPUT TYPE=text NAME=asfirstname size=10></td></tr>

	<tr><td align=right>Status: <select name=asstatus_id><option value=0>All
<?php 
	foreach($fmembership_status_name as $var_key => $var_name) {
		printf("<option value=%s>%s", $var_key, $var_name);
	}
?>
	</select>
	</td><td align=right nowrap>Gender: <INPUT TYPE=text NAME=asgender size=2>
	</tr><tr><td align=right nowrap>Year Joined: <INPUT TYPE=text NAME=asjoined>
	</td><td align=right nowrap>Show: <INPUT TYPE=text NAME=asshow>
	</tr><tr><td align=right nowrap>Added: <INPUT TYPE=text NAME=asadded>
	</td><td align=right nowrap>Modified: <INPUT TYPE=text NAME=asmodified>

	</tr><tr><td align=right nowrap>Programmers: <input type=checkbox name="asdjs">
	<br>Mobile Sound DJ: <input type=checkbox name="asmobile">
	<br>News Dept: <input type=checkbox name="asnewsdept">
	<br>Sports Dept: <input type=checkbox name="assportsdept">
	<br>Board: <input type=checkbox name="asboard">
	<br>Discorder: <input type=checkbox name="asdiscorder">
	<br>Executive: <input type=checkbox name="asexecutive">
	<br>Women: <input type=checkbox name="aswomen">
	<br>Fill In: <input type=checkbox name="asfillin">
	<br>Music Dept: <input type=checkbox name="asdept">
	<br>Unpaid Members: <input type=checkbox name="asunpaid">
	</td><td valign=top align=right nowrap><h2>Interests</h2>
	Music Dept: <input type=checkbox name="asint_music">
	<br>Arts Dept: <input type=checkbox name="asint_arts">
	<br>Show Hosting: <input type=checkbox name="asint_spoken">
	<br>Discorder: <input type=checkbox name="asint_magazine">
	<br>Promotions+Outreach: <input type=checkbox name="asint_promotions">
	<br>Other:<INPUT TYPE=text NAME=asint_other size=20>
	<br><br>Order by: <select name=asorder>
	<option value=membership.lastname>Last Name
	<option value=membership.firstname>First Name
	</select>
	<br>Descending: <input type=checkbox name="asdescending">
	</td></tr></table>
	<center>
<?php
?>
	<input type=submit VALUE="Advanced Search">
	</center>
	</td></tr></table>
	</FORM></CENTER>
	</td></tr></table>
<?php

}

printf("</body></html>");

?>
