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
			<button id='ad_schedule_save' type='button' ng-click='schedule.save()'>Save Ad Schedule</button></h4>
			<div class='text-center loading' ><img ng-show='schedule.loading==true' class='rounded' width ='300' height='20' src='images/loading.gif'/></div>	
			<div id='ad_schedule_wrapper' class='scrolly' scrolly='!schedule.loading ? schedule.load() : ""'>
				<ul class='list-unstyled schedule' >
					<li class='show' ng-repeat='show in schedule.showtimes track by $index | orderBy:"start_unix"'>

						<h3 class='text-left'>{{show.name}}</h3>
						<h4 class='text-left'>{{show.date}}</h4>
						<!-- Template Table -->
						<div id='unix_{{$index}}' unix='{{show.start_unix}}' class='template'>
							<table class='table-condensed'>
								<tr>
									<td>
										<input id='ad_time_{{$index}}' value='{{show.start}}'></input>
									</td>
									<td>
										<select id='ad_type_{{$index}}' ng-model='show_ad.type'>
											<option ng-selected='true' value="announcement">Announcement</option>
											<option value='ad'>Ad</option>
											<option value='psa'>PSA</option>
											<option value='timely'>Timely PSA</option>
											<option value='ubc'>UBC PSA</option>
											<option value='community'>Community PSA</option>
											<option value='promo'>Show Promo</option>
											<option value='id'>Station ID</option>
										</select>
									</td>
								</tr>

							</table>
							<button type='button' ng-click="schedule.add($index)" class='insert_ad'>Insert Additional Ad</button> 
						</div>
						
						<!-- Ad Table -->
						<div class='double-padded-top'>
							<table class='table-condensed'>
								<tr ng-repeat='show_ad in show.ads track by $index | orderBy:"num"'>				
									<td><input ng-model='show_ad.time'></td>
									<td>
										<select ng-model='show_ad.type'>
											<option value="announcement">Announcement</option>
											<option value='ad'>Ad</option>
											<option value='psa'>PSA</option>
											<option value='timely'>Timely PSA</option>
											<option value='ubc'>UBC PSA</option>
											<option value='community'>Community PSA</option>
											<option value='promo'>Show Promo</option>
											<option value='id'>Station ID</option>
										</select>
									</td>
									<td>
										<div ng-if="show_ad.type == 'ad'">
											<!-- Begin Ad Selector -->
											<select ng-model='select[show][show_ad]' ng-change='schedule.update(select[show][show_ad],show_ad)'>
												<option ng-selected='schedule.ads.indexOf(show_sam_id) < 0' value='Any Ad'>Any Ad</option>
												<option ng-selected='show_ad.sam_id == item.ID' ng-repeat='item in schedule.ads | orderBy:["-date_added"]' value='{{item}}'>{{item.title ? item.title : item.artist}}</option>
											</select>
											<!-- End Ad Selector -->
										</div>
													
										<div ng-if="show_ad.type == 'psa'">
											<!-- Begin Combined PSA Selector -->
											<select ng-model='select[show][show_ad]' ng-change='schedule.update(select[show][show_ad],show_ad)'>
												<option ng-selected='schedule.PSAs.indexOf(show_ad.sam_id) < 0' value='Any PSA'>Any PSA</option>
												<option ng-selected='show_ad.sam_id == item.ID' ng-repeat='item in schedule.PSAs | orderBy:["title","artist"]' value='{{item}}'>{{item.title ? item.title : item.artist}}</option>
											</select>
											<!-- End Combined PSA Selector -->
										</div>
										<div ng-if="show_ad.type == 'timely'">
											<!-- Begin Timeply PSA Selector -->
											<select ng-model='select[show][show_ad]' ng-change='schedule.update(select[show][show_ad],show_ad)'>
												<option ng-selected='schedule.timelyPSAs.indexOf(show_ad.sam_id) < 0' value='Any Show Promo'>Any Timely PSA</option>
												<option ng-selected='show_ad.sam_id == item.ID' ng-repeat='item in schedule.timelyPSAs | orderBy:["title","artist"]' value='{{item}}'>{{item.title ? item.title : item.artist}}</option>
											</select>
											<!-- End Timely PSA Selector -->
										</div>
																					
										<div ng-if="show_ad.type == 'ubc'">
											<!-- Begin UBC PSA Selector -->
											<select ng-model='select[show][show_ad]' ng-change='schedule.update(select[show][show_ad],(show_ad'>
												<option ng-selected='schedule.UBCPSAs.indexOf(show_ad.sam_id) < 0' value='Any Show Promo'>Any UBC PSA</option>
												<option ng-selected='show_ad.sam_id == item.ID' ng-repeat='item in schedule.UBCPSAs | orderBy:["title","artist"]' value='{{item}}'>{{item.title ? item.title : item.artist}}</option>
											</select>
											<!-- End UBC PSA Selector -->
										</div>
																				
										<div ng-if="show_ad.type == 'community'">
											<!-- Begin Community PSA Selector -->
											<select ng-model='select[show][show_ad]' ng-change='schedule.update(select[show][show_ad],show_ad)'>
												<option ng-selected='schedule.communityPSAs.indexOf(show_ad.sam_id) < 0' value='Any Community PSA'>Any Community PSA</option>
												<option ng-selected='show_ad.sam_id == item.ID' ng-repeat='item in schedule.communityPSAs | orderBy:["title","artist"]' value='{{item}}'>{{item.title ? item.title : item.artist}}</option>
											</select>
											<!-- End Community PSA Selector -->
										</div>
										
																				
										<div ng-if="show_ad.type == 'promo'">
											<!-- Begin Promo Selector -->
											<select ng-model='select[show][show_ad]' ng-change='schedule.update(select[show][show_ad],show_ad)'>
												<option ng-selected='schedule.promos.indexOf(show_ad.sam_id) < 0' value='Any Show Promo'>Any Show Promo</option>
												<option ng-selected='show_ad.sam_id == item.ID' ng-repeat='item in schedule.promos | orderBy:["title","artist"]' value='{{item}}'>{{item.title ? item.title : item.artist}}</option>
											</select>
											<!-- End Promo Selector -->
										</div>			
										
										<div ng-if="show_ad.type == 'id'">
											<!-- Begin Station ID Selector -->
											<select ng-model='select[show][show_ad]' ng-change='schedule.update(select[show][show_ad],show_ad)'>
												<option ng-selected='schedule.promos.indexOf(show_ad.sam_id) < 0' value='Any Station ID'>You are listening to CiTR Radio 101.9FM, broadcasting from unceded Musqueam territory in Vancouver</option>
												<option ng-selected='show_ad.sam_id == item.ID' ng-repeat='item in schedule.stationIDs | orderBy:["title","artist"]' value='{{item}}'>{{item.title ? item.title : item.artist}}</option>
											</select>
											<!-- End Station ID Selector -->
										</div>
																				
										<div ng-if="show_ad.type == 'announcement'">
											<!-- Begin Announcement -->
											<input class='fullinput' ng-model='show_ad.name' value="Announce Upcoming Program"></input>
											<!-- End Announcement -->
										</div>
																				
										<div ng-if="show_ad.type == ''">
											<!-- Begin Default -->
											<input class='fullinput' ng-model='show_ad.name'></input>
											<!-- End Default -->
										</div>
										
									</td>
									<td><button type='button' class='delete' ng-click='schedule.remove(show,show_ad)'>Remove</button><td>

								</tr>
							</table>
						</div>

					</li>
				</ul>
			</div>
		</div>
		<script type='text/javascript' src='js/angular.js'></script>
		<script type='text/javascript' src='js/jquery-1.11.3.min.js'></script>
		<script type='text/javascript' src='js/api.js'></script>
		<script type='text/javascript' src='js/utils.js'></script>

		<script type='text/javascript' src='js/ads/scheduler.js'></script>

	</body>
</html>