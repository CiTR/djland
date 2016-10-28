<html ng-app='openPlaysheet'>
	<?php
	require_once("headers/security_header.php");
	require_once("headers/menu_header.php");
	?>
	<head>
		<link rel='stylesheet' href='css/bootstrap.min.css'></script>
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
		<div class='text-center'>Filter by show name
			<select ng-model='open_playsheet.show_select'>
				<option value='all'>All Shows</option>
				<option ng-repeat = 'show in open_playsheet.shows | orderBy:show.name' value='{{show.id}}'>{{show.name}}</option>
			</select>
			<button ng-click='open_playsheet.more(true)'>Load</button>
		</div>
		<div ng-hide="open_playsheet.playsheets || open_playsheet.loading == true" class='text-center'>You have no shows assigned to this account. Please ask a staff member to assign you to your show</div>
		<div ng-show="open_playsheet.playsheets" id='wrapper'>
			<div class='scroll<?php if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'firefox') > 0 ){echo " firefox'"; }?>' scrolly='!open_playsheet.loading ? open_playsheet.more():""'>

				<table class='open_playsheet table-hover table-condensed'>
					<tr><th>Show<th>Host<th>Date<th>Status<th ng-if='open_playsheet.staff'>Edit Date<th ng-if='open_playsheet.staff'></tr>
					<tbody when-scrolled='more()' >
						<tr ng-model='open_playsheet.playsheets' ng-repeat='item in open_playsheet.playsheets track by $index'>
								<td onclick='go(this)' data-href='playsheet_angular.php?id={{item.id}}&socan={{item.socan}}' >{{item.show.name}}</td>
								<td onclick='go(this)' data-href='playsheet_angular.php?id={{item.id}}&socan={{item.socan}}' class='host'>{{item.host}}</td>
								<td onclick='go(this)' data-href='playsheet_angular.php?id={{item.id}}&socan={{item.socan}}' >{{item.start_time}}</td>
								<td onclick='go(this)' data-href='playsheet_angular.php?id={{item.id}}&socan={{item.socan}}' >{{item.status != 2 ? "(Draft)" : "" }}</td>
								<td ng-if='open_playsheet.staff' onclick='go(this)' data-href='playsheet_angular.php?id={{item.id}}&socan={{item.socan}}' class='edit_date'>{{item.edit_date}}</td>
								<td ng-if='open_playsheet.staff'><button ng-click='open_playsheet.delete(item.id)' type='button' class='delete'>Delete</button></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div style='clear:both;'></div>
		</div>
	</body>
</html>
