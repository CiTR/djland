<?php
include_once("headers/session_header.php");
require_once("headers/menu_header.php");
//include_once(license_footer.php");
?>
<html>

<head>
	<meta name=ROBOTS content=\"NOINDEX, NOFOLLOW\">
	<link rel=stylesheet href=css/style.css type=text/css>
	<link rel=stylesheet href=css/bootstrap.min.css type=text/css>
	<script src='js/jquery-1.11.3.min.js'></script>

	<title>DJ Land</title>
</head>

<body class='wallpaper'>
	<?php print_menu(); ?>

	
	<h1>User: <?php echo get_username(); ?>
					<br>Logged In
				</h1>
	
				
</body>

</html>