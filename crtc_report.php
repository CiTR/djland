<?php include_once('headers/menu_header.php'); ?>
<html>
	<head>
		<link rel='stylesheet' href='js/bootstrap/bootstrap.min.css'></script>
		<link rel="stylesheet" href="css/style.css" type="text/css">
	</head>
	<body class='wallpaper' ng-app='djland.report'>
		<?php print_menu(); ?>
		<h2>CiTR Radio 101.9 FM Station Report</h2>

		<div ng-controller='reportController as report'>
			<div class='text-center loading' ><img ng-show='report.loading==true' class='rounded' width ='300' height='20' src='images/loading.gif'/></div>

			<div class='print_button'><button ng-click='report.toggle_print()' id='print_friendly'>Print Friendly View</button></div>
			<ul id='filter_bar' class='text-center inline-list'>
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
					<select ng-show='report.is_admin' ng-model='report.type'>
						<option value='crtc'>CRTC Report</option>
						<option value='socan'>Socan Report</option>
						<option value='both'>Combined Report</option>
					</select>
				</li>
				<li>
					<button type='button' id='generate' ng-click='report.report()'>Generate Report</button>
				</li>
			</ul>
			<div class='text-center' ng-hide='report.playsheets.length > 0 || report.loading'>
				No Results
			</div>
			<div class='col1'> Total Spokenword: {{report.totals.spokenword}} minutes</div>
			<div class='col1'>Total Ads: {{report.totals.ads / 60 | number:0}} minutes</div>
			<div class='col1'>
				Total Category 2: {{report.percentage_totals.cancon_2_total}} / {{report.percentage_totals.playitems_2}}
				({{report.percentage_totals.cancon_2_total/report.percentage_totals.playitems_2 > 0 ? report.percentage_totals.cancon_2_total/report.percentage_totals.playitems_2 : 0 | percentage:0}}/35%)
			</div>
			<div class='col1'>
				Total Category 3: {{report.percentage_totals.cancon_3_total}} / {{report.percentage_totals.playitems_3}}
				({{report.percentage_totals.cancon_3_total/report.percentage_totals.playitems_3 > 0 ? report.percentage_totals.cancon_3_total/report.percentage_totals.playitems_3 : 0 | percentage:0}}/12%)
			</div>
			<div class='col1'>
				Total Hits:
				{{report.percentage_totals.hit_total}} / {{report.percentage_totals.playitems}}
				({{report.percentage_totals.hit_total/report.percentage_totals.playitems > 0 ? report.percentage_totals.hit_total/report.percentage_totals.playitems : 0 | percentage:0}}/10% MAX)
			</div>
			<div class='col1'>
				Total Femcon:
				{{report.percentage_totals.femcon_total}} / {{report.percentage_totals.playitems}}
				({{report.percentage_totals.femcon_total/report.percentage_totals.playitems > 0 ? report.percentage_totals.femcon_total/report.percentage_totals.playitems : 0 | percentage:0}}/35%)
			</div>
			<div id='report_summary' class='invisible'>
				<h3>Summary</h3>
				<h4>{{report.from | date:'yyyy/MM/dd'}} - {{report.to | date:'yyyy/MM/dd'}}</h4>


					<table class='table-condensed crtc_report'>
						<tr>
							<th>
								<div ng-if='report.show_names.length > 1'>Show Name</div>
								<div ng-if='report.show_names.length == 1'>Date</div>
							</th>
							<th>Canadian(30)</th>
							<th>Canadian(20)</th>
							<th>Femcon</th>
							<th>Hits</th>
						</tr>

						<tr ng-repeat="item in report.percentages track by $index | orderBy:'item.name'">
							<td>
								<div ng-if='report.show_names.length > 1'>{{item.name}}</div>
								<div ng-if='report.show_names.length == 1'>{{item.date}}</div>

							</td>
							<td ng-class='(item.cancon_3_total/item.playitems_3)*100 >= item.required_cancon || item.playitems_3 == 0 ? "":"red"'>
								{{item.cancon_3_total}} / {{item.playitems_3}}
								({{item.cancon_3_total/item.playitems_3 > 0 ? item.cancon_3_total/item.playitems_3 : 0 | percentage:0}}/{{item.required_cancon}}%)
							</td>
							<td ng-class='(item.cancon_2_total/item.playitems_2)*100 >= item.required_cancon || item.playitems_2 == 0 ? "":"red"'>
								{{item.cancon_2_total}} / {{item.playitems_2}}
								({{item.cancon_2_total/item.playitems_2 > 0 ? item.cancon_2_total/item.playitems_2 : 0 | percentage:0}}/{{item.required_cancon}}%)
							</td>
							<td ng-class='(item.femcon_total/item.total)*100 >= item.required_femcon || item.total == 0 ? "":"red"'>
								{{item.femcon_total}} / {{item.total}}
								({{item.femcon_total/item.total > 0 ? item.femcon_total/item.total : 0 | percentage:0}}/{{item.required_femcon}}%)
							</td>
							<td ng-class='(item.hit_total/item.total)*100 < 10 || item.total == 0 ? "":"red"'>
								{{item.hit_total}} / {{item.total}}
								({{item.hit_total/item.total > 0 ? item.hit_total/item.total : 0 | percentage:0}}/10% MAX)
							</td>
						</tr>

						<tr>
							<td>Total</td>
							<td ng-class='(report.percentage_totals.cancon_3_total/report.percentage_totals.playitems_3*100) >= 35 || report.percentage_totals.playitems_3 == 0 ?"":"red"'>
								{{report.percentage_totals.cancon_3_total}} / {{report.percentage_totals.playitems_3}}
								({{report.percentage_totals.cancon_3_total/report.percentage_totals.playitems_3 > 0 ? report.percentage_totals.cancon_3_total/report.percentage_totals.playitems_3 : 0 | percentage:0}}/35%)
							</td>
							<td ng-class='(report.percentage_totals.cancon_2_total/report.percentage_totals.playitems_2*100) >= 12 || report.percentage_totals.playitems_2 == 0 ?"":"red"'>
								{{report.percentage_totals.cancon_2_total}} / {{report.percentage_totals.playitems_2}}
								({{report.percentage_totals.cancon_2_total/report.percentage_totals.playitems_2 > 0 ? report.percentage_totals.cancon_2_total/report.percentage_totals.playitems_2 : 0 | percentage:0}}/12%)
							</td>
							<td ng-class='(report.percentage_totals.femcon_total/report.percentage_totals.playitems*100) >= 35 || report.percentage_totals.playitems == 0 ?"":"red"'>
								{{report.percentage_totals.femcon_total}} / {{report.percentage_totals.playitems}}
								({{report.percentage_totals.femcon_total/report.percentage_totals.playitems > 0 ? report.percentage_totals.femcon_total/report.percentage_totals.playitems : 0 | percentage:0}}/35%)
							</td>
							<td ng-class='(report.percentage_totals.hit_total/report.percentage_totals.playitems*100) < 10 || report.percentage_totals.playitems == 0 ?"":"red"'>
								{{report.percentage_totals.hit_total}} / {{report.percentage_totals.playitems}}
								({{report.percentage_totals.hit_total/report.percentage_totals.playitems > 0 ? report.percentage_totals.hit_total/report.percentage_totals.playitems : 0 | percentage:0}}/10% MAX)
							</td>
						</tr>
					</table>


			</div>
			<div id='report_list' ng-if='report.is_admin'>
				<div ng-repeat='playsheet in report.playsheets' class='report_item' reportitem ></div>
			</div>
		</div>

		<tr>
		<script type='text/javascript' src='js/angular.js'></script>
		<script type='text/javascript' src='js/jquery-1.11.3.min.js'></script>
		<script type='text/javascript' src='js/bootstrap/ui-bootstrap-tpls-0.12.0-withseconds.js'></script>
		<script type='text/javascript' src='js/api.js'></script>
		<script type='text/javascript' src='js/utils.js'></script>
		<script type='text/javascript' src='js/report/report.js'></script>

	</body>
</html>
