<?php
	require_once("headers/security_header.php");
	require_once("headers/menu_header.php");

	if( permission_level() < $djland_permission_levels['volunteer_leader']['level']){
		header("Location: main.php");
	}
?>
<html>
	<head>
		<meta name=ROBOTS content=\"NOINDEX, NOFOLLOW\">
		<meta charset="utf-8">
		<link rel=stylesheet href='css/style.css' type='text/css'>


		<title>DJLAND | Genre Manager</title>

		<script type='text/javascript' src='js/jquery-1.11.3.min.js'></script>
		<script type='text/javascript' src='js/jquery-ui-1.11.3.min.js'></script>
		<script type='text/javascript' src='js/genremanager/genremanager.js'/></script>
        <script type='text/javascript' src='js/constants.js'/></script>
        <script type="text/javascript" src="js/test.js"></script>

		<script src="js/jquery.form.js"></script>
		<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" />
		<script>
			$(function() {
			$( ".datepicker" ).datepicker({ dateFormat: "yy/mm/dd" });
			});
		</script>
	</head>
	<body class='wallpaper'>
		<?php
        print_menu();
        ?>
		<div style='width:1028px; margin-left:auto; margin-right:auto;'>
			<div id='wrapper' class='grey' style='width:51%;float:left'>
				<h3>Genres</h3>
				<div id="addgenre" class="right pad-bottom"><button>Add New</button></div>
				<br />
				<br />
				<div id="submisison_result" class="left overflow_auto height_cap" name="search">
					<table id="submission_table" name="search">
						<tbody>
							<tr id="headerrow" style="display: table-row;">
								<th>Name</th>
								<th>Created By</th>
								<th>Modified By</th>
								<th>Last Modified</th>
								<th><button id="delete_button">Delete</button></th>
							</tr>
							<?php foreach($djland_primary_genres as $genre){
								printf("<tr class=\"submission_row\">
								<td class=\"submission_row_element name\">$genre</td>
								<td class=\"submission_row_element email\">Digital Library</td>
								<td class=\"submission_row_element primary_phone\">Andy</td>
								<td class=\"submission_row_element submission_type\">Nov 14th, 2016</td>
								<td><input type=\"checkbox\" class=\"delete_submission\" id=\"delete_0\"><div class=\"check hidden\">❏</div></td>
								</tr>");
							}
							?>
						</tbody>
					</table>
				</div>
			</div>
			<div id='wrapper' class="grey" style='width:44%;float:right'>
				<h3>Subgenres for the Electronic Genre</h3>
				<div id="addsubgenre" class="right pad-bottom"><button>Add New</button></div>
				<br />
				<br />
				<div id="submisison_result" class="left overflow_auto height_cap" name="search">
					<table id="submission_table" name="search">
						<thead>
							<tr id="headerrow" style="display: table-row;">
								<th>Name</th>
								<th>Created By</th>
								<th>Modified By</th>
								<th>Last Modified</th>
								<th><button id="delete_button">Delete</button></th>
							</tr>
						</thead>
						<tbody>
							<?php /* foreach($djland_subgenres as $genre => $subgenre_array){
								if(is_array($subgenre_array)){
									foreach($subgenre_array as $subgenre){
										printf("<tr class=\"submission_row\">
										<td class=\"submission_row_element name\">$subgenre</td>
										<td class=\"submission_row_element email\">Digital Library</td>
										<td class=\"submission_row_element primary_phone\">Andy</td>
										<td class=\"submission_row_element submission_type\">Nov 14th, 2016</td>
										<td><input type=\"checkbox\" class=\"delete_submission\" id=\"delete_0\"><div class=\"check hidden\">❏</div></td>
										</tr>");
									}
								}
							} */
							foreach($djland_subgenres["Electronic"] as $subgenre){
										printf("<tr class=\"submission_row\">
										<td class=\"submission_row_element name\">$subgenre</td>
										<td class=\"submission_row_element email\">Digital Library</td>
										<td class=\"submission_row_element primary_phone\">Andy</td>
										<td class=\"submission_row_element submission_type\">Nov 14th, 2016</td>
										<td><input type=\"checkbox\" class=\"delete_submission\" id=\"delete_0\"><div class=\"check hidden\">❏</div></td>
										</tr>");
							}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</body>
</html>
