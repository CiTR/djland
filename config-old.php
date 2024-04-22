<?php
//Set to false in production environment
$testing_environment = true;

//Radio Station Info
$station_info = array(
"name" => "",
"call_letters" => "",
"frequency" => "",
"website" => "",
"station_id" => "",
"tech_email" => "",
"password_recovery_email" => "",
"password_recovery_name" => "",
"city" => "",
"province" => "",
"country" => "",
"timezone" => "America/Vancouver",
);

//Enable or disable features of DJLand
$enabled = array(
"membership" => true,
"library" => true,
"shows" => true,
"ad_scheduler" => true,
"charts" => true,
"report" => true,
"playsheet" => true,
"podcasting" => true,
);

//Database Connections
$db = array(
"address" => "localhost:3306",
"username" => "brad",
"password" => "password",
"database" => "djlandseed",
);

$sam_db = array(
"address" => "",
"username" => "",
"password" => "",
"database" => "",
);

//Podcasting Tools
$path = array(
"audio_base" => "",
"xml_base" => "",
);

$url = array(
"audio_base" => "",
"xml_base" => "",
"archiver_tool" => "",
"archiver_request" => "",
);
$max_podcast_length = array(
);
//Month at which the membership year rolls into the next
$djland_membership_cutoff_month =5;

$djland_permission_levels = array(
'operator' => array("level" => "99","name" => "Operator","tooltip" => "Power Overwhelming.",),
'administrator' => array("level" => "98","name" => "Administrator","tooltip" => "Administrator: Has all permissions, can create administrators.",),
'staff' => array("level" => "6","name" => "Staff","tooltip" => "Staff: Has all permissions, but rollover.",),
'workstudy' => array("level" => "5","name" => "Workstudy","tooltip" => "Workstudy: All access, but only email lists in membership.",),
'volunteer_leader' => array("level" => "4","name" => "Volunteer Leader","tooltip" => "Volunteer Leader: Access to library, email lists, and schedule overrides.",),
'volunteer' => array("level" => "3","name" => "Volunteer","tooltip" => "Volunteer: Access to charts, edit library, ad history.",),
'dj' => array("level" => "2","name" => "DJ","tooltip" => "DJ: Access to playsheets, and personalized CRTC report.",),
'member' => array("level" => "1","name" => "Member","tooltip" => "Member: Access to my Profile, resources, and help.",),
);
$djland_member_types = array(
"UBC Student" => "Student",
"Community Member" => "Community",
"Staff" => "Staff",
"Lifetime" => "Lifetime",
);
$djland_interests = array(
"Arts" => "arts",
"Ads and PSAs" => "ads_psa",
"Digital Library" => "digital_library",
"DJ101.9" => "dj",
"Illustrate for Discorder" => "discorder_illustrate",
"Writing for Discorder" => "discorder_write",
"Live Broadcasting" => "live_broadcast",
"Music" => "music",
"News" => "news",
"Photography" => "photography",
"Programming Committee" => "programming_committee",
"Promos and Outreach" => "promotions_outreach",
"Show Hosting" => "show_hosting",
"Sports" => "sports",
"Tabling" => "tabling",
"Web and Tech" => "tech",
"Women's Collective" => "womens_collective",
"Indigenous Collective" => "indigenous_collective",
"Accessibility Collective" => "accessibility_collective",
"Other" => "other",
);
$djland_training = array(
"Technical" => "technical_training",
"Production" => "production_training",
"Programming" => "programming_training",
"Spoken Word" => "spoken_word_training",
);
$djland_program_years = array(
"1" => "1",
"2" => "2",
"3" => "3",
"4" => "4",
"5+" => "5",
);
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
"Law",
"Medicine",
"Music",
"Nursing",
"Pharmaceutical",
"Public Health",
"Science",
"Social Work",
"Other",
);
$djland_provinces = array(
"AB",
"BC",
"MAN",
"NB",
"NFL",
"NS",
"NVT",
"NWT",
"ONT",
"QUE",
"SASK",
"YUK",
);
$djland_primary_genres = array(
"Electronic",
"Experimental",
"Hip Hop / R&B / Soul",
"International",
"Jazz / Classical",
"Punk / Hardcore / Metal",
"Rock / Pop / Indie",
"Roots / Blues / Folk",
"Talk",
);
$djland_upload_categories = array(
'show_image' => array("jpg","jpeg","gif","png",),
'friend_image' => array("jpg","jpeg","gif","png",),
'special_broadcast_image' => array("jpg","jpeg","gif","png",),
'member_resource' => array("pdf","jpg","jpeg","gif","png",),
'episode_image' => array("jpg","jpeg","gif","png","tiff",),
'episode_audio' => array("mp3",),
);
