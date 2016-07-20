<?php
require_once("headers/security_header.php");
require_once("config.php");
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
		<link rel=stylesheet href='css/style.css' type=text/css>
		<title>Set Socan</title>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
		<script src="js/jquery.form.js"></script>
		<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" />
	  	<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
	  	<script src='js/socan/socan.js'></script>


	</head>
	<body class='wallpaper'>
		<script>
			$(function() {
				$( ".datepicker" ).datepicker({ dateFormat: "yy-mm-dd" });
			});
	  	</script>


		<?php

		print_menu();
		$query="SELECT MAX(idSOCAN) FROM socan";
				$result = mysqli_query($db,$query);
				$row = mysqli_fetch_row($result);
				$num_id = $row[0];
		?>
		<div id='wrapper'>
			<div style='margin-left:15px;'>
				<h1>Set Socan</h1>
				<center>First select a date range:
					<form>
						<label for="from">Start Date: </label>
						<input type="text" id="from" name="from" value="<?php echo $now; ?>"/>

						<label for="to">End Date: </label>
						<input type="text" id="to" name="to" value="<?php echo $twodaysfromnow?>"/>

					</form>

				<button id="createPeriod">Create this SOCAN period</button><span id="loadStatus">&nbsp;</span>
				</center>
				<div id="result">&nbsp;</div>
				<hr><br><center>These are the current SOCAN periods that are set:</center><br>

				<?php
				$request_query="SELECT * FROM socan ORDER BY idSOCAN";
				if($result=mysqli_query($db,$request_query))
				{
					?>
					<table id='socanTable' class='table col1'>
							<tr>
								<th>Socan ID</th>
								<th>Start Time</th>
								<th>End Time</th>
								<th>Delete</th>
							</tr>
							<tr id='rowtemplate' class='invisible'>
								<td id='template_id'>id</td>
								<td id='template_start'>start</td>
								<td id='template_end'>end</td>
								<td id='template_button'><button id='socanDeletetemplate' class='deletePeriod'>Delete Selected Periods</button></td>
							</tr>
					<?php


					//dynamically create a table to show what is in the mySQL database.
					while($row = mysqli_fetch_row($result)){
						$id=$row[0];
						$socanStart=$row[1];
						$socanEnd=$row[2];
						?>
							<tr id='row<?php echo $id; ?>'>
								<td><?php echo $id; ?></td>
								<td><?php echo $socanStart; ?></td>
								<td><?php echo $socanEnd; ?></td>
								<td><button id='socanDelete<?php echo $id; ?>' class='deletePeriod'>Delete this period</button></td>
							</tr>

					<?php } ?>
					</table>
					Note that in order to end on midnight, you must select the next day at 00:00 as it only selects day, and not time!
					<div id='result2'>&nbsp;</div><span id='loadStatus2'>&nbsp;</span>
				<?php
				}
				else{ echo "Retreiving Socan Periods Failed"; } ?>
			</div>
		</div>
	</body>
</html>
