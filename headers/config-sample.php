<?php
//PLEASE CONFIGURE
//*******************************************
//*******************************************
//*******************************************
//
// a MySQL db is the backend storage engine for all DJland's data, including:
// membership
// CD library
// ad scheduling
// playsheet logging
// enter your database credentials here

$djland_db_address = 'p:ip address'; // p means persistant connection (good idea)
$djland_db_username = 'db username';
$djland_db_password = 'password for that user';
$djland_db_dbname = 'database name';

$using_sam = false; // <- change to true if you want to integrate SAM with djland

// please note this username and password: use to log in the first time
// admin user: 'admin'
// admin pass: 'pass'

// enabled sections:
$membership_enabled = false;
$library_enabled = false;
$shows_enabled = true;
$charts_enabled = false;
$report_enabled = true;
$playsheet_enabled = true;