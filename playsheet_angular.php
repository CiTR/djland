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
			 <div class='col1 side-padded'>
		      	<div class='col2 padded'>
		      		
					<div class='col1'>
						Show: <select ng-model="playsheet.show_value" ng-change="playsheet.updateShowValues()" ng-options="id as show.name for (id,show) in playsheet.member_shows">
						</select>
					</div>
					<div class='col1'>
						Show Host(s): <input ng-model="playsheet.active_show.host"></input>
					</div>
					
					<div class='col1 double-padded-top'>
						Type: 
				        <select ng-model="playsheet.type" ng-change="loadIfRebroadcast()">
				          	<option value="Syndicated">Syndicated</option>
				          	<option value="Live">Live</option>
				          	<option value="Rebroadcast">Rebroadcast</option>
				          	<option value="Simulcast">Simulcast</option>
				    	</select>
			        	<span ng-show="playsheet.type == 'Rebroadcast'">
			          	         <select ng-model="desired_playsheet"
				                  ng-options="playsheet.playlist_id as playsheet.start_time + ' - ' + playsheet.show_name
				                   for playsheet
				                   in available_playsheets ">
				          </select>
						<button ng-click="loadPlays(desired_playsheet)">{{available_playsheets.length > 1 ? '<-- load plays from this playsheet' : '...'}}</button>
						</span>
						
					</div>
					<div class='col1'>
						Language: <input ng-model="playsheet.lang"></input>
					</div>
					<div class='col1'>
						CRTC Category: 
						<button class="crtc" ng-model="playsheet.crtc" ng-click="playsheet.crtc == 30? playsheet.crtc = 20 : playsheet.crtc = 30;">{{playsheet.crtc}}</button>
					</div>
					<!-- <<h4 class='text-left'>Show Data</h4> -->
			        
					</div>
		      	<div class='col2 padded'>
			        <div class='col1' >
					    <div class='col2 side-padded' >
			        		<div class="col1" >
					        	Date: {{playsheet.start_time | date:'EEE, MMM d, y'}}
					    	</div>
					    	<div class='col1 padded' ng-controller="datepicker as date">
					        	<button ng-click="date.open($event)" >Change Date</button>
					        	<input class="date_picker" type="text" datepicker-popup="{{format}}"
						                 ng-model="playsheet.start_time"  is-open="date.opened"
						                 ng-required="true" close-text="Close" ng-hide="true"
						                 ng-change="date.date_change();" />
					    	</div>
					    </div>
					    <div class='col2'>
				        	<div>
					        	Start Time:
					        	[<select ng-options="n for n in [] | range:00:24" ng-change='playsheet.updateStart()' ng-model="playsheet.start_hour"></select> :
					       	 	<select ng-model="playsheet.start_minute" ng-change='playsheet.updateStart()' ng-update='playsheet.updateStart()' ng-options="n for n in [] | range:0:60 "
					                ></select> :
				                 <select ng-model="playsheet.start_second" ng-change='playsheet.updateStart()' ng-options="n for n in [] | range:0:60"
					                ></select>]
			                </div>
			                <div class='padded'>
						        End Time:
						        [<select ng-model="playsheet.end_hour" ng-options="n for n in [] | range:0:24 "
						                 ng-change="playsheet.updateEnd()"></select> :
						        <select ng-model="playsheet.end_minute" ng-options="n for n in [] | range:0:60"
						                ng-change="playsheet.updateEnd()"></select> :
						        <select ng-model="playsheet.end_second" ng-options="n for n in [] | range:0:60"
						                ng-change="playsheet.updateEnd()"></select>]
					          	
			                </div>
			          	</div>
		        		<div class='col1 double-padded-top'>
		        			<div >
		        				Podcast Timing
						    	<button ng-click="playsheet.startShow()">
		          						Start Show Now
		          				</button>
								<button ng-click="playsheet.endShow()">
									End Show Now
								</button>
							</div>

					    </div>
				    </div>
			    </div>
			</div>
		    <div class='container'>
				<h3 class='double-padded-top'>Music</h3>
				<table class='playitem'>
					<tr class='music_row_heading playitem border'>
						<th class='side-padded'>#</th>
						<th><input value="Artist"readonly tooltip="{{playsheet.help.artist}}"></input></th>
						<th><input value="Album" readonly  tooltip-side:'bottom' tooltip="{{playsheet.help.album}}"></input></th>
						<th><input value="Song" ng-class="{socan: playsheet.socan}" readonly tooltip="{{playsheet.help.song}}"></input></th>
						<th ng-show="playsheet.socan"><input ng-class="{socan: playsheet.socan}" value="Composer" readonly tooltip="{{compHelp}}"></input></th>
						<th ng-show="playsheet.socan"><input value="Time Start(H:M)" tooltip-placement:'bottom' tooltip="{{playsheet.help.timeHelp1}}"></input></th>
						<th ng-show="playsheet.socan"><input value ="Duration(M:S)"tooltip="{{timeHelp2}}"></input></th>
						<th ng-repeat='tag in playsheet.tags'><button tooltip="{{playsheet.help[tag]}}"class="box {{tag}} filled pad-top"></th>
						<th><a href='http://www.crtc.gc.ca/eng/archive/2010/2010-819.HTM' target='_blank'><input class="lang" readonly tooltip='{{playsheet.help.crtc}}' value="Category"></a></th>
						<th><input class="lang" tooltip='{{playsheet.help.lang}}' readonly value="Language"></th>
						<th><th><th></th>
					</tr>
					<tbody ui-sortable ng-model='playsheet.playitems'>
						<tr class='playitem border' playitem ng-repeat="playitem in playsheet.playitems track by $index"></tr>
					</tbody>
				</table>
				<button id="addRows" class='right' ng-click='playsheet.addFiveRows()'>Add Five More Rows</button>
				<br/>
			</div>
		
			<div class='col1 double-padded-top'>
				<div class='span4col5 side-padded double-padded-top'>
					<h4> Ads, PSAs, Station IDs </h4>
					<table class='table table-responsive border'>
						</th><th>#</th><th>Time</th><th>Type</th><th>Name</th><th>Played</th>
						<tr ad ng-model='playsheet.ads' ng-repeat="ad in playsheet.ads track by $index"></tr>
					</table>
				</div>
				<div class='col5 side-padded right double-padded-top'>
					<h4>Spoken Word Duration</h4>
        			<div class='col2 text-center'>Hours<select ng-model="playsheet.spokenword_hours" ng-options="n for n in [] | rangeNoPad:0:24"></select></div>
      				<div class='col2 text-center'>Minutes<select ng-model="playsheet.spokenword_minutes" ng-options="n for n in [] | rangeNoPad:0:60"></select></div>
				</div>
			</div>
			<div class='col1 side-padded double-padded-top'>
					<h4>Episode Description</h4>
					<textarea class='fill' ng-model='spoken_word'></textarea>
			</div>
			<hr style="side-padded">
			<div class='col1 text-center'>
				<button class="large-button" ng-click="submit()" ng-hide="submitting">Save Show</button>
				<p ng-show="submitting">Submitting...</p>
				<div class="blocker" ng-hide="(songsComplete && formAcceptible && (playsheet.end_time < currentTime) )">
				<span ng-show="!songsComplete">
					Music Incomplete: (<b>{{socan? 'composer, ':''}}artist</b>, <b>album / release title</b>, and <b>song</b>)
					<span ng-show="socan"><br/>Since it's socan period, you must also set the start time and duration of each track</span>
				</span>
				<span ng-show="!(playsheet.end_time < currentTime)"><br/>Cannot save a future podcast - please save a draft and submit later. </span>
				</div>
				<br/>
				<div id="message" ng-show="message.text != '' && message.age < 6 " >{{message.text}}</div>
			</div>
		</div>
	</body>
</html>