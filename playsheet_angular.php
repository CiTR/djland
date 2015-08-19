<html ng-app='djland.editPlaysheet'>
	<?php
	include_once("headers/session_header.php");
	require_once("headers/security_header.php");
	require_once("headers/function_header.php");
	require_once("headers/menu_header.php");
	?>
	<head>
		<link rel='stylesheet' href='js/bootstrap/bootstrap.min.css'></script>
		<link rel="stylesheet" href="css/style.css" type="text/css">
		<div id='playsheet_id' class='hidden'><?php if(isset($_POST['ps_id'])){echo $_POST['ps_id'];}?></div>
	</head>
	<script type='text/javascript' src="js/jquery-ui/external/jquery/jquery.js"></script>
	<script type='text/javascript' src="js/angular.js"></script>
	<script type='text/javascript' src="js/angular/sortable.js"></script>
	<script type='text/javascript' src='js/bootstrap/bootstrap.js'></script>
	<script type='text/javascript' src='js/bootstrap/ui-bootstrap-tpls-0.12.0-withseconds.js'></script>
	<script type='text/javascript' src='js/playsheet/playsheet.js'></script>
	<script type='text/javascript' src='js/playsheet/constants.js'></script>
	<script type='text/javascript' src='js/api.js'></script>
	<script type='text/javascript' src='js/utils.js'></script>
	<script type='text/javascript' src="js/jquery-ui/jquery-ui.js"></script>
	<body class='wallpaper' ng-controller="PlaysheetController as playsheet">
		<?php print_menu() ?>
		<div id='wrapper' ng-class="{socan: playsheet.socan}">
			 <div class='col1' >
		      	<div class='col2'>
			        Playsheet Type: 
			        <select ng-model="playsheet.type" ng-change="loadIfRebroadcast()">
			          	<option value="Syndicated">Syndicated</option>
			          	<option value="Live">Live</option>
			          	<option value="Rebroadcast">Rebroadcast</option>
			          	<option value="Simulcast">Simulcast</option>
			    	</select>
		        	<span ng-show="playsheet.type == 'Rebroadcast'">
		          	<br/>
			          <select ng-model="desired_playsheet"
			                  ng-options="playsheet.playlist_id as playsheet.start_time + ' - ' + playsheet.show_name
			                   for playsheet
			                   in available_playsheets ">
			          </select>

					<button ng-click="loadPlays(desired_playsheet)">{{available_playsheets.length > 1 ? '<-- load plays from this playsheet' : '...'}}</button>
					</span>
					<br/>Show: {{playsheet.show_id}}
					<br/>Host: <input ng-model="playsheet.host"></input>
					<br/>Language: <input ng-model="playsheet.lang"></input>
					<br/>CRTC Category: 
					<button class="crtc" ng-model="playsheet.crtc" ng-click="playsheet.crtc == 30? playsheet.crtc = 20 : playsheet.crtc = 30;">{{playsheet.crtc}}</button>
		      	</div>
		      	<div class='col2'>
			        
			        <div ng-controller="datepicker" >
			        	Start Time:
			        	[<select ng-model="$parent.start_hour" ng-options="n for n in [] | range:0:24"
			                 ng-change="$parent.adjust_times();"></select> :
			       	 	<select ng-model="$parent.start_minute" ng-options="n for n in [] | range:0:60"
			                ng-change="$parent.adjust_times();"></select>]

				        End Time:
				        [<select ng-model="$parent.end_hour" ng-options="n for n in [] | range:0:24 "
				                 ng-change="$parent.adjust_times();"></select> :
				        <select ng-model="$parent.end_minute" ng-options="n for n in [] | range:0:60"
				                ng-change="$parent.adjust_times();"></select>]

			          	<input class="date_picker" type="text" datepicker-popup="{{format}}"
				                 ng-model="playsheet.start_time"  is-open="opened"
				                 ng-required="true" close-text="Close" ng-hide="true"
				                 ng-change="$parent.date_change();" />
			          	<br/>
			         	{{playsheet.start_time | date:'EEE, MMM d, y'}}
				        <button ng-click="open($event)"  >change date</button><br/>
			        </div>
			    	
			    </div>
			</div>
		    <div class='container'>
				<h3> Playsheet Items </h3>
				<table class='playitem'>
					<tr class='music_row_heading playitem' >
						<th><input class='hidden'></th>
						<th><input value="Song" readonly  tooltip-side:'bottom' tooltip="{{playsheet.help.songHelp}}"></input></th>
						<th><input value="Artist"readonly  placement:'bottom' tooltip="{{playsheet.help.artistHelp}}"></input></th>
						<th><input value="Album" ng-class="{socan: playsheet.socan}" readonly tooltip="{{playsheet.help.albumHelp}}"></input></th>
						<th ng-show="playsheet.socan"><input ng-class="{socan: playsheet.socan}" value="Composer" readonly tooltip="{{compHelp}}"></input></th>
						<th ng-show="playsheet.socan"><input value="Time Start(H:M)" tooltip-placement:'bottom' tooltip="{{playsheet.help.timeHelp1}}"></input></th>
						<th ng-show="playsheet.socan"><input value ="Duration(M:S)"tooltip="{{timeHelp2}}"></input></th>
						<th ng-repeat='tag in playsheet.tags'><button class="box {{tag}} filled pad-top"></th>
						<th><input class="lang" value="Category"></th>
						<th><input class="lang" value="Language"></th>
						<th><th><th></th>
					</tr>
					<tbody ui-sortable ng-model='playsheet.playitems'>
					<tr class='playitem' playitem ng-repeat="playitem in playsheet.playitems track by $index"></tr>
					</tbody>
				</table>
				<br/>
			</div>
			<div class='col1'>
				<div class='col2'>
					<h3> Ads and PSAs </h3>
					<table class='table table-responsive border'>
						</th><th>#</th><th>Time</th><th>Name</th><th>Type</th><th>Played</th>
						<tr ad ng-model='playsheet.ads' ng-repeat="ad in playsheet.ads track by $index"></tr>
					</table>
				</div>
				<div class='col2'>
					<h3>Show Content & Spoken Word</h3>
					<textarea class='fill' ng-model='spoken_word'></textarea>
				</div>
			</div>
		</div>
		<div class='col1'>
			<div ng-model='playsheet.shows' ng-repeat='show in playsheet.shows'>{{show.show_name}} {{show.start_time}}</div>
		</div>

	</body>
</html>