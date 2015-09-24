<?php
include_once("headers/session_header.php");
require_once('headers/login_header.php');
require_once('headers/db_header.php');
require_once("headers/password.php");
//echo '<p>after password';
//header("HTTP/1.0 302 Redirect\r\n");
if( isset($_POST['action']) && $_POST['action'] == "signup"){
	header("Location: membership_add.php");
	//echo "signing up"; 
	//printf("<html><head><meta http-equiv=\"refresh\" content=\"0;URL=membership_add.php\"><link rel=stylesheet href=css/style.css type=text/css></head></html>");
	}
else if(is_logged_in() && isset($_GET['action']) && $_GET['action'] == "logout") {
	logout();
	$message = "Logged Out";
	}
else if(is_logged_in()) {
	//header("Location: main.php");
	printf("<html><head><meta http-equiv=\"refresh\" content=\"0;URL=main.php\"><link rel=stylesheet href=css/style.css type=text/css></head></html>");
	}	
else if(isset($_POST['action']) && $_POST['action'] == "login") {
	//isset($_POST['login']) && isset($_POST['password'])
	if(login ($_POST['username'], $_POST['password'], isset( $_POST['permanent_cookie'] ) ) ) {
		//header("Location: main.php");
		printf("<html><head><meta http-equiv=\"refresh\" content=\"0;URL=main.php\"><link rel=stylesheet href=css/style.css type=text/css></head></html>");

		}
	else{
		$message = "Login (Failed) ".login($_POST['username'], $_POST['password'], isset($_POST['permanent_cookie']) );
		}
	}
else {
	logout();
	$message = " ";
	}
if (is_logged_in()) {
	//header("Location: main.php");
}

else {
?>
	<html>
		<head><meta name=ROBOTS content=\"NOINDEX, NOFOLLOW\">
		<link rel=stylesheet href=css/style.css type=text/css>
		<title>DJ Land</title></head>
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
		<div style='font-size:2em;'>Please do not use firefox as it does not currently work with setting playsheet times.</div>
		<FORM METHOD=POST ACTION= <?php echo "'".$_SERVER['SCRIPT_NAME']."'"; ?> name='site_login' >
			<h3>Welcome to DJ Land</h3>
			<label for='username'>Login: </label><input type=text name='username'/><br/>
			<label for='password'>Password: </label><input type=password name='password'/><br/>
			<input type='submit' name='action' value='login'/>
			<input type='submit' name='action' value='signup'/>
		</FORM>
		If you forget your password, please email Hugo at volunteer@citr.ca
		<div id = 'message' >
		<?php echo $message; ?>
		</div>
	</div>

	</body>
</html>

<?php } ?>