<?php
//Go to setup if config.php doesn't exist
if(!file_exists(dirname($_SERVER['DOCUMENT_ROOT']).'/config.php')
|| (isset($_POST['next_form']) && $_POST['next_form'] == 'write_config')
|| (isset($_POST['next_form']) && $_POST['next_form'] == 'setup_database')
){
	require_once(dirname($_SERVER['DOCUMENT_ROOT']).'/setup/setup.php');
	return;
}

require_once("headers/session_header.php");
require_once('headers/login_header.php');
require_once('headers/db_header.php');
require_once("headers/password.php");
$message = '';
if( isset($_POST['action']) && $_POST['action'] == "signup"){
	header("Location: membership_signup.php");
}
else if(is_logged_in() && isset($_GET['action']) && $_GET['action'] == "logout") {
	logout();
}
else if(is_logged_in()) {
	header("Location: main.php");
}
else if(isset($_POST['action']) && $_POST['action'] == "login") {
	if(!login ($_POST['username'], $_POST['password']) ) {
		//Failed to login
		$message = "Login Failed <br> (Incorrect Password)";
	}
	else{
		//Successful Login
		header("Location: main.php");
	}
}
?>
<html>
	<head>
		<meta name=ROBOTS content=\"NOINDEX, NOFOLLOW\"/>
		<link rel=stylesheet href=css/style.css type=text/css />
		<title><?php echo $station_info['name']; ?></title>
	</head>
	<body class='wallpaper'>
		<div id='temp'>
			<div id="temp-background" style="
								position: fixed;
								background-color: #eb1b5d;
								width: 100%;
								height: 100%;
								top: 0;
								left: 0;
								z-index: -2;
			">
			</div>
			<div id="temp-img" style="
										bottom: 0px;
										right: 0;
										display: block;
										position: fixed;
										width: 400px;
										overflow: hidden;
									">
				<img src="https://www.citr.ca/wp-content/uploads/2018/07/401-issue-party-1024x577.jpg" 
				style="left: -300px;bottom: -50px;position: relative;" />
				<style>
					#temp-link:hover {
						background-color: rgba(0,0,0,0);
					}
				</style>
				<a id='temp-link' href="https://www.facebook.com/events/1985941044751665/" target="_blank" style="display: block;position: absolute;width: 100%;height: 100%;top: 0px;"></a>
			</div>
		</div>
		<div id = 'login'>
			<form METHOD=POST ACTION=<?php echo "'".$_SERVER['SCRIPT_NAME']."'"; ?> name='site_login' >
				<h3>Welcome to DJ Land</h3>
				<label for='username'>Login: </label>
				<input type=text name='username' class='right double-margin-right'/><br/>
				<div class='login-spacing-bar'></div>
				<label for='password'>Password: </label>
				<input type=password name='password' class='right double-margin-right'/><br/>
				<div class='big-login-spacing-bar'></div>
				<input type='submit' name='action' value='login'/>
				<?php if($enabled['membership']): ?>
					<input type='submit' name='action' value='signup'/>
				<?php endif; ?>
			</form>
			If you forget your password, please email <?php echo $station_info['password_recovery_name']; ?> at <a href='mailto:<?php echo $station_info['password_recovery_email']; ?>'><?php echo $station_info['password_recovery_email']; ?></a>
			<div>
				<?php echo $message; ?>
			</div>
		</div>

	</body>
</html>
