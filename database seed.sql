-- ----------------------------------------------------------------------------
-- MySQL Workbench Migration
-- Migrated Schemata: citr_live
-- Source Schemata: citr_live
-- Created: Wed Nov 12 15:45:14 2014
-- ----------------------------------------------------------------------------

SET FOREIGN_KEY_CHECKS = 0;;

-- ----------------------------------------------------------------------------
-- Schema citr_live
-- ----------------------------------------------------------------------------
DROP SCHEMA IF EXISTS `citr_live` ;
CREATE SCHEMA IF NOT EXISTS `citr_live` ;

-- ----------------------------------------------------------------------------
-- Table citr_live.adlog
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `citr_live`.`adlog` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `playsheet_id` INT(11) NULL DEFAULT NULL,
  `num` SMALLINT(6) NULL DEFAULT NULL,
  `time` VARCHAR(45) CHARACTER SET 'utf8' NULL DEFAULT NULL,
  `type` VARCHAR(45) CHARACTER SET 'utf8' NULL DEFAULT NULL,
  `name` TEXT CHARACTER SET 'utf8' NULL DEFAULT NULL,
  `played` TINYINT(4) NULL DEFAULT NULL,
  `sam_id` INT(11) NULL DEFAULT NULL,
  `time_block` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 312737
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table citr_live.djs
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `citr_live`.`djs` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` TEXT NOT NULL,
  `day` TEXT NOT NULL,
  `time` TEXT NOT NULL,
  `dj` TEXT NOT NULL,
  `desc` TEXT NOT NULL,
  `image` TEXT NOT NULL,
  `email` TEXT NOT NULL,
  `website` TEXT NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = MyISAM
AUTO_INCREMENT = 5
DEFAULT CHARACTER SET = latin1;

-- ----------------------------------------------------------------------------
-- Table citr_live.group_members
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `citr_live`.`group_members` (
  `userid` INT(10) UNSIGNED NOT NULL,
  `member` VARCHAR(1) NULL DEFAULT NULL,
  `dj` VARCHAR(1) NULL DEFAULT NULL,
  `administrator` VARCHAR(1) NULL DEFAULT NULL,
  `adduser` VARCHAR(1) NULL DEFAULT NULL,
  `addshow` VARCHAR(1) NULL DEFAULT NULL,
  `editdj` VARCHAR(1) NULL DEFAULT NULL,
  `library` VARCHAR(1) NULL DEFAULT NULL,
  `membership` VARCHAR(1) NULL DEFAULT NULL,
  `editlibrary` VARCHAR(1) NULL DEFAULT NULL,
  `operator` VARCHAR(1) NULL DEFAULT NULL,
  PRIMARY KEY (`userid`),
  INDEX `userid_idx` (`userid` ASC),
  CONSTRAINT `user`
    FOREIGN KEY (`userid`)
    REFERENCES `citr_live`.`user` (`userid`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

-- ----------------------------------------------------------------------------
-- Table citr_live.user
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `citr_live`.`user` (
  `userid` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `member_id` INT(11) UNSIGNED NOT NULL,
  `username` VARCHAR(20) NOT NULL,
  `password` VARCHAR(100) NOT NULL,
  `status` VARCHAR(20) NOT NULL DEFAULT 'enabled',
  `create_date` DATE NULL DEFAULT NULL,
  `edit_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `edit_name` VARCHAR(30) NULL DEFAULT NULL,
  `login_fails` MEDIUMINT(9) NULL DEFAULT NULL,
  PRIMARY KEY (`userid`, `member_id`),
  UNIQUE INDEX `member_id_UNIQUE` (`member_id` ASC),
  UNIQUE INDEX `username_UNIQUE` (`username` ASC),
  INDEX `member_id_idx` (`member_id` ASC),
  CONSTRAINT `member_id`
    FOREIGN KEY (`member_id`)
    REFERENCES `citr_live`.`membership` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 353
DEFAULT CHARACTER SET = utf8;

-- ----------------------------------------------------------------------------
-- Table citr_live.group_members_backup
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `citr_live`.`group_members_backup` (
  `username` VARCHAR(20) CHARACTER SET 'latin1' NOT NULL DEFAULT '0',
  `groupname` VARCHAR(20) CHARACTER SET 'latin1' NOT NULL DEFAULT '0')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

-- ----------------------------------------------------------------------------
-- Table citr_live.groups
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `citr_live`.`groups` (
  `name` VARCHAR(20) NOT NULL DEFAULT '0',
  `description` VARCHAR(100) NOT NULL DEFAULT '0',
  UNIQUE INDEX `name` (`name` ASC))
ENGINE = MyISAM
DEFAULT CHARACTER SET = latin1;

-- ----------------------------------------------------------------------------
-- Table citr_live.hosts
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `citr_live`.`hosts` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` TINYTEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id` (`id` ASC),
  INDEX `id_2` (`id` ASC))
ENGINE = MyISAM
AUTO_INCREMENT = 2243
DEFAULT CHARACTER SET = latin1;

-- ----------------------------------------------------------------------------
-- Table citr_live.library
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `citr_live`.`library` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `format_id` TINYINT(4) UNSIGNED NOT NULL DEFAULT '8',
  `catalog` TINYTEXT NULL DEFAULT NULL,
  `crtc` INT(8) NULL DEFAULT NULL,
  `cancon` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
  `femcon` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
  `local` INT(1) UNSIGNED NOT NULL DEFAULT '0',
  `playlist` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
  `compilation` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
  `digitized` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
  `status` TINYTEXT NULL DEFAULT NULL,
  `artist` TINYTEXT NULL DEFAULT NULL,
  `title` TINYTEXT NULL DEFAULT NULL,
  `label` TINYTEXT NULL DEFAULT NULL,
  `genre` TINYTEXT NULL DEFAULT NULL,
  `added` DATE NULL DEFAULT NULL,
  `modified` DATE NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  FULLTEXT INDEX `text_desc` (`artist`(255) ASC, `title`(255) ASC, `label`(255) ASC, `genre`(255) ASC))
ENGINE = MyISAM
AUTO_INCREMENT = 55946
DEFAULT CHARACTER SET = latin1;

-- ----------------------------------------------------------------------------
-- Table citr_live.login_status
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `citr_live`.`login_status` (
  `name` VARCHAR(20) NOT NULL DEFAULT '0',
  INDEX `name` (`name` ASC))
ENGINE = MyISAM
DEFAULT CHARACTER SET = latin1;

-- ----------------------------------------------------------------------------
-- Table citr_live.member_show
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `citr_live`.`member_show` (
  `member_id` INT(11) UNSIGNED NOT NULL,
  `show_id` INT(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`member_id`, `show_id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

-- ----------------------------------------------------------------------------
-- Table citr_live.membership
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `citr_live`.`membership` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `lastname` VARCHAR(45) NOT NULL,
  `firstname` VARCHAR(45) NOT NULL,
  `canadian_citizen` VARCHAR(1) NOT NULL COMMENT 'citizen, immigrant, visitor',
  `address` VARCHAR(55) NOT NULL,
  `city` VARCHAR(45) NOT NULL DEFAULT 'Vancouver',
  `province` VARCHAR(4) NOT NULL DEFAULT 'BC',
  `postalcode` VARCHAR(6) NOT NULL,
  `member_type` VARCHAR(9) NOT NULL COMMENT 'student, community, alumni, lifetime',
  `is_new` VARCHAR(1) NOT NULL DEFAULT '0',
  `alumni` VARCHAR(1) NOT NULL DEFAULT '0',
  `since` VARCHAR(9) NOT NULL DEFAULT '2014/2015',
  `faculty` VARCHAR(22) NULL DEFAULT NULL,
  `schoolyear` VARCHAR(2) NULL DEFAULT NULL,
  `student_no` VARCHAR(8) NULL DEFAULT NULL COMMENT 'Student Number',
  `integrate` VARCHAR(1) NOT NULL DEFAULT '0',
  `has_show` VARCHAR(1) NOT NULL DEFAULT '0',
  `show_name` VARCHAR(100) NULL DEFAULT NULL,
  `primary_phone` VARCHAR(10) NOT NULL,
  `secondary_phone` VARCHAR(10) NULL DEFAULT NULL,
  `email` TINYTEXT NOT NULL,
  `joined` DATE NOT NULL,
  `comments` TINYTEXT NULL DEFAULT NULL,
  `about` TEXT NULL DEFAULT NULL,
  `skills` TEXT NULL DEFAULT NULL,
  `status` VARCHAR(10) NOT NULL DEFAULT 'pending',
  `exposure` TEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  UNIQUE INDEX `student_no_UNIQUE` (`student_no` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 352
DEFAULT CHARACTER SET = utf8;

-- ----------------------------------------------------------------------------
-- Table citr_live.membership_backup
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `citr_live`.`membership_backup` (
  `id` INT(11) NOT NULL DEFAULT '0',
  `lastname` TINYTEXT CHARACTER SET 'latin1' NULL DEFAULT NULL,
  `firstname` TINYTEXT CHARACTER SET 'latin1' NULL DEFAULT NULL,
  `gender` TINYTEXT CHARACTER SET 'latin1' NULL DEFAULT NULL,
  `cdn` TINYINT(1) NULL DEFAULT NULL,
  `address` TINYTEXT CHARACTER SET 'latin1' NULL DEFAULT NULL,
  `city` TINYTEXT CHARACTER SET 'latin1' NULL DEFAULT NULL,
  `postal` TINYTEXT CHARACTER SET 'latin1' NULL DEFAULT NULL,
  `cell` TINYTEXT CHARACTER SET 'latin1' NULL DEFAULT NULL,
  `home` TINYTEXT CHARACTER SET 'latin1' NULL DEFAULT NULL,
  `work` TINYTEXT CHARACTER SET 'latin1' NULL DEFAULT NULL,
  `email` TINYTEXT CHARACTER SET 'latin1' NULL DEFAULT NULL,
  `status_id` INT(11) NOT NULL DEFAULT '5',
  `joined` YEAR NOT NULL DEFAULT '0000',
  `last_paid` YEAR NOT NULL DEFAULT '0000',
  `comments` TINYTEXT CHARACTER SET 'latin1' NULL DEFAULT NULL,
  `show` TINYTEXT CHARACTER SET 'latin1' NULL DEFAULT NULL,
  `djs` TINYINT(4) NOT NULL DEFAULT '0',
  `mobile` TINYINT(4) NOT NULL DEFAULT '0',
  `newsdept` TINYINT(4) NOT NULL DEFAULT '0',
  `sportsdept` TINYINT(4) NOT NULL DEFAULT '0',
  `board` TINYINT(4) NOT NULL DEFAULT '0',
  `discorder` TINYINT(4) NOT NULL DEFAULT '0',
  `executive` TINYINT(4) NOT NULL DEFAULT '0',
  `women` TINYINT(4) NOT NULL DEFAULT '0',
  `fill_in` TINYINT(4) NOT NULL DEFAULT '0',
  `dept` TINYINT(4) NOT NULL DEFAULT '0',
  `int_music` TINYINT(4) NOT NULL DEFAULT '0',
  `int_arts` TINYINT(4) NOT NULL DEFAULT '0',
  `int_spoken` TINYINT(4) NOT NULL DEFAULT '0',
  `int_magazine` TINYINT(4) NOT NULL DEFAULT '0',
  `int_promotions` TINYINT(4) NOT NULL DEFAULT '0',
  `int_livesound` TINYINT(1) NULL DEFAULT NULL,
  `int_design` TINYINT(1) NULL DEFAULT NULL,
  `int_web` TINYINT(1) NULL DEFAULT NULL,
  `int_progcom` TINYINT(1) NULL DEFAULT NULL,
  `int_adpsa` TINYINT(1) NULL DEFAULT NULL,
  `int_video` TINYINT(1) NULL DEFAULT NULL,
  `int_other` TINYTEXT CHARACTER SET 'latin1' NULL DEFAULT NULL,
  `added` DATE NULL DEFAULT NULL,
  `modified` DATE NULL DEFAULT NULL)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

-- ----------------------------------------------------------------------------
-- Table citr_live.membership_status
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `citr_live`.`membership_status` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` TINYTEXT NOT NULL,
  `sort` INT(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`))
ENGINE = MyISAM
AUTO_INCREMENT = 7
DEFAULT CHARACTER SET = latin1;

-- ----------------------------------------------------------------------------
-- Table citr_live.membership_years
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `citr_live`.`membership_years` (
  `member_id` INT(11) UNSIGNED NOT NULL,
  `membership_year` VARCHAR(9) NOT NULL,
  `paid` VARCHAR(1) NOT NULL DEFAULT '0',
  `sports` VARCHAR(1) NULL DEFAULT '0',
  `news` VARCHAR(1) NULL DEFAULT '0',
  `arts` VARCHAR(1) NULL DEFAULT '0',
  `music` VARCHAR(1) NULL DEFAULT '0',
  `show_hosting` VARCHAR(1) NULL DEFAULT '0',
  `live_broadcast` VARCHAR(1) NULL DEFAULT '0',
  `tech` VARCHAR(1) NULL DEFAULT '0',
  `programming_committee` VARCHAR(1) NULL DEFAULT '0',
  `ads_psa` VARCHAR(1) NULL DEFAULT '0',
  `promotions_outreach` VARCHAR(1) NULL DEFAULT '0',
  `discorder` VARCHAR(1) NULL DEFAULT '0',
  `discorder_2` VARCHAR(1) NULL DEFAULT '0',
  `digital_library` VARCHAR(1) NULL DEFAULT '0',
  `photography` VARCHAR(1) NULL DEFAULT '0',
  `tabling` VARCHAR(45) NULL DEFAULT '0',
  `dj` VARCHAR(1) NULL DEFAULT '0',
  `other` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`member_id`, `membership_year`),
  INDEX `member_id_idx` (`member_id` ASC),
  CONSTRAINT `id`
    FOREIGN KEY (`member_id`)
    REFERENCES `citr_live`.`membership` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

-- ----------------------------------------------------------------------------
-- Table citr_live.membership_years_backup
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `citr_live`.`membership_years_backup` (
  `id` INT(11) NOT NULL DEFAULT '0',
  `membership_id` INT(11) NOT NULL DEFAULT '0',
  `paid_year` YEAR NOT NULL DEFAULT '0000')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

-- ----------------------------------------------------------------------------
-- Table citr_live.ncrcdata
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `citr_live`.`ncrcdata` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `fname` VARCHAR(32) NOT NULL,
  `lname` VARCHAR(32) NOT NULL,
  `address` VARCHAR(128) NOT NULL,
  `city` VARCHAR(32) NOT NULL,
  `province` VARCHAR(32) NOT NULL,
  `postal` VARCHAR(8) NOT NULL,
  `station` VARCHAR(16) NOT NULL,
  `phone1` VARCHAR(16) NOT NULL,
  `phone2` VARCHAR(16) NULL DEFAULT NULL,
  `fax` VARCHAR(16) NULL DEFAULT NULL,
  `email` VARCHAR(64) NOT NULL,
  `emailupdates` INT(1) NOT NULL,
  `members` VARCHAR(32) NOT NULL,
  `dates` VARCHAR(64) NOT NULL,
  `transportation` VARCHAR(32) NOT NULL,
  `accomodation` INT(2) NOT NULL,
  `dietary` VARCHAR(255) NOT NULL,
  `comments` TEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 61
DEFAULT CHARACTER SET = utf8;

-- ----------------------------------------------------------------------------
-- Table citr_live.playitems
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `citr_live`.`playitems` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `show_id` INT(10) UNSIGNED NULL DEFAULT NULL,
  `playsheet_id` BIGINT(20) UNSIGNED NULL DEFAULT NULL,
  `song_id` BIGINT(20) UNSIGNED NULL DEFAULT NULL,
  `format_id` TINYINT(3) UNSIGNED NULL DEFAULT NULL,
  `is_playlist` TINYINT(1) UNSIGNED NULL DEFAULT '0',
  `is_canadian` TINYINT(1) UNSIGNED NULL DEFAULT '0',
  `is_yourown` TINYINT(1) UNSIGNED NULL DEFAULT '0',
  `is_indy` TINYINT(1) UNSIGNED NULL DEFAULT '0',
  `is_fem` TINYINT(3) UNSIGNED NULL DEFAULT '0',
  `show_date` DATE NULL DEFAULT NULL,
  `duration` TINYTEXT NULL DEFAULT NULL,
  `is_theme` TINYINT(3) UNSIGNED NULL DEFAULT NULL,
  `is_background` TINYINT(3) UNSIGNED NULL DEFAULT NULL,
  `crtc_category` INT(8) NULL DEFAULT NULL,
  `lang` TINYTEXT NULL DEFAULT NULL,
  `is_part` INT(1) NOT NULL DEFAULT '0',
  `is_inst` INT(1) NOT NULL DEFAULT '0',
  `is_hit` INT(1) NOT NULL DEFAULT '0',
  `insert_song_start_hour` TINYINT(4) NULL DEFAULT NULL,
  `insert_song_start_minute` TINYINT(4) NULL DEFAULT NULL,
  `insert_song_length_minute` TINYINT(4) NULL DEFAULT NULL,
  `insert_song_length_second` TINYINT(4) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id` (`id` ASC),
  INDEX `id_2` (`id` ASC))
ENGINE = MyISAM
AUTO_INCREMENT = 1628736
DEFAULT CHARACTER SET = latin1;

-- ----------------------------------------------------------------------------
-- Table citr_live.playlists
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `citr_live`.`playlists` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `show_id` INT(10) UNSIGNED NULL DEFAULT NULL,
  `host_id` INT(10) UNSIGNED NULL DEFAULT NULL,
  `start_time` DATETIME NULL DEFAULT NULL,
  `end_time` TIME NULL DEFAULT NULL,
  `create_date` DATETIME NULL DEFAULT NULL,
  `create_name` TINYTEXT NULL DEFAULT NULL,
  `edit_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `edit_name` TINYTEXT NULL DEFAULT NULL,
  `spokenword` MEDIUMTEXT NULL DEFAULT NULL,
  `spokenword_duration` INT(11) NULL DEFAULT NULL,
  `status` TINYINT(4) NULL DEFAULT NULL,
  `unix_time` INT(11) NULL DEFAULT NULL,
  `star` TINYINT(4) NULL DEFAULT NULL,
  `crtc` INT(11) NULL DEFAULT NULL,
  `lang` TEXT NULL DEFAULT NULL,
  `type` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id` (`id` ASC),
  INDEX `id_2` (`id` ASC))
ENGINE = MyISAM
AUTO_INCREMENT = 129858
DEFAULT CHARACTER SET = latin1;

-- ----------------------------------------------------------------------------
-- Table citr_live.scheduled_ads
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `citr_live`.`scheduled_ads` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `time_block` INT(11) NULL DEFAULT NULL,
  `show_date` DATE NULL DEFAULT NULL,
  `sam_song_id_list` TEXT CHARACTER SET 'utf8' NULL DEFAULT NULL,
  `type` TINYINT(3) UNSIGNED NULL DEFAULT NULL,
  `description` TEXT CHARACTER SET 'utf8' NULL DEFAULT NULL,
  `playsheet_id` BIGINT(20) UNSIGNED NULL DEFAULT NULL,
  `dj_note` TEXT CHARACTER SET 'utf8' NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `show_id_UNIQUE` (`time_block` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 19324
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table citr_live.show_times
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `citr_live`.`show_times` (
  `show_id` INT(10) NOT NULL,
  `start_day` INT(3) NOT NULL,
  `start_time` TIME NOT NULL,
  `end_day` INT(3) NOT NULL,
  `end_time` TIME NOT NULL,
  `alternating` INT(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`show_id`, `start_day`, `start_time`, `end_day`, `end_time`, `alternating`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table citr_live.shows
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `citr_live`.`shows` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` TINYTEXT CHARACTER SET 'utf8' NULL DEFAULT NULL,
  `host_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `weekday` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  `start_time` TIME NOT NULL DEFAULT '00:00:00',
  `end_time` TIME NOT NULL DEFAULT '00:00:00',
  `pl_req` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  `cc_req` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  `indy_req` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  `fem_req` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  `last_show` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_name` TINYTEXT CHARACTER SET 'utf8' NOT NULL,
  `edit_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `edit_name` TINYTEXT CHARACTER SET 'utf8' NULL DEFAULT NULL,
  `active` TINYINT(1) NOT NULL DEFAULT '1',
  `crtc_default` INT(8) NOT NULL DEFAULT '20',
  `lang_default` TINYTEXT CHARACTER SET 'utf8' NULL DEFAULT NULL,
  `genre` TINYTEXT CHARACTER SET 'utf8' NULL DEFAULT NULL,
  `website` TINYTEXT CHARACTER SET 'utf8' NULL DEFAULT NULL,
  `rss` TINYTEXT CHARACTER SET 'utf8' NULL DEFAULT NULL,
  `show_desc` TEXT CHARACTER SET 'utf8' NULL DEFAULT NULL,
  `notes` TEXT CHARACTER SET 'utf8' NULL DEFAULT NULL,
  `show_img` TINYTEXT CHARACTER SET 'utf8' NULL DEFAULT NULL,
  `sponsor_name` TINYTEXT CHARACTER SET 'utf8' NULL DEFAULT NULL,
  `sponsor_url` TINYTEXT CHARACTER SET 'utf8' NULL DEFAULT NULL,
  `showtype` VARCHAR(45) CHARACTER SET 'utf8' NULL DEFAULT 'Live',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id` (`id` ASC),
  INDEX `id_2` (`id` ASC))
ENGINE = MyISAM
AUTO_INCREMENT = 359
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table citr_live.socan
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `citr_live`.`socan` (
  `idSocan` INT(10) UNSIGNED NOT NULL,
  `socanStart` DATE NULL DEFAULT NULL,
  `socanEnd` DATE NULL DEFAULT NULL,
  PRIMARY KEY (`idSocan`),
  UNIQUE INDEX `idSocan_UNIQUE` (`idSocan` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'table for socan';

-- ----------------------------------------------------------------------------
-- Table citr_live.social
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `citr_live`.`social` (
  `show_id` INT(10) NOT NULL,
  `social_name` VARCHAR(100) CHARACTER SET 'utf8' NOT NULL,
  `social_url` VARCHAR(200) CHARACTER SET 'utf8' NOT NULL,
  `short_name` TINYTEXT CHARACTER SET 'utf8' NULL DEFAULT NULL,
  `unlink` INT(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`show_id`, `social_name`, `social_url`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table citr_live.songs
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `citr_live`.`songs` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `artist` TINYTEXT NULL DEFAULT NULL,
  `title` TINYTEXT NULL DEFAULT NULL,
  `song` TINYTEXT NULL DEFAULT NULL,
  `composer` TINYTEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id` (`id` ASC),
  INDEX `id_2` (`id` ASC))
ENGINE = MyISAM
AUTO_INCREMENT = 1398212
DEFAULT CHARACTER SET = latin1;

-- ----------------------------------------------------------------------------
-- Table citr_live.types_format
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `citr_live`.`types_format` (
  `id` INT(11) NULL DEFAULT '0',
  `name` TINYTEXT NULL DEFAULT NULL,
  `sort` INT(11) NULL DEFAULT NULL,
  UNIQUE INDEX `id` (`id` ASC),
  INDEX `id_2` (`id` ASC))
ENGINE = MyISAM
DEFAULT CHARACTER SET = latin1;

-- ----------------------------------------------------------------------------
-- Table citr_live.user_backup
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `citr_live`.`user_backup` (
  `userid` INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `username` CHAR(20) CHARACTER SET 'latin1' NOT NULL DEFAULT '0',
  `password` CHAR(100) CHARACTER SET 'latin1' NOT NULL DEFAULT '0',
  `status` CHAR(20) CHARACTER SET 'latin1' NOT NULL DEFAULT '0',
  `create_date` DATETIME NULL DEFAULT NULL,
  `create_name` CHAR(30) CHARACTER SET 'latin1' NULL DEFAULT NULL,
  `edit_date` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
  `edit_name` CHAR(30) CHARACTER SET 'latin1' NULL DEFAULT NULL,
  `login_fails` MEDIUMINT(9) NULL DEFAULT '0')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;
SET FOREIGN_KEY_CHECKS = 1;;
