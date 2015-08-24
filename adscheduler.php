

<?php

include_once("headers/session_header.php");
require_once("headers/security_header.php");

require_once("headers/function_header.php");
require_once("headers/menu_header.php");
require_once("headers/showlib.php");
require_once('adLib.php');

echo '<html><head><meta name=ROBOTS content="NOINDEX, NOFOLLOW">';
echo "<link rel=stylesheet href='css/style.css' type='text/css' />";

if( permission_level() >= $djland_permission_levels['staff']){ ?>

<title>Ad Scheduler</title>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" />
  <script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
  <style type="text/css">
.adSave{
	position:fixed;
	right:10px;
	top:40px;
	height:75px;
	width:125px;
	border-width: 2px;
	background-color: beige;
}
.adSave:hover{
background-color: lime;
	}
</style>
 
</head>
<body class='wallpaper'>

<?php


print_menu();

//global $samDB_ip, $samDB_user, $samDB_pass, $samDB_dbname;

//$adLib->sayHello();
$showlib = new Showlib($db);

if($using_sam){
	$adLib = new AdLib($mysqli_sam,$db);
} else {
	$adLib = new AdLib(false, $db );
}

echo '<h1>ad scheduler';
echo '</h1>';


echo '<div id=ad-select-template>'.$adLib->generateAdSelector().'</div>';

echo '<p>"+" button template:</p>'.
	'<div class="adRow" id="invisible-template-generic">'.
	 '<div ><div class="adbuttons ad-delete">-</div><div class="adbuttons ad-add">+</div><div class="adbuttons ad-advert">Ad</div></div>'.
	 '<div class="adTime"><input class="adInput" type="text"></div>'.
	 '<div class="adType" ><input class="adInput" type="text"></div>'.
	 '<div class="adName" ><input class="adInput" type="text"></div>'.
	 '</div>';
	 
echo '<p>"Ad" button template:</p>'.
	'<div class="adRow" id="invisible-template-ad">'.
	 '<div ><div class="adbuttons ad-delete">-</div><div class="adbuttons ad-add">+</div><div class="adbuttons ad-advert">Ad</div></div>'.
	 '<div class="adTime"><input class="adInput" type="text"></div>'.
	 '<div class="adType" ><input class="adInput" type="text" value="'.
	 $adLib->ad_dict['AD'].
	 '"></div>'.
	 '<div class="adName" >'.
	 $adLib->generateAdSelector().
	 '</div>'.
	 '</div>';

$showBlocks = $showlib->getAllCurrentShowBlocks();

$lastSunday = strtotime("last Sunday",get_time());

//for($i=0; $i<105; $i++){
//	$block = $showBlocks[$i];
		
foreach ( $showBlocks as $i => $block ) {
//		echo '<pre>';
//		print_r($block);
//		echo '</pre>';
		
		$startTime = $block['start_time'];
		$endTime = $block['end_time'];

		$thisShow = $showlib->getShowByID($block['show_id']);
		$uniqueTime = $showBlocks[$i][wdt] + $lastSunday;

//		echo '<pre> info for id'.$block['show_id'].'<br/>';
//		print_r($thisShow);
//		echo '</pre>';
				
		echo '<br/>';
		echo '<h3>'.$thisShow->name.'</h3>';
		echo date ( 'D, M j, g:ia', $uniqueTime);

		$duration = $block['duration'];//showBlock::getShowBlockLength($showBlocks[$i]);

		echo '<br/>show duration: '.$duration.' hr(s)<br/>';
//		echo 'starts at '.$showBlocks[$i]['start_time'].'</br/>';

		echo '<div class="adSelectGroup" id="'.$uniqueTime.'" name="'.$thisShow->name.'">';
		
		echo $adLib->generateTable($uniqueTime,'prog', $block);
		echo '</div>';
		
}

if($using_sam){
	$mysqli_sam->close();
}

$db->close();

?>


<button id="save" class="adSave">save</button>

<script type="text/javascript" src='./js/ads.js'></script>

</body>
</html>

<?php }else{
    header("Location: main.php");
}?>

