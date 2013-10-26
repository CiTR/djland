

<?php


session_start();

require("headers/security_header.php");

require("headers/function_header.php");
require("headers/menu_header.php");

require("headers/showlib.php");
require('adLib.php');

echo '<html><head><meta name=ROBOTS content="NOINDEX, NOFOLLOW">';
echo "<link rel=stylesheet href='citr.css' type='text/css' />";


//printf("<title>CiTR 101.9</title></head><body>");

?>

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
<body>

<?php


print_menu();

//global $samDB_ip, $samDB_user, $samDB_pass, $samDB_dbname;

//$adLib->sayHello();
$showlib = new Showlib($db);
$adLib = new AdLib($mysqli_sam,$db, $showlib);

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

$lastSunday = strtotime("last Sunday");
	
//for($i=0; $i<105; $i++){	
//	$block = $showBlocks[$i];
		
foreach ( $showBlocks as $i => $block ) {

		
		$startTime = $block['start_time'];
		$endTime = $block['end_time'];

		$thisShow = $showlib->getShowByID($block['show_id']);
		$uniqueTime = $showBlocks[$i][wdt] + $lastSunday;
				
		echo '<br/>';
		echo '<h3>'.$thisShow->name.'</h3>';
		echo date ( 'D, M j, g:ia', $uniqueTime);

		$duration = showBlock::getShowBlockLength($showBlocks[$i]);

		echo '<br/>show duration: '.$duration.' hr(s)<br/>';
//		echo 'starts at '.$showBlocks[$i]['start_time'].'</br/>';

		echo '<div class="adSelectGroup" id="'.$uniqueTime.'" name="'.$thisShow->name.'">';
		
		echo $adLib->generateTable($uniqueTime,'prog', $thisShow);
		echo '</div>';
		
}


$mysqli_sam->close();

$db->close();

?>


<button id="save" class="adSave">save</button>

<script type="text/javascript" src='./js/ads.js'></script>

</body>
</html>

