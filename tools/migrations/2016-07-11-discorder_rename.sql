ALTER TABLE `djland`.`membership_years`
CHANGE COLUMN `discorder` `discorder_illustrate` VARCHAR(1) NULL DEFAULT '0' ,
CHANGE COLUMN `discorder_2` `discorder_write` VARCHAR(1) NULL DEFAULT '0' ;
