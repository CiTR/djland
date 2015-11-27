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
			<h2>Special Broadcast Schedule Overrides</h2>
			<button class='center' id='save_broadcasts' type='button' ng-click='broadcasts.save()'>Save</button>
			<ul id='broadcasts' class='clean-list'>
				<li ng-repeat='(id,broadcast) in broadcasts.list track by $index'>
					<hr/>
					<h3 class='text-left'>{{broadcast.name}}</h3>
					
					<div class='col1'><input ng-model='broadcast.name' placeholder='name'><button type='button' ng-click='broadcasts.delete($index)'>Delete This Broadcast</button></div>
					<div class='broadcast_info'>
						<!-- Date Code -->
						<div class='col1 double-padded-top'>
							<div ng-controller='datepicker as date' class='col2'>
								<div class='col1'> Start: {{broadcasts.times[$index].start_time | date:'yyyy/MM/dd HH:mm:ss'}} </div>
								<input class="date_picker" type="text" datepicker-popup="yyyy/MM/dd HH:mm:ss"
                               ng-model="broadcasts.times[$index].start_time"  is-open="date.opened" ng-hide='true'
                               ng-required="true" close-text="Close" ng-onload='date.dt = broadcast.start'
                               ng-change="date.update(broadcast.start);" />
                            	<button ng-click="date.open($event)" >Change Date</button>
                            	h:<select ng-model="broadcasts.times[$index].start_hour" ng-options="n for n in [] | range:0:24"
                                      ng-change="broadcasts.updateStart($index)"></select>
                            	m:<select ng-model="broadcasts.times[$index].start_minute" ng-options="n for n in [] | range:0:60"
                                      ng-change="broadcasts.updateStart($index)"></select>
                            	s:<select ng-model="broadcasts.times[$index].start_second" ng-options="n for n in [] | range:0:60"
                                      ng-change="broadcasts.updateStart($index)"></select>
							</div>
							<div ng-controller='datepicker as date' class='col2'>
								<div class='col1'> End: {{broadcasts.times[$index].end_time | date:'yyyy/MM/dd HH:mm:ss'}} </div>
								<input class="date_picker" type="text" datepicker-popup="yyyy/MM/dd HH:mm:ss"
                               ng-model="broadcasts.times[$index].end_time"  is-open="date.opened" ng-hide='true'
                               ng-required="true" close-text="Close" 
                               ng-change="date.date_change();" />
                            	<button ng-click="date.open($event)" >Change Date</button>
                            	h:<select ng-model="broadcasts.times[$index].end_hour" ng-options="n for n in [] | range:0:24"
                                      ng-change="broadcasts.updateEnd($index)"></select>
                            	m:<select ng-model="broadcasts.times[$index].end_minute" ng-options="n for n in [] | range:0:60"
                                      ng-change="broadcasts.updateEnd($index)"></select>
                            	s:<select ng-model="broadcasts.times[$index].end_second" ng-options="n for n in [] | range:0:60"
                                      ng-change="broadcasts.updateEnd($index)"></select>
							</div>
						</div>
						<div class='col1 double-padded-top'>
							<!--Show and Description -->
							<div>
								<label>Show:</label>
								<select ng-model="broadcast.show_id" class='show_select'>
								    <option ng-selected='broadcast.show_id == show.id' ng-repeat="show in broadcasts.shows | orderBy:'name'" value="{{show.id}}">{{show.name}}</option> 
								</select>
							</div>
							<div>
								<label>Description:</label><textarea ng-model="broadcast.description" rows="10"></textarea>
							</div>
						</div>
					</div>
					<div class='image_upload'>
						<!-- Image uploading -->
						<div class='right'>
							<div class='left col1'>                   
				                <input class='file{{broadcast.id}} left' type="file" ng-model-instant/>
				                <button type="button" class='left' ng-click="broadcasts.imageUpload(broadcast.id,broadcast.name)">Upload</button>
			                </div>
			                <div class='broadcast_image'>
	                    		<img src="{{broadcast.image}}" alt='Image'/>
	                		</div>
	                	</div>
            		</div>
	            	
				</li>
			</ul>
			<div class='col1 text-center double-padded-top grey'>
				<button type='button' ng-click='broadcasts.add()'>Add a Broadcast</button>
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