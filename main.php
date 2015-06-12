<?php
session_start();

require_once("headers/security_header.php");
require_once("headers/menu_header.php");
?>


<html>
	<head>
		<meta name=ROBOTS content=\"NOINDEX, NOFOLLOW\">
		<link rel=stylesheet href=css/style.css type=text/css>
		<title>DJ Land</title>
	</head>
	<body class='wallpaper'>
		<?php print_menu(); ?>
		<table width=100%% height=100%%>
			<tr>
				<td align=center>
					<h1>
						User: <?php echo get_username(); ?>
						<br>Logged In
						</h1>
				</td>
			</tr>
		</table>");
	</body>
</html>