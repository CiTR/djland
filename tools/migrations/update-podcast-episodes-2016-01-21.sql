ALTER TABLE `djland`.`podcast_episodes` 
CHANGE COLUMN `date` `iso_date` TEXT NULL DEFAULT NULL ,
ADD COLUMN `CREATED_AT` TIMESTAMP NULL AFTER `UPDATED_AT`;
ALTER TABLE `djland`.`podcast_episodes` 
ADD COLUMN `date` DATETIME NULL AFTER `summary`;
UPDATE `djland`.`podcast_episodes` as pe SET pe.date = (SELECT p.start_time FROM  `djland`.`playsheets`  as p WHERE p.id = pe.playsheet_id);
