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

$station_info['timezone'] = 'America/Vancouver'; 
// for a list of valid timezones, visit 
// http://ca1.php.net/manual/en/timezones.php

//*******************************************
//* 2) Database info (MySQL)
//*******************************************
//

// enter your database credentials here.  If you are using MySQL on the same server
// these files are on, use 'localhost'.
// add 'p:' to the beginning to use a persistant connection


$djland_db_address = 'p:localhost';
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



