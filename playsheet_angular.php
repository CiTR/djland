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
	<script type='text/javascript'>
		var playsheet_id = "<?php if(isset($_GET['id'])){echo $_GET['id']; }else{ echo '-1';} ?>";
		var member_id = "<?php echo $_SESSION['sv_id']; ?>";
	</script>
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
		<?php print_menu(); 
		?>
		<div id='wrapper' ng-class="{socan: playsheet.socan}">
			 <div class='col1 padded side-padded'>
		      	<div class='col2'>
		      		<h4 class='text-left'>Show Data</h4>
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
					<br/>Show: <select ng-model="playsheet.show_value" ng-change="playsheet.updateShowValues()" ng-options="id as show.name for (id,show) in playsheet.member_shows">
					</select>
					<br/>Host: <input ng-model="playsheet.active_show.host"></input>
					<br/>Language: <input ng-model="playsheet.lang"></input>
					<br/>CRTC Category: 
					<button class="crtc" ng-model="playsheet.crtc" ng-click="playsheet.crtc == 30? playsheet.crtc = 20 : playsheet.crtc = 30;">{{playsheet.crtc}}</button>
		      	</div>
		      	<div class='col2'>
			        <div class='col1' >
			        	<h4 class='text-left'>Show Time</h4>
			        	Start Time:
			        	[<select ng-options="n for n in [] | range:00:24"  ng-model="playsheet.start_hour"></select> :
			       	 	<select ng-model="playsheet.start_minute" ng-options="n for n in [] | range:0:60 "
			                ng-change="datepicker.adjust_times();"></select>]
				        End Time:
				        [<select ng-model="playsheet.end_hour" ng-options="n for n in [] | range:0:24 "
				                 ng-change="$datepicker.adjust_times();"></select> :
				        <select ng-model="playsheet.end_minute" ng-options="n for n in [] | range:0:60"
				                ng-change="datepicker.adjust_times();"></select>]

			          	<input class="date_picker" type="text" datepicker-popup="{{format}}"
				                 ng-model="playsheet.start_time"  is-open="datepicker.opened"
				                 ng-required="true" close-text="Close" ng-hide="true"
				                 ng-change="datepicker.date_change();" />
			          	<br/>
			         	{{playsheet.start_time | date:'EEE, MMM d, y'}}
			         	<div ng-controller="datepicker as datepicker">
				        	<button ng-click="datepicker.open($event)" >change date</button><br/>
				    	</div>
			        </div>
			        <br>&nbsp </br>
			        <div class="col1 podcast_block_inner">
			        	<h4 class='text-left'>Podcast Time</h4>
	          			<div class='col2'>
	          				<button ng-click="startPodcast()"  ng-hide="startClicked">
	          					Start Podcast at {{currentTime | date: 'mediumTime'}}
	          				</button>
	          			</div>
	          			<div class='col2'>
	          				<span ng-show="startClicked">
	          					podcast start : {{playsheet.start_time | date: 'mediumTime'}}
	          				</span>
	          			</div>
	          			<div class='col2'>
							<button ng-click="endPodcast()" ng-show="startClicked && !endClicked">
								End Podcast at {{currentTime | date: 'mediumTime'}}
							</button>
						</div>
						<div class='col2'>
							<span ng-show="endClicked">
								podcast end : {{playsheet.end_time | date: 'mediumTime'}}
							</span>
						</div>
	            		(podcast times can also be adjusted from Podcasts page)
	        			<span ng-show="adminStatus"> podcast will be created:
	            			<input type="checkbox" ng-model="playsheet.podcast.active" ng-true-value="'1'" ng-false-value="'0'"></input>
		        		</span>
          			</div>
			    </div>
			</div>
		    <div class='container'>
				<h2>Music</h2>
				<table class='playitem'>
					<tr class='music_row_heading playitem' >
						<th class='side-padded'>#</th>
						<th><input value="Artist"readonly tooltip="{{playsheet.help.artist}}"></input></th>
						<th><input value="Song" readonly  tooltip-side:'bottom' tooltip="{{playsheet.help.song}}"></input></th>
						<th><input value="Album" ng-class="{socan: playsheet.socan}" readonly tooltip="{{playsheet.help.album}}"></input></th>
						<th ng-show="playsheet.socan"><input ng-class="{socan: playsheet.socan}" value="Composer" readonly tooltip="{{compHelp}}"></input></th>
						<th ng-show="playsheet.socan"><input value="Time Start(H:M)" tooltip-placement:'bottom' tooltip="{{playsheet.help.timeHelp1}}"></input></th>
						<th ng-show="playsheet.socan"><input value ="Duration(M:S)"tooltip="{{timeHelp2}}"></input></th>
						<th ng-repeat='tag in playsheet.tags'><button tooltip="{{playsheet.help[tag]}}"class="box {{tag}} filled pad-top"></th>
						<th><a href='http://www.crtc.gc.ca/eng/archive/2010/2010-819.HTM' target='_blank'><input class="lang" readonly tooltip='{{playsheet.help.crtc}}' value="Category"></a></th>
						<th><input class="lang" tooltip='{{playsheet.help.lang}}' readonly value="Language"></th>
						<th><th><th></th>
					</tr>
					<tbody ui-sortable ng-model='playsheet.playitems'>
					<tr class='playitem' playitem ng-repeat="playitem in playsheet.playitems track by $index"></tr>
					</tbody>
				</table>
				<button id="addRows" class='right' ng-click='playsheet.addFiveRows()'>Add Five More Rows</button>
				<br/>
			</div>
			<div class='col1'>
				<div class='col2 side-padded'>
					<h2> Ads, PSAs, Station IDs </h2>
					<table class='table table-responsive border'>
						</th><th>#</th><th>Time</th><th>Type</th><th>Name</th><th>Played</th>
						<tr ad ng-model='playsheet.ads' ng-repeat="ad in playsheet.ads track by $index"></tr>
					</table>
				</div>
				<div class='col2 side-padded'>
					<h2>Episode Info</h2>
					<h4 class='text-left'>Title: </h4>
					<input ng-model="playsheet.podcast.title" required ng-change="isTheFormAcceptible()" placeholder="(required)"><br/>
          			<h4 class='text-left'>Subtitle:</h4>
          			<input ng-model="playsheet.podcast.subtitle" size="60" >
					<h4 class='text-left'>Episode Description</h4>
					<textarea class='fill' ng-model='spoken_word'></textarea>
				</div>
			</div>
			<div class='col1 text-center'>
				<button class="large-button" ng-click="submit()" ng-hide="submitting">Save Show</button>
				<p ng-show="submitting">Submitting...</p>
				<div class="blocker" ng-hide="(songsComplete && formAcceptible && (playsheet.end_time < currentTime) )">
				<span ng-show="!songsComplete">
					Music Incomplete: (<b>{{socan? 'composer, ':''}}artist</b>, <b>album / release title</b>, and <b>song</b>)
					<span ng-show="socan"><br/>Since it's socan period, you must also set the start time and duration of each track</span>
				</span>
				<span ng-show="!formAcceptible">&nbsp;&nbsp;&nbsp;&nbsp;You did not set a podcast title (this is now required)</span>
				<span ng-show="!(playsheet.end_time < currentTime)"><br/>Cannot save a future podcast - please save a draft and submit later. </span>
				</div>
				<br/>
				<div id="message" ng-show="message.text != '' && message.age < 6 " >{{message.text}}</div>
			</div>
		</div>
	</body>
</html>