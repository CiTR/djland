<?php
//Go to setup if config.php doesn't exist
if (
	!file_exists(dirname($_SERVER['DOCUMENT_ROOT']) . '/config.php')
	|| (isset($_POST['next_form']) && $_POST['next_form'] == 'write_config')
	|| (isset($_POST['next_form']) && $_POST['next_form'] == 'setup_database')
) {
	require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/setup/setup.php');
	return;
}

require_once("headers/session_header.php");
require_once('headers/login_header.php');
require_once('headers/db_header.php');
require_once("headers/password.php");
$message = '';
if (isset($_POST['action']) && $_POST['action'] == "signup") {
	header("Location: membership_signup.php");
} else if (is_logged_in() && isset($_GET['action']) && $_GET['action'] == "logout") {
	logout();
} else if (is_logged_in()) {
	header("Location: main.php");
} else if (isset($_POST['action']) && $_POST['action'] == "login") {
	if (!login($_POST['username'], $_POST['password'])) {
		//Failed to login
		$message = "Login Failed <br> (Incorrect Password)";
	} else {
		//Successful Login
		header("Location: main.php");
	}
}

$is_special_wallpaper = (new DateTime('March 28, 2019')) >= (new DateTime());
?>
<html>

<head>
	<meta name=ROBOTS content=\"NOINDEX, NOFOLLOW\" />
	<link rel=stylesheet href="css/style.css?03222019" type=text/css />
	<title><?php echo $station_info['name']; ?></title>
</head>
<?php if ($is_special_wallpaper) : ?>

	<body class='wallpaper special-wallpaper'>
		<a href="https://www.citr.ca/2019/03/11/citr-discorders-annual-general-meeting-volunteer-appreciation-dinner-90353/" target="blank">
			<img src="https://www.citr.ca/wp-content/uploads/2019/03/FACEBOOK_2019AGM-1-1536x866.jpg" alt="2019 AGM is on March 27th from 6pm to 9pm" class="special-header">
		</a>
	<?php else : ?>

		<body class='wallpaper'>
		<?php endif; ?>
		<div id='login'>
			<form METHOD=POST ACTION=<?php echo "'" . $_SERVER['SCRIPT_NAME'] . "'"; ?> name='site_login'>
				<h3>Welcome to DJ Land</h3>
				<label for='username'>Login: </label>
				<input type=text name='username' class='right double-margin-right' /><br />
				<div class='login-spacing-bar'></div>
				<label for='password'>Password: </label>
				<input type=password name='password' class='right double-margin-right' /><br />
				<div class='big-login-spacing-bar'></div>
				<input type='submit' name='action' value='login' />
				<?php if ($enabled['membership']) : ?>
					<input type='submit' name='action' value='signup' />
				<?php endif; ?>
			</form>
			If you forget your password, please email <?php echo $station_info['password_recovery_name']; ?> at <a href='mailto:<?php echo $station_info['password_recovery_email']; ?>'><?php echo $station_info['password_recovery_email']; ?></a>
			<div>
				<?php echo $message; ?>
			</div>
		</div>

		</body>

</html>