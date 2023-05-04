<html ng-app="djland.uploads">
	<?php
	include_once("headers/session_header.php");
	require_once("headers/security_header.php");
	require_once("headers/menu_header.php");
	?>
	<head>
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/style.css" type="text/css">

	</head>
	<script type="text/javascript" src="js/angular.js"></script>
	<script type="text/javascript" src="js/angular/sortable.js"></script>
	<script type="text/javascript" src="js/jquery-1.11.3.min.js"></script>
	<script type="text/javascript" src="js/bootstrap/bootstrap.js"></script>
	<script type="text/javascript" src="js/api.js"></script>
	<script type="text/javascript" src="js/uploads/app.js"></script>
	<body class="wallpaper" ng-controller="UploadController as uc">
		<?php print_menu(); ?>
		<div class="wrapper">
			<table class="table table-striped">
				<thead>
					<tr>
						<th>ID</th>
						<th>File Name</th>
						<th>File Type</th>
						<th>Category</th>
						<th>Description</th>
						<th>URL</th>
					</tr>
				</thead>
				<tbody>
					<tr ng-repeat="upload in uc.uploads" >
						<td>{{upload.id}}</td>
						<td>{{upload.file_name}}</td>
						<td>{{upload.file_type}}</td>
						<td>{{upload.category}}</td>
						<td>{{upload.description}}</td>
						<td>{{upload.url}}</td>
					</td>
				</tbody>
			</table>
		</div>
	</body>
</html>
