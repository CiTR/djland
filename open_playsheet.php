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
		<div class='text-center loading' ><img ng-show='open_playsheet.loading' class='rounded' width ='300' height='20' src='images/loading.gif'/></div>
		<div id='wrapper'>
			
			<div class='scroll' scrolly='!open_playsheet.loading ? open_playsheet.more():""'>
				<table class='table-hover table-condensed'>
					<tr><th>Show<th>Host<th>Date<th>Status</tr>
					<tbody when-scrolled='more()' >
						<tr class='clickable-row' onclick='go(this)' data-href='playsheet_angular.php?id={{item.id}}&socan={{item.socan}}' ng-model='open_playsheet.playsheets' ng-repeat='item in open_playsheet.playsheets track by $index'>
								<td ng-click=go('playsheet.php?id={{item.id}}')>{{item.show_info.name}}</td>
								<td class='host'>{{item.host}}</td>
								<td>{{item.start_time}}</td>
								<td>{{item.status != 2 ? "(Draft)" : "" }}</td>
						</tr>
					<tbody>
				</table>
			</div>
		</div>
	</body>
</html>