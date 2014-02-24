<?php
session_start();
require("headers/db_header.php");
require("headers/login_header.php");

//header("HTTP/1.0 302 Redirect\r\n");

//if logged in, log out...
if(is_logged_in() && isset($_GET['action']) && $_GET['action'] == "logout") {
	//logout of the sysem
	logout();
	$message = "Logged Out";
}
//if logged in send them on their merry way
else if(is_logged_in()) {
	//header("Location: main.php"); //Stupid IIS Bug
	printf("<html><head><meta http-equiv=\"refresh\" content=\"0;URL=main.php\"><link rel=stylesheet href=style.css type=text/css></head></html>");
}
//check for cookies or logging in...
else if(isset($_POST['login']) && isset($_POST['password'])) {
	if(login($_POST['login'], md5($_POST['password']), isset($_POST['permanent_cookie']) ? true : false)) {
		//header("Location: main.php"); //Stupid IIS Bug
		printf("<html><head><meta http-equiv=\"refresh\" content=\"0;URL=main.php\"><link rel=stylesheet href=style.css type=text/css></head></html>");
	}
	else {
		$message = "Login (Failed) ".login($_POST['login'], md5($_POST['password']), isset($_POST['permanent_cookie']));
	}
}
else if(isset($_COOKIE[$cookiename_id]) && isset($_COOKIE[$cookiename_pass]) && $_COOKIE[$cookiename_pass] && $_COOKIE[$cookiename_id]) {
	if(cookie_login()) {
		//header("Location: main.php"); //Stupid IIS Bug
		printf("<html><head><meta http-equiv=\"refresh\" content=\"0;URL=main.php\"><link rel=stylesheet href=style.css type=text/css></head></html>");
	}
	else {
		logout();
		$message = "Login (Bad Cookie)";
	}
}
else {
	logout();
	$message = "";
}


if (is_logged_in()) {
}
else {

	printf("<html><head><meta name=ROBOTS content=\"NOINDEX, NOFOLLOW\">");
	printf("<link rel=stylesheet href=style.css type=text/css>");
	printf("<title>DJ Land</title></head>");
	
preg_match('/MSIE (.*?);/', $_SERVER['HTTP_USER_AGENT'], $matches);

if (count($matches)>1){
  //Then we're using IE
  $version = $matches[1];

  switch(true){
    case ($version<=8):
      print(" <body class='ie'> ");
      break;

    default:
      print("<body>");
  }
}



//	printf("<table width=100%% height=100%%><tr><td align=center>");



	printf("<FORM METHOD=POST ACTION=\"%s\" name=site_login>", $_SERVER['SCRIPT_NAME']);

	printf("<div id='login'>");
	echo "<h3>DJ Land!</h3>";
	printf("Login: <input type=text name=login size=8><br/><br/>");
	printf("Password: <input type=password name=password size=8>");
//	printf("<tr><td align=right>Stay logged in: </td><td><input type=checkbox name=permanent_cookie> 
	echo "<br/><br/><input type=submit value=Login>";
	printf("</div>");
	printf("</FORM>");

	if($message) {
		printf("<h2>%s</h2>", $message);
	}
//printf("<br/><br/><br/><br/><a href=\"http://playlist.citr.ca/podcasting/phpadmin/edit.php\"><font size=\"3\">link to podcast editor</font></a>");
	
	printf("</td></tr></table>");
	
	printf("</body></html>");
}

?>