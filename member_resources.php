<?php

include_once("headers/session_header.php");
require_once("headers/security_header.php");
require_once("headers/menu_header.php");


?>

<html ng-app='memberResources'>
	<head>
		<meta name=ROBOTS content="NOINDEX, NOFOLLOW">
		<base href='shows.php'>
		<link rel="stylesheet" href="css/style.css" type="text/css">
		<title>DJLAND | Resources</title>
	</head>

	<body class='wallpaper' ng-controller='memberResourcesController as resources'>

	<?php print_menu(); ?>

		<div id="member_resources" class="center">
			<p><strong>Member resources:</strong></p>
			<div ng-bind-html='resources.htmlcontent.trusted'></div>
		</div>
		<?php if(permission_level() >= $djland_permission_levels['staff']['level']): ?>
		<button type='button' class='save_button' ng-click='resources.save()'>Save</button>
		<textarea id='resources_textarea' class='member_resources_edit' ng-model='resources.htmlcontent.html'></textarea>
		<?php endif; ?>
		<script type='text/javascript' src="js/jquery-ui/external/jquery/jquery.js"></script>
		<script type='text/javascript' src="js/angular.js"></script>
		<script type='text/javascript' src='js/bootstrap/bootstrap.js'></script>
		<script type='text/javascript' src='js/resources/app.js'></script>
		<script type='text/javascript' src='js/api.js'></script>
	</body>
</html>