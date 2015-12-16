<html>
	<?php
	include_once("headers/session_header.php");
	require_once("headers/security_header.php");
	require_once("headers/functions.php");
	require_once("headers/socan_header.php");
	require_once("headers/menu_header.php");
	?>
	<head>
		<link rel='stylesheet' href='js/bootstrap/bootstrap.min.css'></script>
		<link rel="stylesheet" href="css/style.css" type="text/css">
		
	</head>
	
	
	<script type='text/javascript' src="js/jquery-1.11.3.min.js"></script>
    <script type='text/javascript' src="js/jquery-ui-1.11.3.min.js"></script>
	<script type='text/javascript' src='js/playsheet/object.js'></script>
	<script type='text/javascript' src='js/playsheet/app.js'></script>
	<script type='text/javascript' src='js/playsheet/constants.js'></script>
	<script type='text/javascript' src='js/api.js'></script>
	<script type='text/javascript' src='js/utils.js'></script>
	<body class='wallpaper'>
		<script type='text/javascript'>
		var playsheet_id = "<?php if(isset($_GET['id'])){echo $_GET['id']; }else{ echo '-1';} ?>";
		var member_id = "<?php echo $_SESSION['sv_id']; ?>";
		var username = "<?php echo $_SESSION['sv_username']; ?>";
		</script>
		<?php print_menu(); 
		$api_root = $_SERVER['HTTP_HOST'].'/api2/public/';
		$url = $api_root.'playsheet/'.(isset($_GET['id']) ? $_GET['id'] : '143533');

		echo $url;
       $curl = curl_init($url);
       curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
       $curl_response = curl_exec($curl);
       curl_close($curl);
       echo $curl_response;


		?>
		


	</body>
</html>