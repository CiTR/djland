<?php
include_once("headers/session_header.php");
require_once("headers/security_header.php");
require_once("config.php");
//require_once("headers/function_header.php");
require_once("headers/menu_header.php");
require_once("headers/socan_header.php");


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
 
  <script>
  $(function() {
    $( ".datepicker" ).datepicker({ dateFormat: "yy-mm-dd" });
  });
  </script>
</head>
<body class='wallpaper'>


<?php

print_menu();

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
	
		<button id="submitDates">Create this SOCAN period</button><span id="loadStatus">&nbsp;</span>
		</center>
		<div id="result">&nbsp;</div>
	
		
		<?php	
		$query="SELECT MAX(idSOCAN) FROM socan";
		$result = mysqli_query($db,$query);
		$row = mysqli_fetch_row($result);
		$num_id = $row[0];
	
	
		?>
		<hr><br><center>These are the current SOCAN periods that are set:</center><br>

		<?php
		$request_query="SELECT * FROM socan ORDER BY idSOCAN";
		if($result=mysqli_query($db,$request_query))
		{
			?>
			<table class='table col1'>
					<tr>
						<th>Socan ID</th>
						<th>Start Time</th>
						<th>End Time</th>
						<th>Delete</th>
					</tr>
					<tr id='rowtemplate' class='invisible'>
						<td>id</td>
						<td>start</td>
						<td>end</td>
						<td><button id='socanDeletetemplate' class='socanButton'>Delete Selected Periods</button></td>
					</tr>
			<?php
			
			
			//dynamically create a table to show what is in the mySQL database.	
			while($row = mysqli_fetch_row($result)){
				$id=$row[0];
				$socanStart=$row[1];
				$socanEnd=$row[2];
				?>
					<tr>
						<td><?php echo $id; ?></td>
						<td><?php echo $socanStart; ?></td>
						<td><?php echo $socanEnd; ?></td>
						<td><button id='socanDelete<?php echo $id; ?></td>' class='socanButton'>Delete this period</button></td>
					</tr>

					<?php
			}
			echo "</table>";
			echo "Note that in order to end on midnight, you must select the next day at 00:00 as it only selects day, and not time!";
			echo "<div id='result2'>&nbsp;</div><span id='loadStatus2'>&nbsp;</span>";
		}
		else{ echo "Retreiving Socan Periods Failed"; }
	echo "</div></div>";
?>