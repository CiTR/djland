CREATE TABLE `djland`.`friends` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` TINYTEXT NOT NULL,
  `address` TINYTEXT NULL,
  `website` VARCHAR(60) NULL,
  `phone` VARCHAR(17) NULL,
  `discount` VARCHAR(45) NULL,
  `image_url` VARCHAR(120) NULL,
  `created` DATETIME NULL,
  `edited` TIMESTAMP NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;