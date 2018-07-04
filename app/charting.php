<?php

require_once("headers/security_header.php");
require_once("headers/menu_header.php");

if( permission_level() >= $djland_permission_levels['volunteer']['level'] ){
 ?>

<html>
<head>
<meta name=ROBOTS content=\"NOINDEX, NOFOLLOW\">
<title>DJLAND | Charting</title>

<link rel=stylesheet href=css/style.css type=text/css>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
<script type='text/javascript' src='js/charting.js'></script>
<script>
    $(function() {
    $( ".datepicker" ).datepicker();
    });
  </script>
</head>
<body class='wallpaper'>

<?php	print_menu(); ?>
 <center>
        <input id="now" type="hidden" value="<?php echo get_time(); ?>">
        <div class='text-center loading invisible' ><img class='rounded' width ='300' height='20' src='images/loading.gif'/></div>
         <label for="from">Start Date: </label>
            <input type="text" id="from" name="from"/>
            <label for="to">End Date: </label>
            <input type="text" id="to" name="to" />
            <button id='load_charts'>Load Charts</button>
            <button id='sort_charts'>Sort Charts</button>
        <div id='charting-container'>
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
        <div id='charting-container-sorted'>
            <div id='charting-title'>
                <div class='charting-rowid'>#</div>
                <div class='charting-artist'>Artist</div>
                <div class='charting-album'>Album</div>
                <div class='charting-count'>Count</div>
            </div>
            <div id='charting-body-sorted'>
            </div>
        </div>
    </center>


</body></html>
<?php }else{
    header("Location: main.php");
}?>
