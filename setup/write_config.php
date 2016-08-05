<?php
//if(!file_exists(dirname($_SERVER['DOCUMENT_ROOT']).'/config.php')) return;

$file = fopen(dirname($_SERVER['DOCUMENT_ROOT']).'/config.php','w');

$out[] = "<?php";
$out[] = "\$enabled = array(); \$station_info = array()";
$out[] = "\n";
$out[] = "//Set to false in production environment";
$out[] = "\$tesing_environment = true;";
$out[] = "\n";
$out[] = "//Radio Station Info";
write_post_to_array('station_info',$out);
$out[] ="\n";

$out[] = "//Enable or disable features of DJLand";
write_post_to_array('enabled',$out);
$out[] = "\n";

$out[] = "//Database Connections";
write_post_to_array('db',$out);
if(sizeof($_POST['db_sam']) >0){
	$out[] = "\n";
	write_post_to_array('db_sam',$out);
}
$out[] = "\n";

$out[] = "//Podcasting Tools";
echo "<pre>";
print_r($_POST['enabled']);
if( $_POST['enabled']['podcasting'] ){
	write_post_to_array('path',$out);
	$out[] = "\n";
	write_post_to_array('url',$out);
}


//Ending, Write the file
foreach($out as $line){
	fwrite($file,$line.($line != "\n" ? "\n" : ""));
}

function write_post_to_array($name,& $out){
	write_to_array($_POST[$name],$name,$out);
}
function write_to_array($array,$name,& $out){
	foreach($array as $key=>$item){
		$out[] = "\$".$name."[".$key."] = \"".$item."\";";
	}
}

$djland_permission_levels = array(
    'operator'=>array('level'=>'99','name'=>'Operator','tooltip'=>'Godmode.'),
    'administrator'=> array('level'=>'98','name'=>'Administrator','tooltip'=>'Administrator: Has all permissions, can create administrators.'),
    'staff'=>array('level'=>'6','name'=>'Staff','tooltip'=>'Staff: Has all permissions, but rollover.'),
    'workstudy'=>array('level'=>'5','name'=>'Workstudy','tooltip'=>'Workstudy: All access, but only email lists in membership.'),
	'volunteer_leader'=>array('level'=>'4','name'=>'Volunteer Leader','tooltip'=>'Volunteer Leader: Access to library, email lists, and schedule overrides.'),
    'volunteer'=>array('level'=>'3','name'=>'Volunteer','tooltip'=>'Volunteer: Access to charts, edit library, ad history.'),
    'dj'=>array('level'=>'2','name'=>'DJ','tooltip'=>'DJ: Access to playsheets, and personalized CRTC report.'),
    'member'=>array('level'=>'1','name'=>'Member','tooltip'=>'Member: Access to my Profile, resources, and help.')
	);
$djland_training = array(
	'Station Tour' => 'station_tour',
	'Technical' => 'technical_training',
	'Production'=> 'production_training',
	'Programming'=> 'programming_training',
	'Spoken Word'=> 'spoken_word_training');
$djland_interests = array(
	'Ads and PSAs'=>'ads_psa',
	'Arts'=>'arts',
	'Digital Library'=>'digital_library',
	'DJ101.9'=>'dj',
	'Illustrate for Discorder'=>'discorder_illustrate',
	'Writing for Discorder'=>'discorder_write',
	'Live Broadcasting'=>'live_broadcast',
	'Music'=>'music',
	'News'=>'news',
	'Photography'=>'photography',
	'Programming Committee'=>'programming_committee',
	'Promos and Outreach'=>'promotions_outreach',
	'Show Hosting'=>'show_hosting',
	'Sports'=>'sports',
	'Tabling'=>'tabling',
	'Web and Tech'=>'tech',
	"Women's Collective"=>'womens_collective',
	"Indigenous Collective"=>"indigenous_collective",
	"Accessibility Collective"=>"accessibility_collective",
	"Other"=>"other");
$djland_member_types = array(
	'UBC Student'=>'Student',
	'Community Member'=>'Community',
	'Staff'=>'Staff',
	'Lifetime'=>'Lifetime'
);

$djland_program_years = array(
	'1'=>'1',
	'2'=>'2',
	'3'=>'3',
	'4'=>'4',
	'5+'=>'5');
$djland_faculties = array(
	"Arts",
	"Applied Science",
	"Architecture",
	"Archival Studies",
	"Audiology",
	"Business",
	"Community Planning",
	"Continuing Studies",
	"Dentistry",
	"Doctoral Studies",
	"Education",
	"Environmental Health",
	"Forestry",
	"Graduate Studies",
	"Journalism",
	"Kinesiology",
	"Land and Food Systems",
	"Law","Medicine",
	"Music",
	"Nursing",
	"Pharmaceutical",
	"Public Health",
	"Science",
	"Social Work",
	"Other");
$djland_provinces = array(
	'AB',
	'BC',
	'MAN',
	'NB',
	'NFL',
	'NS',
	'NVT',
	'NWT',
	'ONT',
	'QUE',
	'SASK',
	'YUK');
$djland_primary_genres = array(
	"Electronic",
	"Experimental",
	"Hip Hop / R&B / Soul",
	"International",
	"Jazz / Classical" ,
	"Punk / Hardcore / Metal" ,
	"Rock / Pop / Indie",
	"Roots / Blues / Folk",
	"Talk"
	);
//Upload categories, and their accepted formats.
$djland_upload_categories = array(
	"show_image"=>array('jpg','jpeg','gif','png'),
	"friend_image"=>array('jpg','jpeg','gif','png'),
	"special_broadcast_image"=>array('jpg','jpeg','gif','png'),
	"member_resource"=>array('pdf','jpg','jpeg','gif','png'),
	"episode_image"=>array('jpg','jpeg','gif','png'),
	"episode_audio"=>array('mp3'),
	);
