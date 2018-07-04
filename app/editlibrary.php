<?php
require_once("headers/security_header.php");
require_once("headers/menu_header.php");
?>

<html><head><meta name=ROBOTS content=\"NOINDEX, NOFOLLOW\">
<link rel=stylesheet href=css/style.css type=text/css>
<link rel=stylesheet href=css/src/library.css type=text/css>
<title>DJLAND | music library</title>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="js/library-js.js"></script>
<script type='text/javascript' src='js/membership/functions.js'></script>
<script type="text/javascript" src="js/membership/admin.js"></script>
<script type="text/javascript" src="js/test.js"></script>
<script type='text/javascript' src='./js/libraryedit/libraryedit.js'></script>

<?php
//<script src="js/jquery.form.js"></script>
//<link rel="stylesheet" href="https://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" />
//  <script src="https://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>


echo "</head><body class='wallpaper'>";

print_menu();

echo "<div class=library>";

// *** SEARCH MODE ***
// *** If action=search, get search terms from URL and query database ***
if(permission_level() >= $djland_permission_levels['volunteer']['level'] && isset($_GET['action']) && $_GET['action'] == "search") {

	printf("<br /><table><tr><td>");
	printf("<center><h1>Edit Library Entries</h1></center>");
	printf("<br /><hr width=800px><br />");

	?>
		<table border=0 align=center>
			<tr>
				<td align=left nowrap>
					<INPUT TYPE=hidden NAME=action VALUE=search>
					<table border=0>
						<tr>
							<td align=left colspan="6" style='padding-left:10px;padding-bottom:20px'>Please enter the changes you would like to make to the record(s):</td>
						</tr>
						<tr>
							<td align=left style='padding-left:10px'>Catalog #: </td><td align=left style='padding-left:5px'><INPUT TYPE=text id='ascatalog' size=10></td>
							<td align=left style='padding-left:10px'>Format: </td><td align=left style='padding-left:5px'>
								<select id='asformat'><option value=0>
									<?php
										foreach($fformat_name as $var_key => $var_name) {
											printf("<option value=%s>%s", $var_key, $var_name);
										}
									?>
								</select></td>
							<td align=left style='padding-left:10px'>Status: </td><td align=left style='padding-left:5px'><INPUT TYPE=text id='asstatus' size=2></td>
						</tr>
						<tr>
							<td align=left style='padding-left:10px'>Artist: </td><td align=left style='padding-left:5px'><INPUT TYPE=text id='asartist'></td>
							<td align=left style='padding-left:10px'>Title: </td><td align=left style='padding-left:5px'><INPUT TYPE=text id='astitle'></td>
							<td align=left style='padding-left:10px'>Label: </td><td align=left style='padding-left:5px'><INPUT TYPE=text id='aslabel'></td>
						</tr>
						<tr>
							<td align=left style='padding-left:10px'>Genre: </td>
							<td align=left style='padding-left:5px'>
								<script type="text/javascript">
									$(document).ready(function() {
									$(".js-example-basic-single").select2();
									});
								</script>
									<select class="js-example-basic-single vueselect" style="width:70%;" id='asgenre'><option value=0>
										<?php foreach($djland_primary_genres as $genre){
											printf("<option value=\"$genre\">$genre</option>");
										} ?>
									</select>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	<?php

	printf("<br /><hr width=800px>");
	printf("<center><br /><input type=submit VALUE='Apply Changes to Selected' onClick='saveChanges()'></center><br />");

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

		<table border=0 align=center>
			<tr>
				<td align=right nowrap>Select All <input type=checkbox onClick="toggle(this)"></td>
		</table>
		<br />
	<?php

	$record_limit = 100; // the number of search results to display per page
	$record_start = (isset($_GET['start']) && $_GET['start']) ? (int)$_GET['start'] : 0;
	$record_prev = ($record_start >= $record_limit) ? $record_start - $record_limit : -1;

	$record_next = $record_limit + $record_start;

	if(isset($_GET['searchdesc']) && $_GET['searchdesc']) {
		$search_term = fas($_GET['searchdesc']);
		$sresult = mysqli_query($db['link'],"SELECT *,types_format.name AS format FROM library,types_format WHERE MATCH(library.artist,library.title,library.label,library.genre) AGAINST ('$search_term' IN BOOLEAN MODE) AND types_format.id = library.format_id ORDER BY library.title LIMIT $record_start, $record_limit");
		$snum_rows = mysqli_num_rows($sresult);
		if(!$snum_rows) {
			$sresult = mysqli_query($db['link'],"SELECT *,types_format.name AS format FROM library,types_format WHERE (library.artist LIKE '%".$search_term."%' OR library.title LIKE '%".$search_term."%' OR library.label LIKE '%".$search_term."%' OR library.genre LIKE '%".$search_term."%') AND types_format.id = library.format_id ORDER BY library.title LIMIT $record_start, $record_limit");
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

		$sresult = mysqli_query($db['link'],"SELECT library.*, library.id, types_format.name AS format FROM library,types_format WHERE ". $search_query ."types_format.id = library.format_id ".$search_order."LIMIT $record_start, $record_limit");
		$snum_rows = mysqli_num_rows($sresult);
	}
	else {
		$sresult = mysqli_query($db['link'],"SELECT library.*, library.id, types_format.name AS format FROM library,types_format WHERE types_format.id = library.format_id ORDER BY library.id DESC LIMIT $record_start, $record_limit");
		$snum_rows = mysqli_num_rows($sresult);
	}
	$scount = 0;

	printf("<table border=0>");

	$dbarray = array();
	while($r = mysqli_fetch_array($sresult)) {
		$r['id'] = $r[0]; // weird fix
		$dbarray []=$r;
	}

	$scount = 0;

	foreach($dbarray as $i => $row) {

		$entry_catalog = $row["catalog"];
		$entry_format = $row["format"];
		$entry_status = $row["status"];
		$entry_artist = $row["artist"];
		$entry_title = $row["title"];
		$entry_label = $row["label"];
		$entry_genre = $row["genre"];
		$entry_cancon = $row["cancon"];
		$entry_femcon = $row["femcon"];
		$entry_local = $row["local"];
		$entry_playlist = $row["playlist"];
		$entry_compilation = $row["compilation"];
		$entry_digitized = $row["digitized"];
		$entry_id = $row["id"];

		$genreVals = "";
		foreach($djland_primary_genres as $var_genre) {
			$genreVals .= "*" . $var_genre;
		}

		printf("<tr id='albumEntry'><td onClick='editLine(this, \"$entry_id\", \"$entry_artist\", \"$entry_title\", \"$entry_label\", \"$entry_genre\", \"$entry_catalog\", \"$entry_format\", \"$entry_status\", \"$entry_cancon\", \"$entry_femcon\", \"$entry_local\", \"$entry_playlist\", \"$entry_compilation\", \"$entry_digitized\", \"$genreVals\")' class='editButton'>edit</td>");
		printf("<td><input type=checkbox name='entry' id='\"$entry_id\"'></td>");
		printf("<td align=right>[%s]</td><td>", $row["catalog"]);

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

		echo "<center>|".$row['format']."|</center></td><td><a href=".$_SERVER['SCRIPT_NAME'].
				"?action=view&id=".$row['id']." title='".$title."'>(".$row["artist"].") - ".$row["title"].
				"</a> ";

		if ($row['cancon']==1) echo '<img src="images/tags/cc.png" title="Canadian Content"  height="15"/>';
		if ($row['femcon']==1) echo '<img src="images/tags/fe.png" title="Female Content" height="15"/>';
		if ($row['local']==1) echo '<img src="images/tags/local.png" title="Local Content" height="15"/>';
		if ($row['digitized']==1) echo '<img src="images/tags/sam.png" title="Available to play in SAM" height="15"/>';

		echo "</td></tr>";
		$scount++;
	}

	$prev_url = (($record_prev >= 0) ? ("<a href=\"" . $_SERVER['SCRIPT_NAME'] . "?" . ereg_replace( "(.*)&start=[0-9]*", "\\1" , $_SERVER['QUERY_STRING']) . "&start=" . $record_prev . "\"><< Prev</a> | ") : "");
	$next_url = (($scount >= $record_limit) ? ("<a href=\"" . $_SERVER['SCRIPT_NAME'] . "?" . ereg_replace( "(.*)&start=[0-9]*", "\\1" , $_SERVER['QUERY_STRING']) . "&start=" . $record_next . "\">Next >></a>") : "");

	printf("</table><center>%s %s</center>", $prev_url, $next_url);

	?></td></tr></table><?php
}
// ** VIEW LIBRARY RECORD
else if(permission_level() >= $djland_permission_levels['volunteer']['level'] && isset($_GET['action']) && $_GET['action'] == "view") {

	if(isset($_GET['id']) && $_GET['id']) {
		$id = fas($_GET['id']);
	}
	else {
		$id = 0;
	}

	$sresult = mysqli_query($db['link'],"SELECT *,types_format.name AS format FROM library, types_format WHERE library.id='$id' AND types_format.id = library.format_id");

	printf("<br />");
	printf("<div><center><br /><h1>Library Record</h1><br /></center></div>");

	printf("<div style='width:1050px;margin:auto'>");
	printf("<div id='wrapper' style='width:500px;float:left'>");
		printf("<br /><h2>Album Information</h2><br />");
		printf("<hr width=80%%><br />");
			if(mysqli_num_rows($sresult)) {
					printf("<table align=center border=0>");
					printf("<tr><td align=left>Catalog: </td><td align=left> %s</td></tr>", mysqli_result_dep($sresult,0,"catalog"));
					printf("<tr><td align=left>Format: </td><td align=left> %s</td></tr>", mysqli_result_dep($sresult,0,"format"));
					printf("<tr><td align=left>Status: </td><td align=left> %s</td></tr>", mysqli_result_dep($sresult,0,"status"));
					printf("<tr><td align=left>Artist: </td><td align=left> %s</td></tr>", mysqli_result_dep($sresult,0,"artist"));
					printf("<tr><td align=left>Title: </td><td align=left> %s</td></tr>", mysqli_result_dep($sresult,0,"title"));
					printf("<tr><td align=left>Label: </td><td align=left> %s</td></tr>", mysqli_result_dep($sresult,0,"label"));
					printf("<tr><td align=left>Genre: </td><td align=left> %s</td></tr>", mysqli_result_dep($sresult,0,"genre"));
					printf("<tr><td align=left>Added: </td><td align=left> %s</td></tr>", mysqli_result_dep($sresult,0,"added"));
					printf("<tr><td align=left>Modified: <br><br></td><td align=left> %s<br><br></td></tr>", mysqli_result_dep($sresult,0,"modified"));
					printf("<tr><td align=left>Cancon: </td><td align=left> %s</td></tr>", mysqli_result_dep($sresult,0,"cancon") ? "Yes" : "No");
					printf("<tr><td align=left>Femcon: </td><td align=left> %s</td></tr>", mysqli_result_dep($sresult,0,"femcon") ? "Yes" : "No");
					printf("<tr><td align=left>Local: </td><td align=left> %s</td></tr>", mysqli_result_dep($sresult,0,"local") ? "Yes" : "No");
					printf("<tr><td align=left>Playlist: </td><td align=left> %s</td></tr>", mysqli_result_dep($sresult,0,"playlist") ? "Yes" : "No");
					printf("<tr><td align=left>Compilation: </td><td align=left> %s</td></tr>", mysqli_result_dep($sresult,0,"compilation") ? "Yes" : "No");
					printf("<tr><td align=left>in SAM: </td><td align=left> %s</td></tr>", mysqli_result_dep($sresult,0,"digitized") ? "Yes" : "No");
					printf("</table><br>");
			}
			else {
				printf("<br>No Such Record...<br><br>");
			}
	printf("</div>");
	printf("<div id='wrapper' style='width:500px;float:right'>");
		printf("<br /><h2>Preview Songs</h2><br />");
		printf("<hr width=80%%><br />");
			if(mysqli_num_rows($sresult)) {
					printf("<table align=center border=0>");
					printf("<tr><td align=right>Keep the Family Close:</td><td align=left style='padding:10px'><audio source='audio/mallard_duck_quacking.mp3' controls></audio></td></tr>");
					printf("<tr><td align=right>9:</td><td align=left style='padding:10px'><audio source='audio/mallard_duck_quacking.mp3' controls></audio></td></tr>");
					printf("<tr><td align=right>U with Me?:</td><td align=left style='padding:10px'><audio source='audio/mallard_duck_quacking.mp3' controls></audio></td></tr>");
					printf("<tr><td align=right>Feel No Ways:</td><td align=left style='padding:10px'><audio source='audio/mallard_duck_quacking.mp3' controls></audio></td></tr>");
					printf("<tr><td align=right>Hype:</td><td align=left style='padding:10px'><audio source='audio/mallard_duck_quacking.mp3' controls></audio></td></tr>");
					printf("<tr><td align=right>Weston Road Flows:</td><td align=left style='padding:10px'><audio source='audio/mallard_duck_quacking.mp3' controls></audio></td></tr>");
					printf("<tr><td align=right>Redemption:</td><td align=left style='padding:10px'><audio source='audio/mallard_duck_quacking.mp3' controls></audio></td></tr>");
					printf("<tr><td align=right>With You:</td><td align=left style='padding:10px'><audio source='audio/mallard_duck_quacking.mp3' controls></audio></td></tr>");
					printf("<tr><td align=right>Faithful (ft. Pimp C & dvsn):</td><td align=left style='padding:10px'><audio source='audio/mallard_duck_quacking.mp3' controls></audio></td></tr>");
					printf("<tr><td align=right>Still Here:</td><td align=left style='padding:10px'><audio source='audio/mallard_duck_quacking.mp3' controls></audio></td></tr>");
					printf("</table><br>");
			}
			else {
				printf("<br>No Such Record...<br><br>");
			}
	printf("</div>");
	printf("</div>");
}
// ** SEARCH LIBRARY
else if(permission_level() >= $djland_permission_levels['volunteer']['level']) {

	printf("<br><table><tr><td><center><br><h1>Search to Edit Library</h1><br></center>");

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
	<table border=0 align=center>
		<tr>
			<td align=left nowrap>
				<INPUT TYPE=hidden NAME=action VALUE=search>
				<table border=0>
					<tr>
						<td align=right nowrap>Catalog #: <INPUT TYPE=text NAME=ascatalog size=10></td>
						<td align=right nowrap>Format:
							<select name=asformat><option value=0>All
								<?php
									foreach($fformat_name as $var_key => $var_name) {
										printf("<option value=%s>%s", $var_key, $var_name);
									}
								?>
							</select>
							Status: <INPUT TYPE=text NAME=asstatus size=2>
						</td>
					</tr>
					<tr>
						<td align=right nowrap>Artist: <INPUT TYPE=text NAME=asartist></td>
						<td align=right nowrap>Title: <INPUT TYPE=text NAME=astitle></td>
					</tr>
					<tr>
						<td align=right nowrap>Label: <INPUT TYPE=text NAME=aslabel></td>
						<td align=right nowrap>Genre: <INPUT TYPE=text NAME=asgenre></td>
					</tr>
					<tr>
						<td align=right nowrap>Added: <INPUT TYPE=text NAME=asadded></td>
						<td align=right nowrap>Modified: <INPUT TYPE=text NAME=asmodified></td>
					</tr>
					<tr>
						<td align=right nowrap>Cancon: <input type=checkbox name="ascancon">
								Femcon: <input type=checkbox name="asfemcon">
								Local: <input type=checkbox name="aslocal">
						</td>
						<td align=right nowrap> Playlist: <input type=checkbox name="asplaylist">
								Compilation: <input type=checkbox name="ascompliation">
								in SAM: <input type=checkbox name="asdigitized">
						</td>
					</tr>
					<tr>
						<td align=right nowrap>Order by: <select name=asorder>
								<option value=library.artist>Artist
								<option value=library.catalog>Catalog #
								<option value=library.added>Date Added
								<option value=library.modified>Date Modified
								<option value=library.genre>Genre
								<option value=library.label>Label
								<option value=library.status>Status
								<option value=library.title>Title
							</select>
						</td>
						<td align=right nowrap>Descending: <input type=checkbox name="asdescending"></td>
					</tr>
				</table>
				<center>
  				<br />
					<input type=submit VALUE="Advanced Search">
				</center>
			</td>
		</tr>
	</table>
	</FORM></CENTER>
	<table>
		<tr><center>
			<br />
			<input type=submit VALUE="View Recent Edits" id="viewEdits" onClick="viewEdits()">
			</center><br>
		</tr>
		<tr>
			<table id="recentedits_table" name="search">
				<tbody name="recentEdits">
				</tbody>
			</table>
		</tr>
	</table>
	</td></tr></table>
<?php

}

echo "</div>";
printf("</body></html>");

?>
