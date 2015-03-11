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
                                PRIMARY KEY (`id`));'


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