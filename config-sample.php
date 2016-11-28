<?php

$enabled = array(); $station_info = array();
$testing_environment = true;

//*******************************************
//* 1) Radio Station Info (default is CiTR for demo)
//*******************************************

$station_info['call_letters'] = 'CiTR';
$station_info['frequency'] = '101.9fm';
$station_info['city'] = 'Vancouver';
$station_info['province']= 'BC';
$station_info['country'] = 'Canada';
$station_info['website'] = 'CiTR.ca';
$station_info['tech_email'] = 'tech_person@station.ca';
$station_info['station ID message'] ="'CiTR 101.9, from unceded Musqueam territory, in Vancouver'";
$station_info['timezone'] = 'America/Vancouver';
// for a list of valid timezones, visit
// http://ca1.php.net/manual/en/timezones.php


//*******************************************
//* 2) DJLand Enabled Features
//*******************************************
//

// enabled sections: (write true or false (no quotes) to enable or disable)
// if you go with something enabled and then disable it later on,
// you won't lose any data

$enabled['membership'] = true; // membership database
$enabled['library'] = true; // searchable music catalog (for physical recordings)
$enabled['shows'] = true; // show information database - used to pre-fill playsheets
$enabled['adscheduler'] = false; // display what ads each programmer should play (only works if using SAM integration)
$enabled['charts'] = true; // Chart week display for the music director - pulls play data from playsheets
$enabled['report'] = true; // CRTC formatted printable report view
$enabled['playsheets'] = true; // DJ's log in to the site from any computer with WWW access to create and edit their playsheets
$enabled['podcast_tools'] = false; // audio logging / show podcast manager.  Not implemented yet
$enabled['sam_integration'] = false;

//*******************************************
//* 3) Database info (MySQL)
//*******************************************
//

// enter your database credentials here.  If you are using MySQL on the same server
// these files are on, use '127.0.0.1' - not 'localhost' . (PDO extension doesn't like localhost)
$db = array(
	'address'=>'localhost',
	'username'=>'username',
	'password'=>'password',
	'database'=>'database',
	);
$sam_db = array(
	'address'=>'sam_host_ip',
	'username'=>'username',
	'password'=>'password',
	'database'=>'database',
	)

//*******************************************
//* 4) Podcast Configuration
//*******************************************


if($enabled['podcast_tools']){
	//Local paths & Remote URLs for use with podcasting
	$path = array();
	$url = array();

	//Podcast paths
	$path['audio_base'] = '/home/podcast/audio';
	$url['audio_base'] = 'http://podcast.hostname.com/audio';
	$path['xml_base'] = '/home/podcast/xml';
	$url['xml_base']= 'http://podcast.hostname.com/xml';

	//Archiver Access
	$url['archiver_tool'] = 'http://archive.citr.ca';
	$url['archive_request'] = $url['archiver_tool'].'/py-test/archbrad/download?archive=%2Fmnt%2Faudio-stor%2Flog';

	//Podcast local_dev paths
	if($testing_environment==true){
		$path['test_audio_base'] = $_SERVER['DOCUMENT_ROOT']."/audio";
		$url['test_audio_base'] = $_SERVER['DOCUMENT_ROOT']."/audio";
		$path['test_xml_base'] = $_SERVER['DOCUMENT_ROOT']."/xml";
		$url['test_xml_base'] = $_SERVER['DOCUMENT_ROOT']."/xml";
	}
}

//*******************************************
//* 5) DJLand Configuration Constants / Variables. (Not you must manually edit your database to support changes here)
//*******************************************

$djland_membership_rollover_month = 5;

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
	'Technical' => 'technical_training',
	'Production'=> 'production_training',
	'Programming'=> 'programming_training',
	'Spoken Word'=> 'spoken_word_training');
$djland_interests = array(
	'Arts'=>'arts',
	'Ads and PSAs'=>'ads_psa',
	'Digital Library'=>'digital_library',
	'DJ101.9'=>'dj',
	'Illustrate for Discorder'=>'discorder',
	'Writing for Discorder'=>'discorder_2',
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
	'Staff'=>'Staff');
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
$djland_subgenres = array(
	"Electronic" => array(
		"Ambient",
		"Bass",
		"Chiptune"
		"Drum and Bass",
		"Dub",
		"Dubstep",
		"Electo",
		"Future Bass",
		"Hardstyle",
		"House",
		"Glitch"
		"Jungle"
		"Tech House",
		"Acid House",
		"Tropical House",
		"Deep House",
		"Techno",
		"Trance",
		"Psy Trance",
		"Trap",
		"Vapourwave"
	),
	"Experimental" => array(
		"Avant Garde",
		"Sound Art",
		"Radio Art",
		"Noise",
		"Drone",
		"Minimalist",
		"Free Improv"
	),
	"Hip Hop / R&B / Soul" => array(
		"Contemporary R&B",
		"Motown",
		"Neo Soul",
		"Rap",
		"Turntableism"
	),
	"International" => array(
		"Reggae",
		""
	),
	"Jazz / Classical" => array(
		"Avant-Garde",
		"Baroque",
		"Bebop",
		"Chamber Music",
		"Chant",
		"Choral",
		"Classical Crossover",
		"Early Music",
		"High Classical",
		"Impressionist",
		"Jazz Blues",
		"Medieval",
		"Minimalism",
		"Modern Composition",
		"Opera",
		"Orchestral",
		"Renaissance",
		"Romantic",
		"Symphonic Jazz",
		"Wedding Music"
	),
	"Punk / Hardcore / Metal" => array(
		"Alternative Metal",
		"Black Metal",
		"Crust Punk",
		"Death Metal",
		"Deathcore",
		"Doom Metal",
		"Emo",
		"Garage Punk",
		"Glam Metal",
		"Grindcore",
		"Hardcore Punk",
		"Heavy Metal",
		"Folk Metal",
		"Melodic Punk",
		"Post-Metal",
		"Power Metal",
		"Progressive Metal",
		"Punk",
		"Punk Rock",
		"Screamo",
		"Speed Metal",
		"Stoner Metal",
		"Symphonic Metal",
		"Thrash",
		"Thrash Metal",
	),
	"Rock / Pop / Indie" => array(
		"Alternative Rock",
		"College Rock",
		"Classic Rock",
		"Experimental Rock",
		"Goth Rock",
		"Grunge",
		"Hard Rock",
		"Indie Rock",
		"Indie",
		"Pop",
		"90's Pop",
		"Progressive Rock",
		"Top 40"
	),
	"Roots / Blues / Folk" => array(
		"African Blues",
		"Bluegrass",
		"Canadiana",
		"Canadian Blues",
		"Chicago Blues",
		"Country",
		"Gospel"
	),
	"Talk" => array(
		"Arts and Culture Talk"
		"Comedy",
		"Lecture",
		"Lifestyle",
		"Daytime Talk",
		"Historical Recording"
		"Late Night",
		"News",
		"Interview",
		"Sports Talk Radio",
		"Talk Radio"
	)
);


// debug time operations? leave false unless you
// are developing and need to fake the current time
// for some reason

// in code, do these two things:
// 1) use get_time() instead of time()
// 2) always use get_time() as optional last parameter for all date() calls
// 3) use hidden field to pass time to javascript
//

// developers visit DJland on GitHUB to check out latest version
// or contribute to the project and submit a pull request!
// http://www.github.com/citrtech/djland
// project home page
// http://www.djland.info
// Developed by CiTR
// http://www.citr.ca

// contributors
// Brad Winter
// Evan Friday
// Sandy Fang
// Scott Pidzarko
// Henry Chee
//
