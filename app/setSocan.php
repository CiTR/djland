<?php
require_once("headers/security_header.php");
require_once(dirname($_SERVER['DOCUMENT_ROOT']).'/config.php');
require_once("headers/menu_header.php");
require_once("headers/socan_header.php");

if( permission_level() < $djland_permission_levels['staff']['level']){
	header("Location: main.php");
}
$now = date("m/d/Y",strtotime('now'));
$twodaysfromnow  = date("m/d/Y", mktime(0, 0, 0, date("m"), date("d")+2, date("Y")));

?>
<html>
	<head>
		<meta name=ROBOTS content=\"NOINDEX, NOFOLLOW\">
		<link href="css/jquery.dataTables.min.css" rel="stylesheet" />
		<link rel=stylesheet href='css/style.css' type=text/css>
		<title>Set Socan</title>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
		<script src="js/jquery.form.js"></script>
		<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" />
	  	<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
	  	<script src='js/socan/socan.js'></script>
		<script src="js/jquery.dataTables.min.js"></script>


	</head>
	<body class='wallpaper'>
		<script>
			$(function() {
				$( ".datepicker" ).datepicker({ dateFormat: "yy-mm-dd" });
			});
	  	</script>


		<?php print_menu(); ?>

		<div id='wrapper'>
			<div style='margin-left:15px;'>
				<h1>Set Socan</h1>
				<center>First select a date range:
					<form>
						<label for="from">Start Date: </label>
						<input type="text" id="from" name="from" />

						<label for="to">End Date: </label>
						<input type="text" id="to" name="to" />

					</form>

				<button id="createPeriod">Create this SOCAN period</button><span id="loadStatus">&nbsp;</span>
				</center>
				<div id="result">&nbsp;</div>
				<hr><br><center>These are the current SOCAN periods that are set:</center><br>

				<?php

				$api_base = 'http://'.$_SERVER['HTTP_HOST'];
				$socanPeriods = CallAPI('GET',$api_base.'/api2/public/socan');
				if(count($socanPeriods) != 0)
				{
					?>
					<div class='center'>
						<table id='socanTable' class='table'>
							<thead>
								<tr>
									<th>Socan ID</th>
									<th>Start Time</th>
									<th>End Time</th>
									<th>Delete</th>
								</tr>
							</thead>
							<tbody>
								<tr id='rowtemplate' class='invisible'>
									<td id='template_id'>id</td>
									<td id='template_start'>start</td>
									<td id='template_end'>end</td>
									<td id='template_button'><button id='socanDeletetemplate' class='deletePeriod'>Delete Selected Periods</button></td>
								</tr>

							<?php
								foreach($socanPeriods as $key => $socanPeriod){
									$id=$socanPeriod->idSocan;
									$socanStart=$socanPeriod->socanStart;
									$socanEnd=$socanPeriod->socanEnd;

									echo("<tr id='row".$id."'>");
									echo("<td>".$id."</td>");
									echo("<td>".$socanStart."</td>");
									echo("<td>".$socanEnd."</td>");
									echo("<td><button id='socanDelete".$id."' class='deletePeriod'>Delete this period</button></td>");
									echo("</tr>");
								}
								?>
							</tbody>
						</table>

					<br>
					Note that in order to end on midnight, you must select the next day at 00:00 as it only selects day, and not time!
					
					</div>

					<div id='result2'>&nbsp;</div><span id='loadStatus2'>&nbsp;</span>
				<?php
				}
				else{ echo "Retreiving Socan Periods Failed or you have no Socan Periods Scheduled."; } ?>
			</div>
		</div>
	</body>
</html>
