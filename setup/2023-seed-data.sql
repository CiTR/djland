-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 19, 2023 at 07:11 PM
-- Server version: 5.7.24
-- PHP Version: 7.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- Table structure for table `adlog`
--

CREATE TABLE `adlog` (
  `id` int(11) NOT NULL,
  `playsheet_id` int(11) DEFAULT NULL,
  `num` smallint(6) DEFAULT NULL,
  `time` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  `type` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  `name` text CHARACTER SET utf8,
  `played` tinyint(4) DEFAULT '0',
  `sam_id` int(11) DEFAULT NULL,
  `time_block` int(11) DEFAULT NULL,
  `create_date` timestamp NULL DEFAULT NULL,
  `edit_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `archive`
--

CREATE TABLE `archive` (
  `id` int(11) UNSIGNED NOT NULL,
  `contact` tinytext,
  `catalog` tinytext,
  `artist` tinytext,
  `title` tinytext,
  `submitted` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `djland_options`
--

CREATE TABLE `djland_options` (
  `index` int(10) UNSIGNED NOT NULL,
  `djland_option` tinytext COLLATE utf8_bin NOT NULL,
  `value` text COLLATE utf8_bin NOT NULL,
  `CREATED_AT` datetime NOT NULL,
  `UPDATED_AT` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `djland_options`
--

INSERT INTO `djland_options` (`index`, `djland_option`, `value`, `CREATED_AT`, `UPDATED_AT`) VALUES
(1, 'membership_cutoff', '2015/2016', '2017-05-02 14:37:32', '2022-05-12 23:01:30'),
(3, 'member_resources', '<p><strong>CiTR Resources</strong></p>\n<ul type=\"disc\">\n  <li>The\n    <a onclick=\"javascript:pageTracker._trackPageview(\'/downloads/wp-content/uploads/2012/03/CiTR%20Policy%20-%20Final%20Draft.pdf\');\"\n    href=\"https://www.citr.ca/wp-content/uploads/2012/03/CiTR%20Policy%20-%20Final%20Draft.pdf\">\n    CiTR Programming Policies</a> and\n    <a onclick=\"javascript:pageTracker._trackPageview(\'/downloads/wp-content/uploads/2012/03/CiTR%20Codes%20of%20Conduct%20-%20(web)%20Final%20Draft.pdf\');\"\n    href=\"https://www.citr.ca/wp-content/uploads/2012/03/CiTR%20Codes%20of%20Conduct%20-%20(web)%20Final%20Draft.pdf\">\n    On-Air Codes of Conduct</a> are the basics for broadcasters at our station. Get to know them.\n  </li>\n        \n  <li>Our \n    <a onclick=\"javascript:pageTracker._trackPageview(\'/downloads/wp-content/uploads/2009/06/Marantz-User-Guide1.pdf\');\"\n    href=\"https://www.citr.ca/wp-content/uploads/2009/06/Marantz-User-Guide1.pdf\">\n    Marantz User Guide</a> gives you cheat notes on how to use our Marantz digital recorders, and the proper \n    <a onclick=\"javascript:pageTracker._trackPageview(\'/downloads/wp-content/uploads/2009/06/Marantz-Manual.pdf\');\"\n    href=\"https://www.citr.ca/wp-content/uploads/2009/06/Marantz-Manual.pdf\">\n    Marantz Manual</a> will explain everything in great detail\n  </li>\n  \n  <li>Our \n    <a onclick=\"javascript:pageTracker._trackPageview(\'/downloads/wp-content/uploads/2009/06/Zoom-User-Guide.pdf\');\"\n    href=\"https://www.citr.ca/wp-content/uploads/2009/06/Zoom-User-Guide.pdf\">\n    Zoom User Guide</a> gives you cheat notes on how to use our Zoom digital recorders, and the full \n    <a onclick=\"javascript:pageTracker._trackPageview(\'/downloads/wp-content/uploads/2009/06/H2_user_manual.pdf\');\"\n    href=\"https://www.citr.ca/wp-content/uploads/2009/06/H2_user_manual.pdf\">\n    Zoom H2 Manual</a> will explain everything in great detail\n  </li>\n  \n  <li>Interested in spoken word? Read our \n    <a onclick=\"javascript:pageTracker._trackPageview(\'/downloads/wp-content/uploads/2009/06/SpokenWordFAQ.pdf\');\"\n    href=\"https://www.citr.ca/wp-content/uploads/2009/06/SpokenWordFAQ.pdf\">\n    Spoken Word FAQ</a>\n  </li>\n  \n  <li>Check out this \n    <a onclick=\"javascript:pageTracker._trackPageview(\'/downloads/wp-content/uploads/2009/06/Trans-Style-Guide.pdf\');\"\n    href=\"https://www.citr.ca/wp-content/uploads/2009/06/Trans-Style-Guide.pdf\">\n    Trans Style Guide</a> to help you write clearly, consistently and respectfully about trans issues.\n  </li>\n  \n  <li>Read our \n    <a onclick=\"javascript:pageTracker._trackPageview(\'/downloads/wp-content/uploads/2016/05/ITUNES-PODCASTING-GUIDE.pdf\');\"\n    href=\"https://www.citr.ca/wp-content/uploads/2016/05/ITUNES-PODCASTING-GUIDE.pdf\">\n    iTunes Podcasting Guide</a> for help getting your podcast on iTunes.\n  </li>\n  <li style=\"display:none;\">\n    <a href=\"https://gdoc.pub/doc/e/2PACX-1vQ18PwWaaVcXBsRgmryUgXrh2RMFiJnXBSZV1iFxmEvBirNnu1-bYNsTMZ3QOYVgrJUMDo1z8fWSrl5\">\n    SAM Guide</a>\n  </li>\n  <li>\n    <a href=\"https://gdoc.pub/doc/e/2PACX-1vR4ums6_F9gW0FB5TxAMUVQPEff6hbdeRKOHsTXVsmmzqciFhtKrlcgh_oGDmtVVIP-YVDUjE51o5pG\">\n    Audacity Guide</a>\n  </li>\n  <li>\n    <a href=\"https://citr.ca/wp-content/uploads/2020/02/CiTR-101.9FM-and-Discorder-Magazine-Sexual-Violence-and-Bullying-and-Harassment-Policy-2019.pdf\" target=\"blank\">\n    Sexual Violence and Bullying and Harassment Policy</a>\n  </li>\n\n  <li>\n    <a href=\"https://gdoc.pub/doc/e/2PACX-1vTOs_0aAehEMsFEaCBUEK2EgsQj6y8tLvc_mJ6gvI78FXqp3p9VrmB_KOyIgtYK_Hz1guPYURKe6Uel\">\n    Programming Policies and Content Requirements</a>\n  </li>\n  <li>\n    <a href=\"https://gdoc.pub/doc/e/2PACX-1vQVwa1hwxzwJBfGtb4yY_d95QYvQqYLa-f7GRZNpQZyI2o50E0dnAu4rnP7F5asGT97c7-_LQgKiVPa\">\n    Codes of Conduct</a>\n  </li>\n  <li>\n    <a href=\"https://gdoc.pub/doc/e/2PACX-1vRhNj4lxVLFlPTNrhLKFP3vYD-wV1DPOo8EgGtIa8U7YNs-D4sm2ZuavF5G6bM1JoYMVKQrRYaBbehJ\">\n    Technical Guide</a>\n  </li>\n  <li>\n    <a href=\"https://gdoc.pub/doc/e/2PACX-1vTb1NTTtLirDRyTLzNYd6es7HuEp3XHebcGtk_2PbKYUchUqeJLta-l4_ARqbSUhHFUYrIA5wSdoNDN\">\n    I\'m done training, now what?</a>\n  </li>\n  <li>\n    <a href=\"https://gdoc.pub/doc/e/2PACX-1vQOMifogAUzNlrZux9oKrrRTAKEMQYjwQcf73kyZW6ssbyaroTf7cEbxjBO_qF6WxXXL9D2QWoZ5_AV\">\n    \"Fill In\" Show Instructions</a>\n  </li>\n  <li>\n    <a href=\"https://www.citr.ca/contact/\">\n    Contact Information</a>\n  </li>\n  <li>\n    <a href=\"https://gdoc.pub/doc/e/2PACX-1vSsLkGU06ZaosNq7NDZHELDe5iaFWDibyo-eHTL3dr8MMLNL9DCyBnEDeqBE9bVfLgAKO6v6KTJqFIr\">\n    PSA Assignment Instructions</a>\n  </li>\n  <li>\n    <a href=\"https://drive.google.com/file/d/1ys12Xqn9j8dd3DLe44cGiAtIg54XjntT/view?usp=sharing\">\n    Podcasting a Live Radio Show</a>\n  <li>\n    <p>\n    <a href=\"https://gdoc.pub/doc/e/2PACX-1vRkEk37mp-mQgkRKyoCB3xLZdZllVJQ-gOYV0RWQF5Hhcx9_B5DXvfjK34ylaFswXN2p4GNyUbdvSHj\">\n    Broadcast Libel</a>\n  </li>\n  <li>\n    <a href=\"https://gdoc.pub/doc/e/2PACX-1vQ8f84-NlImDX2JOR0oeXLgbJj6Tk_ApbNMHA_Ll_0kxU9X2W3fzmQiugySJ6cBibbhSa9IhS4buJFz\">\n    Best Practices for Reporting on Accessibility Issues</a>\n    </p>\n    <audio controls=\"\" class=\"ng-isolate-scope\" \n            ng-src=\"https://playlist.citr.ca/podcasting/audio/memeber-resources/Accessibility-issue-manual.mp3\" \n               src=\"https://playlist.citr.ca/podcasting/audio/memeber-resources/Accessibility-issue-manual.mp3\"></audio>\n  </li>\n  <li>\n    <p><a href=\"https://gdoc.pub/doc/e/2PACX-1vQP__GWdyv9edxYV9sNNGfHpSvc3mkHwdZwy4LKIv8Jue4xFZgHy0i54TSf2ghjhg-vxW6mlfkZYdjj\">\n    Best Practices for Reporting on Women, Gender Non-Conforming People, Trans People, and Related Issues</a>\n    </p>\n    <audio controls=\"\" class=\"ng-isolate-scope\" \n            ng-src=\"https://playlist.citr.ca/podcasting/audio/memeber-resources/Gender-manual.mp3\" \n               src=\"https://playlist.citr.ca/podcasting/audio/memeber-resources/Gender-manual.mp3\"></audio>\n  </li>\n  <li>\n    <p><a href=\"https://gdoc.pub/doc/e/2PACX-1vTYdLsvunQWnShol0icEhYTMwqs4F8lu7v6VuIBPKLJjqBsvE17e_a0LON9UTM0LxEY15n5593el-6r\">\n    Best Practices for Reporting on Current Affairs</a>\n    </p>\n    <audio controls=\"\" class=\"ng-isolate-scope\" \n            ng-src=\"https://playlist.citr.ca/podcasting/audio/memeber-resources/Current-Affairs-manual.mp3\" \n               src=\"https://playlist.citr.ca/podcasting/audio/memeber-resources/Current-Affairs-manual.mp3\"></audio>\n  </li>\n  <li>\n    <p><a href=\"https://gdoc.pub/doc/e/2PACX-1vTZSKx6jfVHGSEtt7MTJIH4Aqimcorbn65uWebz11TtEzIJ1--gFXyGOKgkp047U3P5Zw5TmEGRJ1h0\">\n    Best Practices for Reporting on Indigenous Peoples and Related Issues</a>\n    </p>\n    <audio controls=\"\" class=\"ng-isolate-scope\" \n            ng-src=\"https://playlist.citr.ca/podcasting/audio/memeber-resources/Indigenous-manual.mp3\" \n               src=\"https://playlist.citr.ca/podcasting/audio/memeber-resources/Indigenous-manual.mp3\"></audio>\n  </li>\n\n  \n</ul>\n\n</br>\n\n<p><strong>External Resources</strong></p>\n<ul type=\"disc\">\n  <li>\n    <a onclick=\"javascript:pageTracker._trackPageview(\'/outgoing/https://www.ncra.ca/training-webinars/the-regulatory-survival-guide-webinar\');\"\n    href=\"https://www.ncra.ca/training-webinars/the-regulatory-survival-guide-webinar\">\n    NCRA Regulatory Guide</a>\n  </li>\n  <li>The \n    <a href=\"https://www.crtc.gc.ca/eng/archive/2010/2010-819.htm\">\n    CRTC Broadcasting Regulatory Policy</a> - Music categories, election campaigns, best practices, Canadian content, turntablism, etc. Truly a thrilling read.\n  </li>\n</ul>\n\n<p><strong>Training</strong></p>\n\n<ol>\n  <li>Technical</li>\n  <li>Programming</li>\n  <li>Production</li>\n  <li>Music Show Host Training</li>\n  <li>Talk Show Host Training</li>\n</ol>\n\n<p>\nYou must sign up in the above order and at least 24 hours in advance. You must be an active member to take the full set of training, \nbut non-members can sit in on one session to help them decide if they want to become a member. If you are not able to physically sign \nup at the station, please send us an email at \n  <a onclick=\"javascript:pageTracker._trackPageview(\'/mailto/volunteer@citr.ca\');\"\n  href=\"mailto:hello@citr.ca\">\n  hello@citr.ca</a>.\n</p>\n<p>\nYou can take the trainings multiple times and do not need to be an expert (or even competent) by the end of the them. Practice your skills by booking studio time <a href=\"https://djland.citr.ca/studio_booking.php\"> here</a>.\n</p>', '2017-05-05 23:58:31', '2023-02-23 02:17:20');

-- --------------------------------------------------------

--
-- Table structure for table `djs`
--

CREATE TABLE `djs` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `day` text NOT NULL,
  `time` text NOT NULL,
  `dj` text NOT NULL,
  `desc` text NOT NULL,
  `image` text NOT NULL,
  `email` text NOT NULL,
  `website` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `friends`
--

CREATE TABLE `friends` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` tinytext NOT NULL,
  `address` tinytext,
  `website` varchar(100) DEFAULT NULL,
  `phone` varchar(17) DEFAULT NULL,
  `discount` tinytext,
  `image` tinytext,
  `created` datetime DEFAULT NULL,
  `edited` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `fundrive_donors`
--

CREATE TABLE `fundrive_donors` (
  `id` int(11) NOT NULL,
  `donation_amount` varchar(10) DEFAULT NULL,
  `swag` varchar(1) DEFAULT NULL,
  `tax_receipt` varchar(1) DEFAULT NULL,
  `show_inspired` tinytext,
  `prize` varchar(45) DEFAULT NULL,
  `firstname` varchar(45) DEFAULT NULL,
  `lastname` varchar(45) DEFAULT NULL,
  `address` varchar(90) DEFAULT NULL,
  `city` varchar(45) DEFAULT NULL,
  `province` varchar(4) DEFAULT NULL,
  `postalcode` varchar(6) DEFAULT NULL,
  `country` varchar(60) DEFAULT NULL,
  `phonenumber` varchar(12) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `payment_method` varchar(45) DEFAULT NULL,
  `mail_yes` varchar(1) DEFAULT NULL,
  `postage_paid` varchar(30) DEFAULT NULL,
  `recv_updates_citr` varchar(1) DEFAULT NULL,
  `recv_updates_alumni` varchar(1) DEFAULT NULL,
  `donor_recognition_name` varchar(45) DEFAULT NULL,
  `LP_yes` varchar(1) DEFAULT NULL,
  `notes` text,
  `paid` varchar(1) DEFAULT NULL,
  `prize_picked_up` varchar(1) DEFAULT NULL,
  `UPDATED_AT` timestamp NULL DEFAULT NULL,
  `CREATED_AT` timestamp NULL DEFAULT NULL,
  `LP_amount` varchar(5) NOT NULL,
  `status` varchar(45) DEFAULT 'unsaved'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `genres`
--

CREATE TABLE `genres` (
  `id` int(11) UNSIGNED NOT NULL,
  `genre` varchar(255) NOT NULL,
  `default_crtc_category` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `genres`
--

INSERT INTO `genres` (`id`, `genre`, `default_crtc_category`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'Electronic', 20, 1, 1, '2017-05-04 12:31:51', '2017-05-04 12:31:51'),
(2, 'Experimental', 30, 1, 1, '2017-05-04 12:31:51', '2017-05-04 12:31:51'),
(3, 'Hip Hop / R&B / Soul', 20, 1, 1, '2017-05-04 12:31:51', '2017-05-04 12:31:51'),
(4, 'International', 30, 1, 1, '2017-05-04 12:31:51', '2017-05-04 12:31:51'),
(5, 'Jazz / Classical', 30, 1, 1, '2017-05-04 12:31:51', '2017-05-04 12:31:51'),
(6, 'Punk / Hardcore / Metal', 20, 1, 1, '2017-05-04 12:31:51', '2017-05-04 12:31:51'),
(7, 'Rock / Pop / Indie', 20, 1, 1, '2017-05-04 12:31:51', '2017-05-04 12:31:51'),
(8, 'Roots / Blues / Folk', 30, 1, 1, '2017-05-04 12:31:51', '2017-05-04 12:31:51'),
(9, 'Talk', 10, 1, 1, '2017-05-04 12:31:51', '2017-05-04 12:31:51');

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `name` varchar(20) NOT NULL DEFAULT '0',
  `description` varchar(100) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`name`, `description`) VALUES
('member', 'A regular member'),
('dj', 'DJs can post playlists'),
('administrator', 'Full site powers'),
('adduser', 'Can create new user accounts in the \'member\' group'),
('addshow', 'Can create and edit shows'),
('editdj', 'Can edit playsheets'),
('library', 'Can search and view music library records'),
('membership', 'Can view and edit CITR Membership'),
('editlibrary', 'Can edit records in the music library');

-- --------------------------------------------------------

--
-- Table structure for table `group_members`
--

CREATE TABLE `group_members` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `operator` varchar(1) DEFAULT '0',
  `administrator` varchar(1) DEFAULT '0',
  `staff` varchar(1) DEFAULT '0',
  `workstudy` varchar(1) DEFAULT '0',
  `volunteer_leader` varchar(1) DEFAULT '0',
  `volunteer` varchar(45) DEFAULT '0',
  `dj` varchar(1) DEFAULT '0',
  `member` varchar(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `hosts`
--

CREATE TABLE `hosts` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` tinytext
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `library`
--

CREATE TABLE `library` (
  `id` int(11) NOT NULL,
  `format_id` tinyint(4) UNSIGNED NOT NULL DEFAULT '8',
  `catalog` tinytext,
  `crtc` int(8) DEFAULT NULL,
  `cancon` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `femcon` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `local` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `playlist` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `compilation` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `digitized` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `status` tinytext,
  `artist` tinytext,
  `title` tinytext,
  `label` tinytext,
  `genre` tinytext,
  `added` date DEFAULT NULL,
  `modified` date DEFAULT NULL,
  `description` longtext,
  `email` tinytext,
  `art_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `library_edits`
--

CREATE TABLE `library_edits` (
  `id` int(11) UNSIGNED NOT NULL,
  `format_id` tinyint(4) DEFAULT NULL,
  `old_format_id` tinyint(4) DEFAULT NULL,
  `catalog` tinytext,
  `old_catalog` tinytext,
  `cancon` tinyint(1) DEFAULT NULL,
  `old_cancon` tinyint(1) DEFAULT NULL,
  `femcon` tinyint(1) DEFAULT NULL,
  `old_femcon` tinyint(1) DEFAULT NULL,
  `local` int(1) DEFAULT NULL,
  `old_local` int(1) DEFAULT NULL,
  `playlist` tinyint(1) DEFAULT NULL,
  `old_playlist` tinyint(1) DEFAULT NULL,
  `compilation` tinyint(1) DEFAULT NULL,
  `old_compilation` tinyint(1) DEFAULT NULL,
  `digitized` tinyint(1) DEFAULT NULL,
  `old_digitized` tinyint(1) DEFAULT NULL,
  `status` tinytext,
  `old_status` tinytext,
  `artist` tinytext,
  `old_artist` tinytext,
  `title` tinytext,
  `old_title` tinytext,
  `label` tinytext,
  `old_label` tinytext,
  `genre` tinytext,
  `old_genre` tinytext,
  `library_id` int(11) DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  `created_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `library_songs`
--

CREATE TABLE `library_songs` (
  `song_id` int(10) UNSIGNED NOT NULL,
  `library_id` int(11) DEFAULT NULL,
  `artist` varchar(255) DEFAULT NULL,
  `album_artist` varchar(255) DEFAULT NULL,
  `album_title` varchar(255) DEFAULT NULL,
  `song_title` varchar(255) DEFAULT NULL,
  `credit` varchar(45) DEFAULT NULL,
  `track_num` smallint(6) DEFAULT '0',
  `tracks_total` smallint(6) DEFAULT '0',
  `genre` varchar(255) DEFAULT NULL,
  `s/t` bit(1) DEFAULT b'0',
  `v/a` bit(1) DEFAULT b'0',
  `compilation` bit(1) DEFAULT b'0',
  `composer` varchar(255) DEFAULT NULL,
  `crtc` tinyint(4) DEFAULT NULL,
  `year` year(4) DEFAULT NULL,
  `length` int(10) UNSIGNED DEFAULT NULL,
  `file_location` mediumtext,
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `log`
--

CREATE TABLE `log` (
  `index` int(10) UNSIGNED NOT NULL,
  `error` tinytext COLLATE utf8_bin NOT NULL,
  `data` tinytext COLLATE utf8_bin NOT NULL,
  `user` varchar(40) COLLATE utf8_bin NOT NULL,
  `DATE_CREATED` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `login_status`
--

CREATE TABLE `login_status` (
  `name` varchar(20) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `membership`
--

CREATE TABLE `membership` (
  `id` int(11) UNSIGNED NOT NULL,
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
  `since` varchar(9) NOT NULL DEFAULT '2023/2024',
  `faculty` varchar(22) DEFAULT NULL,
  `schoolyear` varchar(2) DEFAULT NULL,
  `student_no` varchar(8) DEFAULT NULL COMMENT 'Student Number',
  `integrate` varchar(1) NOT NULL DEFAULT '0',
  `has_show` varchar(1) NOT NULL DEFAULT '0',
  `show_name` varchar(100) DEFAULT NULL,
  `primary_phone` varchar(10) NOT NULL,
  `secondary_phone` varchar(10) DEFAULT NULL,
  `email` tinytext NOT NULL,
  `comments` tinytext,
  `about` text,
  `skills` text,
  `status` varchar(10) NOT NULL DEFAULT 'pending',
  `exposure` text,
  `station_tour` varchar(1) DEFAULT '0',
  `technical_training` varchar(1) DEFAULT '0',
  `programming_training` varchar(1) DEFAULT '0',
  `production_training` varchar(1) DEFAULT '0',
  `spoken_word_training` varchar(1) DEFAULT '0',
  `create_date` datetime NOT NULL,
  `edit_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `discorder_contributor` varchar(1) DEFAULT '0',
  `preferred_name` varchar(100) DEFAULT NULL,
  `pronouns` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `membership_status`
--

CREATE TABLE `membership_status` (
  `id` int(11) NOT NULL,
  `name` tinytext NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `membership_status`
--

INSERT INTO `membership_status` (`id`, `name`, `sort`) VALUES
(1, 'Alumni', 1),
(2, 'Community', 1),
(3, 'Lifetime', 1),
(4, 'Student', 1),
(5, 'Unknown', 0),
(6, 'UBC Alumni', 1);

-- --------------------------------------------------------

--
-- Table structure for table `membership_years`
--

CREATE TABLE `membership_years` (
  `id` int(10) UNSIGNED NOT NULL,
  `member_id` int(11) UNSIGNED NOT NULL,
  `membership_year` varchar(9) NOT NULL,
  `paid` varchar(1) NOT NULL DEFAULT '0',
  `sports` varchar(1) DEFAULT '0',
  `news` varchar(1) DEFAULT '0',
  `arts` varchar(1) DEFAULT '0',
  `music` varchar(1) DEFAULT '0',
  `show_hosting` varchar(1) DEFAULT '0',
  `live_broadcast` varchar(1) DEFAULT '0',
  `tech` varchar(1) DEFAULT '0',
  `programming_committee` varchar(1) DEFAULT '0',
  `ads_psa` varchar(1) DEFAULT '0',
  `promotions_outreach` varchar(1) DEFAULT '0',
  `discorder_illustrate` varchar(1) DEFAULT '0',
  `discorder_write` varchar(1) DEFAULT '0',
  `digital_library` varchar(1) DEFAULT '0',
  `photography` varchar(1) DEFAULT '0',
  `tabling` varchar(45) DEFAULT '0',
  `dj` varchar(1) DEFAULT '0',
  `other` varchar(45) DEFAULT NULL,
  `create_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `edit_date` timestamp NULL DEFAULT NULL,
  `womens_collective` varchar(16) DEFAULT '0',
  `indigenous_collective` varchar(16) DEFAULT '0',
  `accessibility_collective` varchar(16) DEFAULT '0',
  `music_affairs_collective` varchar(1) DEFAULT '0',
  `ubc_affairs_collective` varchar(1) DEFAULT '0',
  `podcasting` varchar(1) DEFAULT '0',
  `lgbt_collective` varchar(1) DEFAULT '0',
  `poc_collective` varchar(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `member_resources`
--

CREATE TABLE `member_resources` (
  `index` int(10) NOT NULL,
  `blurb` tinytext,
  `link` tinytext,
  `type` varchar(45) DEFAULT 'general',
  `CREATED_AT` timestamp NULL DEFAULT NULL,
  `UPDATED_AT` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `member_show`
--

CREATE TABLE `member_show` (
  `id` int(10) UNSIGNED NOT NULL,
  `member_id` int(11) NOT NULL,
  `show_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `playitems`
--

CREATE TABLE `playitems` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `show_id` int(10) UNSIGNED DEFAULT NULL,
  `playsheet_id` bigint(20) UNSIGNED DEFAULT NULL,
  `song_id` bigint(20) UNSIGNED DEFAULT NULL,
  `format_id` tinyint(3) UNSIGNED DEFAULT NULL,
  `is_playlist` tinyint(1) UNSIGNED DEFAULT '0',
  `is_canadian` tinyint(1) UNSIGNED DEFAULT '0',
  `is_yourown` tinyint(1) UNSIGNED DEFAULT '0',
  `is_indy` tinyint(1) UNSIGNED DEFAULT '0',
  `is_accesscon` tinyint(1) UNSIGNED DEFAULT '0',
  `is_afrocon` tinyint(1) UNSIGNED DEFAULT '0',
  `is_fem` tinyint(3) UNSIGNED DEFAULT '0',
  `is_indigicon` tinyint(1) UNSIGNED DEFAULT '0',
  `is_poccon` tinyint(1) UNSIGNED DEFAULT '0',
  `is_queercon` tinyint(1) UNSIGNED DEFAULT '0',
  `is_local` tinyint(1) UNSIGNED DEFAULT '0',
  `show_date` date DEFAULT NULL,
  `duration` tinytext,
  `is_theme` tinyint(3) UNSIGNED DEFAULT NULL,
  `is_background` tinyint(3) UNSIGNED DEFAULT NULL,
  `crtc_category` int(8) DEFAULT '20',
  `lang` tinytext,
  `is_part` int(1) NOT NULL DEFAULT '0',
  `is_inst` int(1) NOT NULL DEFAULT '0',
  `is_hit` int(1) NOT NULL DEFAULT '0',
  `insert_song_start_hour` tinyint(4) DEFAULT '0',
  `insert_song_start_minute` tinyint(4) DEFAULT '0',
  `insert_song_length_minute` tinyint(4) DEFAULT '0',
  `insert_song_length_second` tinyint(4) DEFAULT '0',
  `artist` varchar(80) DEFAULT NULL,
  `song` varchar(80) DEFAULT NULL,
  `album` varchar(80) DEFAULT NULL,
  `composer` varchar(80) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `playsheets`
--

CREATE TABLE `playsheets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `show_id` int(10) UNSIGNED DEFAULT NULL,
  `host` tinytext CHARACTER SET latin1,
  `host_id` int(10) UNSIGNED DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `end` time DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `create_name` tinytext CHARACTER SET latin1,
  `edit_date` datetime NOT NULL,
  `title` tinytext CHARACTER SET latin1,
  `edit_name` tinytext CHARACTER SET latin1,
  `summary` mediumtext CHARACTER SET latin1,
  `spokenword_duration` int(11) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `unix_time` int(11) DEFAULT NULL,
  `star` tinyint(4) DEFAULT NULL,
  `crtc` int(11) DEFAULT NULL,
  `lang` text CHARACTER SET latin1,
  `type` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `show_name` tinytext CHARACTER SET latin1,
  `socan` varchar(1) CHARACTER SET latin1 DEFAULT NULL,
  `web_exclusive` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `podcast_episodes`
--

CREATE TABLE `podcast_episodes` (
  `id` int(11) NOT NULL,
  `playsheet_id` bigint(20) UNSIGNED NOT NULL,
  `show_id` int(11) DEFAULT NULL,
  `image` tinytext,
  `title` text,
  `subtitle` text,
  `summary` text,
  `date` datetime DEFAULT NULL,
  `iso_date` text,
  `url` text,
  `length` int(11) DEFAULT NULL,
  `author` text,
  `active` tinyint(1) DEFAULT '0',
  `duration` int(7) DEFAULT '0',
  `UPDATED_AT` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `CREATED_AT` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `rejected`
--

CREATE TABLE `rejected` (
  `id` int(11) UNSIGNED NOT NULL,
  `email` tinytext,
  `artist` tinytext,
  `title` tinytext,
  `submitted` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `shows`
--

CREATE TABLE `shows` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` tinytext,
  `host` tinytext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `primary_genre_tags` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `secondary_genre_tags` text,
  `weekday` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `start_time` time NOT NULL DEFAULT '00:00:00',
  `end_time` time NOT NULL DEFAULT '00:00:00',
  `pl_req` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `cc_20_req` tinyint(3) UNSIGNED NOT NULL DEFAULT '35',
  `cc_30_req` tinyint(3) NOT NULL DEFAULT '12',
  `indy_req` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `fem_req` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `last_show` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `create_name` tinytext NOT NULL,
  `edit_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `edit_name` tinytext,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `crtc_default` int(8) NOT NULL DEFAULT '20',
  `lang_default` tinytext,
  `website` tinytext,
  `rss` tinytext,
  `show_desc` text,
  `notes` mediumtext,
  `image` tinytext,
  `sponsor_name` tinytext,
  `sponsor_url` tinytext,
  `showtype` varchar(45) DEFAULT 'Live',
  `explicit` varchar(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '0',
  `alerts` text,
  `podcast_xml` tinytext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `podcast_slug` varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `podcast_title` tinytext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `podcast_subtitle` tinytext CHARACTER SET utf8 COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `show_times`
--

CREATE TABLE `show_times` (
  `id` int(10) NOT NULL,
  `show_id` int(10) NOT NULL,
  `start_day` int(3) NOT NULL,
  `start_time` time NOT NULL,
  `end_day` int(3) NOT NULL,
  `end_time` time NOT NULL,
  `alternating` int(2) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `socan`
--

CREATE TABLE `socan` (
  `id` int(10) UNSIGNED NOT NULL,
  `socanStart` date DEFAULT NULL,
  `socanEnd` date DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='table for socan';

-- --------------------------------------------------------

--
-- Table structure for table `social`
--

CREATE TABLE `social` (
  `id` int(10) UNSIGNED NOT NULL,
  `show_id` int(10) NOT NULL,
  `social_name` varchar(100) CHARACTER SET utf8 NOT NULL,
  `social_url` varchar(200) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `songs`
--

CREATE TABLE `songs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `artist` tinytext,
  `title` tinytext,
  `song` tinytext,
  `composer` tinytext
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `special_events`
--

CREATE TABLE `special_events` (
  `id` int(11) NOT NULL,
  `name` varchar(455) DEFAULT NULL,
  `show_id` int(11) DEFAULT NULL,
  `description` text,
  `start` int(11) DEFAULT NULL,
  `end` int(11) DEFAULT NULL,
  `image` varchar(455) DEFAULT NULL,
  `url` varchar(455) DEFAULT NULL,
  `edited` timestamp NULL DEFAULT NULL,
  `created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `subgenres`
--

CREATE TABLE `subgenres` (
  `id` int(11) UNSIGNED NOT NULL,
  `subgenre` varchar(255) NOT NULL,
  `parent_genre_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `submissions`
--

CREATE TABLE `submissions` (
  `id` int(10) UNSIGNED NOT NULL,
  `format_id` tinyint(3) DEFAULT NULL,
  `catalog` tinytext,
  `crtc` int(11) DEFAULT NULL,
  `cancon` tinyint(1) DEFAULT NULL,
  `femcon` tinyint(1) DEFAULT NULL,
  `local` int(11) DEFAULT NULL,
  `playlist` tinyint(1) DEFAULT NULL,
  `compilation` tinyint(1) DEFAULT NULL,
  `digitized` tinyint(1) DEFAULT NULL,
  `status` tinytext,
  `is_trashed` tinyint(1) DEFAULT '0',
  `artist` tinytext,
  `title` tinytext,
  `label` tinytext,
  `genre` tinytext,
  `tags` tinytext,
  `submitted` date DEFAULT NULL,
  `releasedate` date DEFAULT NULL,
  `assignee` int(10) UNSIGNED DEFAULT NULL,
  `reviewed` int(10) DEFAULT NULL,
  `approved` tinyint(1) DEFAULT NULL,
  `description` longtext,
  `location` tinytext,
  `email` tinytext,
  `songlist` bigint(20) UNSIGNED NOT NULL,
  `credit` tinytext,
  `art_url` tinytext,
  `review_comments` mediumtext,
  `staff_comment` varchar(45) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `submissions_archive`
--

CREATE TABLE `submissions_archive` (
  `id` int(11) UNSIGNED NOT NULL,
  `contact` tinytext,
  `catalog` tinytext,
  `artist` tinytext,
  `title` tinytext,
  `submitted` date DEFAULT NULL,
  `format_id` tinyint(3) DEFAULT NULL,
  `cancon` tinyint(1) DEFAULT NULL,
  `femcon` tinyint(1) DEFAULT NULL,
  `local` tinyint(1) DEFAULT NULL,
  `label` tinytext,
  `review_comments` tinytext,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `submissions_rejected`
--

CREATE TABLE `submissions_rejected` (
  `id` int(11) UNSIGNED NOT NULL,
  `email` tinytext,
  `artist` tinytext,
  `title` tinytext,
  `submitted` date DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `submission_songs`
--

CREATE TABLE `submission_songs` (
  `song_id` int(10) UNSIGNED NOT NULL,
  `submission_id` int(10) UNSIGNED DEFAULT NULL,
  `artist` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `album_artist` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `album_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `song_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `credit` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `track_num` smallint(6) DEFAULT '0',
  `tracks_total` smallint(6) DEFAULT '0',
  `genre` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `s/t` bit(1) DEFAULT b'0',
  `v/a` bit(1) DEFAULT b'0',
  `compilation` bit(1) DEFAULT b'0',
  `composer` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `crtc` tinyint(4) DEFAULT NULL,
  `year` year(4) DEFAULT NULL,
  `length` int(10) UNSIGNED DEFAULT NULL,
  `file_location` mediumtext COLLATE utf8_unicode_ci,
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `types_format`
--

CREATE TABLE `types_format` (
  `id` int(11) DEFAULT '0',
  `name` tinytext,
  `sort` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `types_format`
--

INSERT INTO `types_format` (`id`, `name`, `sort`) VALUES
(1, 'CD', 1),
(2, 'LP', 2),
(3, '7\"', 2),
(4, 'CASS', 3),
(5, 'CART', 3),
(6, 'MP3', 2),
(7, 'MD', 3),
(8, '??', 3);

-- --------------------------------------------------------

--
-- Table structure for table `uploads`
--

CREATE TABLE `uploads` (
  `id` int(10) NOT NULL,
  `file_name` tinytext NOT NULL,
  `file_type` varchar(45) NOT NULL,
  `category` varchar(45) NOT NULL,
  `path` tinytext,
  `size` tinytext,
  `description` tinytext,
  `url` tinytext,
  `relation_id` int(10) DEFAULT NULL,
  `CREATED_AT` datetime DEFAULT NULL,
  `UPDATED_AT` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(10) UNSIGNED NOT NULL,
  `member_id` int(11) UNSIGNED NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'enabled',
  `create_date` timestamp NULL DEFAULT NULL,
  `edit_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `edit_name` varchar(30) DEFAULT NULL,
  `login_fails` mediumint(9) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_backup`
--

CREATE TABLE `user_backup` (
  `userid` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `username` char(20) CHARACTER SET latin1 NOT NULL DEFAULT '0',
  `password` char(100) CHARACTER SET latin1 NOT NULL DEFAULT '0',
  `status` char(20) CHARACTER SET latin1 NOT NULL DEFAULT '0',
  `create_date` datetime DEFAULT NULL,
  `create_name` char(30) CHARACTER SET latin1 DEFAULT NULL,
  `edit_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `edit_name` char(30) CHARACTER SET latin1 DEFAULT NULL,
  `login_fails` mediumint(9) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `year_rollover`
--

CREATE TABLE `year_rollover` (
  `id` int(11) NOT NULL,
  `membership_year` varchar(16) NOT NULL DEFAULT '2014/2015'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adlog`
--
ALTER TABLE `adlog`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD KEY `unixtime` (`id`,`time_block`);

--
-- Indexes for table `archive`
--
ALTER TABLE `archive`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`);

--
-- Indexes for table `djland_options`
--
ALTER TABLE `djland_options`
  ADD PRIMARY KEY (`index`);

--
-- Indexes for table `djs`
--
ALTER TABLE `djs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `friends`
--
ALTER TABLE `friends`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fundrive_donors`
--
ALTER TABLE `fundrive_donors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `genres`
--
ALTER TABLE `genres`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `group_members`
--
ALTER TABLE `group_members`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `userid_idx` (`user_id`);

--
-- Indexes for table `hosts`
--
ALTER TABLE `hosts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `id_2` (`id`);

--
-- Indexes for table `library`
--
ALTER TABLE `library`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `library_edits`
--
ALTER TABLE `library_edits`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`);

--
-- Indexes for table `library_songs`
--
ALTER TABLE `library_songs`
  ADD PRIMARY KEY (`song_id`);

--
-- Indexes for table `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`index`);

--
-- Indexes for table `login_status`
--
ALTER TABLE `login_status`
  ADD KEY `name` (`name`);

--
-- Indexes for table `membership`
--
ALTER TABLE `membership`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD UNIQUE KEY `student_no_UNIQUE` (`student_no`);

--
-- Indexes for table `membership_status`
--
ALTER TABLE `membership_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `membership_years`
--
ALTER TABLE `membership_years`
  ADD PRIMARY KEY (`id`),
  ADD KEY `member_id_idx` (`member_id`);

--
-- Indexes for table `member_resources`
--
ALTER TABLE `member_resources`
  ADD PRIMARY KEY (`index`);

--
-- Indexes for table `member_show`
--
ALTER TABLE `member_show`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `playitems`
--
ALTER TABLE `playitems`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `id_2` (`id`),
  ADD KEY `playitem_playsheet_id_idx` (`playsheet_id`),
  ADD KEY `playitem_show_id_idx` (`show_id`);

--
-- Indexes for table `playsheets`
--
ALTER TABLE `playsheets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `id_2` (`id`),
  ADD KEY `recent` (`id`,`edit_date`,`status`),
  ADD KEY `playsheet_show_id_idx` (`show_id`);

--
-- Indexes for table `podcast_episodes`
--
ALTER TABLE `podcast_episodes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rejected`
--
ALTER TABLE `rejected`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`);

--
-- Indexes for table `shows`
--
ALTER TABLE `shows`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `id_2` (`id`);

--
-- Indexes for table `show_times`
--
ALTER TABLE `show_times`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `socan`
--
ALTER TABLE `socan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idSocan_UNIQUE` (`id`);

--
-- Indexes for table `social`
--
ALTER TABLE `social`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `songs`
--
ALTER TABLE `songs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `id_2` (`id`);

--
-- Indexes for table `special_events`
--
ALTER TABLE `special_events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subgenres`
--
ALTER TABLE `subgenres`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `submissions`
--
ALTER TABLE `submissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`);

--
-- Indexes for table `submissions_archive`
--
ALTER TABLE `submissions_archive`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`);

--
-- Indexes for table `submissions_rejected`
--
ALTER TABLE `submissions_rejected`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`);

--
-- Indexes for table `submission_songs`
--
ALTER TABLE `submission_songs`
  ADD PRIMARY KEY (`song_id`),
  ADD KEY `fk_submission_songs_1_idx` (`submission_id`);

--
-- Indexes for table `types_format`
--
ALTER TABLE `types_format`
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `id_2` (`id`);

--
-- Indexes for table `uploads`
--
ALTER TABLE `uploads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`,`member_id`),
  ADD UNIQUE KEY `member_id_UNIQUE` (`member_id`),
  ADD UNIQUE KEY `username_UNIQUE` (`username`),
  ADD KEY `member_id_idx` (`member_id`);

--
-- Indexes for table `year_rollover`
--
ALTER TABLE `year_rollover`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adlog`
--
ALTER TABLE `adlog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `archive`
--
ALTER TABLE `archive`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `djland_options`
--
ALTER TABLE `djland_options`
  MODIFY `index` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `djs`
--
ALTER TABLE `djs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `friends`
--
ALTER TABLE `friends`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fundrive_donors`
--
ALTER TABLE `fundrive_donors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `genres`
--
ALTER TABLE `genres`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `hosts`
--
ALTER TABLE `hosts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `library`
--
ALTER TABLE `library`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `library_edits`
--
ALTER TABLE `library_edits`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `library_songs`
--
ALTER TABLE `library_songs`
  MODIFY `song_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `log`
--
ALTER TABLE `log`
  MODIFY `index` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `membership`
--
ALTER TABLE `membership`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `membership_status`
--
ALTER TABLE `membership_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `membership_years`
--
ALTER TABLE `membership_years`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `member_show`
--
ALTER TABLE `member_show`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `playitems`
--
ALTER TABLE `playitems`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `playsheets`
--
ALTER TABLE `playsheets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `podcast_episodes`
--
ALTER TABLE `podcast_episodes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rejected`
--
ALTER TABLE `rejected`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shows`
--
ALTER TABLE `shows`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `show_times`
--
ALTER TABLE `show_times`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `socan`
--
ALTER TABLE `socan`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `social`
--
ALTER TABLE `social`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `songs`
--
ALTER TABLE `songs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `special_events`
--
ALTER TABLE `special_events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subgenres`
--
ALTER TABLE `subgenres`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `submissions`
--
ALTER TABLE `submissions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `submissions_archive`
--
ALTER TABLE `submissions_archive`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `submissions_rejected`
--
ALTER TABLE `submissions_rejected`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `submission_songs`
--
ALTER TABLE `submission_songs`
  MODIFY `song_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `uploads`
--
ALTER TABLE `uploads`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `year_rollover`
--
ALTER TABLE `year_rollover`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `membership_years`
--
ALTER TABLE `membership_years`
  ADD CONSTRAINT `id` FOREIGN KEY (`member_id`) REFERENCES `membership` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `playitems`
--
ALTER TABLE `playitems`
  ADD CONSTRAINT `playitem_playsheet_id` FOREIGN KEY (`playsheet_id`) REFERENCES `playsheets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `playsheets`
--
ALTER TABLE `playsheets`
  ADD CONSTRAINT `playsheet_show_id` FOREIGN KEY (`show_id`) REFERENCES `shows` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `submission_songs`
--
ALTER TABLE `submission_songs`
  ADD CONSTRAINT `fk_submission_songs_1` FOREIGN KEY (`submission_id`) REFERENCES `submissions` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `member_id` FOREIGN KEY (`member_id`) REFERENCES `membership` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

INSERT INTO `membership` 
(`id`, `lastname`, `firstname`, `canadian_citizen`, `address`, `city`, `province`, `postalcode`, `member_type`, `is_new`, `alumni`, `since`, `faculty`, `schoolyear`, `student_no`, `integrate`, `has_show`, `show_name`, `primary_phone`, `secondary_phone`, `email`, `comments`, `about`, `skills`, `status`, `exposure`, `station_tour`, `technical_training`, `programming_training`, `production_training`, `spoken_word_training`, `create_date`, `edit_date`, `discorder_contributor`, `preferred_name`, `pronouns`) 
VALUES 
('1', 'admin', 'djland', '1', 'station', '', '', '', 'Staff', '0', '0', '2013/2014', NULL, NULL, NULL, '0', '0', '', '123456789', '', 'admin@djland.citr.ca', '', '', '', 'approved', '', '1', '1', '1', '1', '1', NOW(), NOW(), '0', 'Admin', 'Admin pronouns');

INSERT INTO `membership_years` 
(`id`, `member_id`, `membership_year`, `paid`, `sports`, `news`, `arts`, `music`, `show_hosting`, `live_broadcast`, `tech`, `programming_committee`, `ads_psa`, `promotions_outreach`, `discorder_illustrate`, `discorder_write`, `digital_library`, `photography`, `tabling`, `dj`, `other`, `create_date`, `edit_date`, `womens_collective`, `indigenous_collective`, `accessibility_collective`, `music_affairs_collective`, `ubc_affairs_collective`, `podcasting`, `lgbt_collective`, `poc_collective`) 
VALUES 

('1', '1', '2023/2024', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', NULL, NOW(), NULL, '0', '0', '0', '0', '0', '0', '0', '0'); 

-- admin user default pass is '1234'

INSERT INTO `user` 
(`id`, `member_id`, `username`, `password`, `status`, `create_date`, `edit_date`, `edit_name`, `login_fails`) 
VALUES 
('1', '1', 'admin', '$2y$10$hza8F199V5ADR2yRdXXbs.bs6zOi5.CJeqDJYzyT3yc7/2LSfqePu', 'Enabled', NOW(),NOW(), 'admin', '0');

INSERT INTO `group_members` 
(`user_id`, `operator`, `administrator`, `staff`, `workstudy`, `volunteer_leader`, `volunteer`, `dj`, `member`) 
VALUES 

('1', '1', '1', '0', '0', '0', '0', '0', '0') 