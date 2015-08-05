<html ng-app='playsheet'>
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
	<script type='text/javascript' src="js/jquery-ui/external/jquery/jquery.js"></script>
	<script type='text/javascript' src="js/angular.js"></script>
	<script type='text/javascript' src="js/jquery-ui/jquery-ui.js"></script>
	<script type='text/javascript' src="js/angular/sortable.js"></script>
	<script type='text/javascript' src='js/bootstrap/bootstrap.js'></script>
	<script type='text/javascript' src='js/bootstrap/ui-bootstrap-tpls-0.12.0-withseconds.js'></script>
	<script type='text/javascript' src='js/playsheet.js'></script>
	<body ng-controller="PlaysheetController as playsheet">
		<playitem ng-model='playsheet.playitems' ng-repeat="playitem in playsheet.playitems track by $index"></playitem>
		<table>
			</th><th>#</th><th>Time</th><th>Name</th><th>Type</th><th>Played</th>
			<tr ad class='spaced' ng-model='playsheet.ads' ng-repeat="ad in playsheet.ads track by $index"></tr>
		</table>
	</body>
</html>