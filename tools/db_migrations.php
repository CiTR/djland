
<html>
    <head>
        <link rel='stylesheet' href='../../../js/bootstrap/bootstrap.min.css'></script>
    </head>
    <body>
        <table class='table'>
            <tr><th>Description</th><th>Query</th><th>Result</th></tr>  
<?php
require_once('../headers/db_header.php');

$cutoff_date = date('04/31/'.idate('Y'));
$year = idate('Y');
$today_date = date('m/d/Y',strtotime("today"));
//Check to see if we are in a this years membership year or not.
if(strtotime($today_date) < strtotime($cutoff_date)){
    $year--;
}
$initial_cutoff_year = $year."/".($year+1);

$queries = array(
    'change playlists table to playsheets' => 'ALTER TABLE playlists RENAME TO playsheets;',
    'update playsheets' => 
    'ALTER TABLE `playsheets` 
        CHANGE COLUMN `spokenword` `summary` MEDIUMTEXT NULL DEFAULT NULL,
        ADD COLUMN `title` TINYTEXT NULL DEFAULT NULL AFTER `edit_date`;',
    
    'expand shows to hold podcast channel data' => 'ALTER TABLE `shows` 
        ADD COLUMN `host` TINYTEXT NULL AFTER `name`,
        ADD COLUMN `podcast_xml` TINYTEXT NULL AFTER `alerts`,
        ADD COLUMN `podcast_slug` VARCHAR(45) NULL AFTER `podcast_xml`,
        ADD COLUMN `podcast_title` TINYTEXT NULL AFTER `podcast_slug`,
        ADD COLUMN `podcast_subtitle` TINYTEXT NULL AFTER `podcast_title`,
        ADD COLUMN `podcast_summary` TEXT NULL AFTER `podcast_subtitle`,
        ADD COLUMN `podcast_author` TINYTEXT NULL AFTER `podcast_summary`;',   
    'prep for removal of hosts table dependancy' => 
        'UPDATE shows as s INNER JOIN hosts as h ON s.host_id = h.id SET s.host = h.name;',   
    'create podcast episodes table'=>'CREATE TABLE IF NOT EXISTS `podcast_episodes` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `playsheet_id` BIGINT(20) UNSIGNED NOT NULL,
        `show_id` int(11) DEFAULT NULL,
        `title` text,
        `subtitle` text,
        `summary` text,
        `date` text,
        `url` text,
        `length` int(11) DEFAULT NULL,
        `author` text,
        `active` tinyint(1) DEFAULT 0,
        `duration` int(7) DEFAULT 0,
        `UPDATED_AT` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8;',
    'create special events table'=>'CREATE TABLE IF NOT EXISTS `special_events` (
                                `id` INT NOT NULL AUTO_INCREMENT,
                                `name` VARCHAR(455) NULL,
                                `show_id` INT NULL,
                                `description` TEXT NULL,
                                `start` INT NULL,
                                `end` INT NULL,
                                `image` VARCHAR(455) NULL,
                                `url` VARCHAR(455) NULL,
                                PRIMARY KEY (`id`));',
    'rename userid to id in user' => 
        'ALTER TABLE user
            CHANGE COLUMN `userid` `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT;',
    'rename userid to user_id in group_members' =>
        'ALTER TABLE group_members 
            CHANGE COLUMN userid user_id INT(10) UNSIGNED NOT NULL;',
    'edit membership permissions' => "ALTER TABLE `group_members`
                                    DROP COLUMN `editlibrary`,
                                    DROP COLUMN `membership`,
                                    DROP COLUMN `library`,
                                    DROP COLUMN `editdj`,
                                    DROP COLUMN `addshow`,
                                    DROP COLUMN `adduser`,
                                    CHANGE COLUMN `operator` `operator` VARCHAR(1) NULL DEFAULT '0' AFTER `user_id`,
                                    CHANGE COLUMN `administrator` `administrator` VARCHAR(1) NULL DEFAULT '0' AFTER `operator`,
                                    ADD COLUMN `staff` VARCHAR(1) NULL DEFAULT '0' AFTER `administrator`,
                                    ADD COLUMN `workstudy` VARCHAR(1) NULL DEFAULT '0' AFTER `staff`,
                                    ADD COLUMN `volunteer` VARCHAR(45) NULL DEFAULT '0' AFTER `workstudy`,
                                    CHANGE COLUMN `dj` `dj` VARCHAR(1) NULL DEFAULT '0' AFTER `volunteer`,
                                    CHANGE COLUMN `member` `member` VARCHAR(1) NULL DEFAULT '0'",
    'add training' => "ALTER TABLE `membership`
                        CHANGE COLUMN `joined` `create_date` TIMESTAMP NOT NULL AFTER `spoken_word_training`,
                        ADD COLUMN `edit_date` TIMESTAMP NOT NULL AFTER `create_date`;",                        
    'additional committees' => "ALTER TABLE membership_years 
                                ADD COLUMN `womens_collective` VARCHAR(16) NULL DEFAULT '0' AFTER `other`,
                                ADD COLUMN `indigenous_collective` VARCHAR(16) NULL DEFAULT '0' AFTER `womens_collective`,
                                ADD COLUMN `accessibility_collective` VARCHAR(16) NULL DEFAULT '0' AFTER `indigenous_collective`;",                                    
    'add timestamps to membership_years' => 
        'ALTER TABLE `membership_years` 
            ADD COLUMN `create_date` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP AFTER `other`,
            ADD COLUMN `edit_date` TIMESTAMP NULL AFTER `create_date`;',
    'Add timestampts to user' =>
        'ALTER TABLE `user` 
            CHANGE COLUMN `create_date` `create_date` TIMESTAMP NULL DEFAULT NULL;',
    'fill in membership_year timestamps' => "update membership_years as my inner join membership as m on my.member_id = m.id SET my.create_date = m.create_date;",
    'removing reliance on songs table' => 
        'ALTER TABLE `playitems` 
            ADD COLUMN `artist` VARCHAR(80) NULL AFTER `insert_song_length_second`,
            ADD COLUMN `song` VARCHAR(80) NULL AFTER `artist`,
            ADD COLUMN `album` VARCHAR(80) NULL AFTER `song`,
            ADD COLUMN `composer` VARCHAR(80) NULL AFTER `album`;',
    'move song info into playitems' =>
        'UPDATE playitems as p INNER JOIN songs as s ON s.id = p.song_id SET 
            p.artist = s.artist, 
            p.song = s.song, 
            p.album = s.title, 
            p.composer = s.composer;',




);
foreach($queries as $description => $query){
    if($result =   mysqli_query($db,$query) ){
        echo '<tr><td>'.$description.'</td><td>'.$query.'</td><td>Complete</td></tr>';
    }else {
        echo '<tr class="danger"><td>'.$description.'</td><td>'.$query.'</td><td> Failed: '.mysqli_error($db).'</td></tr>';
    }
}
?>
        </table>
    </body>
</html>
