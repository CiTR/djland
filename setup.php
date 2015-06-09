<?php

require_once('headers/db_header.php');
require_once('headers/password.php');
date_default_timezone_set($station_info['timezone']);
$setup_queries = array();

$setup_query ['membership']= "CREATE TABLE IF NOT EXISTS `membership` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `lastname` varchar(90) NOT NULL,
  `firstname` varchar(90) NOT NULL,
  `canadian_citizen` varchar(1) NOT NULL COMMENT 'citizen, immigrant, visitor',
  `address` varchar(55) NOT NULL,
  `city` varchar(45) NOT NULL DEFAULT 'Vancouver',
  `province` varchar(4) NOT NULL DEFAULT 'BC',
  `postalcode` varchar(6) NOT NULL,
  `member_type` varchar(9) NOT NULL COMMENT 'student, community, alumni, lifetime',
  `is_new` varchar(1) NOT NULL DEFAULT '0',
  `alumni` varchar(1) NOT NULL DEFAULT '0',
  `since` varchar(9) NOT NULL DEFAULT '2014/2015',
  `faculty` varchar(22) DEFAULT NULL,
  `schoolyear` varchar(2) DEFAULT NULL,
  `student_no` varchar(8) DEFAULT NULL COMMENT 'Student Number',
  `integrate` varchar(1) NOT NULL DEFAULT '0',
  `has_show` varchar(1) NOT NULL DEFAULT '0',
  `show_name` varchar(100) DEFAULT NULL,
  `primary_phone` varchar(10) NOT NULL,
  `secondary_phone` varchar(10) DEFAULT NULL,
  `email` tinytext NOT NULL,
  `joined` date NOT NULL,
  `comments` tinytext,
  `about` text,
  `skills` text,
  `status` varchar(10) NOT NULL DEFAULT 'pending',
  `exposure` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `student_no_UNIQUE` (`student_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
";

$setup_query ['membership_years']= "CREATE TABLE IF NOT EXISTS `membership_years` (
  `member_id` int(11) unsigned NOT NULL,
  `membership_year` varchar(16) NOT NULL,
  `paid` varchar(16) NOT NULL DEFAULT '0',
  `sports` varchar(16) DEFAULT '0',
  `news` varchar(16) DEFAULT '0',
  `arts` varchar(16) DEFAULT '0',
  `music` varchar(16) DEFAULT '0',
  `show_hosting` varchar(16) DEFAULT '0',
  `live_broadcast` varchar(16) DEFAULT '0',
  `tech` varchar(16) DEFAULT '0',
  `programming_committee` varchar(25) DEFAULT '0',
  `ads_psa` varchar(16) DEFAULT '0',
  `promotions_outreach` varchar(25) DEFAULT '0',
  `discorder` varchar(16) DEFAULT '0',
  `discorder_2` varchar(16) DEFAULT '0',
  `digital_library` varchar(16) DEFAULT '0',
  `photography` varchar(16) DEFAULT '0',
  `tabling` varchar(16) DEFAULT '0',
  `dj` varchar(16) DEFAULT '0',
  `other` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`member_id`,`membership_year`),
  CONSTRAINT `id` FOREIGN KEY (`member_id`) REFERENCES `membership` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
";


$setup_query ['user']= "CREATE TABLE IF NOT EXISTS `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `member_id` int(11) unsigned NOT NULL,
  `username` char(20) NOT NULL DEFAULT '0',
  `password` char(100) NOT NULL DEFAULT '0',
  `status` char(20) NOT NULL DEFAULT '0',
  `create_date` datetime DEFAULT NULL,
  `create_name` char(30) DEFAULT NULL,
  `edit_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `edit_name` char(30) DEFAULT NULL,
  `login_fails` mediumint(9) DEFAULT '0',
  PRIMARY KEY (`id`,`member_id`),
  CONSTRAINT `id` FOREIGN KEY (`member_id`) REFERENCES `membership` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
) ENGINE=InnoDB  DEFAULT CHARSET=utf8
";

$setup_query ['group_members']= "CREATE TABLE IF NOT EXISTS `group_members` (
  `user_id` int(10) unsigned NOT NULL,
  `operator` varchar(1) DEFAULT '0',
  `administrator` varchar(1) DEFAULT '0',
  `staff` varchar(1) DEFAULT '0',
  `workstudy` varchar(1) DEFAULT '0',
  `volunteer` varchar(1) DEFAULT '0',
  `dj` varchar(1) DEFAULT '0',
  `member` varchar(1) DEFAULT '0',
  PRIMARY KEY (`user_id`),
  CONSTRAINT `id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
) ENGINE=InnoDB DEFAULT CHARSET=utf8
";
$setup_query ['library']= "CREATE TABLE IF NOT EXISTS `library` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `format_id` tinyint(4) unsigned NOT NULL DEFAULT '8',
  `catalog` tinytext,
  `crtc` int(8) DEFAULT NULL,
  `cancon` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `femcon` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `local` int(1) unsigned NOT NULL DEFAULT '0',
  `playsheet` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `compilation` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `status` tinytext,
  `artist` tinytext,
  `title` tinytext,
  `label` tinytext,
  `genre` tinytext,
  `added` date DEFAULT NULL,
  `modified` date NOT NULL DEFAULT CURRENT_DATE ON UPDATE CURRENT_DATE,
  PRIMARY KEY (`id`),
  CREATE INDEX `artist` ON (artist),
  CREATE INDEX `title` ON (title),
  CREATE INDEX `label` ON (label),
  FULLTEXT KEY `text_desc` (`artist`,`title`,`label`,`genre`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8
";

$setup_query ['playsheets']="CREATE TABLE IF NOT EXISTS `playsheets` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `show_id` int(10) unsigned DEFAULT NULL,
  `host_id` int(10) unsigned DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `create_name` tinytext,
  `edit_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `edit_name` tinytext,
  `spokenword` mediumtext,
  `spokenword_duration` int(11) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `unix_time` int(11) DEFAULT NULL,
  `star` tinyint(4) DEFAULT NULL,
  `crtc` int(11) DEFAULT NULL,
  `lang` text,
  `type` tinytext,
  PRIMARY KEY (`id`,`show_id`,`host_id`),
  CONSTRAINT `id` FOREIGN KEY (`show_id`) REFERENCES `shows` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `id` FOREIGN KEY (`song_id`) REFERENCES `songs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CREATE INDEX `show_idx` ON (show_id),
  CREATE INDEX `host_idx` ON (host_id),
) ENGINE=InnoDB  DEFAULT CHARSET=utf8
";

$setup_query ['adlog']= "CREATE TABLE IF NOT EXISTS `adlog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playsheet_id` int(11) DEFAULT NULL,
  `num` smallint(6) DEFAULT NULL,
  `time` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  `type` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  `name` text CHARACTER SET utf8,
  `played` tinyint(4) DEFAULT NULL,
  `sam_id` int(11) DEFAULT NULL,
  `time_block` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`,`playsheet_id`),
  CONSTRAINT `id` FOREIGN KEY (`playsheet_id`) REFERENCES `playsheets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
) ENGINE=InnoDB  DEFAULT CHARSET=utf8
";

$setup_query ['hosts']= "CREATE TABLE IF NOT EXISTS `hosts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` tinytext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8
";

$setup_query['member_show'] = "CREATE TABLE IF NOT EXISTS `member_show` (
  `member_id` int(11) unsigned NOT NULL,
  `show_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`member_id`,`show_id`),
  CONSTRAINT `id` FOREIGN KEY (`member_id`) REFERNCES `membership` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
"

$setup_query ['playitems']= "CREATE TABLE IF NOT EXISTS `playitems` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `show_id` int(10) unsigned DEFAULT NULL,
  `playsheet_id` bigint(20) unsigned DEFAULT NULL,
  `song_id` bigint(20) unsigned DEFAULT NULL,
  `format_id` tinyint(3) unsigned DEFAULT NULL,
  `is_playsheet` tinyint(1) unsigned DEFAULT '0',
  `is_canadian` tinyint(1) unsigned DEFAULT '0',
  `is_yourown` tinyint(1) unsigned DEFAULT '0',
  `is_indy` tinyint(1) unsigned DEFAULT '0',
  `is_fem` tinyint(3) unsigned DEFAULT '0',
  `show_date` date DEFAULT NULL,
  `duration` tinytext,
  `is_theme` tinyint(3) unsigned DEFAULT NULL,
  `is_background` tinyint(3) unsigned DEFAULT NULL,
  `crtc_category` int(8) DEFAULT NULL,
  `lang` tinytext,
  `is_part` int(1) NOT NULL DEFAULT '0',
  `is_inst` int(1) NOT NULL DEFAULT '0',
  `is_hit` int(1) NOT NULL DEFAULT '0',
  `insert_song_start_hour` tinyint(4) DEFAULT NULL,
  `insert_song_start_minute` tinyint(4) DEFAULT NULL,
  `insert_song_length_minute` tinyint(4) DEFAULT NULL,
  `insert_song_length_second` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  CREATE INDEX `playitem_idx` ON (`id`,`show_id`,`playsheet_id`,`song_id`),
  CONSTRAINT `id` FOREIGN KEY (`playsheet_id`) REFERENCES `playsheets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `id` FOREIGN KEY (`show_id`) REFERENCES `shows` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `id` FOREIGN KEY (`song_id`) REFERENCES `songs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
) ENGINE=InnoDB  DEFAULT CHARSET=utf8
";

$setup_query ['scheduled_ads']= "CREATE TABLE IF NOT EXISTS `scheduled_ads` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `time_block` int(11) DEFAULT NULL,
  `show_date` date DEFAULT NULL,
  `sam_song_id_list` text CHARACTER SET utf8,
  `type` tinyint(3) unsigned DEFAULT NULL,
  `description` text CHARACTER SET utf8,
  `playsheet_id` bigint(20) unsigned DEFAULT NULL,
  `dj_note` text CHARACTER SET utf8,
  PRIMARY KEY (`id`),
  UNIQUE KEY `show_id_UNIQUE` (`time_block`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
";
$setup_query ['show_times']= "CREATE TABLE IF NOT EXISTS `show_times` (
  `show_id` int(10) NOT NULL,
  `start_day` int(3) NOT NULL,
  `start_time` time NOT NULL,
  `end_day` int(3) NOT NULL,
  `end_time` time NOT NULL,
  `alternating` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`show_id`,`start_day`,`start_time`,`end_day`,`end_time`,`alternating`),
  CREATE INDEX `start_index` ON (start_day,start_time),
  CREATE INDEX `end_index` ON (end_day,end_time),
) ENGINE=InnoDB DEFAULT CHARSET=utf8
";
$setup_query ['shows']= "CREATE TABLE IF NOT EXISTS `shows` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` tinytext CHARACTER SET utf8,
  `host_id` int(10) unsigned NOT NULL DEFAULT '0',
  `weekday` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `start_time` time NOT NULL DEFAULT '00:00:00',
  `end_time` time NOT NULL DEFAULT '00:00:00',
  `pl_req` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `cc_req` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `indy_req` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `fem_req` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `last_show` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_name` tinytext CHARACTER SET utf8 NOT NULL,
  `edit_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `edit_name` tinytext CHARACTER SET utf8,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `crtc_default` int(8) NOT NULL DEFAULT '20',
  `lang_default` tinytext CHARACTER SET utf8,
  `genre` tinytext CHARACTER SET utf8,
  `website` tinytext CHARACTER SET utf8,
  `rss` tinytext CHARACTER SET utf8,
  `show_desc` text CHARACTER SET utf8,
  `notes` text CHARACTER SET utf8,
  `show_img` tinytext CHARACTER SET utf8,
  `sponsor_name` tinytext CHARACTER SET utf8,
  `sponsor_url` tinytext CHARACTER SET utf8,
  PRIMARY KEY (`id`),
  CONSTRAINT `id` FOREIGN KEY (`host_id`) REFERENCES `hosts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
  CREATE INDEX `host_idx` ON ('name)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8
";
$setup_query ['socan']= "CREATE TABLE IF NOT EXISTS `socan` (
  `idSocan` int(10) unsigned NOT NULL,
  `socanStart` date DEFAULT NULL,
  `socanEnd` date DEFAULT NULL,
  PRIMARY KEY (`idSocan`),
  UNIQUE KEY `idSocan_UNIQUE` (`idSocan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='table for socan'
";
$setup_query ['social']= "CREATE TABLE IF NOT EXISTS `social` (
  `show_id` int(10) NOT NULL,
  `social_name` varchar(100) CHARACTER SET utf8 NOT NULL,
  `social_url` varchar(200) CHARACTER SET utf8 NOT NULL,
  `short_name` tinytext CHARACTER SET utf8,
  `unlink` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`show_id`,`social_name`,`social_url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
";
$setup_query ['songs']= "CREATE TABLE IF NOT EXISTS `songs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `artist` tinytext,
  `title` tinytext,
  `song` tinytext,
  `composer` tinytext,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `id_2` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8
";
$setup_query ['types_format']= "CREATE TABLE IF NOT EXISTS `types_format` (
  `id` int(11) DEFAULT '0',
  `name` tinytext,
  `sort` int(11) DEFAULT NULL,
  UNIQUE KEY `id` (`id`),
  KEY `id_2` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
";
$setup_query ['year_rollover'] = "CREATE TABLE IF NOT EXISTS `year_rollover` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `membership_year` VARCHAR(16) NOT NULL DEFAULT '2013/2014',
  PRIMARY KEY (`id`))";


$worked = false;
 foreach($setup_query as $i => $query) {
    if ($db->query($query) ){
        $worked = true;
        echo '<h4>table was created (or already existed): '.$i;

    }else {
        $worked = false;
        echo '<h2>something went wrong while creating '.$i;
    }
}

$init_query = array();







if($worked || !$worked){

    $pass = password_hash('pass',PASSWORD_DEFAULT);
//    $init_query['create example disabled user'] = "INSERT INTO user SET username='example', password='{$md5pass}', status = 'Disabled' ";
  
    $init_query['create admin user'] = "INSERT IGNORE INTO user SET userid = 1, username='admin', password='{$pass}', status = 'Enabled'";
    $init_query['set up format: cd'] = "INSERT INTO `types_format` (`id`,`name`,`sort`) VALUES (1, 'CD', 1), (2, 'LP',2), (3, '7inch', 2), (4,'CASS',3), (5, 'CART', 3), (6, 'MP3', 2), (7, 'FLAC',3), (8, 'WAV',3), (9, 'MD',3),(10,'??',3)";



    $check_admin_group_q = "SELECT COUNT(*) FROM group_members INNER JOIN user on user.userid = group_members.userid WHERE username='admin'";
    $check_admin_group_r = $db->query($check_admin_group_q);
    $check_admin_group = $check_admin_group_r->fetch_all()[0][0];
    echo 'ADMIN GROUP: '.$check_admin_group;
    if ($check_admin_group == 0){

            $db->query("INSERT INTO group_members SET username='admin', member='1',operator='1',administrator='1' ");

    } else {

    }

//    $init_query['put admin user in admin group'] = 






	// TABLE ALTERATIONS GO HERE
	$init_query['adding type column to playsheets'] = "ALTER TABLE `playsheets` ADD COLUMN `type` TINYTEXT";
	



	
    foreach($init_query as $i => $query){
    	echo '<hr/>';
        if ($db->query($query)  ){
            echo '<h4>initialization task successful:'.$i;
            
        } else {
        // there was an error, but if the error was "Duplicate entry..."
        // so it doesn't matter
        
        	if ( strpos($db->error,'uplicate') == 1 ){
            	echo '<h3>initialization task successful (already done): '.$i;
            } else {
            	echo '<h3>initalization task failed:'.$i;
              echo '</br>the error is: '.$db->error;
            }
        }
    }

      $count_result = $db->query('SELECT COUNT(*) as numrows FROM shows');
      $num_shows = $count_result->fetch_all()[0][0];
      if ( $num_shows == 0 ){
        // if there are no show rows, make an example show, member, and host

            $example_show_query['make example member'] = "INSERT IGNORE INTO membership SET id = 1, lastname='example', firstname='joe', gender='M', address='123 example street', status_id='2', joined='2013', last_paid='2013', comments='example comment' ";
            $example_show_query['make example show'] = "INSERT IGNORE INTO `shows` SET id = 1, name='example hour', host_id='1', active=1, end_time='01:00:00'";
            $example_show_query['make example host'] = "INSERT IGNORE INTO `hosts` SET id = 1, name='joe example'";

            foreach($example_show_query as $i => $query){
                    if ($db->query($query)  ){
                        echo '<h4>created example: '.$i;
                        
                    } else {
                        echo '<h4>example creation failed: '.$i;

                        }
            }

      } else {
          echo ' <h4>there are already some shows, so not creating example';

      }

}
$content = "Empty";
if ( !file_exists("static") ){
  if (mkdir('./static','0766')){
    echo '<h4>successfully created the static directory.<br/>';
  } else {
    echo '<h4>creating the static directory did not work. Perhaps this is a permissions issue.<br/>';
    echo 'I will now try to guess the user that needs permission to write to this web directory: ';
      $processUser = posix_getpwuid(posix_geteuid());
      print $processUser['name'];

//get_current_user();
  }
} else {
    echo '<h4>static directory already exists<br/>';
}

$fp = fopen('static/theShowList.html','w');

if(fwrite($fp,$content)){
  echo '<h4>initialized the static show list file';
} 
fclose($fp);
chmod('static/theShowList.html','0766');

if ( !file_exists("logs")){

  if (mkdir('./logs','0766')){
    echo '<h4>successfully created log directory';
  } else {
    echo '<h4>unable to create log directory. Probably a permissions problem.<br/>';
    echo 'I will now try to guess the user that needs permission to write to this web directory: ';
      $processUser = posix_getpwuid(posix_geteuid());
      print $processUser['name'];

  }
} else {
  echo '<h4>the log directory already exists';
}
$fp = fopen('logs/log.html','w');
fwrite($fp,$content);
fclose($fp);
chmod('static/theShowList.html','0766');

require_once('db_migrations.php');

?>
