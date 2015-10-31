<?php include_once('headers/menu_header.php'); ?>
<html>
	<head>
		<link rel='stylesheet' href='js/bootstrap/bootstrap.min.css'></script>
		<link rel="stylesheet" href="css/style.css" type="text/css">
	</head>
	<body class='wallpaper' ng-app='djland.report'>
		<?php print_menu(); ?>
		<h2>Station Report</h2>

		<div ng-controller='reportController as report'>
			<button ng-click='report.toggle_print(this)' id='print_friendly'>Printer View</button>
			<ul id='filter-bar' class='text-center inline-list'>
				<li>
					Filter By Show: 
					<select ng-model='report.show_filter'>
						<div ng-if="{{list.is_admin}}">
							<option selected value='all'>All Shows</option>
						</div>
						<option ng-repeat='show in report.shows' value='{{show.id}}'>{{show.name}}</option>
					</select>
				</li>
				<li class='side-padded'>
					<div class="dropdown">
						<div ng-controller="datepicker as date">
			        		<button ng-click="date.open($event)" >Change Start Day</button>
			        		<input class="date_picker" type="text" datepicker-popup="yyyy/MM/dd"
				                 ng-model="report.from"  is-open="date.opened"
				                 ng-required="true" close-text="Close" ng-hide="true"
				                 ng-change="date.date_change();" />
			    		</div>
			    	</div>
			    	<div class="col1" >
					        	{{report.from | date:'yyyy/MM/dd'}}
			    	</div>
			    </li>
			    <li class='side-padded'>
			    	<div class="dropdown">
				    	<div ng-controller="datepicker as date">
				        	<button ng-click="date.open($event)" >Change End Day</button>
				        	<input class="date_picker" type="text" datepicker-popup="yyyy/MM/dd"
					                 ng-model="report.to"  is-open="date.opened"
					                 ng-required="true" close-text="Close" ng-hide="true"
					                 ng-change="date.date_change();" />
				    	</div>
			    	</div>
			    	<div class="col1" >
					    {{report.to | date:'yyyy/MM/dd'}}
			    	</div>

				</li>
				<li>
					<button type='button' id='generate' ng-click='report.search()'>Generate Report</button>
				</li>
			</ul>
			<div>
				<div ng-repeat='playsheet in report.playsheets track by $index' reportitem ></div>
			</div>
		</div>

		<script type='text/javascript' src='js/angular.js'></script>
		<script type='text/javascript' src='js/jquery-1.11.3.min.js'></script>
		<script type='text/javascript' src='js/bootstrap/ui-bootstrap-tpls-0.12.0-withseconds.js'></script>
		<script type='text/javascript' src='js/api.js'></script>
		<script type='text/javascript' src='js/utils.js'></script>
		<script type='text/javascript' src='js/report/report.js'></script>

	</body>
</html>