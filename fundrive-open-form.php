<?php

	include_once("headers/session_header.php");
	require_once("headers/security_header.php");
	require_once("headers/function_header.php");
	require_once("headers/menu_header.php");

?>

<!DOCTYPE html>
<html ng-app='djland.open_fundrive'>
<head>
<meta name=ROBOTS content=\"NOINDEX, NOFOLLOW\">
<link rel=stylesheet href=css/style.css type=text/css>

	<title>Fundrive Form Editing | DJLand</title>
</head>
<body ng-controller='openFundrive as fundrive' class='wallpaper'>
	<?php print_menu() ?>
	<div class='wrapper clean-list'>
		<table class='table-condensed open_fundrive' ng-repeat='form in fundrive.forms track by $index'>
			<tr><th>Firstname<th>Lastname<th>Donation Amount<th></tr>
			<tr> 
				<td>{{form.firstname}}</td> 
				<td>{{form.lastname}}</td> 
				<td>{{form.donation_amount}}</td> 
				<td><button onclick='go(this)' data-href='fundrive-form.php?id={{form.id}}'>Open Form</button></td>
			</tr>
		</table>
	</div>
	<script type='text/javascript' src="js/jquery-ui/external/jquery/jquery.js"></script>
	<script type='text/javascript' src="js/angular.js"></script>
	<script type='text/javascript' src='js/bootstrap/bootstrap.js'></script>
	<script src='js/fundrive/open.js'></script>
	<script type='text/javascript' src='js/api.js'></script>

</body>
</html>