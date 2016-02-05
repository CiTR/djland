<?php
include_once("headers/session_header.php");
require_once("headers/security_header.php");
require_once("headers/function_header.php");
require_once("headers/menu_header.php");

printf("<html><head><meta name=ROBOTS content=\"NOINDEX, NOFOLLOW\">");
printf("<link rel=stylesheet href=css/style.css type=text/css>");
printf("<title>DJLAND | music library</title>");

echo '<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>';
echo '<script src="js/library-js.js"></script>';

//<script src="js/jquery.form.js"></script>
//<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" />
//  <script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>



echo "</head><body class='wallpaper'>";

print_menu();

// *** SEARCH MODE ***
// *** If action=search, get search terms from URL and query database ***
if(permission_level() >= $djland_permission_levels['member']['level'] && isset($_GET['action']) && $_GET['action'] == "search") {

	printf("<br><table class=center><tr><td>");
	printf("<center><h1>Search Results</h1></center>");

	$record_limit = 100; // the number of search results to display per page
	$record_start = (isset($_GET['start']) && $_GET['start']) ? (int)$_GET['start'] : 0;
	$record_prev = ($record_start >= $record_limit) ? $record_start - $record_limit : -1;

	$record_next = $record_limit + $record_start;

	if(isset($_GET['searchdesc']) && $_GET['searchdesc']) {
		$search_term = fas($_GET['searchdesc']);
		$sresult = mysqli_query($db,"SELECT *,types_format.name AS format FROM library,types_format WHERE MATCH(library.artist,library.title,library.label,library.genre) AGAINST ('$search_term' IN BOOLEAN MODE) AND types_format.id = library.format_id ORDER BY library.title LIMIT $record_start, $record_limit");
		$snum_rows = mysqli_num_rows($sresult);
		if(!$snum_rows) {
			$sresult = mysqli_query($db,"SELECT *,types_format.name AS format FROM library,types_format WHERE (library.artist LIKE '%".$search_term."%' OR library.title LIKE '%".$search_term."%' OR library.label LIKE '%".$search_term."%' OR library.genre LIKE '%".$search_term."%') AND types_format.id = library.format_id ORDER BY library.title LIMIT $record_start, $record_limit");
			$snum_rows = mysqli_num_rows($sresult);
		}
	}
	else if(isset($_GET['ascatalog'])) {
		$search_query = "";
		$search_query .= (isset($_GET['ascatalog']) && $_GET['ascatalog']) ? "library.catalog LIKE '" . fas($_GET['ascatalog']) . "%' AND " : "";
		$search_query .= (isset($_GET['asformat']) && $_GET['asformat']) ? "library.format_id='" . fas($_GET['asformat']) . "' AND " : "";
		$search_query .= (isset($_GET['asstatus']) && $_GET['asstatus']) ? "library.status LIKE '" . fas($_GET['asstatus']) . "%' AND " : "";
		$search_query .= (isset($_GET['asartist']) && $_GET['asartist']) ? "library.artist LIKE '" . fas($_GET['asartist']) . "%' AND " : "";
		$search_query .= (isset($_GET['astitle']) && $_GET['astitle']) ? "library.title LIKE '" . fas($_GET['astitle']) . "%' AND " : "";
		$search_query .= (isset($_GET['aslabel']) && $_GET['aslabel']) ? "library.label LIKE '" . fas($_GET['aslabel']) . "%' AND " : "";
		$search_query .= (isset($_GET['asgenre']) && $_GET['asgenre']) ? "library.genre LIKE '" . fas($_GET['asgenre']) . "%' AND " : "";
		$search_query .= (isset($_GET['asadded']) && $_GET['asadded']) ? "library.added LIKE '" . fas($_GET['asadded']) . "%' AND " : "";
		$search_query .= (isset($_GET['asmodified']) && $_GET['asmodified']) ? "library.modified LIKE '" . fas($_GET['asmodified']) . "%' AND " : "";

		$search_query .= (isset($_GET['ascancon'])) ? "library.cancon='1' AND " : "";
		$search_query .= (isset($_GET['asfemcon'])) ? "library.femcon='1' AND " : "";
		$search_query .= (isset($_GET['aslocal'])) ? "library.local='1' AND " : "";
		$search_query .= (isset($_GET['ascompilation'])) ? "library.compilation='1' AND " : "";
		$search_query .= (isset($_GET['asdigitized'])) ? "library.digitized='1' AND " : "";
		$search_query .= (isset($_GET['asplaylist'])) ? "library.playlist='1' AND added >'". date('Y-m-d', strtotime("-6 months")) ."' AND " : "";

		$search_order = "ORDER BY ". fas($_GET['asorder']) . (isset($_GET['asdescending']) ? " DESC " : " ");

		$sresult = mysqli_query($db,"SELECT library.*, library.id, types_format.name AS format FROM library,types_format WHERE ". $search_query ."types_format.id = library.format_id ".$search_order."LIMIT $record_start, $record_limit");
		$snum_rows = mysqli_num_rows($sresult);
	}
	else {
		$sresult = mysqli_query($db,"SELECT library.*, library.id, types_format.name AS format FROM library,types_format WHERE types_format.id = library.format_id ORDER BY library.id DESC LIMIT $record_start, $record_limit");
		$snum_rows = mysqli_num_rows($sresult);
	}
	$scount = 0;

	printf("<table border=0>");
	if(permission_level() >= $djland_permission_levels['volunteer']['level'] && isset($_GET['bulkedit'])) {

		?>
		<!--JAVASCRIPT HELPER CALLS-->
		<script language=JavaScript>
			<!--
			var isConfirmed = false;
			//window.onbeforeunload = ConfirmExit;
			function ExitOkay(setTo) {
				isConfirmed = setTo;
				return setTo;
			}
			function ConfirmExit() {
				if (!isConfirmed) {
					return "CHANGES WILL BE LOST!";
				}
			}
			function EnterPressed(event) {
				if (document.all) {
					if (event.keyCode == 13) {  // handles IE browsers...
						event.keyCode = 9;
					}
				}
				else if (document.getElementById) { // handles NS and Mozilla browsers...
					if (event.which == 13) {
						event.keyCode = 9;
					}
				}
				else if (document.layers) { // handles NS ver. 4+ browsers...
					if (event.which == 13) {
						event.keyCode = 9;
					}
				}
			}
			-->
		</script>
<?php
// *** EDIT MODE ***
		printf("<FORM METHOD=\"POST\" ONSUBMIT=\"return ExitOkay(confirm('Are you sure you want to make these changes?'))\" ACTION=\"%s?action=bulkedit\" name=\"the_form\">\n", $_SERVER['SCRIPT_NAME']);
		?>
		<tr>
			<td align=right>New Catalog #</td>
			<td></td>
		</tr><?php
	}
	$dbarray = array();
	while($r = mysqli_fetch_array($sresult)){
		$r['id'] = $r[0]; // weird fix
		$dbarray []=$r;
//		print_r($r);
//		echo "<hr>";
	}

	$scount = 0;

foreach($dbarray as $i => $row){

		if(permission_level() >= $djland_permission_levels['volunteer']['level']) {
//			printf("<tr><td align=right>[<a href=%s?action=edit&id=%s title=\"Click to Edit\">%s</a>]%s</td><td>", $_SERVER['SCRIPT_NAME'], $row["id"], $row["catalog"] ? $row["catalog"] : "N/A",  isset($_GET['bulkedit']) ? "<input type=hidden value=\"".$row["id"]."\" name=id".$scount."><input type=hidden value=\"".$row["catalog"]."\" name=oldcat".$scount."><input type=text size=5 name=newcat".$scount." tabindex=".($scount+1)." onkeydown=\"EnterPressed(event)\">" : "");

		echo "<tr><td align=right>[<a href=".$_SERVER['SCRIPT_NAME'].
			"?action=edit&id=".$row["id"].
			" title='Click to Edit'>";

		if ($row["catalog"])
			echo $row["catalog"];
		else echo "N/A";

		echo "</a>] ";

		if (isset($_GET['bulkedit']))
			echo "<input type=hidden value='".$row["id"].
					"' name=id".$scount."><input type=hidden value='".
					$row["catalog"]."' name=oldcat".$scount.
					"><input type=text size=5 name=newcat".$scount.
					" tabindex=".($scount+1)." onkeydown='EnterPressed(event)'>";
		else echo "";

		echo "</td><td>";



			}
		else {
			printf("<tr><td align=right>[%s]</td><td>", $row["catalog"]);
		}
		$title = "Catalog: " . htmlspecialchars($row["catalog"]);
		$title .= "\nFormat: " . htmlspecialchars($row["format"]);
		$title .= "\nStatus: " . htmlspecialchars($row["status"]);
		$title .= "\nArtist: " . htmlspecialchars($row["artist"]);
		$title .= "\nTitle: " . htmlspecialchars($row["title"]);
		$title .= "\nLabel: " . htmlspecialchars($row["label"]);
		$title .= "\nGenre: " . htmlspecialchars($row["genre"]);
		$title .= "\nAdded: " . htmlspecialchars($row["added"]);
		$title .= "\nModified: " . htmlspecialchars($row["modified"]);
		$title .= "\nCancon: " . (htmlspecialchars($row["cancon"] ? "Yes" : "No"));
		$title .= "\nFemcon: " . (htmlspecialchars($row["femcon"] ? "Yes" : "No"));
		$title .= "\nLocal: " . (htmlspecialchars($row["local"] ? "Yes" : "No"));
		$title .= "\nPlaylist: " . (htmlspecialchars($row["playlist"] ? "Yes" : "No"));
		$title .= "\nCompilation: " . (htmlspecialchars($row["compilation"] ? "Yes" : "No"));
		$title .= "\nIn SAM: " . (htmlspecialchars($row["digitized"] ? "Yes" : "No"));


	//	printf("<center>|%s|</center></td><td><a href=%s?action=view&id=%s title=\"%s\">(%s) - %s</a></td></tr>", $row["format"], $_SERVER['SCRIPT_NAME'], $row["id"], $title, $row["artist"], $row["title"]);
		echo "<center>|".$row['format']."|</center></td><td><a href=".$_SERVER['SCRIPT_NAME'].
				"?action=view&id=".$row['id']." title='".$title."'>(".$row["artist"].") - ".$row["title"].
				"</a> ";
	if ($row['cancon']==1) echo '<img src="images/tags/cc.png" title="Canadian Content"  height="15"/>';
	if ($row['femcon']==1) echo '<img src="images/tags/fe.png" title="Female Content" height="15"/>';
	if ($row['local']==1) echo '<img src="images/tags/local.png" title="Local Content" height="15"/>';
	if ($row['digitized']==1) echo '<img src="images/tags/sam.png" title="Available to play in SAM" height="15"/>';
		if (isset($_GET['bulkedit'])){
		echo "<a class='lib-delete' id=".$row['id'].">delete</a>";
		}
		echo "</td></tr>";




		$scount++;






	}



	if(permission_level() >= $djland_permission_levels['volunteer']['level'] && isset($_GET['bulkedit'])) {
		?><tr><td align=right><input type=submit VALUE="Update" tabindex=32767></td><td></td></tr><?php

	}
	$prev_url = (($record_prev >= 0) ? ("<a href=\"" . $_SERVER['SCRIPT_NAME'] . "?" . ereg_replace( "(.*)&start=[0-9]*", "\\1" , $_SERVER['QUERY_STRING']) . "&start=" . $record_prev . "\"><< Prev</a> | ") : "");
	$next_url = (($scount >= $record_limit) ? ("<a href=\"" . $_SERVER['SCRIPT_NAME'] . "?" . ereg_replace( "(.*)&start=[0-9]*", "\\1" , $_SERVER['QUERY_STRING']) . "&start=" . $record_next . "\">Next >></a>") : "");
	printf("</table><center>%s %s</center>", $prev_url, $next_url);
	?></td></tr></table><?php
	if(is_member("editlibrary") &&isset($_GET['bulkedit'])) {
		printf("</FORM>");
	}

}
else if(permission_level() >= $djland_permission_levels['volunteer']['level'] && isset($_GET['action']) && $_GET['action'] == "bulkedit") {
	printf("<br><table align=center class=center><tr><td>");
	printf("<center><br><h1>Bulk Catalog Edit</h1></center>");
	$scount = 0;
	while(isset($_POST['id'.$scount]) && isset($_POST['newcat'.$scount])) {
		if($_POST['newcat'.$scount]) {
			mysqli_query($db,"UPDATE `library` SET catalog='".fas($_POST['newcat'.$scount])."' WHERE id='".fas($_POST['id'.$scount])."'");
			echo "<br>Catalog: " . $_POST['oldcat'.$scount] . " changed to: " . $_POST['newcat'.$scount];
		}
		$scount++;
	}
	?></td></tr></table><?php
}
else if(permission_level() >= $djland_permission_levels['member']['level'] && isset($_GET['action']) && $_GET['action'] == "view") {

	if(isset($_GET['id']) && $_GET['id']) {
		$id = fas($_GET['id']);
	}
	else {
		$id = 0;
	}

	$sresult = mysqli_query($db,"SELECT *,types_format.name AS format FROM library, types_format WHERE library.id='$id' AND types_format.id = library.format_id");

	printf("<br><table class=center><tr><td>");
	printf("<center><br><h1>Library Record</h1></center>");
	if(mysqli_num_rows($sresult)) {
			printf("<table align=center border=0>");
			printf("<tr><td align=right>Catalog:</td><td align=left> %s</td></tr>", mysqli_result_dep($sresult,0,"catalog"));
			printf("<tr><td align=right>Format:</td><td align=left> %s</td></tr>", mysqli_result_dep($sresult,0,"format"));
			printf("<tr><td align=right>Status:</td><td align=left> %s</td></tr>", mysqli_result_dep($sresult,0,"status"));
			printf("<tr><td align=right>Artist:</td><td align=left> %s</td></tr>", mysqli_result_dep($sresult,0,"artist"));
			printf("<tr><td align=right>Title:</td><td align=left> %s</td></tr>", mysqli_result_dep($sresult,0,"title"));
			printf("<tr><td align=right>Label:</td><td align=left> %s</td></tr>", mysqli_result_dep($sresult,0,"label"));
			printf("<tr><td align=right>Genre:</td><td align=left> %s</td></tr>", mysqli_result_dep($sresult,0,"genre"));
			printf("<tr><td align=right>Added:</td><td align=left> %s</td></tr>", mysqli_result_dep($sresult,0,"added"));
			printf("<tr><td align=right>Modified:<br><br></td><td align=left> %s<br><br></td></tr>", mysqli_result_dep($sresult,0,"modified"));
			printf("<tr align=right><td>Cancon: %s</td>", mysqli_result_dep($sresult,0,"cancon") ? "Yes" : "No");
			printf("<td>Femcon: %s</td></tr>", mysqli_result_dep($sresult,0,"femcon") ? "Yes" : "No");
			printf("<tr><td>Local: %s</td>", mysqli_result_dep($sresult,0,"local") ? "Yes" : "No");
			printf("<td>Playlist: %s</td>", mysqli_result_dep($sresult,0,"playlist") ? "Yes" : "No");
			printf("<tr><td>Compilation: %s</td></tr>", mysqli_result_dep($sresult,0,"compilation") ? "Yes" : "No");
			printf("<tr><td>in SAM: %s</td></tr>", mysqli_result_dep($sresult,0,"digitized") ? "Yes" : "No");
			printf("</table><br>");
	}
	else {
		printf("<br>No Such Record...<br><br>");
	}
	?></td></tr></table><?php

}
else if(permission_level() >= $djland_permission_levels['volunteer']['level'] && isset($_GET['action']) && ($_GET['action'] == 'add' || $_GET['action'] == 'edit' || $_GET['action'] == 'submit')) {

	printf("<br><table class=center><tr><td>");

	if(isset($_GET['action']) && $_GET['action'] == 'submit') {
		$current_date = date('Y-m-d');
		if(isset($_POST['id']) && $_POST['id']) {
			$submit_edit = true;
			$ed = fas($_POST['id']);
		}
		else {
			mysqli_query($db,"INSERT INTO `library` (id, added) VALUES (NULL, '$current_date')");
			$ed = mysqli_insert_id($db);
		}
		$sresult = mysqli_query($db, "UPDATE `library` SET format_id='".fas($_POST['format'])."', catalog='".fas($_POST['catalog'])."', cancon='".(isset($_POST['cancon'])?1:0)."', femcon='".(isset($_POST['femcon'])?1:0)."', local='".(isset($_POST['local'])?1:0)."', playlist='".(isset($_POST['playlist'])?1:0)."', compilation='".(isset($_POST['compilation'])?1:0)."', digitized='".(isset($_POST['digitized'])?1:0)."', status='".fas($_POST['status'])."', artist='".fas($_POST['artist'])."', title='".fas($_POST['title'])."', label='".fas($_POST['label'])."', genre='".fas($_POST['genre'])."', modified='$current_date' WHERE id='$ed'");



		//Display just added entry...
//		printf("<center><br><h1>Record %s</h1>", $submit_edit ? "Updated" : "Added");
		printf("<center>");

		$sresult = mysqli_query($db,"SELECT *,types_format.name AS format FROM library, types_format WHERE library.id='".$ed."' AND types_format.id = library.format_id");
		if(mysqli_num_rows($sresult)) {


			$thisCDid = $ed;

			printf("<table border=0 width=50%%>");
			printf("<tr><td align=right>Catalog:</td><td align=left> %s</td></tr>", mysqli_result_dep($sresult,0,"catalog"));
			printf("<tr><td align=right>Format:</td><td align=left> %s</td></tr>", mysqli_result_dep($sresult,0,"format"));
			printf("<tr><td align=right>Status:</td><td align=left> %s</td></tr>", mysqli_result_dep($sresult,0,"status"));
			printf("<tr><td align=right>Artist:</td><td align=left> %s</td></tr>", mysqli_result_dep($sresult,0,"artist"));
			printf("<tr><td align=right>Title:</td><td align=left> %s</td></tr>", mysqli_result_dep($sresult,0,"title"));
			printf("<tr><td align=right>Label:</td><td align=left> %s</td></tr>", mysqli_result_dep($sresult,0,"label"));
			printf("<tr><td align=right>Genre:</td><td align=left> %s</td></tr>", mysqli_result_dep($sresult,0,"genre"));

//			printf("<tr><td align=right>in SAM:</td><td align=left> %s</td></tr>", mysqli_result_dep($sresult,0,"digitized"));

			//printf("<tr><td align=right>Added:</td><td align=left> %s</td></tr>", mysqli_result_dep($sresult,0,"added"));
			//printf("<tr><td align=right>Modified:</td><td align=left> %s</td></tr>", mysqli_result_dep($sresult,0,"modified"));
			//printf("<tr align=right><td>Cancon: %s</td><td>", mysqli_result_dep($sresult,0,"cancon") ? "Yes" : "No");
			//printf("Femcon: %s</td></tr>", mysqli_result_dep($sresult,0,"femcon") ? "Yes" : "No");
			//printf("<tr align=right><td>Playlist: %s</td><td>", mysqli_result_dep($sresult,0,"playlist") ? "Yes" : "No");
			//printf("Compilation: %s</td></tr>", mysqli_result_dep($sresult,0,"compilation") ? "Yes" : "No");
			printf("</table>");
			if(is_member("editlibrary")) {
//				printf("[<a href=%s?action=edit&id=%s>edit</a>] ", $_SERVER['SCRIPT_NAME'], mysqli_result_dep($sresult,0,"id"));
				printf("[<a href=%s?action=edit&id=%s>edit</a>] ", $_SERVER['SCRIPT_NAME'], $thisCDid);

			}
		}
		else {
			printf("<br>No Such Record...");
		}

		printf("</center><hr width=50%%>");

		$ed = 0;
	}
	else if(isset($_GET['action']) && $_GET['action'] == 'edit') {
		$ed = fas($_GET['id']);
		$result = mysqli_query($db,"SELECT *,types_format.name AS format FROM library, types_format WHERE library.id='".fas($_GET['id'])."' AND types_format.id = library.format_id");
	}
	else {
		$ed = 0;
	}

	if(!isset($submit_edit)) {
?>

		<center><h1><?= $ed ? "Update" : "Add New"; ?> Record</h1>
<?php
	// *** ADD NEW MODE ***
		printf("<FORM METHOD=\"POST\" ACTION=\"%s?action=submit\" name=\"the_form\">\n", $_SERVER['SCRIPT_NAME']);
		if($ed) {
			printf("<INPUT type=hidden name=id value=%s>", $ed);
		}
		$catalog = $ed ? mysqli_result_dep($result,0,"catalog") : "";
		$format = $ed ? mysqli_result_dep($result,0,"format") : "";
		$format_id = $ed ? mysqli_result_dep($result,0,"format_id") : "";
		$cancon = ($ed && mysqli_result_dep($result,0,"cancon")) ? " checked" : "";
		$femcon = ($ed && mysqli_result_dep($result,0,"femcon")) ? " checked" : "";
		$local = ($ed && mysqli_result_dep($result,0,"local")) ? " checked" : "";
		$playlist = ($ed && mysqli_result_dep($result,0,"playlist")) ? " checked" : "";
		$compilation = ($ed && mysqli_result_dep($result,0,"compilation")) ? " checked" : "";
		$digitized = ($ed && mysqli_result_dep($result,0,"digitized")) ? " checked" : "";
		$status =  $ed ? mysqli_result_dep($result,0,"status") : "";
		$artist =  $ed ? mysqli_result_dep($result,0,"artist") : "";
		$title =  $ed ? mysqli_result_dep($result,0,"title") : "";
		$label =  $ed ? mysqli_result_dep($result,0,"label") : "";
		$genre =  $ed ? mysqli_result_dep($result,0,"genre") : "";
?>
		<table border=0>
		<tr align=right><td>Catalog #: </td><td align=left><INPUT SIZE=10 TYPE=text NAME=catalog value="<?=$catalog?>"></td></tr>
		<tr align=right><td>Format : </td><td align=left><select name=format>
<?php
		if($ed) {
			printf("<option value=%s>%s", $format_id, $format);
		}
		foreach($fformat_name as $var_key => $var_name) {
			printf("<option value=%s>%s", $var_key, $var_name);
		}
?>
		</select></td></tr>

		<tr><td>Status: </td><td><INPUT SIZE=4 TYPE=text NAME=status value="<?=$status?>"></td></tr>
		<tr><td>Artist: </td><td><INPUT SIZE=40 TYPE=text NAME=artist value="<?=$artist?>"></td></tr>
		<tr><td>Title: </td><td ><INPUT SIZE=40 TYPE=text NAME=title value="<?=$title?>"></td></tr>
		<tr><td>Label: </td><td ><INPUT SIZE=40 TYPE=text NAME=label value="<?=$label?>"></td></tr>
		<tr><td>Genre: </td><td ><INPUT SIZE=40 TYPE=text NAME=genre value="<?=$genre?>"></td></tr>
		<tr>
			<td>Cancon: <input type=checkbox name="cancon"<?=$cancon?>></td>
			<td>Femcon: <input type=checkbox name="femcon"<?=$femcon?>>
				Local: <input type=checkbox name="local"<?=$local?>>
		</td>

		</tr>
		<tr>
			<td>Playlist: <input type=checkbox name="playlist"<?=$playlist?>></td>
			<td>Compilation: <input type=checkbox name="compilation"<?=$compilation?>>
			in SAM: <input type=checkbox name="digitized"<?=$digitized?>>
			</td>
		</tr>
		</table>
		<br>
		<?php
				if ($ed){
				echo '<INPUT TYPE=submit VALUE="Update Record">';
				echo "<br/><br/><br/><a class='lib-delete' id=".$ed.">Delete Record</a>";
				} else {
				echo '<INPUT TYPE=submit VALUE="Add Record">';
				}
		?>
		</FORM>
		</center></td></tr></table>
		<script language=javascript>
			document.the_form.catalog.focus();
		</script>
<?php
	}
}
	// *** VIEW MODE ***
else if(permission_level() >= $djland_permission_levels['member']['level']){
	printf("<br><table align=center class=center><tr><td><center><br><h1>Search Library</h1></center>");

	printf("<CENTER><FORM METHOD=\"GET\" ACTION=\"%s\" name=\"the_form\">\n", $_SERVER['SCRIPT_NAME']);
	printf("<INPUT TYPE=hidden NAME=action VALUE=search>");
	printf("<INPUT TYPE=text NAME=searchdesc>");
	printf(" <INPUT TYPE=submit VALUE=\"Basic Search\">\n");
	printf("</FORM></CENTER>\n");

	printf("<hr width=50%%><CENTER><FORM METHOD=\"GET\" ACTION=\"%s\" name=\"the_form\">\n", $_SERVER['SCRIPT_NAME']);
	printf("<INPUT TYPE=hidden NAME=action VALUE=search>");
	printf("<INPUT TYPE=submit VALUE=\"Recent Entries\">\n");
	printf("</FORM></CENTER>\n");

	printf("<hr width=50%%><CENTER><FORM METHOD=\"GET\" ACTION=\"%s\" name=\"the_form\">\n", $_SERVER['SCRIPT_NAME']);
?>
	<table border=0 align=center><tr><td align=left nowrap>
	<INPUT TYPE=hidden NAME=action VALUE=search>
	<table border=0><tr><td align=right nowrap>
	Catalog #: <INPUT TYPE=text NAME=ascatalog size=10>
	</td><td align=right nowrap>Format: <select name=asformat><option value=0>All
<?php
	foreach($fformat_name as $var_key => $var_name) {
		printf("<option value=%s>%s", $var_key, $var_name);
	}
?>
	</select>
	Status: <INPUT TYPE=text NAME=asstatus size=2>
	</tr><tr><td align=right nowrap>Artist: <INPUT TYPE=text NAME=asartist>
	</td><td align=right nowrap>Title: <INPUT TYPE=text NAME=astitle>
	</tr><tr><td align=right nowrap>Label: <INPUT TYPE=text NAME=aslabel>
	</td><td align=right nowrap>Genre: <INPUT TYPE=text NAME=asgenre>
	</tr><tr><td align=right nowrap>Added: <INPUT TYPE=text NAME=asadded>
	</td><td align=right nowrap>Modified: <INPUT TYPE=text NAME=asmodified>
	</tr><tr><td align=right nowrap>Cancon: <input type=checkbox name="ascancon">
	Femcon: <input type=checkbox name="asfemcon">
	Local: <input type=checkbox name="aslocal">
	</td><td align=right nowrap> Playlist: <input type=checkbox name="asplaylist">
	Compilation: <input type=checkbox name="ascompliation">
	in SAM: <input type=checkbox name="asdigitized">
	</tr><tr><td align=right nowrap>Order by: <select name=asorder>
	<option value=library.artist>Artist
	<option value=library.catalog>Catalog #
	<option value=library.added>Date Added
	<option value=library.modified>Date Modified
	<option value=library.genre>Genre
	<option value=library.label>Label
	<option value=library.status>Status
	<option value=library.title>Title
	</select>

	</td><td align=right nowrap>Descending: <input type=checkbox name="asdescending">

	</td></tr></table>
	<center>
<?php
	if(permission_level() >= $djland_permission_levels['volunteer']['level']) {
		echo "Edit<input type=checkbox name=bulkedit>";

	}
?>
	<input type=submit VALUE="Advanced Search">
	<?php
	if(permission_level() >= $djland_permission_levels['volunteer']['level']) {
		echo "<br/><br/><br/><br/><a href='dupenuker.php'>duplicate finder </a>";
	}
	?>
	</center>
	</td></tr></table>
	</FORM></CENTER>
	</td></tr></table>
<?php

}

printf("</body></html>");

?>
