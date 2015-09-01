<?php

$enabled = array(); $station_info = array();


//PLEASE CONFIGURE - below this line
// Also please note this username and password: use to log in the first time.
// Please change this password or create a new admin user and delete 'admin'
// after installing
// admin user: 'admin'
// admin pass: 'pass'



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
//* 2) Database info (MySQL)
//*******************************************
//

// enter your database credentials here.  If you are using MySQL on the same server
// these files are on, use '127.0.0.1' - not 'localhost' . (PDO extension doesn't like localhost)



$djland_db_address = '127.0.0.1';
$djland_db_username = 'djland-username';
$djland_db_password = 'djland-password';
$djland_db_dbname = 'djland-databasename';


//*******************************************
//* 3) DJLand Enabled Features
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


//*******************************************
//* 4) optional station-wide login
//*******************************************
//
// if your station has many DJ's that play live on the air in a row you might opt
// to create a station-wide login so that DJ's don't have to log in and out everytime the show slot changes.
// Although it would be more secure if DJ's are trained from the get-go
// to always log out and log in using their own username at the start of the show,
// this is just not what happened at CiTR so we leave this option available.

// Filling this out just disables changing the password for this specific username.
// You still have to create this user yourself.
$station_wide_login_name = 'djs';

//*******************************************
//* 5) Optional SAM Broadcaster integration - http://spacial.com/sam-broadcaster
//*******************************************
// This enables access to items played from a SAM installation
// DJ's can pull individual items into playsheets for "typing-free" playsheet logging
// Playsheet entries from SAM can be edited and re-ordered just like manually entered items
// DJ's can pull individual plays from a 'most recent' list or specify a time range and bulk-load plays

$using_sam = false; // <- change to true if you want to integrate SAM with djland
// if SAM Broadcaster is being used, it must be installed using the MySQL option
// SAM integration is fast if the IP address is on the local network (something like 192.168.x.x)
// Highly recommended to use local network. 
$samDB_ip = 'ip address of computer running SAM mysql database';
$samDB_user = 'mysql username of above mysql database with select, insert, etc priveleges';
$samDB_pass = 'password for that user';
$samDB_dbname = 'name of SAM table in the db (probably is SAMDB)';

$djland_permission_levels = array(
    'operator'=> '6',
    'administrator'=> '5',
    'staff'=>'4',
    'workstudy'=>'3',
    'volunteer'=>'2',
    'dj'=>'1',
    'member'=>'0');
$djland_training = array(
	'Station Tour' => 'station_tour',
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
$djland_primary_genres = [
	'Blues',
	'Classical',
	'Electronic',
	'Experimental',
	'Folk',
	'Hip Hop',
	'Hardcore',
	'Indie',
	'International',
	'Jazz',
	'Metal',
	'Pop',
	'Punk',
	'R&B',
	'Rock',
	'Soul',
	'Spoken Word'
	];

// used to retreive podcast audio
$archive_tool_url = 'http://archive.citr.ca';
$archive_access_url = $archive_tool_url.
    "/py-test/archbrad/download?archive=%2Fmnt%2Faudio-stor%2Flog";

// use this to put podcast audio, rss xml files on a network drive that provides FTP access

$ftp_url = 'ip address of drive';
$ftp_user = 'username';
$ftp_pass = 'password';
$ftp_port = 21;


$audio_path_online = 'http://mypodcast.com/audio/';
$audio_path_local = '/var/www/audio/or/something/';

$xml_path_online   = 'http://mypodcast.com/rss/';
$xml_path_local   = '/var/www/xml/or/something/';



//default permission levels

$djland_membership_year_date =  $cutoff_date = date('04/31/'.idate('Y'));

$djland_training = array(
	'Technical'=>'technical_training',
	'Production'=>'production_training',
	'Programming'=>'programming_trianing',
	'Spoken Word'=>'spoken_word_training'
	);
$djland_permission_levels = array(
    'operator'=> '6',
    'administrator'=> '5',
    'staff'=>'4',
    'workstudy'=>'3',
    'volunteer'=>'2',
    'dj'=>'1',
    'member'=>'0');

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
	"Other"=>"other");
$djland_member_types = array(
	'UBC Student'=>'Student',
	'Community Member'=>'Community',
	'Staff'=>'Staff',
	'Lifetime'=>'Lifetime');
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
	"Rock / Pop / Indie",
	"Electronic",
	"Experimental",
	"Hip Hop / R&B / Soul",
	"International",
	"Jazz / Classical" ,
	"Punk / Hardcore / Metal" ,
	"Roots / Blues / Folk",
	"Talk"
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
// Henry Chee
//



