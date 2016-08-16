<?php
if(!file_exists(dirname($_SERVER['DOCUMENT_ROOT']).'/config.php') || (isset($_POST['next_form']) && $_POST['next_form'] == 'write_config') || (isset($_POST['next_form']) && $_POST['next_form'] == 'setup_database')){
	require_once($_SERVER['DOCUMENT_ROOT'].'/setup.php');
	return;
}


require_once("headers/session_header.php");
require_once('headers/login_header.php');
require_once('headers/db_header.php');
require_once("headers/password.php");

if( isset($_POST['action']) && $_POST['action'] == "signup"){
	header("Location: membership_add.php");
	}
else if(is_logged_in() && isset($_GET['action']) && $_GET['action'] == "logout") {
	logout();
	$message = "Logged Out";
	}
else if(is_logged_in()) {
	printf("<html><head><meta http-equiv=\"refresh\" content=\"0;URL=main.php\"><link rel=stylesheet href=css/style.css type=text/css></head></html>");
	}
else if(isset($_POST['action']) && $_POST['action'] == "login") {
	if(login ($_POST['username'], $_POST['password'], isset( $_POST['permanent_cookie'] ) ) ) {

		printf("<html><head><meta http-equiv=\"refresh\" content=\"0;URL=main.php\"><link rel=stylesheet href=css/style.css type=text/css></head></html>");
		}
	else{
		$message = "Login Failed <br> (Incorrect Password)".login($_POST['username'], $_POST['password'], isset($_POST['permanent_cookie']) );
		}
	}
else {
	logout();
	$message = " ";
	}
if (is_logged_in()) {
	header("Location: main.php");
}
else {
?>
<html>
	<head><meta name=ROBOTS content=\"NOINDEX, NOFOLLOW\">
	<link rel=stylesheet href=css/style.css type=text/css>
	<link rel=stylesheet href=css/bootstrap.css type=text/css>
	<title><?php echo $station_info['name'] ?></title>
</head>
<?php
	preg_match('/MSIE (.*?);/', $_SERVER['HTTP_USER_AGENT'], $matches);
	if (count($matches)>1){
		$version = $matches[1];
		if($version <= 8 ) echo "<body class='ie'>"; else echo "<body class='wallpaper'>";
	}
	else{
		echo "<body class='wallpaper'>";
	}
?>
	<div id = 'login'>
		<FORM METHOD=POST ACTION= <?php echo "'".$_SERVER['SCRIPT_NAME']."'"; ?> name='site_login' >
			<h3>Welcome to DJ Land</h3>
			<label for='username'>Login: </label><input type=text name='username' class='right double-margin-right'/><br/>
			<div class='login-spacing-bar'></div>
			<label for='password'>Password: </label><input type=password name='password' class='right double-margin-right'/><br/>
			<div class='big-login-spacing-bar'></div>
			<input type='submit' name='action' value='login'/>
			<?php if($enabled['membership']: ?><input type='submit' name='action' value='signup'/><? endif; ?>
		</FORM>
		If you forget your password, please email Hugo at volunteer@citr.ca
		<div id = 'message' >
		<?php
			echo $message;
		?>
		</div>
	</div>

	</body>
</html>

<?php } ?>
