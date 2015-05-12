<?php
/**
 * Created by PhpStorm.
 * User: brad
 * Date: 3/5/15
 * Time: 5:34 PM
 */
require_once('headers/db_header.php');

$queries = array(
    'remove obsolete scheduled_ads table'=>'DROP TABLE `scheduled_ads`;',
    'remove obsolete ncrc data' => 'DROP TABLE `ncrcdata`;',

    'create podcast channels table'=>'CREATE TABLE IF NOT EXISTS `podcast_channels` (
                                `id` int(11) NOT NULL AUTO_INCREMENT,
                                `title` text,
                                `subtitle` text,
                                `summary` text,
                                `author` text,
                                `keywords` text,
                                `owner_name` text,
                                `owner_email` text,
                                `episode_default_title` text,
                                `episode_default_subtitle` text,
                                `episode_default_author` text,
                                `link` text,
                                `image_url` text,
                                `audio_url_prefix` text,
                                `keep_n_episodes` int(4) DEFAULT NULL,
                                `active` tinyint(1) DEFAULT NULL,
                                `xml` text,
                                PRIMARY KEY (`id`)
                              ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;',

    'create podcast episodes table'=>'CREATE TABLE IF NOT EXISTS `podcast_episodes` (
                                `id` int(11) NOT NULL AUTO_INCREMENT,
                                `title` text,
                                `subtitle` text,
                                `summary` text,
                                `date` text,
                                `channel_id` int(11) DEFAULT NULL,
                                `url` text,
                                `length` int(11) DEFAULT NULL,
                                `author` text,
                                `active` tinyint(1) DEFAULT 0,
                                `duration` int(7) DEFAULT 0,
                                PRIMARY KEY (`id`)
                              ) ENGINE=InnoDB DEFAULT CHARSET=utf8;',
    'add alert field to shows'=>'ALTER TABLE `shows` ADD COLUMN `alerts` TEXT NULL;',
    'add podcast channel id to shows' => 'ALTER TABLE `shows` ADD COLUMN `podcast_channel_id` int(11) DEFAULT NULL',
    'add podcsat episode id to playsheet' => 'ALTER TABLE `playlists` ADD COLUMN `podcast_episode` INT NULL',
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
    'add edit_date to channel'  => 'ALTER TABLE `podcast_channels` ADD COLUMN `edit_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;',
    'add edit_date to episode'  => 'ALTER TABLE `podcast_episodes` ADD COLUMN `edit_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;',
    'add top_tags to show'  => 'ALTER TABLE `shows` ADD COLUMN `primary_genre_tags` TINYTEXT NULL AFTER `lang_default`;',
    'rename genre tables to tags' => "ALTER TABLE `shows`
                                  CHANGE COLUMN `top_tags` `primary_genre_tags` TEXT CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL ,
                                  CHANGE COLUMN `genre` `secondary_genre_tags` TEXT CHARACTER SET 'utf8' NULL DEFAULT NULL ;",
    'adjust member_show' => 'ALTER TABLE `member_show`
                                  CHANGE COLUMN `member_id` `member_id` INT(11) NOT NULL ,
                                  CHANGE COLUMN `show_id` `show_id` INT(11) NOT NULL ,
                                  ADD COLUMN `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                                  DROP PRIMARY KEY,
                                  ADD PRIMARY KEY (`id`);',
    'edit membership permissions' => "ALTER TABLE `group_members`
                                    DROP COLUMN `editlibrary`,
                                    DROP COLUMN `membership`,
                                    DROP COLUMN `library`,
                                    DROP COLUMN `editdj`,
                                    DROP COLUMN `addshow`,
                                    DROP COLUMN `adduser`,
                                    CHANGE COLUMN `operator` `operator` VARCHAR(1) NULL DEFAULT '0' AFTER `userid`,
                                    CHANGE COLUMN `administrator` `administrator` VARCHAR(1) NULL DEFAULT '0' AFTER `operator`,
                                    CHANGE COLUMN `dj` `dj` VARCHAR(1) NULL DEFAULT '0' AFTER `volunteer`,
                                    CHANGE COLUMN `member` `member` VARCHAR(1) NULL DEFAULT '0' ,
                                    ADD COLUMN `staff` VARCHAR(1) NULL DEFAULT '0' AFTER `administrator`,
                                    ADD COLUMN `workstudy` VARCHAR(1) NULL DEFAULT '0' AFTER `staff`,
                                    ADD COLUMN `volunteer` VARCHAR(45) NULL DEFAULT '0' AFTER `workstudy`",
    'add training' => "ALTER TABLE `membership`
                          ADD COLUMN `tech_training` VARCHAR(1) NULL DEFAULT '0' AFTER  `exposure`,
                          ADD COLUMN `programming_training` VARCHAR(1) NULL DEFAULT '0' AFTER  `tech_training`,
                          ADD COLUMN `production_training` VARCHAR(1) NULL DEFAULT '0' AFTER `programming_training`",
    'add spoken word' => "ALTER TABLE `membership`
                          ADD COLUMN `spoken_word` VARCHAR(1) NULL DEFAULT '0' AFTER `production_training`",
    'create cutoff' => "CREATE TABLE IF NOT EXISTS `year_rollover` (
                            `id` INT NOT NULL AUTO_INCREMENT,
                            `membership_year` VARCHAR(16) NOT NULL DEFAULT '2013/2014',
                            PRIMARY KEY (`id`))"

);

foreach($queries as $description => $query){
  echo '<hr/>';
  echo 'task - '.$description.': ';

  if($result =   mysqli_query($db,$query) ){
    echo 'complete';
  } else {
    echo 'fail: '. $query;
    echo '<br/>';
    echo mysqli_error($db);
  }
}