<?php
	session_start();
	require("headers/security_header.php");
	require("headers/function_header.php");
	require("headers/menu_header.php");
?>
<html>
	<head>
		<meta name=ROBOTS content=\"NOINDEX, NOFOLLOW\">
		<meta charset="utf-8">
		<link rel=stylesheet href='css/style.css' type='text/css'>

		<title>DJLAND | Membership</title>

		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
		<script src="js/jquery.form.js"></script> 
		<script type='text/javascript' src='js/membership.js'></script>
		<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" />
		<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
		<script>
			$(function() {
			$( ".datepicker" ).datepicker({ dateFormat: "yy-mm-dd" });
			});
		</script>
	</head>
	<body>
		<?php print_menu2();
		membership_menu(); ?>
		<div id='membership' >
		</div></center>
	</body>
</html>