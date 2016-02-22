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
	<div class='wrapper'>
		<div class='col2'>
			<button onclick="location.href='fundrive-dump-stats.php';">Fundrive Data Download (.csv)</button>
		</div>
		<div class='col2'>
			<h2>Fundrive Pledge Total: ${{fundrive.donationTotal}}</h2>
		</div>
	</div>
	<br><br>
	<div class='wrapper clean-list'>
		<div class='side-padded padded double-padded-bottom'>
			<table class='table-condensed open_fundrive' >
				<tr><th>Firstname<th>Lastname<th>Donation Amount<th></tr>
				<tr>
					<td colspan="4"> <hr /> </td>
	 			</tr>
				<?php if(permission_level() >= $djland_permission_levels['administrator']['level'] ): ?>
				<tr ng-repeat='form in fundrive.forms track by $index'>
					<td>{{form.firstname}}</td>
					<td>{{form.lastname}}</td>
					<td>{{form.donation_amount}}</td>
					<td><button onclick='go(this)' data-href='fundrive-form.php?id={{form.id}}'>Open Form</button></td>
				</tr>
			<?php else: ?>
				<tr> <td> <h3>PERMISSION DENIED</h3> </td></tr>
				<td> </td>
				<td> </td>
				<td> </td>
			<?php endif; ?>
			</table>
		</div>
	</div>
	<script type='text/javascript' src="js/jquery-ui/external/jquery/jquery.js"></script>
	<script type='text/javascript' src="js/angular.js"></script>
	<script type='text/javascript' src='js/bootstrap/bootstrap.js'></script>
	<script src='js/fundrive/open.js'></script>
	<script type='text/javascript' src='js/api.js'></script>

</body>
</html>
