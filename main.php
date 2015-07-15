<?php
//session_start();
require_once("headers/menu_header.php");
print_r($_SESSION);
?>
<html>
	<head>
		<meta name=ROBOTS content=\"NOINDEX, NOFOLLOW\">
		<link rel=stylesheet href=css/style.css type=text/css>
		<script src='js/jquery-1.4.2.js'></script>
		
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
		</table>
	</body>
</html>