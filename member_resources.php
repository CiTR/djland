<?php

include_once("headers/session_header.php");
require_once("headers/security_header.php");
require_once("headers/functions.php");
require_once("headers/menu_header.php");


error_reporting(E_ALL);
?>

<html>
	<head>
		<meta name=ROBOTS content="NOINDEX, NOFOLLOW">
		<base href='shows.php'>
		<link rel="stylesheet" href="css/style.css" type="text/css">
		<title>DJLAND | Resources</title>
	</head>

	<body class='wallpaper' ng-app='djland.resources'>

	<?php print_menu(); ?>
	<div ng-controller='resourceController as rc'>
		{{ rc.resources }}
		<ul >
			<li ng-repeat='(id,resource) in rc.resources["general"]'>
				<input ng-model='resource.id'/>
				<input ng-model='resource.blurb'/>
				<input ng-model='resource.url'/>
				<input ng-model='resource.url_text'/>
				<input ng-model='resource.type'/>
			</li>
		</ul>
		<ul >
			<li ng-repeat='(id,resource) in rc.resources["programming"]'>
				<input ng-model='resource.id'/>
				<input ng-model='resource.blurb'/>
				<input ng-model='resource.url'/>
				<input ng-model='resource.url_text'/>
				<input ng-model='resource.type'/>
			</li>
		</ul>
		<ul >
			<li ng-repeat='(id,resource) in rc.resources["training"]'>
				<input ng-model='resource.id'/>
				<input ng-model='resource.blurb'/>
				<input ng-model='resource.url'/>
				<input ng-model='resource.url_text'/>
				<input ng-model='resource.type'/>
			</li>
		</ul>
	</div>

		<script src="js/jquery-1.11.3.min.js"></script>
		<script src="js/angular.js"></script>
		<script src="js/api.js"></script>
		<script src="js/resources/resources.js"></script>
	</body>
</html>
