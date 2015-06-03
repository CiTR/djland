<?php
session_start();
require_once("headers/security_header.php");
require_once("headers/function_header.php");
require_once("headers/menu_header.php");

if( permission_level() >= $djland_permission_levels['volunteer']){ ?>
<html>
<head>
<meta name=ROBOTS content=\"NOINDEX, NOFOLLOW\">
<title>DJLAND | Charting</title>

<link rel=stylesheet href=css/style.css type=text/css>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
<script type='text/javascript' src='js/charting.js'></script>

</head>
<body class='wallpaper'>

<?php	print_menu(); ?>
    <center>
        <input id="now" type="hidden" value="<?php echo get_time();?>">
        <div id="loadbar">loading charts...</div>
        <div id='charting-container' style='display:none;'>
            <div id='charting-title'>
                <div class='charting-artist'>Artist</div>
                <div class='charting-song'>Song</div>
                <div class='charting-album'>Album</div>
                <div class='charting-showname'>Show Name</div>
                <div class='charting-date'>Date</div>
                <div class='charting-cancon'>CC</div>
                <div class='charting-playlist'>PL</div>
                <div class='charting-status'>Status</div>
            </div>
            <div id='charting-body'>
            </div>
        </div>
    </center>

</body></html>
<?php }else{
    header("Location: main.php");
}?>