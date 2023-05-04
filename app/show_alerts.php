<?php
	require_once("headers/security_header.php");
	require_once("headers/menu_header.php");
?>
<html>
	<head>
		<meta name=ROBOTS content=\"NOINDEX, NOFOLLOW\">
		<meta charset="utf-8">
		<link rel='stylesheet' href='css/bootstrap.min.css'></script>
		<link rel=stylesheet href='css/style.css' type='text/css'>
		<title>DJLAND | Show Alert Listing</title>
	</head>
	<body class='wallpaper'>
		<?php
			print_menu();
		?>
		<div class='wrapper'>
			<table class='table-hover table'>
				<thead>
					<tr>
						<th>Show Name</th>
						<th style='min-width:180px'>Alert Updated At</th>
						<th>Alert</th>
					</tr>
				</thead>
				<tbody>
				<?php
					$alerts = array();
					$api_base = 'https://'.$_SERVER['HTTP_HOST'];
					$alerts = CallAPI('GET',$api_base.'/api2/public/show/alert');
					foreach($alerts as $alert){
						echo "<tr>";
						foreach($alert as $key=>$field){
							echo $key != 'id' ? "<td>".$field."</td>" : '';
						}
						echo "</tr>";
					}
				?>
				</tbody>
			</table>
		</div>
	</body>
</html>
