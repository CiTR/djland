<html ng-app='djland.specialbroadcasts'>
	<?php require_once("headers/menu_header.php"); ?>
	<head>
		<link rel='stylesheet' href='js/bootstrap/bootstrap.min.css'></script>
		<link rel="stylesheet" href="css/style.css" type="text/css">
	</head>
	<body class='wallpaper' ng-controller='specialbroadcastController as broadcasts'>
		<?php print_menu(); ?>
		<div class='grey loadingbar_container'>
			<div class='text-center' ng-show='broadcasts.loading'><img class='rounded' width ='300' height='20' src='images/loading.gif'/></div>
		</div>
		<div class='grey wrapper'>
			<h2>Special Broadcasts</h2>
			<button class='center' id='save_broadcasts' type='button' ng-click='broadcasts.save()'>Save</button>
			<ul id='broadcasts' class='clean-list'>
				<li ng-repeat='(id,broadcast) in broadcasts.list track by $index'>
					<hr/>
					<h3 class='text-left'>{{broadcast.name}}</h3>
					
					<div class='col1'><input ng-model='broadcast.name' placeholder='name'><button type='button' ng-click='friends.delete($index)'>Delete This Friend</button></div>
					
					

					<div class='friend_info'>
						<div class='col1 double-padded-top'>
							<div ng-controller='datepicker as date' class='col2'>
								<input class="date_picker" type="text" datepicker-popup="yyyy/MM/dd HH:mm:ss"
                               ng-model="broadcast.start"  is-open="date.opened"
                               ng-required="true" close-text="Close" ng-onload='date.dt = broadcast.start'
                               ng-change="date.update(broadcast.start);" />
                            	<button ng-click="date.open($event)" >Change Date</button>
                            	h:<select ng-model="date.start_hour" ng-options="n for n in [] | range:0:24"
                                      ng-change="date.setHour({'date':broadcast.start})"></select>
                            	m:<select ng-model="broadcast.start_minute" ng-options="n for n in [] | range:0:60"
                                      ng-change="list.updateStart()"></select>
                            	s:<select ng-model="broadcast.start_second" ng-options="n for n in [] | range:0:60"
                                      ng-change="list.updateStart()"></select>
							</div>
							<div ng-controller='datepicker as date' class='col2'>
								<input class="date_picker" type="text" datepicker-popup="yyyy/MM/dd HH:mm:ss"
                               ng-model="broadcast.end"  is-open="date.opened"
                               ng-required="true" close-text="Close" 
                               ng-change="date.date_change();" />
                            	<button ng-click="date.open($event)" >Change Date</button>
							</div>
							
						</div>
						<div class='col1 double-padded-top'>
							<div class='col2'>Name<input class='padded-left' ng-model='broadcast.address' placeholder='address'></div>
							<div class='col2'>Website<input class='padded-left' ng-model='broadcast.website' placeholder='website'></div>
						</div>
						<div class='col1 double-padded-top'>
							<div class='col2'>Phone<input class='padded-left' ng-model='broadcast.phone' placeholder='phone'></div>
							<div class='col2'>Discount<input class='padded-left' ng-model='broadcast.discount' placeholder='discount'></div>
						</div>
					</div>
					<div class='left double-padded-top'>                   
		                <input class='file{{broadcast.id}}' type="file" ng-model-instant/>
		                <button type="button" ng-click="friends.imageUpload(broadcast.id,broadcast.name)">Upload</button>
	                </div>
	                <div class='friend_image left'>
                    	<img src="{{broadcast.image_url}}" alt='Image'/>
                	</div>
				</li>
			</ul>
			<div class='col1 text-center double-padded-top grey'>
				<button type='button' ng-click='broadcasts.add()'>Add a friend</button>
			</div>
		</div>
		<script type='text/javascript' src='js/angular.js'></script>
		<script type='text/javascript' src='js/jquery-1.11.3.min.js'></script>
	    <script type='text/javascript' src='js/bootstrap/ui-bootstrap-tpls-0.12.0-withseconds.js'></script>
		<script type='text/javascript' src='js/api.js'></script>
		<script type='text/javascript' src='js/utils.js'></script>

		<script type='text/javascript' src='js/specialbroadcasts/app.js'></script>
	</body>

</html>