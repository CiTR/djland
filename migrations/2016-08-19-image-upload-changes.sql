ALTER TABLE `djland`.`podcast_episodes`
ADD COLUMN `image` TINYTEXT NULL DEFAULT NULL AFTER `show_id`;
ALTER TABLE `djland`.`shows`
CHANGE COLUMN `show_img` `image` TINYTEXT NULL DEFAULT NULL ;
ALTER TABLE `djland`.`friends`
CHANGE COLUMN `image_url` `image` TINYTEXT NULL DEFAULT NULL ;
