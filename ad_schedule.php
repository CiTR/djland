<?php include_once('headers/menu_header.php'); ?>
<html>
	<head>
		<link rel='stylesheet' href='js/bootstrap/bootstrap.min.css'></script>
		<link rel="stylesheet" href="css/style.css" type="text/css">
	</head>
	<body ng-app='djland.adScheduler' class='wallpaper'>
		<?php print_menu(); ?>
		<div ng-controller='adScheduler as schedule'>
			<h1>Ad Scheduler</h1>
			<ul class='list-unstyled schedule'>
				<li ng-repeat='(unix,show) in schedule.showtimes' value='{{unix}}'>
					<h2 class='text-left'>{{show.name}}</h2>
					<h3 class='text-left'>{{show.date}}</h3>
					<!-- Template Table -->
					<div class='template'>
						<table class='table-condensed'>
							<tr id='{{unix}}'>
								<td>
									<input value='{{show.start}}'></input>
								</td>
								<td>
									<select ng-model='show_ad.type'>
										<option value="">Announcement</option>
										<option value='ad'>Ad</option>
										<option value='psa'>PSA</option>
										<option value='promo'>Show Promo</option>
										<option value='ID'>Station ID</option>
									</select>
								</td>
							</tr>

						</table>
						<button type='button' class='insert_ad'>Insert Additional Ad</button> 
					</div>
					
					<!-- Ad Table -->
					<div class='double-padded-top'>
						<table class='table-condensed'>
							<tr ng-repeat='show_ad in show.ads | orderBy:"time"'>					
								<td><input ng-model='show_ad.time'></td>
								<td>
									<select ng-model='show_ad.type'>
										<option value="announcement">Announcement</option>
										<option ng-selected='show_ad.type.toLowerCase().indexOf("ad") > -1' value='ad'>Ad</option>
										<option ng-selected='show_ad.type.toLowerCase().indexOf("psa") > -1' value='psa'>PSA</option>
										<option ng-selected='show_ad.type.toLowerCase().indexOf("promo") > -1' value='promo'>Show Promo</option>
										<option ng-selected='show_ad.type.toLowerCase().indexOf("id") > -1' value='ID'>Station ID</option>
									</select>
								</td>
								<td>
									<div ng-if="show_ad.type.toLowerCase().indexOf('ad') > -1">
										<select ng-model='show_ad.name'>
											<option ng-selected='schedule.ads.indexOf(show_ad.name) < 0' value='Any Ad'>Any Ad</option>
											<option ng-selected='show_ad.name == item.ID' ng-repeat='item in schedule.ads | orderBy:["title","artist"]' value='{{item.ID}}'>{{item.title ? item.title : item.artist}}</option>
										</select>
									</div>
									<div ng-if="show_ad.type.toLowerCase().indexOf('psa') > -1">
										<select ng-model='show_ad.name'>
											<option ng-selected='schedule.PSAs.indexOf(show_ad.name) < 0' value='Any PSA'>Any PSA</option>
											<option ng-selected='show_ad.name == item.ID' ng-repeat='item in schedule.PSAs | orderBy:["title","artist"]' value='{{item.ID}}'>{{item.title ? item.title : item.artist}}</option>
										</select>
									</div>
									<div ng-if="show_ad.type.toLowerCase().indexOf('promo') > -1">
										<select ng-model='show_ad.name'>
											<option ng-selected='schedule.promos.indexOf(show_ad.name) < 0' value='Any Show Promo'>Any Show Promo</option>
											<option ng-selected='show_ad.name == item.ID' ng-repeat='item in schedule.promos | orderBy:["title","artist"]' value='{{item.ID}}'>{{item.title ? item.title : item.artist}}</option>
										</select>
									</div>
									<div ng-if="show_ad.type.toLowerCase().indexOf('station id') > -1">
										<select ng-model='show_ad.name'>
											<option ng-selected='schedule.promos.indexOf(show_ad.name) < 0' value='Any Show Promo'>You are listening to CiTR Radio 101.9FM, broadcasting from unceded Musqueam territory in Vancouver</option>
											<option ng-selected='show_ad.name == item.ID' ng-repeat='item in schedule.promos | orderBy:["title","artist"]' value='{{item.ID}}'>{{item.title ? item.title : item.artist}}</option>
										</select>
									</div>
									<div ng-if="show_ad.type.toLowerCase().indexOf('announcement') > -1">
										<input class='fullinput' ng-model='show_ad.name' value="Announce Upcoming Program"></input>
									</div>
									<div ng-if="show_ad.type == ''">
										<input class='fullinput' ng-model='show_ad.name'></input>
									</div>
								</td>
								<td><button type='button' class='delete'>Remove</button><td>

							</tr>
						</table>
					</div>

				</li>
			</ul>
		</div>
		<script type='text/javascript' src='js/angular.js'></script>
		<script type='text/javascript' src='js/jquery-1.11.3.min.js'></script>
		<script type='text/javascript' src='js/api.js'></script>
		<script type='text/javascript' src='js/utils.js'></script>
		<script type='text/javascript' src='js/ads/scheduler.js'></script>
	</body>
</html>