<html>
	<head>

	</head>
	<body ng-app='djland.adScheduler'>
		<div ng-controller='adScheduler as schedule'>
			<h2>Ad Scheduler<h2>
			<h4>Station IDs:</h4>
			<select>
				<option ng-repeat='item in schedule.stationIDs | orderBy:["title","artist"]'>{{item.title ? item.title : item.artist}}</option>
			</select>
			<h4>Ads:</h4>
			<select>
				<option ng-repeat='item in schedule.ads | orderBy:["title","artist"]'>{{item.title ? item.title : item.artist}}</option>
			</select>
			<h4>PSAs:</h4>
			<select>
				<option ng-repeat='item in schedule.PSAs | orderBy:["title","artist"]'>{{item.title ? item.title : item.artist}}</option>
			</select>
		</div>
		<script type='text/javascript' src='js/angular.js'></script>
		<script type='text/javascript' src='js/jquery-1.11.3.min.js'></script>
		<script type='text/javascript' src='js/api.js'></script>
		<script type='text/javascript' src='js/utils.js'></script>
		<script type='text/javascript' src='js/ads/scheduler.js'></script>
	</body>
</html>