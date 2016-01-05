<html ng-app='djland.editPlaysheet'>
	<?php
	include_once("headers/session_header.php");
	require_once("headers/security_header.php");
	require_once("headers/functions.php");
	require_once("headers/socan_header.php");
	require_once("headers/menu_header.php");
	?>
	<head>
		<link rel='stylesheet' href='js/bootstrap/bootstrap.min.css'></script>
		<link rel="stylesheet" href="css/style.css" type="text/css">
		
	</head>
	
	
	<script type='text/javascript' src="js/jquery-1.11.3.min.js"></script>
    <script type='text/javascript' src="js/jquery-ui-1.11.3.min.js"></script>
	<script type='text/javascript' src="js/angular.js"></script>
	<script type='text/javascript' src="js/angular/sortable.js"></script>
	<script type='text/javascript' src='js/bootstrap/bootstrap.js'></script>
	<script type='text/javascript' src='js/bootstrap/ui-bootstrap-tpls-0.12.0-withseconds.js'></script>
	<script type='text/javascript' src='js/playsheet/object.js'></script>
	<script type='text/javascript' src='js/playsheet/app.js'></script>
	<script type='text/javascript' src='js/playsheet/constants.js'></script>
	<script type='text/javascript' src='js/api.js'></script>
	<script type='text/javascript' src='js/utils.js'></script>
	<body class='wallpaper' ng-controller="PlaysheetController as c">
		<script type='text/javascript'>
		var playsheet_id = "<?php if(isset($_GET['id'])){echo $_GET['id']; }else{ echo '-1';} ?>";
		var member_id = "<?php echo $_SESSION['sv_id']; ?>";
		var username = "<?php echo $_SESSION['sv_username']; ?>";
		</script>
		<?php print_menu(); ?>
		<div class='text-center' ng-show='c.playsheet.loading'><img class='rounded' width ='300' height='20' src='images/loading.gif'/></div>
		<div id='socan' class='hidden'><?php if(isset($_GET['socan'])) echo $_GET['socan']; elseif(isset($_POST['socan'])) echo $_POST['socan']; else echo socanCheck($db); ?></div>
		<div id='playsheet_id' class='hidden'><?php if(isset($_POST['ps_id'])){echo $_POST['ps_id'];}?></div>
		
		<div ng-hide="c.playsheet.shows || c.playsheet.loading == true" class='text-center'>You have no shows assigned to this account. Please ask a staff member to assign you to your show</div>
		<div ng-show="c.playsheet.shows" id='wrapper' ng-class="{socan: c.playsheet.socan }">
			 <div class='col1 side-padded'>
		      	<div class='col2 padded'>
		      		
					<div class='col1'>
						Show: <select id='show_select' ng-model="c.playsheet.show.id" ng-change="c.playsheet.updateShowValues(this)" ng-options="show.id as show.name for show in c.playsheet.shows | orderBy:'name'">
						</select>
					</div>
					<div class='col1'>
						Show Host(s): <input ng-model="c.playsheet.info.host"></input>
					</div>
					<div class='col1 double-padded-top'>
						Type: 
				        <select ng-model="c.playsheet.info.type" ng-change="loadIfRebroadcast()">
				          	<option value="Syndicated">Syndicated</option>
				          	<option value="Live">Live</option>
				          	<option value="Rebroadcast">Rebroadcast</option>
				          	<option value="Simulcast">Simulcast</option>
				    	</select>
			        	<span ng-show="c.playsheet.info.type == 'Rebroadcast'">
			          		<select ng-model="c.playsheet.existing_playsheet" ng-options="ps.id as ps.start_time for (id,ps) in c.playsheet.existing_playsheets | orderBy:'-id' "></select>
						<button ng-click="c.playsheet.loadRebroadcast()">Load Rebroadcast</button>
						</span>
						
					</div>
					<div class='col1'>
						Language: <input ng-model="c.playsheet.info.lang"></input>
					</div>
					<div class='col1'>
						CRTC Category: 
						<button class="crtc" ng-model="c.playsheet.info.crtc" ng-click="c.playsheet.info.crtc == 30? c.playsheet.info.crtc = 20 : c.playsheet.info.crtc = 30;">{{c.playsheet.info.crtc}}</button>
					</div>
					<!-- <<h4 class='text-left'>Show Data</h4> -->
			        
					</div>
		      	<div class='col2 padded'>
			        <div class='col1' >
					    <div class='col2 side-padded' >
			        		<div class="col1" >
					        	Start: {{c.playsheet.start.date | date:'yyyy/MM/dd HH:mm:ss'}}

					    	</div>
					    	<div class='col1 padded' ng-controller="datepicker as date">
					        	<button ng-click="date.open($event)" >Change Start Day</button>
					        	<input class="date_picker" type="text" datepicker-popup="yyyy/MM/dd HH:mm:ss"
						                 ng-model="c.playsheet.info.start_time"  is-open="date.opened"
						                 ng-required="true" close-text="Close" ng-hide="true"
						                 ng-change="date.date_change();" />
					    	</div>
			        		<div class="col1" >
					        	End: {{c.playsheet.end.date | date:'yyyy/MM/dd HH:mm:ss'}}
					    	</div>
					    	<div class='col1 padded' ng-controller="datepicker as date">
					        	<button ng-click="date.open($event)" >Change End Day</button>
					        	<input class="date_picker" type="text" datepicker-popup="yyyy/MM/dd HH:mm:ss"
						                 ng-model="c.playsheet.end.date"  is-open="date.opened"
						                 ng-required="true" close-text="Close" ng-hide="true"
						                 ng-change="date.date_change();" />
					    	</div>
					    </div>
					    <div class='col2'>
				        	<div>
					        	Start Time:
					        	[<select id='start_hour' ng-model="c.playsheet.start.hour" ng-options="n for n in [] | range:00:24" ng-change='c.playsheet.updateStart()' ></select> :
					       	 	<select id='start_minute' ng-model="c.playsheet.start.minute" ng-options="n for n in [] | range:0:60 "></select> :
				                 <select id='start_seconds' ng-model="c.playsheet.start.second" ng-options="n for n in [] | range:0:60"></select>]
			                </div>
			                <div class='padded'>
						        End Time:
						        [<select ng-model="c.playsheet.end.hour" ng-options="n for n in [] | range:0:24 " ng-change="c.playsheet.updateEnd()"></select> :
						        <select ng-model="c.playsheet.end.minute" ng-options="n for n in [] | range:0:60" ng-change="c.playsheet.updateEnd()"></select> :
						        <select ng-model="c.playsheet.end.second" ng-options="n for n in [] | range:0:60"  ng-change="c.playsheet.updateEnd()"></select>]
					          	
			                </div>
			          	</div>
		        		<div class='col1 double-padded-top'>
		        			<div >
		        				Podcast Timing
						    	<button ng-click="c.playsheet.startShow()">
		          						Start Show Now
		          				</button>
								<button ng-click="c.playsheet.endShow()">
									End Show Now
								</button>
							</div>

					    </div>
				    </div>
			    </div>
			</div>
		    <div id='container'>
				<h3 class='double-padded-top'>Music</h3>
				<table class='playitem'>
					<tr class='music_row_heading playitem border'>
						<th class='side-padded'>#</th> 
						<th><input value="Artist" readonly tooltip="{{c.playsheet.help.artist}}" ng-class="{socan: c.playsheet.socan }"></input></th>
						<th><input value="Song" ng-class="{socan: c.playsheet.socan}" readonly tooltip="{{c.playsheet.help.song}}" ng-class="{socan: c.playsheet.socan }"></input></th>
						<th><input value="Album" readonly  tooltip-side:'bottom' tooltip="{{c.playsheet.help.album}}" ng-class="{socan: c.playsheet.socan }"></input></th>
						<th ng-show="c.playsheet.socan"><input ng-class="{socan: c.playsheet.socan}" value="Composer" readonly tooltip="{{compHelp}}" ng-class="{socan: c.playsheet.socan }"></input></th>
						<th ng-show="c.playsheet.socan"><input value="Time Start(H:M)" tooltip-placement:'bottom' tooltip="{{c.playsheet.help.timeHelp1}}" class='socantiming'></input></th>
						<th ng-show="c.playsheet.socan"><input value ="Duration(M:S)"tooltip="{{timeHelp2}}" class='socantiming'></input></th>
						<th><button tooltip="{{c.playsheet.help['playlist']}}" class="box playlist filled pad-top"></button></th>
						<th><button tooltip="{{playlist.help['cancon']}}" class="box cancon filled pad-top"></button>
						<th><button tooltip="{{c.playsheet.help['femcon']}}" class="box femcon filled pad-top"></button></th>
						<th><button tooltip="{{c.playsheet.help['instrumental']}}" class="box instrumental filled pad-top"></button></th>
						<th><button tooltip="{{c.playsheet.help['partial']}}" class="box partial filled pad-top"></button></th>
						<th><button tooltip="{{c.playsheet.help['hit']}}" class="box hit filled pad-top"></button></th>
						<th ng-show="c.playsheet.socan"><button tooltip="{{c.playsheet.help['background']}}" class="box background filled pad-top"></button></th>
						<th ng-show="c.playsheet.socan"><button tooltip="{{c.playsheet.help['theme']}}" class="box theme filled pad-top"></button></th>
						<th><a href='http://www.crtc.gc.ca/eng/archive/2010/2010-819.HTM' target='_blank'><input class="lang" readonly tooltip='{{c.playsheet.help.crtc}}' value="Category"></a></th>
						<th><input class="lang" tooltip='{{c.playsheet.help.lang}}' readonly value="Language"/></th>
						<th><th><th></th>
					</tr>
					<tbody ui-sortable id='playitems' ng-change='c.playsheet.checkIfComplete()' ng-update='c.playsheet.checkIfComplete()' ng-model='c.playsheet.playitems'>
						<tr class='playitem border' ng-class="{socan: c.playsheet.socan }" playitem ng-repeat="playitem in c.playsheet.playitems track by $index"></tr>
					</tbody>
				</table>
				<button id="addRows" class='right' ng-click='c.playsheet.addFiveRows()'>Add Five More Rows</button>
				<br/>
			</div>
			<div class='col1 double-padded-top'>
				<div class='span4col5 side-padded double-padded-top'>
					<h4> Ads, PSAs, Station IDs </h4>
					<table class='table table-responsive border'>
						<th>#</th><th>Time</th><th>Type</th><th>Name</th><th>Played</th>
						<tr ad class='ads' ng-model='c.playsheet.ads' ng-repeat="ad in c.playsheet.ads"></tr>
					</table>
				</div>
				<div class='col5 side-padded right double-padded-top'>
					<h4>Spoken Word Duration</h4>
        			<div class='col2 text-center'>Hours<select class='required' ng-change='c.playsheet.checkIfComplete(); c.playsheet.updateSpokenword()' ng-model="c.playsheet.spokenword_hours" ng-options="n for n in [] | rangeNoPad:0:24"></select></div>
      				<div class='col2 text-center'>Minutes<select class='required' ng-change='c.playsheet.checkIfComplete(); c.playsheet.updateSpokenword()' ng-model="c.playsheet.spokenword_minutes" ng-options="n for n in [] | rangeNoPad:0:60"></select></div>
				</div>
			</div>
			<div class='col1 side-padded double-padded-top'>
					<h4>Episode Title</h4>
					<input class='wideinput required' ng-change='c.playsheet.checkIfComplete()' ng-model = 'c.playsheet.info.title'/>
					<h4>Episode Description</h4>
					<textarea class='fill required' ng-change='c.playsheet.checkIfComplete()' ng-model='c.playsheet.info.summary'></textarea>
			</div>

			<hr style="side-padded">

			<div class='col1 text-center'>
				<button class="large-button" ng-click="c.playsheet.submit()" ng-hide="submitting">Save Show</button>
				<div class="blocker" ng-hide="c.playsheet.complete">
					{{ c.playsheet.missing }}
				</div>
				<br/>
				<div id="message" ng-show="message.text != '' && message.age < 6 " >{{message.text}}</div>
			</div>
			<div class="floating">
				<button type="button" ng-click="c.playsheet.saveDraft()" ng-hide="c.playsheet.info.status == 2" >Save Draft</button><br/><br/>
				<div ng-show='c.playsheet.using_sam'>
					<button type="button" ng-click="samVisible = !samVisible;" >SAM</button>
				</div>
				
			</div>
		
				<div id="sam_picker" ng-show="samVisible">
					<div id="sam_title"><span ng-click="samVisible = false;">X</span>Sam Plays</div><br/><br/>
						<button ng-click="c.playsheet.samRange()">add all plays from {{c.playsheet.start | date:'y-MM-dd HH:mm:ss'}} to {{c.playsheet.end | date:'HH:mm:ss'}}	</button>
					<div ng-repeat="sam_playitem in c.playsheet.samRecentPlays" class="sam_row">
						<button class='side-padded' ng-click="c.playsheet.addSamPlay(sam_playitem);">+</button>
						<span class="one_sam">{{sam_playitem.artist}} - {{sam_playitem.song}} ({{ sam_playitem.insert_song_start_hour}}:{{sam_playitem.insert_song_start_minute}})</span>
					</div>
				</div>


			<!-- Popup Overlay during submission -->
			<div class="tracklist_overlay" ng-show="c.playsheet.tracklist_overlay">
				<button ng-click='c.playsheet.tracklist_overlay = !c.playsheet.tracklist_overlay'> X </button>
				<h3>{{c.playsheet.tracklist_overlay_header}}</h3>
				<h3 ng-show='!c.playsheet.error'>If you're done, please <a class='logout' href="index.php?action=logout" target="_self">click here to log out now</a> </h3>
				<div ng-show='c.playsheet.info.status == 2' class='text-center'> {{c.playsheet.podcast_status}}</div>
				<div class='text-center' id = 'playsheet_error'> </div>				
				<hr/>
				<h4 ng-show='!c.playsheet.error'>Tracklist:</h4>
				<ul>
					<li ng-repeat="playitem in c.playsheet.playitems track by $index">{{playitem.artist}} "{{playitem.song}}" - {{playitem.album}}</li>
				</ul>

			</div>
			<!-- Darkens Background during submission popup -->
			<div class="dark" ng-show="c.playsheet.tracklist_overlay"></div>
			{{c.playsheet.spokenword_hour}}
		</div>
	</body>
</html>