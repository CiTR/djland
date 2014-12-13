<?php

if (!isset($_GET['channel'])){
	echo 'please append podcasting.php with ?channel=CHANNEL# after migrating your database. (For example, <a href="/podcasting.php?channel=12">click here for channel 12</a>) <br/><br/>see <a href="/podcasting/NOTES.txt"> notes.txt </a> for more info about migration</a></a>';
} else {
	$channel_id = $_GET['channel'];
	date_default_timezone_set('America/Vancouver');
}

?>
<html>
<head>
<style type='text/css'>

body {
	background-color: white;
	padding-left: 12px;
}

.big{
	font-size:2em;
	cursor:default;
}

.big:hover{
	background-color:grey;

}
.nav, .pagination, .carousel, .panel-title a { cursor: pointer; }

.podcast_date{
	width:10px;
}
	.date_pick{

	}

	.play_episode{
		color:black;
	//	background-color:white;

	}
	.play_episode:hover{
		color:blue;
	}

	.timepicker{
		border-width:1px;
		border-style: solid;

		display:inline-block;
		padding:7px;

	}

	.summary{
		width:80%;
		min-height:200px;
		margin-top:5px;
		margin-bottom:5px;
		overflow: auto;
	}

	.subtitle{
		font-weight:bold;
	}
	.episode{
		width:800px;
		padding-left:35px;
	}

	.title{  margin-left:-15px;
	}
	input,textarea{
		border-style:solid;
		border-color:white;
		width:100%;
	}
	.editing{
		border-color:lightskyblue;
		border-width:1px;
		border-style:solid;
		background-color:#eef6ff;
	}

	.title-label{
		position:relative;
		bottom:-18px;
	}

	.previews{
		display:inline-block;
	}
</style>

<link rel="stylesheet" href='js/bootstrap/bootstrap.min.css'></script>

</head>
<body ng-app='podcastEditor'>

<div ng-controller='channelCtrl as channel'>

	<div ng-controller='episodeCtrl' ng-repeat="episode in episodes" class=episode>
<i ng-hide="!editing" class='title-label'>title:</i>
		<h3>
			<input ng-model="episode.title" ng-readonly="!editing" ng-class="{editing: editing}" class="title"/> ({{episode.id}})
			<button class='play_episode' ng-click="episode.sound.togglePause()">&#9658;/ &#10074;&#10074;</button>
			<button ng-click="editToggle()" ng-class="{editing: editing}">edit</button>
			<button ng-click='save(episode)' ng-hide="!editing">save</button>
			{{status}}
		</h3>
<i ng-hide="!editing">subtitle:<br/></i>
			<input ng-model="episode.subtitle" ng-readonly="!editing" ng-class="{editing: editing}" class="subtitle"/><br/>
<i ng-hide="!editing">description:<br/></i>
			<textarea ng-model="episode.summary" ng-readonly="!editing"  class='summary' ng-class="{editing: editing}"></textarea><br/>
			<div ng-hide="!editing" ng-class="{editing: editing}">
				active: <input type="checkbox"
							ng-model="episode.active"
							ng-true-value="1"
							ng-false-value="0" editable=true/>

			<span ng-controller='datepicker' class='date_pick'>
			<input class="podcast_date" type="text" datepicker-popup="{{format}}"
			ng-model="episode.date"  is-open="opened"
			ng-required="true" close-text="Close" ng-hide="true"/>
			<br/>
			<h4 ><i>broadcast on </i><b>{{episode.date | date:'medium'}}</b><i> until </i><b>{{episode.end}}</b> <button type="button" ng-click="open($event)">
change date
</button>
</h4>
</span>
			<span ng-controller='timepicker'  class='timepicker'>
				<h4>edit start time</h4>
				<timepicker ng-model="episode.time" ng-change="start_changed(episode.time)"></timepicker>
			</span>

			<span ng-controller='timepicker' class='timepicker'>
				<h4>edit duration:</h4>
				<timepicker ng-model="episode.duration_obj" ng-change="length_changed(episode.duration_obj)" show-meridian="false" ></timepicker>
			</span>
			<span class="previews">
				<button class='play_start' ng-click="preview_start(episode)" ng-hide="!editing">play beginning</button>
				<button class='play_end' ng-click="preview_end(episode)" ng-hide="!editing">play end</button>
				<span ng-class="{playing: playing}"><image src='images/speaker.png'></span>
			</span>
			</div>


		<br/>
		<hr/>
	</div>

</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<script type='text/javascript' src='js/soundmanager2/script/soundmanager2.js'></script>	

<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.3.5/angular.js"></script>
<script type='text/javascript' src='js/bootstrap/bootstrap.js'></script>
<script type='text/javascript' src='js/bootstrap/ui-bootstrap-tpls-0.12.0-withseconds.js'></script>

<script type='text/javascript'>

var podcastEditor = angular.module('podcastEditor', ['ui.bootstrap'])
	.controller('datepicker', ['$scope','$filter',function($scope, $filter) {
		$scope.today = function() {
			$scope.dt = new Date();
		};

		$scope.clear = function () {
			$scope.dt = null;
		};

		$scope.open = function($event) {
			$event.preventDefault();
			$event.stopPropagation();

			$scope.opened = true;
		};

		$scope.format = 'medium';

	}])
	.controller('timepicker', ['$scope','$filter','timezone_offset', function($scope, $filter, timezone_offset) {
		var episode = $scope.$parent.$parent.episode;
		episode.time = episode.date;
		episode.duration_obj = new Date((episode.duration-timezone_offset) * 1000);

		$scope.start_changed = function(time){
			var hh = time.getHours();var mm = time.getMinutes();var ss = time.getSeconds();
			var episode_date = new Date(episode.date);
			episode_date.setHours( hh);episode_date.setMinutes( mm);episode_date.setSeconds( ss);
			episode.date = $filter('date')(episode_date, 'medium');
			episode.date_unix = episode_date.getTime() / 1000;

			episode.updateEndTime()
		};

		$scope.length_changed = function(time){

			var existing_duration = time.getSeconds();
			episode.duration = ( time.getTime() / 1000 ) + timezone_offset;
			var hh = time.getHours();var mm = time.getMinutes();var ss = time.getSeconds();

			var new_end_date = new Date(episode.date);
			var start_hh = new_end_date.getHours();
			var start_mm = new_end_date.getMinutes();
			var start_ss = new_end_date.getSeconds();

			new_end_date.setSeconds(start_ss + ss + timezone_offset);
			new_end_date.setMinutes(start_mm + mm);
			new_end_date.setHours(start_hh + hh);

			episode.end_obj = new_end_date;
			episode.updateEndTime()

		};
	}])
	.controller('channelCtrl', ['$scope', '$http', '$filter','channel_id', function($scope, $http, $filter, channel_id){

		$http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";

		sm = new SoundManager();

		$http.get('/podcasting/list.php?channel='+channel_id)
			.success(function(data, status, headers, config){

			$scope.episodes = angular.fromJson(data);

		});
	}])
	.controller('episodeCtrl', ['$scope', '$http', '$filter', 'archiveService', function($scope, $http, $filter, archiveService){

		var episode = $scope.$parent.episode;

		episode.active = parseInt(episode.active,10);
		episode.archiveURL = archiveService.url(episode.start_obj, episode.end_obj);

		episode.start_obj = new Date(episode.date_unix*1000);
		episode.date = $filter('date')(episode.start_obj, 'medium');

		episode.updateEndTime = function(){

			var end = new Date(episode.date_unix*1000);
			var end_seconds = end.getSeconds();
			end.setSeconds(end_seconds + parseInt(episode.duration,10));
			episode.end = $filter('date')(end, 'mediumTime');
			episode.end_obj = end;
		};
		episode.updateEndTime();

		$scope.editing = false;

		$scope.editToggle = function(){
			$scope.editing = !$scope.editing

			if($scope.editing){
				$scope.initSoundPreviews();
			}


		}

		$scope.save = function(episode){
			$scope.status = 'saving...';

			var data_to_post = {};
			data_to_post.url = episode.url;
			data_to_post.channel = 124;
			data_to_post.data = {
				title:episode.title,
				subtitle:episode.subtitle,
				summary:episode.summary,
				id:episode.id,
				date:episode.date,
				duration:episode.duration,
				active:episode.active,
				author:episode.author

			};

			$http({
				url:'/podcasting/episode.php',
				method:'POST',
				data:$.param(data_to_post)
			})
			.success(function(data, status, headers, config){
				$scope.status = data;

			})
			.error(function(data,status,headers,config){
				$scope.status = 'error: '+status;

			});
		}

		var basic_sound_options = {
			debugMode:false,
			useConsole:false,
			autoLoad:true,
			multiShot:false,
			stream:true/*,
			onplay: function(){
				for (var i = 0; i< sm.soundIDs.length ; i++){
					var soundID = sm.soundIDs[i];
					if( (this.id != soundID) && sm.getSoundById(soundID).playState ){
						sm.getSoundById(soundID).stop();
						console.warn('stopped playing sound id '+soundID);
					}
				}
			}*/
		};

		sm.setup({
			debugMode:true,
			useConsole:true,/*
			onready:function(){

					var music_url = episode.url;

					episode.sound = sm.createSound(
						angular.extend(basic_sound_options,{
							id:'full'+episode.id,
							url:episode.url
						})
					);
				
			},
			ontimeout: function() {
				console.error('Soundmanager init failed!');
			}*/

		});

		$scope.preview_start = function(episode){
			sm.stopAll();
			if ( typeof(episode.sound_end) != 'undefined') episode.sound_end.destruct();

			if ( typeof(episode.sound_start) == 'undefined'){

				var start_prev_end = new Date(episode.start_obj);
				start_prev_end.setSeconds(start_prev_end.getSeconds() + 5);
				episode.sound_start_url = archiveService.url(episode.start_obj, start_prev_end);

				episode.sound_start = sm.createSound(
					angular.extend(basic_sound_options,{
						autoPlay:true,
						multiShot:false,
						id:'start'+episode.id,
						url:episode.sound_start_url,
						onfinish:function(){
							this.destruct();
						}
					})
				);

			} else {
				// start sound already exists
				if ( episode.sound_start.playState != 1){
					episode.sound_start.play();
				}
			}
		}

		$scope.preview_end = function(episode){
			sm.stopAll();
			if(typeof(episode.sound_start) != 'undefined') episode.sound_start.destruct();

			if (typeof(episode.sound_end) == 'undefined'){


				var end_prev_start = new Date(episode.end_obj);
				end_prev_start.setSeconds(end_prev_start.getSeconds() - 5);
				episode.sound_end_url = archiveService.url(end_prev_start, episode.end_obj);

				episode.sound_end = sm.createSound(
					angular.extend(basic_sound_options,{
						autoPlay:true,
						multiShot:false,
						id:'end'+episode.id,
						url:episode.sound_end_url,
						onfinish:function(){
							this.destruct();
						}
					})
				);



			} else {
				//end sound already exists
				if ( episode.sound_end.playState != 1){
					episode.sound_end.play();
				}
			}
		}

		$scope.initSoundPreviews = function(){





		}

	}]);

podcastEditor.value('timezone_offset',<?php echo date_offset_get(new DateTime); ?>); //timezone offset is in seconds
podcastEditor.value('channel_id','<?php echo $channel_id; ?>');

podcastEditor.factory('archiveService', ['$filter', function($filter) {
	return {
		url: function(date, end) {

			return 'http://archive.citr.ca/py-test/archbrad/download?'+
			'archive=%2Fmnt%2Faudio-stor%2Flog'+
			'&startTime='+$filter('date')(date,'dd-MM-yyyy hh:mm:ss')+
			'&endTime='+$filter('date')(end,'dd-MM-yyyy hh:mm:ss');

		}
	};
}]);

podcastEditor.factory('soundManager', function() {

	return {
		sm: function(){

		}
	}
})

</script>

</body>


