ALTER TABLE `djland`.`fundrive_donors`
  ADD COLUMN `status` varchar(45) DEFAULT 'unsaved' AFTER LP_amount;
