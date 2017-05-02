ALTER TABLE `library`
DROP COLUMN `songlist_id`,
ADD COLUMN `art_url` varchar(255) DEFAULT NULL AFTER `email`;
