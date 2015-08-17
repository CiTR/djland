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

	<body class='wallpaper' ng-controller='openPlaysheetController as playsheet'>
		<script type='text/javascript' src="js/jquery-ui/external/jquery/jquery.js"></script>
		<script type='text/javascript' src="js/angular.js"></script>
		<script type='text/javascript' src='js/bootstrap/bootstrap.js'></script>
		<script type='text/javascript' src='js/open_playsheet/open_playsheet.js'></script>
		<script type='text/javascript' src='js/api.js'></script>
	    <?php print_menu(); ?>
		<table class='table-hover table'>
			<tr><th>Show<th>Host<th>Date</tr>
			<tbody >
				<tr ng-model='playsheet.playsheets' ng-repeat='item in playsheet.playsheets'>
					<td>{{item.show.name}}</td>
					<td><div ng-repeat='host in item.hosts'>{{host}}</div></td>
					<td>{{item.start_time}}</td>
				</tr>
			<tbody>
		</table>
		{{playsheet.playsheets}}
	</body>

</html>