<?php
include_once("headers/session_header.php");
require_once("headers/security_header.php");
require_once("headers/function_header.php");
require_once("headers/menu_header.php");

print_menu();
$shows = array();
$api_base = 'http://'.$_SERVER['HTTP_HOST'];
$shows = CallAPI('GET',$api_base.'/api2/public/show/active');
?>

<html ng-app='djland.open_fundrive'>
  <head>
    <meta name=ROBOTS content=\"NOINDEX, NOFOLLOW\">
    <link rel=stylesheet href=css/style.css type=text/css>
    <title>DJLAND | Fundrive Data Download</title>
    <script src='js/jquery-1.11.3.min.js'></script>
  </head>
  <body ng-controller='fundriveDump as fundrive' class='wallpaper'>
    <div class="text-center">
      <br>
      <br>
      <h3>Downloading info...</h3>
      <br>
      <br>
      <button onclick="location.href='fundrive-open-form.php';">Back</button>
    </div>

    <script type='text/javascript' src="js/jquery-ui/external/jquery/jquery.js"></script>
    <script type='text/javascript' src="js/angular.js"></script>
    <script type='text/javascript' src='js/bootstrap/bootstrap.js'></script>
    <script src='js/fundrive/download-info.js'></script>
    <script type='text/javascript' src='js/api.js'></script>
  </body>

</html>
