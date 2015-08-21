<html ng-app='openPlaysheet'>
	<?php
	include_once("headers/session_header.php");
	require_once("headers/security_header.php");
	require_once("headers/function_header.php");
	require_once("headers/menu_header.php");
	?>
	<head>
		<link rel='stylesheet' href='js/bootstrap/bootstrap.min.css'></script>
		<link rel="stylesheet" href="css/style.css" type="text/css">
	</head>

	<body class='wallpaper' ng-controller='openPlaysheetController as open_playsheet'>
		<script type='text/javascript' src="js/jquery-ui/external/jquery/jquery.js"></script>
		<script type='text/javascript' src="js/angular.js"></script>
		<script type='text/javascript' src='js/bootstrap/bootstrap.js'></script>
		<script type='text/javascript' src='js/open_playsheet/open_playsheet.js'></script>
		<script type='text/javascript' src='js/api.js'></script>
	    <?php print_menu(); ?>
		<div id='wrapper'>
			<table class='table-hover table'>
				<tr><th>Show<th>Host<th>Date</tr>
				<tbody >
					<tr class='clickable-row' onclick=go('playsheet_angular.php?id={{item.id}}') ng-model='open_playsheet.playsheets' ng-repeat='item in open_playsheet.playsheets track by $index'>

							<td ng-click=go('playsheet.php?action=edit&id={{item.id}}')>{{item.show_info.name}}</td>
							<td>{{item.host_info.name}}</td>
							<td>{{item.start_time}}</td>

					</tr>
					
				<tbody>
			</table>
		</div>
	</body>
</html>